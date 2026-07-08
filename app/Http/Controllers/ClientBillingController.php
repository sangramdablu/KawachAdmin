<?php

namespace App\Http\Controllers;

use App\Models\BillingAgreement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ClientBillingController extends Controller
{
    // Read-only list of the logged-in client's own agreements.
    // client_email is stored encrypted (no plain, queryable column and
    // no client_user_id FK), so rows are matched by decrypting in PHP.
    public function index(): View
    {
        $email = Auth::user()->email;

        $agreements = BillingAgreement::orderByDesc('created_at')
            ->get()
            ->filter(fn (BillingAgreement $a) => strcasecmp($a->client_email, $email) === 0)
            ->values();

        return view('client.billing', compact('agreements'));
    }

    public function show(string $uuid): View
    {
        $agreement = $this->findOwnedOrFail($uuid);

        return view('billing.pdf.agreement', compact('agreement'));
    }

    public function pdf(string $uuid): Response
    {
        $agreement = $this->findOwnedOrFail($uuid);

        $pdf = Pdf::loadView('billing.pdf.agreement', ['agreement' => $agreement])
                  ->setPaper('A4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled'      => false,
                      'defaultFont'          => 'DejaVu Sans',
                  ]);

        return $pdf->download('Agreement_' . $agreement->invoice_no . '.pdf');
    }

    private function findOwnedOrFail(string $uuid): BillingAgreement
    {
        $agreement = BillingAgreement::with('developers')->where('uuid', $uuid)->firstOrFail();

        abort_unless(
            strcasecmp($agreement->client_email, Auth::user()->email) === 0,
            403,
            'This agreement does not belong to your account.'
        );

        return $agreement;
    }
}
