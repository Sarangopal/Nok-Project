@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Register | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>

<section data-bg-src="{{ asset('nokw/assets//img/hero/banok.jpg') }}" class="image-overlay-dark">
    <div class="container">
        <div class="row gx-60">
            <div class="col-xl-5 align-self-center space wow fadeInUp" data-wow-delay="0.2s">
                <span class="sec-subtitle text-white">Register Now</span>
                <h2 class="sec-title3 h1 text-white">Become a Proud Member of NOK</h2>
                <hr class="hr-style1">
                <p class="mb-4 mt-1 pb-3 text-white">
                    Register today to access exclusive resources, connect with fellow nurses, and participate in community initiatives.
                </p>
            </div>
            


            <div class="col-xl-7 form-wrap1 regi-form">

              <form action="{{ route('registration.submit') }}" method="POST" class="form-style1 register-form">
                    @csrf

                    <!-- STEP 1 -->
                    <div class="form-step active" id="step1">
                        <h2 class="form-title h4">Membership Details</h2>

                        <!-- Toggle Section (Optional) -->
                        <div class="form-row" style="margin-bottom: 20px; display: flex; align-items: center; gap: 20px;">
                            <label class="switch">
                                <input type="checkbox" id="memberSwitch" name="member_type_checkbox" value="existing">
                                <span class="slider round"></span>
                            </label>
                           
                            <span style="color: #fff; font-weight: 600;" id="memberStatusText">Already Member (Optional)</span>
                        </div>
                        
                        <!-- Hidden field for actual member_type value -->
                        <input type="hidden" id="member_type" name="member_type" value="new">

                        <div class="form-row existing-member-fields" style="display: none; gap: 20px;">
                            <div class="form-group">
                                <input type="text" placeholder="NOK ID Number (e.g., NOK001234)" name="nok_id">
                            </div>
                            <div class="form-group">
                                <input type="date" placeholder="Date of Joining" name="doj">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Full Name *" name="memberName" required id="memberName">
                            </div>
                            <div class="form-group">
                                <input type="number" placeholder="Age (18-100) *" name="age" required min="18" max="100">
                            </div>
                        </div>

                        <div class="form-row gender-row">
                            <label><input type="radio" name="gender" value="Male" required> Male</label>
                            <label><input type="radio" name="gender" value="Female"> Female</label>
                            <label><input type="radio" name="gender" value="Others"> Others</label>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="email" placeholder="Email Address *" name="email" required id="email">
                            </div>
                            <div class="form-group">
                                <input type="tel" placeholder="Kuwait Mobile *" name="mobile" required id="mobile">
                                <input type="hidden" name="mobile_full" id="mobile_full">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="tel" placeholder="WhatsApp Number" name="whatsapp" id="whatsapp">
                                <input type="hidden" name="whatsapp_full" id="whatsapp_full">
                                <small style="color: #ddd; font-size: 11px;">Include your country code for WhatsApp.</small>
                            </div>
                        </div>

                        <div class="form-btn">
                            <button class="vs-btn style5" type="button" id="nextStepBtn1">Next Step <i class="far fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="form-step" id="step2">
                        <h2 class="form-title h4">Professional Details</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Department" name="department" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Job Title" name="job_title" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Hospital / Clinic / Institution Name" name="institution" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Passport Number *" name="passport" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Civil ID (12 digits) *" name="civil_id" required pattern="[0-9]{12}" maxlength="12">
                                <small style="color: #ddd; font-size: 11px;">Exactly 12 digits</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <select name="blood_group" required>
                                    <option value="">Select Blood Group</option>
                                    <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                                    <option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-btn" style="display:flex; justify-content: space-between;">
                            <button class="vs-btn style4" type="button" id="prevStepBtn2"><i class="far fa-arrow-left"></i> Back</button>
                            <button class="vs-btn style5" type="button" id="nextStepBtn2">Next Step <i class="far fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="form-step" id="step3">
                        <h2 class="form-title h4">Permanent Address & Nominee Details</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <textarea placeholder="Full Address with Pin Code" name="address" required></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="tel" placeholder="India Phone *" name="phone_india" required id="phone_india">
                                <input type="hidden" name="phone_india_full" id="phone_india_full">
                            </div>
                        </div>

                        <h4 style="margin-top: 10px;">Nominee Details</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Name of the Nominee" name="nominee_name" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Relationship with NOK ID Holder" name="nominee_relation" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Contact Number of Nominee" name="nominee_contact" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Parent/Guardian Name (if minor)" name="guardian_name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Parent/Guardian Contact Number (if minor)" name="guardian_contact">
                            </div>
                        </div>

                        <div class="form-btn" style="display:flex; justify-content: space-between;">
                            <button class="vs-btn style4" type="button" id="prevStepBtn3"><i class="far fa-arrow-left"></i> Back</button>
                            <button class="vs-btn style5" type="button" id="nextStepBtn3">Next Step <i class="far fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- STEP 4 -->
                    <div class="form-step" id="step4">
                        <h2 class="form-title h4">Bank Account Details of Nominee (Optional)</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Nominee Account Holder Name" name="bank_account_name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Account Number" name="account_number">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="IFSC Code" name="ifsc_code">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="Nominee Bank with Branch Name" name="bank_branch">
                            </div>
                        </div>

                        <h4 style="margin-top: 20px;">Declaration</h4>
                        <p id="declarationText" style="color:#fff; font-size: 15px; line-height: 1.6;">
                            I, <span id="declarationName">_________________</span>, HEREBY DECLARE THAT ALL THE ABOVE INFORMATION PROVIDED IS TRUE AND CORRECT TO THE BEST OF MY KNOWLEDGE. I AGREE TO ABIDE BY THE RULES AND REGULATIONS OF THE NIGHTINGALES OF KUWAIT (NOK). I UNDERSTAND THAT ALL SERVICES AND BENEFITS PROVIDED BY NOK WILL BE AVAILABLE ONLY DURING THE PERIOD OF ACTIVE MEMBERSHIP.
                        </p>

                        <div class="form-btn" style="display:flex; justify-content: space-between;">
                            <button class="vs-btn style4" type="button" id="prevStepBtn4"><i class="far fa-arrow-left"></i> Back</button>
                            <button class="vs-btn style5" type="submit">Submit <i class="far fa-paper-plane"></i></button>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>
</section>


<script>
// Initialize intl-tel-input for phone fields
let mobileInput, whatsappInput, phoneIndiaInput;
let itiMobile, itiWhatsapp, itiPhoneIndia;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize mobile field (Kuwait default)
    mobileInput = document.querySelector("#mobile");
    itiMobile = window.intlTelInput(mobileInput, {
        initialCountry: "kw",
        preferredCountries: ["kw", "in"],
        separateDialCode: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js"
    });

    // Initialize WhatsApp field (auto-detect)
    whatsappInput = document.querySelector("#whatsapp");
    itiWhatsapp = window.intlTelInput(whatsappInput, {
        initialCountry: "kw",
        preferredCountries: ["kw", "in"],
        separateDialCode: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js"
    });

    // Initialize India phone field (India default)
    phoneIndiaInput = document.querySelector("#phone_india");
    itiPhoneIndia = window.intlTelInput(phoneIndiaInput, {
        initialCountry: "in",
        preferredCountries: ["in"],
        separateDialCode: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js"
    });

    // Update hidden fields with full international number before form submission
    const form = document.querySelector('.register-form');
    form.addEventListener('submit', function() {
        if (itiMobile.isValidNumber()) {
            document.getElementById('mobile_full').value = itiMobile.getNumber();
        }
        if (whatsappInput.value && itiWhatsapp.isValidNumber()) {
            document.getElementById('whatsapp_full').value = itiWhatsapp.getNumber();
        }
        if (itiPhoneIndia.isValidNumber()) {
            document.getElementById('phone_india_full').value = itiPhoneIndia.getNumber();
        }
    });
});

// --- Regex rules for validation ---
const regexRules = {
    memberName: /^[a-zA-Z\s]{2,50}$/,
    age: /^(1[89]|[2-6][0-9]|70)$/,
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    passport: /^[A-PR-WY][1-9]\d{5,7}$/,
    civil_id: /^\d{12}$/,
    nok_id: /^[a-zA-Z0-9]{4,20}$/,
    doj: /^\d{4}-\d{2}-\d{2}$/,
    department: /^[a-zA-Z\s]{2,50}$/,
    job_title: /^[a-zA-Z\s]{2,50}$/,
    institution: /^[a-zA-Z0-9\s]{2,100}$/,
    blood_group: /^(A|B|AB|O)[+-]$/,
    address: /^.{5,250}$/,
    nominee_name: /^[a-zA-Z\s]{2,50}$/,
    nominee_relation: /^[a-zA-Z\s]{2,30}$/,
    nominee_contact: /^.{5,20}$/,
    guardian_name: /^[a-zA-Z\s]{2,50}$/,
    guardian_contact: /^.{5,20}$/,
    bank_account_name: /^[a-zA-Z\s]{2,50}$/,
    account_number: /^\d{6,20}$/,
    ifsc_code: /^[A-Z]{4}0[A-Z0-9]{6}$/,
    bank_branch: /^.{2,100}$/
};

// Country-specific phone validation
function validatePhoneByCountry(iti, fieldName) {
    if (!iti) return { isValid: false, message: "Phone field not initialized" };
    
    const number = iti.getNumber();
    const countryCode = iti.getSelectedCountryData().dialCode;
    const nationalNumber = iti.getNumber(intlTelInputUtils.numberFormat.NATIONAL).replace(/\D/g, '');
    
    // Kuwait validation: 8 digits starting with 5, 6, or 9
    if (countryCode === '965') {
        if (nationalNumber.length !== 8) {
            return { isValid: false, message: "Kuwait number must be 8 digits" };
        }
        if (!['5', '6', '9'].includes(nationalNumber[0])) {
            return { isValid: false, message: "Kuwait number must start with 5, 6, or 9" };
        }
        return { isValid: true, message: "" };
    }
    
    // India validation: 10 digits starting with 6-9
    if (countryCode === '91') {
        if (nationalNumber.length !== 10) {
            return { isValid: false, message: "India number must be 10 digits" };
        }
        if (!['6', '7', '8', '9'].includes(nationalNumber[0])) {
            return { isValid: false, message: "India number must start with 6-9" };
        }
        return { isValid: true, message: "" };
    }
    
    // For other countries, use intl-tel-input's validation
    if (!iti.isValidNumber()) {
        return { isValid: false, message: "Invalid phone number for selected country" };
    }
    
    return { isValid: true, message: "" };
}

// Fields to check for duplicates
const duplicateCheckFields = ['email', 'mobile', 'passport', 'civil_id', 'nok_id'];
let duplicateCheckTimers = {};

// Check for duplicate email via AJAX
async function checkEmailDuplicate(email) {
    if (!email.trim()) return { exists: false };
    
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000);
        
        const response = await fetch("{{ route('registration.checkEmail') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email }),
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        if (error.name === 'AbortError') {
            console.warn('Email check timed out');
        } else {
            console.error('Email check failed:', error);
        }
        return { exists: false };
    }
}

// Check for duplicate phone via AJAX
async function checkPhoneDuplicate(phone, country) {
    if (!phone.trim()) return { exists: false };
    
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000);
        
        const response = await fetch("{{ route('registration.checkPhone') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone, country }),
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        if (error.name === 'AbortError') {
            console.warn('Phone check timed out');
        } else {
            console.error('Phone check failed:', error);
        }
        return { exists: false };
    }
}

// Check for duplicate NOK ID via AJAX
async function checkNokIdDuplicate(nokId) {
    if (!nokId.trim()) return { exists: false };
    
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000);
        
        const response = await fetch("{{ route('registration.checkNokId') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nok_id: nokId }),
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        if (error.name === 'AbortError') {
            console.warn('NOK ID check timed out');
        } else {
            console.error('NOK ID check failed:', error);
        }
        return { exists: false };
    }
}

// Check for duplicate in database via AJAX (for other fields)
async function checkDuplicate(field, value) {
    if (!value.trim()) return { exists: false };
    
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000);
        
        const response = await fetch("{{ route('registration.checkDuplicate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ field, value }),
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        if (error.name === 'AbortError') {
            console.warn('Duplicate check timed out for', field);
        } else {
            console.error('Duplicate check failed:', error);
        }
        return { exists: false };
    }
}

// Validate individual input
async function validateInput(input) {
    // Skip validation for member_type fields (toggle button fields)
    if (input.name === 'member_type' || input.name === 'member_type_checkbox') {
        return true;
    }
    
    // Handle radio buttons differently
    if (input.type === 'radio') {
        const genderRow = input.closest('.gender-row');
        const msgEl = genderRow ? genderRow.querySelector('.validation-message') : null;
        if (!msgEl) return true;
        
        // Check if any radio button with this name is selected
        const isSelected = document.querySelector(`input[name="${input.name}"]:checked`);
        if (!isSelected) {
            msgEl.textContent = "✗ Please select a gender.";
            msgEl.style.color = "red";
            return false;
        }
        
        // Don't show any message for valid gender selection
        msgEl.textContent = "";
        return true;
    }
    
    const msgEl = input.parentNode.querySelector('.validation-message');
    if (!msgEl) return true;

    const val = input.value.trim();
    let isValid = true, errorMsg = "";

    // Handle phone fields with intl-tel-input
    if (input.id === 'mobile' && itiMobile) {
        if (input.hasAttribute("required") && !val) {
            isValid = false;
            errorMsg = "This field is required.";
        } else if (val) {
            const phoneValidation = validatePhoneByCountry(itiMobile, 'mobile');
            if (!phoneValidation.isValid) {
                isValid = false;
                errorMsg = phoneValidation.message;
            } else {
                // Check for duplicate phone
                msgEl.textContent = "Checking...";
                msgEl.style.color = "orange";
                input.style.borderColor = "orange";
                
                const fullNumber = itiMobile.getNumber();
                const country = itiMobile.getSelectedCountryData().iso2;
                const duplicateResult = await checkPhoneDuplicate(fullNumber, country);
                if (duplicateResult.exists) {
                    isValid = false;
                    errorMsg = "⚠️ This phone number is already registered.";
                }
            }
        }
    } else if (input.id === 'whatsapp' && itiWhatsapp) {
        if (val) {
            const phoneValidation = validatePhoneByCountry(itiWhatsapp, 'whatsapp');
            if (!phoneValidation.isValid) {
                isValid = false;
                errorMsg = phoneValidation.message;
            }
        }
    } else if (input.id === 'phone_india' && itiPhoneIndia) {
        if (input.hasAttribute("required") && !val) {
            isValid = false;
            errorMsg = "This field is required.";
        } else if (val) {
            const phoneValidation = validatePhoneByCountry(itiPhoneIndia, 'phone_india');
            if (!phoneValidation.isValid) {
                isValid = false;
                errorMsg = phoneValidation.message;
            }
        }
    } else if (input.id === 'email') {
        // Handle email field
        if (input.hasAttribute("required") && !val) {
            isValid = false;
            errorMsg = "This field is required.";
        } else if (!regexRules.email.test(val)) {
            isValid = false;
            errorMsg = "Invalid email format.";
        } else {
            // Check for duplicate email
            msgEl.textContent = "Checking...";
            msgEl.style.color = "orange";
            input.style.borderColor = "orange";
            
            const duplicateResult = await checkEmailDuplicate(val);
            if (duplicateResult.exists) {
                isValid = false;
                errorMsg = "⚠️ This email is already registered.";
            }
        }
    } else if (input.name === 'nok_id') {
        // Handle NOK ID field with live duplicate checking
        if (input.hasAttribute("required") && !val) {
            isValid = false;
            errorMsg = "This field is required.";
        } else if (val) {
            // Check format first
            if (regexRules.nok_id && !regexRules.nok_id.test(val)) {
                isValid = false;
                errorMsg = "Invalid NOK ID format.";
            } else {
                // Check for duplicate NOK ID
                msgEl.textContent = "Checking...";
                msgEl.style.color = "orange";
                input.style.borderColor = "orange";
                
                const duplicateResult = await checkNokIdDuplicate(val);
                if (duplicateResult.exists) {
                    isValid = false;
                    errorMsg = "⚠️ This NOK ID already exists.";
                }
            }
        }
    } else {
        // Handle other fields
        if (input.hasAttribute("required") && !val) {
            isValid = false;
            errorMsg = "This field is required.";
        } else if (regexRules[input.name]) {
            isValid = regexRules[input.name].test(val);
            if (!isValid) {
                errorMsg = "Invalid " + input.name.replace("_", " ") + ".";
            }
            
            // Check for duplicates only if format is valid (for other fields like passport, civil_id)
            if (isValid && duplicateCheckFields.includes(input.name) && input.name !== 'nok_id') {
                msgEl.textContent = "Checking...";
                msgEl.style.color = "orange";
                input.style.borderColor = "orange";
                
                const duplicateResult = await checkDuplicate(input.name, val);
                if (duplicateResult.exists) {
                    isValid = false;
                    errorMsg = duplicateResult.message || "This value is already registered.";
                }
            }
        }
    }

    // Special handling for NOK ID - show errors but not success messages
    if (input.name === 'nok_id') {
        if (isValid) {
            msgEl.textContent = ""; // No "Looks good!" message
            input.style.borderColor = ""; // Reset border color
        } else {
            msgEl.textContent = "✗ " + errorMsg; // Show error message (including duplicate)
            input.style.borderColor = "red";
            msgEl.style.color = "red";
        }
    } 
    // Don't show validation feedback for DOJ field
    else if (input.name === 'doj') {
        if (isValid) {
            msgEl.textContent = ""; // No message when valid
            input.style.borderColor = ""; // Reset border color
        } else {
            msgEl.textContent = "✗ " + errorMsg; // Show error when invalid
            input.style.borderColor = "red";
            msgEl.style.color = "red";
        }
    } else {
        // All other fields show full validation feedback
        input.style.borderColor = isValid ? "green" : "red";
        msgEl.style.color = isValid ? "limegreen" : "red";
        msgEl.textContent = isValid ? "✓ Looks good!" : "✗ " + errorMsg;
    }

    return isValid;
}

// Check all required inputs in a step (async version)
async function checkStepValidityAsync(step) {
    const inputs = Array.from(step.querySelectorAll("input[required]:not([type=hidden]), select[required], textarea[required]"))
        .filter(input => input.offsetParent !== null); // Only visible inputs
    
    const results = await Promise.all(inputs.map(input => validateInput(input)));
    return results.every(result => result === true);
}

// Check all required inputs in a step (sync version for backward compatibility)
function checkStepValidity(step) {
    const inputs = step.querySelectorAll("input[required]:not([type=hidden]), select[required], textarea[required]");
    return Array.from(inputs)
        .filter(input => input.offsetParent !== null) // Only visible inputs
        .every(input => {
            const msgEl = input.parentNode.querySelector('.validation-message');
            const val = input.value.trim();
            if (input.hasAttribute("required") && !val) return false;
            if (regexRules[input.name] && !regexRules[input.name].test(val)) return false;
            return true;
        });
}

// Check all form inputs (async version)
async function checkFormValidityAsync() {
    const inputs = Array.from(document.querySelectorAll(".register-form input[required], .register-form select[required], .register-form textarea[required]"))
        .filter(input => input.offsetParent !== null); // Only visible inputs
    
    const results = await Promise.all(inputs.map(input => validateInput(input)));
    return results.every(result => result === true);
}

// Check all form inputs (sync version for backward compatibility)
function checkFormValidity() {
    const inputs = document.querySelectorAll(".register-form input[required], .register-form select[required], .register-form textarea[required]");
    return Array.from(inputs)
        .filter(input => input.offsetParent !== null) // Only visible inputs
        .every(input => {
            const val = input.value.trim();
            if (input.hasAttribute("required") && !val) return false;
            if (regexRules[input.name] && !regexRules[input.name].test(val)) return false;
            return true;
        });
}

// Add validation messages dynamically
document.querySelectorAll('.register-form input, .register-form select, .register-form textarea').forEach(input => {
    // Skip hidden member_type field (no validation message needed)
    if (input.name === 'member_type' || input.name === 'member_type_checkbox') {
        return;
    }
    
    // Skip radio buttons - they will be handled separately
    if (input.type === 'radio') {
        // Add validation message container to the parent row (only once)
        const genderRow = input.closest('.gender-row');
        if (genderRow && !genderRow.querySelector('.validation-message')) {
            const msgEl = document.createElement('span');
            msgEl.className = 'validation-message';
            msgEl.style.fontSize = '13px';
            msgEl.style.display = 'block';
            msgEl.style.marginTop = '5px';
            msgEl.style.width = '100%';
            genderRow.appendChild(msgEl);
        }
        
        // Add change listener for radio buttons
        input.addEventListener("change", async function () {
            const msgEl = genderRow.querySelector('.validation-message');
            if (msgEl) {
                // Don't show any message for valid gender selection
                msgEl.textContent = "";
            }
        });
        return;
    }
    
    // For other inputs, add validation message to parent
    if (!input.parentNode.querySelector('.validation-message')) {
        const msgEl = document.createElement('span');
        msgEl.className = 'validation-message';
        msgEl.style.fontSize = '13px';
        msgEl.style.display = 'block';
        msgEl.style.marginTop = '5px';
        input.parentNode.appendChild(msgEl);
    }

    // Validation function to be called on input and blur
    const runValidation = async () => {
        await validateInput(input);
        
        const step = input.closest(".form-step");
        const nextBtn = step ? step.querySelector(".vs-btn.style5[type='button']") : null;
        if (nextBtn) {
            const isStepValid = await checkStepValidityAsync(step);
            nextBtn.disabled = !isStepValid;
        }
    };

    input.addEventListener("input", function () {
        // Clear existing timer for this field
        if (duplicateCheckTimers[input.name]) {
            clearTimeout(duplicateCheckTimers[input.name]);
        }
        
        // Debounce validation and duplicate checking (wait 800ms after user stops typing)
        duplicateCheckTimers[input.name] = setTimeout(runValidation, 800);
    });
    
    // Also validate on blur (when user leaves the field)
    input.addEventListener("blur", function () {
        // Clear any pending debounced validation
        if (duplicateCheckTimers[input.name]) {
            clearTimeout(duplicateCheckTimers[input.name]);
        }
        // Run validation immediately on blur
        runValidation();
    });
});

// Step Navigation
let currentStep = 0;
const steps = ["step1","step2","step3","step4"];
function showStep(index) {
    steps.forEach((id,i)=>{
        document.getElementById(id).classList.toggle("active", i===index);
    });
}

// Next buttons (using async validation)
document.getElementById("nextStepBtn1").addEventListener("click", async () => {
    const step1 = document.getElementById('step1');
    const btn = document.getElementById("nextStepBtn1");
    btn.disabled = true;
    btn.textContent = "Validating...";
    
    if (await checkStepValidityAsync(step1)) {
        currentStep++;
        showStep(currentStep);
    }
    
    btn.disabled = false;
    btn.innerHTML = 'Next Step <i class="far fa-arrow-right"></i>';
});

document.getElementById("nextStepBtn2").addEventListener("click", async () => {
    const step2 = document.getElementById('step2');
    const btn = document.getElementById("nextStepBtn2");
    btn.disabled = true;
    btn.textContent = "Validating...";
    
    if (await checkStepValidityAsync(step2)) {
        currentStep++;
        showStep(currentStep);
    }
    
    btn.disabled = false;
    btn.innerHTML = 'Next Step <i class="far fa-arrow-right"></i>';
});

document.getElementById("nextStepBtn3").addEventListener("click", async () => {
    const step3 = document.getElementById('step3');
    const btn = document.getElementById("nextStepBtn3");
    btn.disabled = true;
    btn.textContent = "Validating...";
    
    if (await checkStepValidityAsync(step3)) {
        currentStep++;
        document.getElementById("declarationName").textContent = document.getElementById("memberName").value || "_________________";
        showStep(currentStep);
    }
    
    btn.disabled = false;
    btn.innerHTML = 'Next Step <i class="far fa-arrow-right"></i>';
});

// Prev buttons
document.getElementById("prevStepBtn2").addEventListener("click", () => { currentStep--; showStep(currentStep); });
document.getElementById("prevStepBtn3").addEventListener("click", () => { currentStep--; showStep(currentStep); });
document.getElementById("prevStepBtn4").addEventListener("click", () => { currentStep--; showStep(currentStep); });

// Member type toggle
const memberSwitch = document.getElementById('memberSwitch');
const memberStatusText = document.getElementById('memberStatusText');
const hiddenInput = document.querySelector('input[name="member_type"]');
const existingFields = document.querySelector('.existing-member-fields');

// Remove any validation messages from member_type fields (they shouldn't have any)
const memberTypeValidationMsg = hiddenInput?.parentNode?.querySelector('.validation-message');
if (memberTypeValidationMsg) {
    memberTypeValidationMsg.remove();
}
const memberSwitchValidationMsg = memberSwitch?.closest('.form-row')?.querySelector('.validation-message');
if (memberSwitchValidationMsg) {
    memberSwitchValidationMsg.remove();
}

// Initialize toggle state - ensure fields are hidden and cleared
hiddenInput.value = "new";
existingFields.style.display = "none";

// Clear and reset existing member fields on page load
existingFields.querySelectorAll('input').forEach(input => {
    input.removeAttribute('required');
    input.value = '';
    
    // Ensure validation message element exists for NOK ID and DOJ fields
    if (!input.parentNode.querySelector('.validation-message')) {
        const msgEl = document.createElement('span');
        msgEl.className = 'validation-message';
        msgEl.style.fontSize = '13px';
        msgEl.style.display = 'block';
        msgEl.style.marginTop = '5px';
        input.parentNode.appendChild(msgEl);
    }
    
    const msgEl = input.parentNode.querySelector('.validation-message');
    if (msgEl) {
        msgEl.textContent = '';
    }
    input.style.borderColor = '';
});

memberSwitch.addEventListener('change', async function(){
    if(this.checked){
        memberStatusText.textContent = "Already a Member";
        hiddenInput.value = "existing";
        existingFields.style.display = "flex";
        // NOK ID and DOJ are REQUIRED when existing member
        existingFields.querySelectorAll('input').forEach(input => {
            input.setAttribute('required', 'required');
        });
        
        // Trigger validation for both fields when they become visible and required
        // This ensures "This field is required" shows immediately for empty fields
        setTimeout(async () => {
            for (const input of existingFields.querySelectorAll('input')) {
                await validateInput(input);
            }
        }, 100);
    } else {
        memberStatusText.textContent = "Already Member (Optional)";
        hiddenInput.value = "new";
        existingFields.style.display = "none";
        // Remove required attribute and clear values when hidden
        existingFields.querySelectorAll('input').forEach(input => {
            input.removeAttribute('required');
            input.value = ''; // Clear the field value
            // Clear any validation messages
            const msgEl = input.parentNode?.querySelector('.validation-message');
            if (msgEl) {
                msgEl.textContent = '';
            }
            // Reset border color
            input.style.borderColor = '';
        });
    }
});


</script>


<!-- intl-tel-input CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Form submission with validation
document.querySelector('.register-form').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    
    // Final validation check before submission
    const isFormValid = await checkFormValidityAsync();
    if (!isFormValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation Error',
            text: 'Please fix all validation errors before submitting.',
            confirmButtonText: 'OK',
        });
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        return;
    }
    
    let formData = new FormData(form);

    fetch("{{ route('registration.submit') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(async response => {
        let data = await response.json();
        if (response.ok && data.status === 'success') {
            Swal.fire({
                icon: "success",
                title: "Registration Successful!",
                html: data.message + '<br><br><small>Redirecting in a moment...</small>',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal-wide',
                    title: 'swal-title-success'
                }
            }).then(() => {
                // Refresh the page for a clean form
                window.location.reload();
            });

        } else if (response.status === 422) {
            let errors = data.errors;
            let errorList = '<ul style="text-align:left; padding-left: 20px;">';
            for (let field in errors) {
                errors[field].forEach(msg => { errorList += `<li style="margin: 5px 0;">${msg}</li>`; });
            }
            errorList += '</ul>';

            Swal.fire({
                icon: 'error',
                title: '❌ Validation Errors',
                html: errorList,
                confirmButtonText: 'Fix Errors',
                customClass: {
                    htmlContainer: 'swal-error-list'
                }
            });
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Something went wrong!'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    })
    .catch(error => {
        console.error('Form submission error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Connection Error',
            text: 'Unable to connect to the server. Please check your internet connection.'
        });
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});
</script>



<style>
/* intl-tel-input styling */
.iti {
    width: 100%;
}

.iti__flag-container {
    height: 100%;
}

.iti__selected-flag {
    height: 100%;
    padding: 0 8px;
}

.iti__country-list {
    z-index: 999;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

#mobile, #whatsapp, #phone_india {
    width: 100%;
    padding: 12px 12px 12px 52px !important;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 15px;
}

/* Progress Bar */
.progress-container {
    position: relative;
    height: 8px;
    background: #444;
    border-radius: 5px;
    margin-bottom: 20px;
}

.progress-bar {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 0%;
    background: #4caf50;
    border-radius: 5px;
    transition: width 0.3s ease;
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.progress-steps .step {
    width: 25px;
    height: 25px;
    background: #ccc;
    border-radius: 50%;
    text-align: center;
    line-height: 25px;
    color: #fff;
    font-weight: bold;
}

.progress-steps .step.active {
    background: #ff9800;
}

.progress-steps .step.completed {
    background: #4caf50;
}



.regi-form{
    display: flex; justify-content: center; align-items: center; min-height: 85vh;
}
@media (max-width: 768px) {
    .regi-form {
        min-height: auto; /* remove fixed height on mobile */
    }
}

/* Switch */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 24px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}
input:checked + .slider {
  background-color: #4CAF50;
}
input:checked + .slider:before {
  transform: translateX(26px);
}

    .form-step {
    display: none;
    opacity: 0;
    transform: translateX(30px);
    transition: all 0.4s ease-in-out;
}
.form-step.active {
    display: block;
    opacity: 1;
    transform: translateX(0);
}

    /* FORCE radios to be visible */
.register-form .gender-row input[type="radio"] {
  all: unset; /* Reset any theme overrides */
  display: inline-block !important;
  appearance: none !important;
  -webkit-appearance: none !important;
  -moz-appearance: none !important;
  width: 18px !important;
  height: 18px !important;
  border: 2px solid #fff !important;
  border-radius: 50% !important;
  margin-right: 6px !important;
  background: transparent !important;
  position: relative;
  cursor: pointer;
  vertical-align: middle;
}

/* Show custom fill when selected */
.register-form .gender-row input[type="radio"]:checked::before {
  content: "";
  position: absolute;
  top: 3px;
  left: 3px;
  width: 8px;
  height: 8px;
  background: #ff9800;
  border-radius: 50%;
  display: block;
}

/* Align labels left */
.register-form .gender-row {
  display: flex;
  justify-content: flex-start;
  gap: 20px;
  margin-bottom: 8px;
}

.register-form .gender-row label {
  display: flex;
  align-items: center;
  color: #fff;
  font-size: 15px;
  cursor: pointer;
}

/* Mobile - stack vertically */
@media (max-width: 768px) {
  .register-form .gender-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
}

    /* Base form row layout */
    .register-form {
        width: 800px;
    }
/* Base form row layout */
.register-form .form-row {
  display: flex;
  gap: 15px;
  
}

.register-form .form-group {
  flex: 1;
}

.register-form input,
.register-form select {
  width: 100%;
  padding: 12px;
  border-radius: 6px;
  border: 1px solid #ddd;
  font-size: 15px;
}

/* Gender row style - left aligned */
.register-form .gender-row {
  display: flex;
  gap: 20px;
  align-items: center;
  justify-content: flex-start; /* Align to left */
  margin-bottom: 15px;
}

.register-form .gender-row label {
  color: #fff; /* Make visible on dark background */
  font-weight: 500;
  font-size: 15px;
  display: flex;
  align-items: center;
}

.register-form .gender-row input[type="radio"] {
  margin-right: 6px;
  accent-color: #ff9800; /* Highlight color */
  transform: scale(1.1); /* Slightly bigger for better visibility */
  cursor: pointer;
}

/* Mobile responsive - single field per row */
@media (max-width: 768px) {
  .register-form .form-row {
    flex-direction: column;
  }
  .register-form .gender-row {
    flex-direction: column;
    align-items: flex-start; /* Stack left on mobile */
    gap: 10px;
  }
}


    .form-extra {
  margin-top: 20px;
  text-align: center;
  color: #fff;
  font-size: 17px;
}

.form-extra a {
  color: #fff;
  text-decoration: underline;
  font-weight: 500;
}

.form-extra a.register-link {
  font-weight: 500;
}
.image-overlay-dark {
  position: relative;
  background-size: cover;
  background-position: center;
}

.image-overlay-dark::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5); /* Change 0.5 for darker/lighter effect */
  z-index: 1;
}

.image-overlay-dark > * {
  position: relative;
  z-index: 2; /* keeps content visible above overlay */
}

.swal-title-small {
    font-size: 14px !important; /* adjust as needed */
}
</style>



    <style>
/* ===== Custom Footer Background ===== */
.custom-footer-bg {
  background-image: url("nokw/assets//img/fooot.jpg");
  background-size: cover;      
  background-position: center;  
  background-repeat: no-repeat;
  width: 100%;
}

/* Optional: Improve mobile visibility */
@media (max-width: 768px) {
  .custom-footer-bg {
    background-position: top center; 
    background-size: cover;       
  }
}

</style>



@endsection
