<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Renewal;
use App\Models\Registration;
use PDF; // if you are using barryvdh/laravel-dompdf

class MembershipCardController extends Controller
{
    public function show()
    {
        return view('membership-renewals'); // Blade file: resources/views/membership-renewals.blade.php
    }
    // public function download(Registration $registration)
    // {
    //     // Generate the PDF using a Blade view
    //     $pdf = PDF::loadView('membership_card', ['registration' => $registration]);

    //     // Return the PDF as a download
    //     return $pdf->download('membership_card_' . $registration->id . '.pdf');

    //     //  // Generate PDF or whatever membership card you need
    //     // $pdf = PDF::loadView('membership-card', compact('renewal'));
    //     // return $pdf->download("MembershipCard-{$renewal->nok_id}.pdf");
    // }

    public function download($record)
    {
        // Accept either numeric ID, NOK ID, or Civil ID for convenience
        $registration = Registration::query()
            ->when(is_numeric($record), fn ($q) => $q->where('id', (int) $record))
            ->when(! is_numeric($record), function ($q) use ($record) {
                $q->orWhere('nok_id', (string) $record)
                  ->orWhere('civil_id', (string) $record);
            })
            ->first();

        // Fallback: try renewal model too (same table, but kept for safety)
        $registration = $registration ?: (Renewal::query()
            ->when(is_numeric($record), fn ($q) => $q->where('id', (int) $record))
            ->when(! is_numeric($record), function ($q) use ($record) {
                $q->orWhere('nok_id', (string) $record)
                  ->orWhere('civil_id', (string) $record);
            })
            ->first());

        if (! $registration) {
            abort(404, 'Membership record not found.');
        }

        // Generate the PDF using a Blade view
        $pdf = PDF::loadView('membership_card', ['record' => $registration]);

        // Return the PDF as a download
        return $pdf->download('membership_card_' . $registration->id . '.pdf');
    }
}
