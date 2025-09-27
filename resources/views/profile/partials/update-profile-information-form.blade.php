<section class="profile-cont">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
    @method('PUT') <!-- Change to match your route definition -->

    <div class="grid grid-cols-2 sm:grid-cols-2 gap-6">

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Profile Picture -->
        <div class="col-span-1 sm:col-span-2">
            <x-input-label for="photo" :value="__('Profile Picture')" />
            <div class="mt-2 flex items-center">
                @if ($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover">
                @else
                    <span class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center text-gray-500">
                        <i class="fas fa-user"></i>
                    </span>
                @endif
                <input id="photo" name="photo" type="file" accept="image/png, image/gif, image/jpeg" class="form-control mt-1 ms-4" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <!-- Position -->
        <div>
            <x-input-label for="position" :value="__('Position')" />
            <x-text-input id="position" class="form-control" type="text" name="position" :value="old('position', $user->position)" autofocus autocomplete="position" />
            <x-input-error :messages="$errors->get('position')" class="mt-2" />
        </div>

        <!-- Office -->
        <div>
            <x-input-label for="office" :value="__('Office')" />
            <select id="office" name="office" class="form-control" required>
                <option value="" disabled selected>Select Office</option>
                <option value="Department of Environment and Natural Resources" {{ old('office', $user->office) == 'Department of Environment and Natural Resources' ? 'selected' : '' }}>Department of Environment and Natural Resources</option>
                <option value="Environmental Management Bureau" {{ old('office', $user->office) == 'Environmental Management Bureau' ? 'selected' : '' }}>Environmental Management Bureau</option>
                <option value="Mines and Geosciences Bureau" {{ old('office', $user->office) == 'Mines and Geosciences Bureau' ? 'selected' : '' }}>Mines and Geosciences Bureau</option>
                <option value="PENRO Bukidnon" {{ old('office', $user->office) == 'PENRO Bukidnon' ? 'selected' : '' }}>PENRO Bukidnon</option>
                <option value="PENRO Camiguin" {{ old('office', $user->office) == 'PENRO Camiguin' ? 'selected' : '' }}>PENRO Camiguin</option>
                <option value="PENRO Lanao Del Norte" {{ old('office', $user->office) == 'PENRO Lanao Del Norte' ? 'selected' : '' }}>PENRO Lanao Del Norte</option>
                <option value="PENRO Misamis Occidental" {{ old('office', $user->office) == 'PENRO Misamis Occidental' ? 'selected' : '' }}>PENRO Misamis Occidental</option>
                <option value="PENRO Misamis Oriental" {{ old('office', $user->office) == 'PENRO Misamis Oriental' ? 'selected' : '' }}>PENRO Misamis Oriental</option>
                <option value="CENRO Don Carlos" {{ old('office', $user->office) == 'CENRO Don Carlos' ? 'selected' : '' }}>CENRO Don Carlos</option>
                <option value="CENRO Manolo Fortich" {{ old('office', $user->office) == 'CENRO Manolo Fortich' ? 'selected' : '' }}>CENRO Manolo Fortich</option>
                <option value="CENRO Valencia" {{ old('office', $user->office) == 'CENRO Valencia' ? 'selected' : '' }}>CENRO Valencia</option>
                <option value="CENRO Talakag" {{ old('office', $user->office) == 'CENRO Talakag' ? 'selected' : '' }}>CENRO Talakag</option>
                <option value="CENRO Iligan" {{ old('office', $user->office) == 'CENRO Iligan' ? 'selected' : '' }}>CENRO Iligan</option>
                <option value="CENRO Kolambugan" {{ old('office', $user->office) == 'CENRO Kolambugan' ? 'selected' : '' }}>CENRO Kolambugan</option>
                <option value="CENRO Oroquieta" {{ old('office', $user->office) == 'CENRO Oroquieta' ? 'selected' : '' }}>CENRO Oroquieta</option>
                <option value="CENRO Ozamis" {{ old('office', $user->office) == 'CENRO Ozamis' ? 'selected' : '' }}>CENRO Ozamis</option>
                <option value="CENRO Gingoog" {{ old('office', $user->office) == 'CENRO Gingoog' ? 'selected' : '' }}>CENRO Gingoog</option>
                <option value="CENRO Initao" {{ old('office', $user->office) == 'CENRO Initao' ? 'selected' : '' }}>CENRO Initao</option>
            </select>
            <x-input-error :messages="$errors->get('office')" class="mt-2" />
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" class="form-control" type="text" name="address" :value="old('address', $user->address)" autocomplete="address" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Religion -->
        <div>
            <x-input-label for="religion" :value="__('Religion')" />
            <x-text-input id="religion" class="form-control" type="text" name="religion" :value="old('religion', $user->religion)" autocomplete="religion" />
            <x-input-error :messages="$errors->get('religion')" class="mt-2" />
        </div>

        <!-- Sex -->
        <div>
            <x-input-label for="sex" :value="__('Sex')" />
            <select id="sex" name="sex" class="form-control">
                <option value="" disabled selected>Select Sex</option>
                <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('sex', $user->sex) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            <x-input-error :messages="$errors->get('sex')" class="mt-2" />
        </div>

        <!-- Marital Status -->
        <div>
            <x-input-label for="marital_status" :value="__('Marital Status')" />
            <select id="marital_status" name="marital_status" class="form-control">
                <option value="" disabled selected>Select Marital Status</option>
                <option value="Single" {{ old('marital_status', $user->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ old('marital_status', $user->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                <option value="Divorced" {{ old('marital_status', $user->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                <option value="Widowed" {{ old('marital_status', $user->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
            </select>
            <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
        </div>

        <!-- Annual Income -->
        <div>
            <x-input-label for="annual_income" :value="__('Annual Income')" />
            <x-text-input id="annual_income" class="form-control" type="text" name="annual_income" :value="old('annual_income', $user->annual_income)" autocomplete="annual_income" />
            <x-input-error :messages="$errors->get('annual_income')" class="mt-2" />
        </div>

        <!-- Beneficiaries -->
        <div>
            <x-input-label for="beneficiaries" :value="__('Beneficiary/ies')" />
            <x-text-input id="beneficiaries" class="form-control" type="text" name="beneficiaries" :value="old('beneficiaries', $user->beneficiaries)" autocomplete="beneficiaries" />
            <x-input-error :messages="$errors->get('beneficiaries')" class="mt-2" />
        </div>

    </div>

    <!-- Save Button -->
    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>

        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600"
            >{{ __('Saved.') }}</p>
        @endif
    </div>
</form>

</section>
