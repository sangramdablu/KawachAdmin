<?php

namespace App\Http\Controllers;

use App\Mail\AgreementMail;
use App\Mail\SigningInvitationMail;
use App\Mail\WelcomeAndCredentialsMail;
use App\Models\BillingAgreement;
use App\Models\ClientPortalInvoice;
use App\Models\ClientPortalProject;
use App\Services\BillingCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BillingAndAgreementController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────
    // Index
    // ─────────────────────────────────────────────────────────────────────
    public function index(): View
    {
        $agreements = BillingAgreement::with('creator')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('billing.index', compact('agreements'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Create form
    // ─────────────────────────────────────────────────────────────────────
    public function create(): View
    {
        $techRates = BillingCalculator::TECH_RATES;
        $techIcons = BillingCalculator::TECH_ICONS;

        $last = BillingAgreement::withTrashed()->orderByDesc('id')->value('invoice_no');
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $nextNo = 'KWT-' . date('Y') . '-' . str_pad((int)$m[1] + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNo = 'KWT-' . date('Y') . '-001';
        }

        return view('billing.create', compact('techRates', 'techIcons', 'nextNo'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // AJAX: live calculate
    // ─────────────────────────────────────────────────────────────────────
    public function calculate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'developers'              => ['required', 'array', 'min:1', 'max:30'],
            'developers.*.technology' => ['required', 'string'],
            'developers.*.quantity'   => ['required', 'integer', 'min:1', 'max:20'],
            'developers.*.label'      => ['nullable', 'string', 'max:120'],
            'daily_hours'             => ['required', Rule::in([4, 6, 8, 10])],
            'duration_months'         => ['required', 'integer', 'min:1', 'max:60'],
            'tax_pct'                 => ['required', 'numeric', 'min:0', 'max:50'],
            'advance_pct'             => ['required', 'numeric', 'min:0', 'max:100'],
            'currency'                => ['required', Rule::in(['USD','EUR','GBP','INR','CAD','AUD'])],
        ]);

        try {
            $calc = BillingCalculator::calculate(
                $data['developers'],
                (int)  $data['daily_hours'],
                (int)  $data['duration_months'],
                (float)$data['tax_pct'],
                (float)$data['advance_pct'],
            );

            $symbols = ['USD'=>'$','EUR'=>'€','GBP'=>'£','INR'=>'₹','CAD'=>'CA$','AUD'=>'A$'];
            $sym     = $symbols[$data['currency']] ?? '$';
            $fmt     = fn(int $c) => BillingCalculator::fmt($c, $sym);

            return response()->json([
                'ok'               => true,
                'monthly_hours'    => $calc['monthly_hours'],
                'dev_count'        => count($data['developers']),
                'subtotal'         => $fmt($calc['subtotal_cents']),
                'tax'              => $fmt($calc['tax_cents']),
                'monthly_total'    => $fmt($calc['monthly_total_cents']),
                'advance'          => $fmt($calc['advance_cents']),
                'balance'          => $fmt($calc['balance_cents']),
                'contract_pre'     => $fmt($calc['contract_pre_cents']),
                'contract_tax'     => $fmt($calc['contract_tax_cents']),
                'grand_total'      => $fmt($calc['grand_total_cents']),
                'grand_total_raw'  => $calc['grand_total_cents'],
                'dev_lines'        => array_map(fn($d) => [
                    'technology'   => $d['technology'],
                    'icon'         => $d['tech_icon'],
                    'label'        => $d['developer_label'],
                    'qty'          => $d['quantity'],
                    'rate'         => $sym . number_format($d['hourly_rate_cents'] / 100, 2),
                    'hours'        => $d['monthly_hours'],
                    'monthly_cost' => $fmt($d['monthly_cost_cents']),
                ], $calc['dev_lines']),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 422);
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Store — only fires on "Save Agreement" (not draft)
    // Generates a secure signing token and sends invitation email
    // ─────────────────────────────────────────────────────────────────────
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $this->validateAgreementRequest($request);

        DB::beginTransaction();
        try {
            $calc = BillingCalculator::calculate(
                $validated['developers'],
                (int)  $validated['daily_hours'],
                (int)  $validated['duration_months'],
                (float)$validated['tax_pct'],
                (float)$validated['advance_pct'],
            );

            $symbols = ['USD'=>'$','EUR'=>'€','GBP'=>'£','INR'=>'₹','CAD'=>'CA$','AUD'=>'A$'];
            $status = $validated['status'] ?? 'draft';

            // Generate secure signing token for non-draft saves
            $signingToken = null;
            $expiresAt    = null;
            if ($status !== 'draft') {
                $signingToken = hash('sha256', Str::random(64) . microtime(true) . config('app.key'));
                $expiresAt    = now()->addDays(7); // 7-day signing window
                $status       = 'sent';
            }

            $agreement = BillingAgreement::create([
                'invoice_no'          => $validated['invoice_no'],
                'agreement_date'      => $validated['agreement_date'],
                'contract_start'      => $validated['contract_start'],
                'duration_months'     => $validated['duration_months'],
                'status'              => $status,
                'signing_token'       => $signingToken,
                'expires_at'          => $expiresAt,
                'client_name'         => $validated['client_name'],
                'client_contact'      => $validated['client_contact'],
                'client_email'        => $validated['client_email'],
                'client_phone'        => $validated['client_phone'] ?? null,
                'client_address'      => $validated['client_address'] ?? null,
                'project_name'        => $validated['project_name'],
                'project_type'        => $validated['project_type'] ?? null,
                'project_scope'       => $validated['project_scope'] ?? null,
                'daily_hours'         => $validated['daily_hours'],
                'monthly_days'        => BillingCalculator::WORK_DAYS_PER_MONTH,
                'payment_cycle'       => $validated['payment_cycle'],
                'payment_due_days'    => $validated['payment_due_days'],
                'late_fee_pct'        => $validated['late_fee_pct'],
                'advance_pct'         => $validated['advance_pct'],
                'tax_pct'             => $validated['tax_pct'],
                'currency'            => $validated['currency'],
                'currency_symbol'     => $symbols[$validated['currency']] ?? '$',
                'payment_methods'     => $validated['payment_methods'] ?? null,
                'custom_clauses'      => $validated['custom_clauses'] ?? null,
                'subtotal_cents'      => $calc['subtotal_cents'],
                'tax_cents'           => $calc['tax_cents'],
                'monthly_total_cents' => $calc['monthly_total_cents'],
                'advance_cents'       => $calc['advance_cents'],
                'grand_total_cents'   => $calc['grand_total_cents'],
                'company_signature'   => $this->sanitizeSignature($validated['company_signature'] ?? null),
                'client_signature'    => $this->sanitizeSignature($validated['client_signature'] ?? null),
                'created_by'          => auth()->id(),
            ]);

            foreach ($calc['dev_lines'] as $line) {
                $agreement->developers()->create($line);
            }

            DB::commit();

            // ── Send signing invitation (queued, non-blocking) ─────────────
            if ($signingToken) {
                Mail::to($agreement->client_email)->queue(new SigningInvitationMail($agreement));
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'ok'         => true,
                    'id'         => $agreement->uuid,
                    'invoice_no' => $agreement->invoice_no,
                    'sent'       => (bool) $signingToken,
                ]);
            }
            return redirect()
                ->route('billing.show', $agreement->uuid)
                ->with('success', 'Agreement saved. Signing invitation sent to client.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('BillingAgreement store failed', ['error' => $e->getMessage()]);
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'error' => 'Could not save agreement.'], 500);
            }
            return back()->withInput()->withErrors(['general' => 'Save failed. Please try again.']);
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Show
    // ─────────────────────────────────────────────────────────────────────
    public function show(string $uuid): View
    {
        $agreement = BillingAgreement::with('developers')->where('uuid', $uuid)->firstOrFail();
        return view('billing.pdf.agreement', compact('agreement'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Edit
    // ─────────────────────────────────────────────────────────────────────
    public function edit(string $uuid): View
    {
        $agreement = BillingAgreement::with('developers')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $techRates = BillingCalculator::TECH_RATES;
        $techIcons = BillingCalculator::TECH_ICONS;

        return view('billing.edit', compact('agreement', 'techRates', 'techIcons'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Update
    // ─────────────────────────────────────────────────────────────────────
    public function update(Request $request, string $uuid): JsonResponse|RedirectResponse
    {
        $agreement = BillingAgreement::where('uuid', $uuid)->firstOrFail();

        // Prevent editing a signed agreement
        if ($agreement->status === 'signed') {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'error' => 'Cannot edit a signed agreement.'], 403);
            }
            return back()->withErrors(['general' => 'Signed agreements cannot be edited.']);
        }

        $validated = $this->validateAgreementRequest($request, $agreement->id);

        DB::beginTransaction();
        try {
            $calc    = BillingCalculator::calculate($validated['developers'], (int)  $validated['daily_hours'], (int)  $validated['duration_months'], (float)$validated['tax_pct'], (float)$validated['advance_pct'],);
            $symbols = ['USD'=>'$','EUR'=>'€','GBP'=>'£','INR'=>'₹','CAD'=>'CA$','AUD'=>'A$'];

            $agreement->update([
                'agreement_date'      => $validated['agreement_date'],
                'contract_start'      => $validated['contract_start'],
                'duration_months'     => $validated['duration_months'],
                'status'              => $validated['status'] ?? $agreement->status,
                'client_name'         => $validated['client_name'],
                'client_contact'      => $validated['client_contact'],
                'client_email'        => $validated['client_email'],
                'client_phone'        => $validated['client_phone'] ?? null,
                'client_address'      => $validated['client_address'] ?? null,
                'project_name'        => $validated['project_name'],
                'project_type'        => $validated['project_type'] ?? null,
                'project_scope'       => $validated['project_scope'] ?? null,
                'daily_hours'         => $validated['daily_hours'],
                'payment_cycle'       => $validated['payment_cycle'],
                'payment_due_days'    => $validated['payment_due_days'],
                'late_fee_pct'        => $validated['late_fee_pct'],
                'advance_pct'         => $validated['advance_pct'],
                'tax_pct'             => $validated['tax_pct'],
                'currency'            => $validated['currency'],
                'currency_symbol'     => $symbols[$validated['currency']] ?? '$',
                'payment_methods'     => $validated['payment_methods'] ?? null,
                'custom_clauses'      => $validated['custom_clauses'] ?? null,
                'subtotal_cents'      => $calc['subtotal_cents'],
                'tax_cents'           => $calc['tax_cents'],
                'monthly_total_cents' => $calc['monthly_total_cents'],
                'advance_cents'       => $calc['advance_cents'],
                'grand_total_cents'   => $calc['grand_total_cents'],
                'company_signature'   => $this->sanitizeSignature($validated['company_signature'] ?? $agreement->company_signature),
                'client_signature'    => $this->sanitizeSignature($validated['client_signature'] ?? $agreement->client_signature),
            ]);

            $agreement->developers()->delete();
            foreach ($calc['dev_lines'] as $line) {
                $agreement->developers()->create($line);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['ok' => true]);
            }
            return redirect()
                ->route('billing.show', $agreement->uuid)
                ->with('success', 'Agreement updated.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('BillingAgreement update failed', ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'error' => 'Update failed.'], 500);
            }
            return back()->withInput()->withErrors(['general' => 'Update failed.']);
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Resend signing invitation
    // ─────────────────────────────────────────────────────────────────────
    public function resendSigningInvitation(string $uuid): JsonResponse
    {
        $agreement = BillingAgreement::where('uuid', $uuid)->firstOrFail();

        if ($agreement->status === 'signed') {
            return response()->json(['ok' => false, 'error' => 'Agreement already signed.'], 422);
        }

        DB::beginTransaction();
        try {
            // Regenerate token and extend expiry
            $newToken = hash('sha256', Str::random(64) . microtime(true) . config('app.key'));
            $agreement->update([
                'signing_token' => $newToken,
                'expires_at'    => now()->addDays(7),
                'status'        => 'sent',
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Resend signing invitation failed', ['uuid' => $uuid, 'error' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => 'Could not regenerate token.'], 500);
        }

        Mail::to($agreement->client_email)
            ->queue(new SigningInvitationMail($agreement->fresh()));

        return response()->json(['ok' => true, 'message' => 'Signing invitation resent to ' . $agreement->client_email]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Show signing page (GET — public, no auth, token-gated)
    // ─────────────────────────────────────────────────────────────────────
    public function signAgreement(string $token): View
    {
        // Look up by token; 404 if invalid or already consumed and purged
        $agreement = BillingAgreement::where('signing_token', $token)->firstOrFail();

        // Prevent resigned agreements
        if ($agreement->status === 'signed') {
            // Show "already signed" state — not an error
            return view('billing.pdf.sign', compact('agreement'));
        }

        // Expired?
        if ($agreement->expires_at && now()->greaterThan($agreement->expires_at)) {
            return view('billing.pdf.sign', compact('agreement'));
        }

        // Record first open (for audit trail)
        if (! $agreement->viewed_at) {
            $agreement->update(['viewed_at' => now()]);
        }

        return view('billing.pdf.sign', compact('agreement'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Submit signature (POST — public, no auth, token-gated)
    // ─────────────────────────────────────────────────────────────────────
    public function submitSignature(Request $request, string $token): JsonResponse
    {
        // ── 1. Rate-limit: 5 attempts per IP per 10 minutes ───────────────
        $ipKey = 'sign_attempt_' . sha1($request->ip() . $token);
        $attempts = cache()->get($ipKey, 0);
        if ($attempts >= 5) {
            Log::warning('Signing rate limit hit', ['ip' => $request->ip(), 'token' => substr($token, 0, 8)]);
            return response()->json(['ok' => false, 'error' => 'Too many attempts. Please try again in 10 minutes.'], 429);
        }
        cache()->put($ipKey, $attempts + 1, now()->addMinutes(10));

        // ── 2. Validate token matches URL ─────────────────────────────────
        $submitted = $request->input('token');
        if (! hash_equals($token, (string) $submitted)) {
            Log::warning('Signing token mismatch', ['ip' => $request->ip()]);
            return response()->json(['ok' => false, 'error' => 'Invalid request. Token mismatch.'], 403);
        }

        // ── 3. Load agreement ─────────────────────────────────────────────
        $agreement = BillingAgreement::where('signing_token', $token)->first();
        if (! $agreement) {
            return response()->json(['ok' => false, 'error' => 'Agreement not found or link already used.'], 404);
        }

        // ── 4. Guard: already signed ──────────────────────────────────────
        if ($agreement->status === 'signed') {
            return response()->json(['ok' => false, 'error' => 'This agreement has already been signed.'], 422);
        }

        // ── 5. Guard: expired ─────────────────────────────────────────────
        if ($agreement->expires_at && now()->greaterThan($agreement->expires_at)) {
            return response()->json(['ok' => false, 'error' => 'Signing link has expired. Please contact KawachTech.'], 422);
        }

        // ── 6. Validate signature data ────────────────────────────────────
        $sig = $request->input('client_signature');
        if (! $sig || ! str_starts_with($sig, 'data:image/png;base64,')) {
            return response()->json(['ok' => false, 'error' => 'Invalid signature data.'], 422);
        }
        $b64 = substr($sig, strlen('data:image/png;base64,'));
        if (! base64_decode($b64, true)) {
            return response()->json(['ok' => false, 'error' => 'Corrupt signature data.'], 422);
        }

        // ── 7. Mark as signed inside a transaction ────────────────────────
        DB::beginTransaction();
        try {
            $agreement->update([
                'status'           => 'signed',
                'signed_at'        => now(),
                'client_signature' => $sig,
                // Consume (nullify) the token so the link can never be replayed
                'signing_token'    => null,
                'expires_at'       => null,
            ]);

            // ── 8. Create portal user + project + first invoice ────────────
            [$clientUser, $plainPassword, $isNewUser] = $this->provisionClientPortal($agreement);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Signature submission failed', [
                'agreement_id' => $agreement->id,
                'error'        => $e->getMessage(),
                'trace'        => $e->getTraceAsString(),
            ]);
            return response()->json(['ok' => false, 'error' => 'Could not save signature. Please try again.'], 500);
        }

        // ── 9. Send welcome + credentials email (outside transaction) ──────
        if ($isNewUser) {
            try {
                Mail::to($clientUser->email)
                    ->queue(new WelcomeAndCredentialsMail($clientUser, $agreement, $plainPassword));
            } catch (\Throwable $e) {
                // Non-fatal: log but don't fail the response
                Log::error('Welcome email failed to queue', [
                    'user_id'      => $clientUser->id,
                    'agreement_id' => $agreement->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        // ── 10. Log the signing audit event ───────────────────────────────
        Log::info('Agreement signed', [
            'agreement_id' => $agreement->id,
            'invoice_no'   => $agreement->invoice_no,
            'client_email' => $agreement->client_email,
            'client_ip'    => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'signed_at'    => now()->toIso8601String(),
        ]);

        return response()->json([
            'ok'       => true,
            'message'  => 'Agreement signed successfully! Check your email for portal access.',
            'redirect' => route('billing.sign.success'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Signing success page (public — after redirect)
    // ─────────────────────────────────────────────────────────────────────
    public function signSuccess(): View
    {
        return view('billing.pdf.sign-success');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Generate & download PDF
    // ─────────────────────────────────────────────────────────────────────
    public function generatePdf(string $uuid)
    {
        $agreement = BillingAgreement::with('developers')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $pdf = Pdf::loadView('billing.pdf.agreement', ['agreement' => $agreement])
                  ->setPaper('A4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled'      => false,
                      'defaultFont'          => 'DejaVu Sans',
                  ]);

        return $pdf->download('Agreement_' . $agreement->invoice_no . '.pdf');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Send agreement PDF to client by email (admin action)
    // ─────────────────────────────────────────────────────────────────────
    public function sendEmail(Request $request, string $uuid): JsonResponse
    {
        $agreement = BillingAgreement::with('developers')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $request->validate([
            'custom_message' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $pdf = Pdf::loadView('billing.pdf.agreement', ['agreement' => $agreement])
                ->setPaper('A4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false]);

            $tmpPath = storage_path('app/temp/agreement_' . $agreement->uuid . '.pdf');
            if (! is_dir(dirname($tmpPath))) {
                mkdir(dirname($tmpPath), 0775, true);
            }
            $pdf->save($tmpPath);

            Mail::to($agreement->client_email)->send(new AgreementMail($agreement, $tmpPath));
            $agreement->update(['sent_at' => now()]);
            @unlink($tmpPath);

            return response()->json(['ok' => true, 'message' => 'Agreement PDF sent to ' . $agreement->client_email]);
        } catch (\Throwable $e) {
            Log::error('Agreement email failed', ['uuid' => $uuid, 'error' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => 'Failed to send email.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Soft-delete
    // ─────────────────────────────────────────────────────────────────────
    public function destroy(string $uuid): JsonResponse|RedirectResponse
    {
        $agreement = BillingAgreement::where('uuid', $uuid)->firstOrFail();
        $agreement->delete();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('billing.index')->with('success', 'Agreement deleted.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Private: provision client portal user + project + invoice
    // Called inside an active DB transaction
    // Returns [$user, $plainPassword, $isNewUser]
    // ─────────────────────────────────────────────────────────────────────
    private function provisionClientPortal(BillingAgreement $agreement): array
    {
        $isNewUser     = false;
        $plainPassword = '';

        // Find or create User with 'client' role
        $user = \App\Models\User::where('email', $agreement->client_email)->first();
        if (! $user) {
            $isNewUser     = true;
            $plainPassword = Str::password(12, symbols: false);

            $user = \App\Models\User::create([
                'name'     => $agreement->client_name,
                'email'    => $agreement->client_email,
                'password' => Hash::make($plainPassword),
            ]);
        }

        // Ensure 'client' Spatie role is assigned
        if (! $user->hasRole('client')) {
            $user->assignRole('client');
        }

        // Seed ClientPortalProject only if one doesn't already exist for this agreement
        $existing = ClientPortalProject::where('billing_agreement_id', $agreement->id)->first();
        if (! $existing) {
            ClientPortalProject::create([
                'billing_agreement_id' => $agreement->id,
                'client_user_id'       => $user->id,
                'project_name'         => $agreement->project_name,
                'project_type'         => $agreement->project_type,
                'icon'                 => 'fas fa-code',
                'color_bg'             => '#e8f1fd',
                'color'                => 'var(--primary)',
                'status'               => 'inprogress',
                'progress'             => 0,
                'phases'               => [
                    ['name' => 'Discovery',   'state' => 'active'],
                    ['name' => 'Design',      'state' => 'pending'],
                    ['name' => 'Development', 'state' => 'pending'],
                    ['name' => 'Testing',     'state' => 'pending'],
                    ['name' => 'Launch',      'state' => 'pending'],
                ],
                'tags'       => [],
                'start_date' => $agreement->contract_start,
                'deadline'   => $agreement->contract_start
                                    ?->addMonths($agreement->duration_months),
                'done_tasks'  => 0,
                'total_tasks' => 0,
            ]);
        }

        // Create first advance invoice if not already seeded
        $hasInvoice = ClientPortalInvoice::where('billing_agreement_id', $agreement->id)->exists();
        if (! $hasInvoice && $agreement->advance_cents > 0) {
            ClientPortalInvoice::create([
                'client_user_id'       => $user->id,
                'billing_agreement_id' => $agreement->id,
                'invoice_id'           => 'INV-' . $agreement->invoice_no,
                'invoice_date'         => now()->toDateString(),
                'amount_cents'         => $agreement->advance_cents,
                'currency_symbol'      => $agreement->currency_symbol ?? '₹',
                'status'               => 'due',
                'icon_bg'              => '#fff4d6',
                'icon_color'           => '#b8860b',
            ]);
        }

        return [$user, $plainPassword, $isNewUser];
    }

    // ─────────────────────────────────────────────────────────────────────
    // Private: validate agreement form input
    // ─────────────────────────────────────────────────────────────────────
    private function validateAgreementRequest(Request $request, ?int $excludeId = null): array
    {
        return $request->validate([
            'invoice_no'       => ['required', 'string', 'max:40',
                                   Rule::unique('billing_agreements', 'invoice_no')->ignore($excludeId)],
            'agreement_date'   => ['required', 'date'],
            'contract_start'   => ['required', 'date'],
            'duration_months'  => ['required', 'integer', 'min:1', 'max:60'],
            'status'           => ['nullable', Rule::in(['draft','sent','signed','cancelled'])],

            'client_name'    => ['required', 'string', 'max:200'],
            'client_contact' => ['required', 'string', 'max:200'],
            'client_email'   => ['required', 'email', 'max:200'],
            'client_phone'   => ['nullable', 'string', 'max:40'],
            'client_address' => ['nullable', 'string', 'max:600'],

            'project_name'   => ['required', 'string', 'max:200'],
            'project_type'   => ['nullable', 'string', 'max:100'],
            'project_scope'  => ['nullable', 'string', 'max:3000'],

            'daily_hours'      => ['required', Rule::in([4, 6, 8, 10])],
            'payment_cycle'    => ['required', Rule::in(['monthly','biweekly','weekly','milestone'])],
            'payment_due_days' => ['required', 'integer', Rule::in([7,15,30,45])],
            'late_fee_pct'     => ['required', 'numeric', 'min:0', 'max:10'],
            'advance_pct'      => ['required', 'numeric', 'min:0', 'max:100'],
            'tax_pct'          => ['required', 'numeric', 'min:0', 'max:50'],
            'currency'         => ['required', Rule::in(['USD','EUR','GBP','INR','CAD','AUD'])],
            'payment_methods'  => ['nullable', 'string', 'max:500'],
            'custom_clauses'   => ['nullable', 'string', 'max:5000'],

            'developers'                => ['required', 'array', 'min:1', 'max:30'],
            'developers.*.technology'   => ['required', 'string'],
            'developers.*.quantity'     => ['required', 'integer', 'min:1', 'max:20'],
            'developers.*.label'        => ['nullable', 'string', 'max:120'],

            'company_signature' => ['nullable', 'string'],
            'client_signature'  => ['nullable', 'string'],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Private: sanitize signature — only accept valid PNG data URIs
    // ─────────────────────────────────────────────────────────────────────
    private function sanitizeSignature(?string $sig): ?string
    {
        if (! $sig) return null;
        if (! str_starts_with($sig, 'data:image/png;base64,')) return null;
        $b64 = substr($sig, strlen('data:image/png;base64,'));
        if (! base64_decode($b64, true)) return null;
        return $sig;
    }
}