<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmationMail;

class RegistrationController extends Controller

{


    public function showMembers()
    {
        $members = Registration::all(); // fetch all records
        return view('pages.user-management', compact('members')); // pass to Blade view
    }
    // public function show()
    // {
    //     // Fetch data if needed
    //     // $enquires = Enquiry::all();
    //     return view('membership-renewals'); // Blade file: resources/views/membership-renewals.blade.php
    // }

    // API endpoint for checking duplicates
    public function checkDuplicate(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        
        if (!$field || !$value) {
            return response()->json(['exists' => false]);
        }
        
        $exists = Registration::where($field, $value)->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? "This " . str_replace('_', ' ', $field) . " is already registered." : ""
        ]);
    }

    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'member_type' => 'required|in:new,existing',
                'memberName' => 'required|string|max:255',
                'age' => 'required|integer|min:18|max:70',
                'gender' => 'required|string',
                'email' => 'required|email|unique:registrations,email',
                'mobile' => 'required|string|max:15|unique:registrations,mobile',
                'nok_id' => 'nullable|string|max:50',
                'doj' => 'nullable|date',
                'whatsapp' => 'nullable|string|max:15',
                'department' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'passport' => 'required|string|max:50|unique:registrations,passport',
                'civil_id' => 'required|string|max:50|unique:registrations,civil_id',
                'blood_group' => 'required|string|max:5',
                'address' => 'required|string',
                'phone_india' => 'required|string|max:15',
                'nominee_name' => 'required|string|max:255',
                'nominee_relation' => 'required|string|max:255',
                'nominee_contact' => 'required|string|max:15',
                'guardian_name' => 'nullable|string|max:255',
                'guardian_contact' => 'nullable|string|max:15',
                'bank_account_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:50',
                'ifsc_code' => 'nullable|string|max:20',
                'bank_branch' => 'nullable|string|max:255',
            ]);

            // If DOJ is empty, set it to today's date before creating
            if (empty($validated['doj'])) {
                $validated['doj'] = now()->toDateString();
            }

            $created = Registration::create($validated);

            // Send confirmation email
            try {
                Mail::to($created->email)->send(new RegistrationConfirmationMail([
                    'memberName' => $created->memberName,
                ]));
            } catch (\Throwable $e) {
                // ignore mail failure
            }

            return response()->json([
                'status' => 'success',
                'message' => '✅ Registration successful! Please wait for admin approval of your membership card.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '❌ Something went wrong. Please try again!'
            ], 500);
        }
    }

    // Edit member
    public function edit($id)
    {
        $member = Registration::findOrFail($id); // Fetch by ID or fail
        return view('pages.profile-static', compact('member')); // Send to profile page
    }

    public function update(Request $request, $id)
{
    $member = Registration::findOrFail($id);

    // Validate all fields
    $validated = $request->validate([
        'member_type' => 'required|in:new,existing', //new code added
        'memberName'        => 'required|string|max:255',
        'age'               => 'required|integer',
        'gender'            => 'required|string',
        'email'             => 'required|email|unique:registrations,email,' . $id, // ignore current email
        'mobile'            => 'required|string|max:15',
        'member_type'       => 'nullable|string',
        'nok_id'            => 'nullable|string|max:50',
        'doj'               => 'nullable|date',
        'whatsapp'          => 'nullable|string|max:15',
        'department'        => 'required|string|max:255',
        'job_title'         => 'required|string|max:255',
        'institution'       => 'required|string|max:255',
        'passport'          => 'required|string|max:50',
        'civil_id'          => 'required|string|max:50',
        'blood_group'       => 'required|string|max:5',
        'address'           => 'required|string',
        'phone_india'       => 'required|string|max:15',
        'nominee_name'      => 'required|string|max:255',
        'nominee_relation'  => 'required|string|max:255',
        'nominee_contact'   => 'required|string|max:15',
        'guardian_name'     => 'nullable|string|max:255',
        'guardian_contact'  => 'nullable|string|max:15',
        'bank_account_name' => 'nullable|string|max:255',
        'account_number'    => 'nullable|string|max:50',
        'ifsc_code'         => 'nullable|string|max:20',
        'bank_branch'       => 'nullable|string|max:255',
    ]);

    // Update the record
    $member->update($validated);

    return redirect()
        ->route('members.edit', $id)
        ->with('success', 'Member details updated successfully!');
}

}
