<x-register-layout>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
                <!-- <form id="registerForm" enctype="multipart/form-data"> -->
            @csrf
              <div class="form-row row gy-3 overflow-hidden">

                <div class="form-group col-md-6">
                  <div class="form-floating mb-3">
                    <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    <x-input-label for="name" :value="__('Full Name') . ' *'" />
                  </div>
                </div>

                <div class="form-group col-md-6">
                  <div class="form-floating mb-3">
                    <x-text-input id="email" class="form-control " type="email" name="email" :value="old('email')" required autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    <x-input-label for="email" :value="__('Email') . ' *'" />
                  </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                         <x-text-input id="position" class="form-control" type="text" name="position" :value="old('position')" required autofocus autocomplete="position" />
                         <x-input-error :messages="$errors->get('position')" class="mt-2" />
                         <x-input-label for="position" :value="__('Position') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <select id="office" name="office" class="form-control" required>
                        <option value="" disabled selected>Select Office</option>
                            <option value="Department of Environment and Natural Resources" {{ old('office') == 'Department of Environment and Natural Resources' ? 'selected' : '' }}>Department of Environment and Natural Resources</option>
                            <option value="Environmental Management Bureau" {{ old('office') == 'Environmental Management Bureau' ? 'selected' : '' }}>Environmental Management Bureau</option>
                            <option value="Mines and Geosciences Bureau" {{ old('office') == 'Mines and Geosciences Bureau' ? 'selected' : '' }}>Mines and Geosciences Bureau</option>
                            <option value="PENRO Bukidnon" {{ old('office') == 'PENRO Bukidnon' ? 'selected' : '' }}>PENRO Bukidnon</option>
                            <option value="PENRO Camiguin" {{ old('office') == 'PENRO Camiguin' ? 'selected' : '' }}>PENRO Camiguin</option>
                            <option value="PENRO Lanao Del Norte" {{ old('office') == 'PENRO Lanao Del Norte' ? 'selected' : '' }}>PENRO Lanao Del Norte</option>
                            <option value="PENRO Misamis Occidental" {{ old('office') == 'PENRO Misamis Occidental' ? 'selected' : '' }}>PENRO Misamis Occidental</option>
                            <option value="PENRO Misamis Oriental" {{ old('office') == 'PENRO Misamis Oriental' ? 'selected' : '' }}>PENRO Misamis Oriental</option>
                            <option value="CENRO Don Carlos" {{ old('office') == 'Other' ? 'CENRO Don Carlos' : '' }}>CENRO Don Carlos</option>
                            <option value="CENRO Manolo Fortich" {{ old('office') == 'CENRO Manolo Fortich' ? 'selected' : '' }}>CENRO Manolo Fortich</option>
                            <option value="CENRO Valencia" {{ old('office') == 'CENRO Valencia' ? 'selected' : '' }}>CENRO Valencia</option>
                            <option value="CENRO Talakag" {{ old('office') == 'CENRO Talakag' ? 'selected' : '' }}>CENRO Talakag</option>
                            <option value="CENRO Iligan" {{ old('office') == 'CENRO Iligan' ? 'selected' : '' }}>CENRO Iligan</option>
                            <option value="CENRO Kolambugan" {{ old('office') == 'CENRO Kolambugan' ? 'selected' : '' }}>CENRO Kolambugan</option>
                            <option value="CENRO Oroquieta" {{ old('office') == 'CENRO Oroquieta' ? 'selected' : '' }}>CENRO Oroquieta</option>
                            <option value="CENRO Ozamis" {{ old('office') == 'CENRO Ozamis' ? 'selected' : '' }}>CENRO Ozamis</option>
                            <option value="CENRO Gingoog" {{ old('office') == 'CENRO Gingoog' ? 'selected' : '' }}>CENRO Gingoog</option>
                            <option value="CENRO Initao" {{ old('office') == 'CENRO Initao' ? 'selected' : '' }}>CENRO Initao</option>
                        </select>
                        <x-input-error :messages="$errors->get('office')" class="mt-2" />
                        <x-input-label for="office" :value="__('Office') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="address" class="form-control" type="text" name="address" :value="old('address')" required autocomplete="address" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        <x-input-label for="address" :value="__('Address') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="religion" class="form-control" type="text" name="religion" :value="old('religion')" required autocomplete="religion" />
                        <x-input-error :messages="$errors->get('religion')" class="mt-2" />
                        <x-input-label for="religion" :value="__('Religion') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <select id="sex" name="sex" class="form-control" required>
                        <option value="" disabled selected>Select Sex</option>
                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                        <x-input-label for="sex" :value="__('Sex') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                    <select id="marital_status" name="marital_status" class="form-control" required>
                        <option value="" disabled selected>Select Marital Status</option>
                        <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                    <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
                    <x-input-label for="marital_status" :value="__('Marital Status') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="annual_income" class="form-control" type="text" name="annual_income" :value="old('annual_income')" required autocomplete="annual_income" />
                        <x-input-error :messages="$errors->get('annual_income')" class="mt-2" />
                        <x-input-label for="annual_income" :value="__('Annual Income') . ' *'" />
                        <small>Do not add a comma (,)</small>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="beneficiaries" class="form-control" type="text" name="beneficiaries" :value="old('beneficiaries')" required autocomplete="beneficiaries" />
                        <x-input-error :messages="$errors->get('beneficiaries')" class="mt-2" />
                        <x-input-label for="beneficiaries" :value="__('Name of Beneficiary/ies') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <x-input-label for="birthdate" :value="__('Birthdate') . ' *'" />
                    <div class="d-flex gap-2">
                        <!-- Month Dropdown -->
                        <select id="birth_month" name="birth_month" class="form-control" required>
                            <option value="" disabled selected>Month</option>
                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $month)
                                <option value="{{ $index + 1 }}" {{ old('birth_month') == ($index + 1) ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>

                        <!-- Day Dropdown -->
                        <select id="birth_day" name="birth_day" class="form-control" required>
                            <option value="" disabled selected>Day</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ old('birth_day') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>

                        <!-- Year Dropdown -->
                        <select id="birth_year" name="birth_year" class="form-control" required>
                            <option value="" disabled selected>Year</option>
                            @for($year = date('Y') - 18; $year >= 1900; $year--)
                                <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                </div>


                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="education" class="form-control" type="text" name="education" :value="old('education')" required autocomplete="education" />
                        <x-input-error :messages="$errors->get('education')" class="mt-2" />
                        <x-input-label for="education" :value="__('Educational Attainment') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <select id="employment_status" name="employment_status" class="form-control" required>
                        <option value="" disabled selected>Select Employment Status</option>
                        <option value="Regular" {{ old('employment_status') == 'Regular' ? 'selected' : '' }}>Regular</option>
                        <option value="Casual" {{ old('employment_status') == 'Casual' ? 'selected' : '' }}>Casual</option>
                        <option value="Contract of Service" {{ old('employment_status') == 'Contract of Service' ? 'selected' : '' }}>Contract of Service</option>
                    </select>
                        <x-input-label for="employment_status" :value="__('Employment Status') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="contact_no" class="form-control" type="text" name="contact_no" :value="old('contact_no')" required autocomplete="contact_no" />
                        <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
                        <x-input-label for="contact_no" :value="__('Contact No.') . ' *'" />
                        <small>Enter your 11 digits phone number with no space</small>
                    </div>
                </div>

                <hr/>

                <div class="col-12" style="margin: 10px 0;">
                    <h3>Monthly Contribution</h3>
                </div>
                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="shares" class="form-control" type="text" name="shares" :value="250" required autocomplete="shares" />
                        <x-input-error :messages="$errors->get('shares')" class="mt-2" />
                        <x-input-label for="shares" :value="__('Shares') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="savings" class="form-control" type="text" name="savings" :value="250" required autocomplete="savings" />
                        <x-input-error :messages="$errors->get('savings')" class="mt-2" />
                        <x-input-label for="savings" :value="__('Savings') . ' *'" />
                    </div>
                </div>

                <hr/>

                <div class="col-12" style="margin: 10px 0;">
                    <h3>Account</h3>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="username" class="form-control" type="text" name="username" :value="old('username')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        <x-input-label for="username" :value="__('Username') . ' *'" />
                    </div>
                </div>
                <!-- <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <select id="member_stat" name="member_stat" class="form-control" required>
                        <option value="" disabled selected>Select Membership Status</option>
                            <option value="New Member" {{ old('member_stat') == 'New Member' ? 'selected' : '' }}>New Member</option>
                            <option value="Old Member" {{ old('member_stat') == 'Old Member' ? 'selected' : '' }}>Old Member</option>
                        </select>
                        <x-input-error :messages="$errors->get('member_stat')" class="mt-2" />
                        <x-input-label for="member_stat" :value="__('Select Membership Status') . ' *'" />
                    </div>
                </div> -->
                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="password" class="form-control"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <x-input-label for="password" :value="__('Password') . ' *'" />
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <x-text-input id="password_confirmation" class="form-control"
                               type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        <x-input-label for="password_confirmation" :value="__('Confirm Password') . ' *'" />
                    </div>
                </div>

                <!---Terms and Conditions-->
                <!-- <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="iAgree" id="iAgree" required>
                    <label class="form-check-label text-secondary" for="iAgree">
                      I agree to the <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>
                    </label>
                  </div>
                </div> -->

                        <!-- Photo Upload -->
                <div class="form-group col-md-6">
                    <div class="form-floating mb-3">
                        <input id="photo" class="form-control" type="file" name="photo" accept="image/png, image/gif, image/jpeg" required>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                        <x-input-label for="photo" :value="__('Photo') . ' *'" />
                        <small>Photo size: 2MB Max</small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                        {{ __('Register') }}
                        </button>
                    </div>
                    </div>

              </div>
            </form>

            <!-- <div class="row">
              <div class="col-12">
                <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-4">
                  <p class="m-0 text-secondary text-center">Already have an account? <a href="{{ route('login') }}" class="link-primary text-decoration-none">Sign in</a></p>
                </div>
              </div>
            </div> -->


<!-- Verify Email Modal -->
<div id="verifyEmailModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">  <!-- Ensure modal has a size -->
        <div class="modal-content">
            <div class="modal-header" style="background: #20be50;color: #ffffff;">
                <h5 class="modal-title">Thank You For Signin Up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(0);color:#ffffff;padding-top: 0;">X</button>
            </div>
            <div class="modal-body">
                <iframe id="verifyEmailFrame" src="" style="width:100%; height:400px; border:none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal for displaying error messages -->
<div id="errorModal" class="modal fade" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="errorMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white" data-bs-dismiss="modal" style="background: #069B33;color: #ffffff !important;">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- JavaScript for AJAX Registration -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");
    const registerButton = registerForm.querySelector("button[type='submit']");
    const errorModalEl = document.getElementById("errorModal");
    const errorMessageEl = document.getElementById("errorMessage");
    const modalElement = document.getElementById("verifyEmailModal");
    const verifyEmailFrame = document.getElementById("verifyEmailFrame");

    // 🛑 Ensure JS is running
    console.log("✅ JS Loaded");

    registerForm.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent page reload

        const formData = new FormData(registerForm);
        let hasErrors = false;

        // Basic client-side validation
        document.querySelectorAll("#registerForm [required]").forEach(function (input) {
            input.classList.remove("is-invalid");
            if (!input.value.trim()) {
                input.classList.add("is-invalid");
                hasErrors = true;
            }
        });

        if (hasErrors) {
            errorMessageEl.innerText = "Please fill in all required fields.";
            new bootstrap.Modal(errorModalEl).show();
            return;
        }

        // Disable button
        registerButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span> Processing...`;
        registerButton.disabled = true;

        fetch("{{ route('register') }}", {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            console.log("✅ Server response:", data);

            if (response.ok && data.success) {
                const pdfUrl = `/user/pdf/${data.user_id}`;
                verifyEmailFrame.srcdoc = `
                    <div style='padding:2rem; text-align:center'>
                        <h2>Thank you for registering!</h2>
                        <p>Please check your email for additional instructions.</hp>
                    </div>
                `;
                new bootstrap.Modal(modalElement).show();
                modalElement.addEventListener("hidden.bs.modal", () => registerForm.reset());
            } else {
                throw data;
            }
        })
        .catch(error => {
            console.warn("⚠️ Registration failed:", error);
            let errorHtml = "Something went wrong.";

            if (error.errors) {
                errorHtml = "";
                for (const field in error.errors) {
                    errorHtml += `<div class="text-danger mb-1">• ${error.errors[field][0]}</div>`;
                }
            }

            errorMessageEl.innerHTML = errorHtml;
            new bootstrap.Modal(errorModalEl).show();
        })
        .finally(() => {
            registerButton.innerHTML = "Register";
            registerButton.disabled = false;
        });
    });
});
</script>



</x-register-layout>
