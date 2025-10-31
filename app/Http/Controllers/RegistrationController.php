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
        
        // Validate input
        if (!$field || !$value) {
            return response()->json(['exists' => false]);
        }
        
        // Whitelist allowed fields to prevent SQL injection
        $allowedFields = ['email', 'mobile', 'passport', 'civil_id'];
        if (!in_array($field, $allowedFields)) {
            return response()->json(['exists' => false, 'error' => 'Invalid field']);
        }
        
        // Use indexed query with cache for 10 seconds to reduce DB load
        $cacheKey = "duplicate_check_{$field}_{$value}";
        $exists = cache()->remember($cacheKey, 10, function () use ($field, $value) {
            return Registration::where($field, $value)->exists();
        });
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? "This " . str_replace('_', ' ', $field) . " is already registered." : ""
        ]);
    }

    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'member_type' => 'nullable|in:new,existing',
                'memberName' => 'required|string|max:255',
                'age' => 'required|integer|min:18|max:100',
                'gender' => 'required|string|in:Male,Female,Transgender',
                'email' => 'required|email|max:255|unique:registrations,email',
                'mobile' => 'required|string|regex:/^\+965[0-9]{8}$/|unique:registrations,mobile',
                'nok_id' => 'nullable|string|max:50',
                'doj' => 'nullable|date|before_or_equal:today',
                'whatsapp' => 'nullable|string|regex:/^\+965[0-9]{8}$/',
                'department' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'passport' => 'required|string|max:50|unique:registrations,passport',
                'civil_id' => 'required|string|size:12|unique:registrations,civil_id',
                'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'address' => 'required|string|max:500',
                'phone_india' => 'required|string|regex:/^\+91[0-9]{10}$/',
                'nominee_name' => 'required|string|max:255',
                'nominee_relation' => 'required|string|max:255',
                'nominee_contact' => 'required|string|max:20',
                'guardian_name' => 'nullable|string|max:255',
                'guardian_contact' => 'nullable|string|max:20',
                'bank_account_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:50',
                'ifsc_code' => 'nullable|string|max:20',
                'bank_branch' => 'nullable|string|max:255',
            ], [
                'mobile.regex' => 'Kuwait mobile number must be in format: +965XXXXXXXX (8 digits after +965)',
                'whatsapp.regex' => 'WhatsApp number must be in format: +965XXXXXXXX (8 digits after +965)',
                'phone_india.regex' => 'India phone must be in format: +91XXXXXXXXXX (10 digits after +91)',
                'civil_id.size' => 'Civil ID must be exactly 12 digits',
                'age.min' => 'Age must be at least 18 years',
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
