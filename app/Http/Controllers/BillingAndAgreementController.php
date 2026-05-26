<?php

namespace App\Http\Controllers;

use App\Mail\AgreementMail;
use App\Models\BillingAgreement;
use App\Services\BillingCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

        // Auto-generate next invoice number
        $last = BillingAgreement::withTrashed()->orderByDesc('id')->value('invoice_no');
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $nextNo = 'KWT-' . date('Y') . '-' . str_pad((int)$m[1] + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNo = 'KWT-' . date('Y') . '-001';
        }

        return view('billing.create', compact('techRates', 'techIcons', 'nextNo'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // AJAX: live calculate (POST /billing/calculate)
    // Called by the frontend on every field change — no totals ever touch JS
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
                (int) $data['daily_hours'],
                (int) $data['duration_months'],
                (float) $data['tax_pct'],
                (float) $data['advance_pct'],
            );

            $symbols = ['USD'=>'$','EUR'=>'€','GBP'=>'£','INR'=>'₹','CAD'=>'CA$','AUD'=>'A$'];
            $sym = $symbols[$data['currency']] ?? '$';

            $fmt = fn(int $c) => BillingCalculator::fmt($c, $sym);

            return response()->json([
                'ok' => true,
                'monthly_hours'          => $calc['monthly_hours'],
                'dev_count'              => count($data['developers']),
                'subtotal'               => $fmt($calc['subtotal_cents']),
                'tax'                    => $fmt($calc['tax_cents']),
                'monthly_total'          => $fmt($calc['monthly_total_cents']),
                'advance'                => $fmt($calc['advance_cents']),
                'balance'                => $fmt($calc['balance_cents']),
                'contract_pre'           => $fmt($calc['contract_pre_cents']),
                'contract_tax'           => $fmt($calc['contract_tax_cents']),
                'grand_total'            => $fmt($calc['grand_total_cents']),
                'grand_total_raw'        => $calc['grand_total_cents'],
                'dev_lines'              => array_map(fn($d) => [
                    'technology'    => $d['technology'],
                    'icon'          => $d['tech_icon'],
                    'label'         => $d['developer_label'],
                    'qty'           => $d['quantity'],
                    'rate'          => $sym . number_format($d['hourly_rate_cents'] / 100, 2),
                    'hours'         => $d['monthly_hours'],
                    'monthly_cost'  => $fmt($d['monthly_cost_cents']),
                ], $calc['dev_lines']),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 422);
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Store (Save Draft or full save)
    // ─────────────────────────────────────────────────────────────────────
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $this->validateAgreementRequest($request);

        DB::beginTransaction();
        try {
            $calc = BillingCalculator::calculate(
                $validated['developers'],
                (int) $validated['daily_hours'],
                (int) $validated['duration_months'],
                (float) $validated['tax_pct'],
                (float) $validated['advance_pct'],
            );

            $symbols = ['USD'=>'$','EUR'=>'€','GBP'=>'£','INR'=>'₹','CAD'=>'CA$','AUD'=>'A$'];

            $agreement = BillingAgreement::create([
                'invoice_no'           => $validated['invoice_no'],
                'agreement_date'       => $validated['agreement_date'],
                'contract_start'       => $validated['contract_start'],
                'duration_months'      => $validated['duration_months'],
                'status'               => $validated['status'] ?? 'draft',
                'client_name'          => $validated['client_name'],
                'client_contact'       => $validated['client_contact'],
                'client_email'         => $validated['client_email'],
                'client_phone'         => $validated['client_phone'] ?? null,
                'client_address'       => $validated['client_address'] ?? null,
                'project_name'         => $validated['project_name'],
                'project_type'         => $validated['project_type'] ?? null,
                'project_scope'        => $validated['project_scope'] ?? null,
                'daily_hours'          => $validated['daily_hours'],
                'monthly_days'         => BillingCalculator::WORK_DAYS_PER_MONTH,
                'payment_cycle'        => $validated['payment_cycle'],
                'payment_due_days'     => $validated['payment_due_days'],
                'late_fee_pct'         => $validated['late_fee_pct'],
                'advance_pct'          => $validated['advance_pct'],
                'tax_pct'              => $validated['tax_pct'],
                'currency'             => $validated['currency'],
                'currency_symbol'      => $symbols[$validated['currency']] ?? '$',
                'payment_methods'      => $validated['payment_methods'] ?? null,
                'custom_clauses'       => $validated['custom_clauses'] ?? null,
                'subtotal_cents'       => $calc['subtotal_cents'],
                'tax_cents'            => $calc['tax_cents'],
                'monthly_total_cents'  => $calc['monthly_total_cents'],
                'advance_cents'        => $calc['advance_cents'],
                'grand_total_cents'    => $calc['grand_total_cents'],
                'company_signature'    => $this->sanitizeSignature($validated['company_signature'] ?? null),
                'client_signature'     => $this->sanitizeSignature($validated['client_signature'] ?? null),
                'created_by'           => auth()->id(),
            ]);

            foreach ($calc['dev_lines'] as $line) {
                $agreement->developers()->create($line);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['ok' => true, 'id' => $agreement->uuid, 'invoice_no' => $agreement->invoice_no]);
            }

            return redirect()->route('billing.show', $agreement->uuid)
                             ->with('success', 'Agreement saved successfully.');
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
    // Show (view saved agreement)
    // ─────────────────────────────────────────────────────────────────────
    public function show(string $uuid): View
    {
        $agreement = BillingAgreement::with('developers')
            ->where('uuid', $uuid)
            ->firstOrFail();

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
        $validated = $this->validateAgreementRequest($request, $agreement->id);

        DB::beginTransaction();
        try {
            $calc = BillingCalculator::calculate(
                $validated['developers'],
                (int) $validated['daily_hours'],
                (int) $validated['duration_months'],
                (float) $validated['tax_pct'],
                (float) $validated['advance_pct'],
            );

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
            return redirect()->route('billing.show', $agreement->uuid)->with('success', 'Agreement updated.');
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

        $filename = 'Agreement_' . $agreement->invoice_no . '.pdf';

        return $pdf->download($filename);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Send agreement to client by email
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
            // Generate PDF to a temp file
            $pdf = Pdf::loadView('billing.pdf.agreement', ['agreement' => $agreement])
                      ->setPaper('A4', 'portrait')
                      ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false]);

            $tmpPath = storage_path('app/temp/agreement_' . $agreement->uuid . '.pdf');
            if (!is_dir(dirname($tmpPath))) {
                mkdir(dirname($tmpPath), 0775, true);
            }
            $pdf->save($tmpPath);

            $clientEmail = $agreement->client_email;

            Mail::to($clientEmail)
                ->send(new AgreementMail($agreement, $tmpPath));

            // Mark as sent
            $agreement->update(['status' => 'sent', 'sent_at' => now()]);

            // Clean up temp file
            @unlink($tmpPath);

            return response()->json(['ok' => true, 'message' => 'Agreement sent to ' . $clientEmail]);
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
    // Private helpers
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

            // Client PII — validated before encryption
            'client_name'      => ['required', 'string', 'max:200'],
            'client_contact'   => ['required', 'string', 'max:200'],
            'client_email'     => ['required', 'email', 'max:200'],
            'client_phone'     => ['nullable', 'string', 'max:40'],
            'client_address'   => ['nullable', 'string', 'max:600'],

            'project_name'     => ['required', 'string', 'max:200'],
            'project_type'     => ['nullable', 'string', 'max:100'],
            'project_scope'    => ['nullable', 'string', 'max:3000'],

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

    /**
     * Ensure only PNG data URIs from canvas are accepted, strip anything else.
     */
    private function sanitizeSignature(?string $sig): ?string
    {
        if (!$sig) return null;
        if (!str_starts_with($sig, 'data:image/png;base64,')) return null;
        $b64 = substr($sig, strlen('data:image/png;base64,'));
        if (!base64_decode($b64, true)) return null;
        return $sig;
    }
}