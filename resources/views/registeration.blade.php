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
                            <label><input type="radio" name="gender" value="Transgender"> Transgender</label>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="email" placeholder="Email Address *" name="email" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Kuwait Mobile (+965XXXXXXXX) *" name="mobile" required pattern="^\+965[0-9]{8}$">
                                <small style="color: #ddd; font-size: 11px;">Format: +965XXXXXXXX</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="WhatsApp Number (+965XXXXXXXX)" name="whatsapp" pattern="^\+965[0-9]{8}$">
                                <small style="color: #ddd; font-size: 11px;">Optional, format: +965XXXXXXXX</small>
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
                                <input type="text" placeholder="India Phone (+91XXXXXXXXXX) *" name="phone_india" required pattern="^\+91[0-9]{10}$">
                                <small style="color: #ddd; font-size: 11px;">Format: +91XXXXXXXXXX (10 digits)</small>
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
// --- Regex rules for validation ---
const regexRules = {
    memberName: /^[a-zA-Z\s]{2,50}$/,
    age: /^(1[89]|[2-6][0-9]|70)$/,
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    mobile: /^(?:\+965)?[569]\d{7}$/,
    whatsapp: /^(?:\+965)?[569]\d{7}$/,
    passport: /^[A-PR-WY][1-9]\d{5,7}$/,
    civil_id: /^\d{12}$/,
    nok_id: /^[a-zA-Z0-9]{4,20}$/,
    doj: /^\d{4}-\d{2}-\d{2}$/,
    department: /^[a-zA-Z\s]{2,50}$/,
    job_title: /^[a-zA-Z\s]{2,50}$/,
    institution: /^[a-zA-Z0-9\s]{2,100}$/,
    blood_group: /^(A|B|AB|O)[+-]$/,
    address: /^.{5,250}$/,
    phone_india: /^(?:\+91)?[6-9]\d{9}$/,
    nominee_name: /^[a-zA-Z\s]{2,50}$/,
    nominee_relation: /^[a-zA-Z\s]{2,30}$/,
    nominee_contact: /^(?:\+91)?[6-9]\d{9}$/,
    guardian_name: /^[a-zA-Z\s]{2,50}$/,
    guardian_contact: /^(?:\+91)?[6-9]\d{9}$/,
    bank_account_name: /^[a-zA-Z\s]{2,50}$/,
    account_number: /^\d{6,20}$/,
    ifsc_code: /^[A-Z]{4}0[A-Z0-9]{6}$/,
    bank_branch: /^.{2,100}$/
};

// Fields to check for duplicates
const duplicateCheckFields = ['email', 'mobile', 'passport', 'civil_id'];
let duplicateCheckTimers = {};

// Check for duplicate in database via AJAX
async function checkDuplicate(field, value) {
    if (!value.trim()) return { exists: false };
    
    try {
        // Create an AbortController for timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 second timeout
        
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
        // Return false on error to allow submission (fail-open approach)
        return { exists: false };
    }
}

// Validate individual input
async function validateInput(input) {
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
        
        msgEl.textContent = "✓ Looks good!";
        msgEl.style.color = "limegreen";
        return true;
    }
    
    const msgEl = input.parentNode.querySelector('.validation-message');
    if (!msgEl) return true;

    const val = input.value.trim();
    let isValid = true, errorMsg = "";

    if (input.hasAttribute("required") && !val) {
        isValid = false;
        errorMsg = "This field is required.";
    } else if (regexRules[input.name]) {
        isValid = regexRules[input.name].test(val);
        if (!isValid) {
            errorMsg = "Invalid " + input.name.replace("_", " ") + ".";
        }
        
        // Check for duplicates only if format is valid
        if (isValid && duplicateCheckFields.includes(input.name)) {
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

    input.style.borderColor = isValid ? "green" : "red";
    msgEl.style.color = isValid ? "limegreen" : "red";
    msgEl.textContent = isValid ? "✓ Looks good!" : "✗ " + errorMsg;

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
                msgEl.textContent = "✓ Looks good!";
                msgEl.style.color = "limegreen";
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

    input.addEventListener("input", function () {
        // Clear existing timer for this field
        if (duplicateCheckTimers[input.name]) {
            clearTimeout(duplicateCheckTimers[input.name]);
        }
        
        // Debounce validation and duplicate checking (wait 800ms after user stops typing)
        duplicateCheckTimers[input.name] = setTimeout(async () => {
            await validateInput(input);
            
            const step = input.closest(".form-step");
            const nextBtn = step ? step.querySelector(".vs-btn.style5[type='button']") : null;
            if (nextBtn) {
                const isStepValid = await checkStepValidityAsync(step);
                nextBtn.disabled = !isStepValid;
            }
        }, 800);
    });
    
    // Also validate on blur (when user leaves the field)
    input.addEventListener("blur", async function () {
        await validateInput(input);
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

// Initialize toggle state
hiddenInput.value = "new";
existingFields.style.display = "none";

memberSwitch.addEventListener('change', function(){
    if(this.checked){
        memberStatusText.textContent = "Already a Member";
        hiddenInput.value = "existing";
        existingFields.style.display = "flex";
        // NOK ID and DOJ are optional even when existing member
        existingFields.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
    } else {
        memberStatusText.textContent = "Already Member (Optional)";
        hiddenInput.value = "new";
        existingFields.style.display = "none";
        // Remove required attribute
        existingFields.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
    }
});


</script>


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
