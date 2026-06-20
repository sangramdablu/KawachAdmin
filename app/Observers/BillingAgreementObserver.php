<?php

namespace App\Observers;

use App\Models\BillingAgreement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgreementMail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;


class BillingAgreementObserver
{
    /**
     * Fired after a BillingAgreement is created.
     * Creates (or finds) a client User, assigns the 'client' role,
     * seeds a ClientPortalProject record, auto-creates an invoice,
     * and dispatches the credentials email.
     */
    public function created(BillingAgreement $agreement): void
    {
        DB::beginTransaction();
        try {
            $agreement->forceFill([ 'status' => 'sent', 'signing_token' => Str::uuid(), 'sent_at' => now(), 'expires_at' => now()->addDays(14), ])->saveQuietly();
            // $plainPassword = Str::password(12, symbols: false); // readable password

            // // ── 1. Find or create the client user ─────────────────────────
            // $isNewUser = false;
            // $user = User::where('email', $agreement->client_email)->first();

            // if (! $user) {
            //     $isNewUser = true;
            //     $user = User::create([
            //         'name'     => $agreement->client_name,
            //         'email'    => $agreement->client_email,
            //         'password' => Hash::make($plainPassword),
            //     ]);
            // }

            // // ── 2. Assign 'client' role (Spatie) ──────────────────────────
            // // Make sure you have a 'client' role: php artisan db:seed --class=RoleSeeder
            // if (! $user->hasRole('client')) {
            //     $user->assignRole('client');
            // }

            // // ── 3. Create the portal project record ───────────────────────
            // $project = ClientPortalProject::create([
            //     'billing_agreement_id' => $agreement->id,
            //     'client_user_id'       => $user->id,
            //     'project_name'         => $agreement->project_name,
            //     'project_type'         => $agreement->project_type,
            //     'icon'                 => 'fas fa-code',
            //     'color_bg'             => '#e8f1fd',
            //     'color'                => 'var(--primary)',
            //     'status'               => 'inprogress',
            //     'progress'             => 0,
            //     'phases'               => self::defaultPhases(),
            //     'tags'                 => [],
            //     'start_date'           => $agreement->contract_start,
            //     'deadline'             => $agreement->contract_start ?->addMonths($agreement->duration_months),
            //     'done_tasks'           => 0,
            //     'total_tasks'          => 0,
            // ]);

            // // ── 4. Seed first invoice from the advance amount ─────────────
            // if ($agreement->advance_cents > 0) {
            //     ClientPortalInvoice::create([
            //         'client_user_id'       => $user->id,
            //         'billing_agreement_id' => $agreement->id,
            //         'invoice_id'           => 'INV-' . $agreement->invoice_no,
            //         'invoice_date'         => now()->toDateString(),
            //         'amount_cents'         => $agreement->advance_cents,
            //         'currency_symbol'      => $agreement->currency_symbol ?? '₹',
            //         'status'               => 'due',
            //         'icon_bg'              => '#fff4d6',
            //         'icon_color'           => '#b8860b',
            //     ]);
            // }

            DB::commit();
            Mail::to($agreement->client_email) ->queue(new AgreementMail($agreement) );

            // ── 5. Send credentials email (queued) ────────────────────────
            // Only send plain password to new users; existing users keep theirs.
            // if ($isNewUser) {
            //     Mail::to($user->email)
            //         ->queue(new ClientCredentialsMail($user, $agreement, $plainPassword));
            // }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('BillingAgreementObserver@created failed', [ 'agreement_id' => $agreement->id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString(), ]);
        }
    }

    // ── Default phase skeleton ─────────────────────────────────────────────
    // private static function defaultPhases(): array
    // {
    //     return [
    //         ['name' => 'Discovery',   'state' => 'active'],
    //         ['name' => 'Design',      'state' => 'pending'],
    //         ['name' => 'Development', 'state' => 'pending'],
    //         ['name' => 'Testing',     'state' => 'pending'],
    //         ['name' => 'Launch',      'state' => 'pending'],
    //     ];
    // }
}