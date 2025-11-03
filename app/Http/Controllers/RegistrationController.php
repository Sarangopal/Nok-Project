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

    // API endpoint for checking email duplicates
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        
        if (!$email) {
            return response()->json(['exists' => false]);
        }
        
        // Use indexed query with cache for 10 seconds to reduce DB load
        $cacheKey = "email_check_{$email}";
        $exists = cache()->remember($cacheKey, 10, function () use ($email) {
            return Registration::where('email', $email)->exists();
        });
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? "⚠️ This email is already registered." : ""
        ]);
    }

    // API endpoint for checking phone duplicates
    public function checkPhone(Request $request)
    {
        $phone = $request->input('phone');
        $country = $request->input('country');
        
        if (!$phone) {
            return response()->json(['exists' => false]);
        }
        
        // Use indexed query with cache for 10 seconds to reduce DB load
        $cacheKey = "phone_check_{$phone}_{$country}";
        $exists = cache()->remember($cacheKey, 10, function () use ($phone) {
            return Registration::where('mobile', $phone)->exists();
        });
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? "⚠️ This phone number is already registered." : ""
        ]);
    }

    // API endpoint for checking NOK ID duplicates
    public function checkNokId(Request $request)
    {
        $nokId = $request->input('nok_id');
        
        if (!$nokId) {
            return response()->json(['exists' => false]);
        }
        
        // Use indexed query with cache for 10 seconds to reduce DB load
        $cacheKey = "nok_id_check_{$nokId}";
        $exists = cache()->remember($cacheKey, 10, function () use ($nokId) {
            return Registration::where('nok_id', $nokId)->exists();
        });
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? "⚠️ This NOK ID already exists." : ""
        ]);
    }

    // API endpoint for checking duplicates (other fields)
    public function checkDuplicate(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        
        // Validate input
        if (!$field || !$value) {
            return response()->json(['exists' => false]);
        }
        
        // Whitelist allowed fields to prevent SQL injection
        $allowedFields = ['passport', 'civil_id'];
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
            // Use the full international numbers if provided by intl-tel-input
            if ($request->filled('mobile_full')) {
                $request->merge(['mobile' => $request->mobile_full]);
            }
            if ($request->filled('whatsapp_full')) {
                $request->merge(['whatsapp' => $request->whatsapp_full]);
            }
            if ($request->filled('phone_india_full')) {
                $request->merge(['phone_india' => $request->phone_india_full]);
            }

            $validated = $request->validate([
                'member_type' => 'nullable|in:new,existing',
                'memberName' => 'required|string|max:255',
                'age' => 'required|integer|min:18|max:100',
                'gender' => 'required|string|in:Male,Female,Others',
                'email' => 'required|email|max:255|unique:registrations,email',
                'mobile' => 'required|string|max:20|unique:registrations,mobile',
                'nok_id' => 'required_if:member_type,existing|nullable|unique:registrations,nok_id|string|max:50',
                'doj' => 'required_if:member_type,existing|nullable|date|before_or_equal:today',
                'whatsapp' => 'nullable|string|max:20',
                'department' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'passport' => 'required|string|max:50|unique:registrations,passport',
                'civil_id' => 'required|string|size:12|unique:registrations,civil_id',
                'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'address' => 'required|string|max:500',
                'phone_india' => 'required|string|max:20',
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
                'mobile.required' => 'Kuwait mobile number is required',
                'mobile.unique' => 'This mobile number is already registered',
                'email.unique' => 'This email is already registered',
                'nok_id.unique' => 'This NOK ID already exists',
                'nok_id.required_if' => 'NOK ID is required',
                'nok_id.exists' => 'This NOK ID already exists',
                'phone_india.required' => 'India phone number is required',
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
        'nok_id'            => 'nullable|string|max:50|unique:registrations,nok_id',
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
