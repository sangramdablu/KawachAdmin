<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private const STATUSES = ['new', 'read', 'replied', 'archived'];

    /**
     * List all contact-form submissions from the public site.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = trim((string) $request->get('search'));

        $query = Contact::query()->latest();

        if (in_array($status, self::STATUSES, true)) {
            $query->where('status', $status);
        }

        // email / phone are encrypted at rest, so they can't be filtered in
        // SQL — search is limited to the plaintext columns.
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $contacts = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => Contact::count(),
            'new'       => Contact::where('status', 'new')->count(),
            'replied'   => Contact::where('status', 'replied')->count(),
            'this_week' => Contact::where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        return view('contacts.index', compact('contacts', 'stats', 'status', 'search'));
    }

    /**
     * Show one submission in full. Opening a "new" submission marks it read.
     */
    public function show(Contact $contact)
    {
        if ($contact->status === 'new') {
            $contact->markAsRead();
        }

        return view('contacts.show', compact('contact'));
    }

    /**
     * Update a submission's status (contacts.manage).
     */
    public function updateStatus(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $contact->setStatus($validated['status']);

        return back()->with('success', 'Status updated to ' . $validated['status'] . '.');
    }

    /**
     * Permanently delete a submission (contacts.manage).
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact submission deleted.');
    }
}
