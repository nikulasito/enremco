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


        @php
            // Prefer DB value unless old() exists for validation error case
            $officeCurrent = session()->hasOldInput() ? old('office') : old('office', $user->office);
            $sexCurrent = session()->hasOldInput() ? old('sex') : old('sex', $user->sex);
            $msCurrent = session()->hasOldInput() ? old('marital_status') : old('marital_status', $user->marital_status);

            // Normalize helper
            $norm = fn($v) => trim(mb_strtolower((string) $v));
        @endphp

        <!-- Office -->
        <div>
            <x-input-label for="office" :value="__('Office')" />
            <select id="office" name="office" class="form-control" required autocomplete="off">
                <option value="" disabled {{ $officeCurrent ? '' : 'selected' }}>Select Office</option>

                @foreach($offices as $officeOption)
                    @php $selected = $norm($officeCurrent) === $norm($officeOption); @endphp
                    <option value="{{ $officeOption }}" {{ $selected ? 'selected' : '' }}>
                        {{ $officeOption }}
                    </option>
                @endforeach
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
            <select id="sex" name="sex" class="form-control" autocomplete="off">
                <option value="" disabled {{ $sexCurrent ? '' : 'selected' }}>Select Sex</option>
                @foreach($sexes as $sexOption)
                    @php $selected = $norm($sexCurrent) === $norm($sexOption); @endphp
                    <option value="{{ $sexOption }}" {{ $selected ? 'selected' : '' }}>
                        {{ $sexOption }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('sex')" class="mt-2" />
        </div>

        
        <!-- Marital Status -->
        <div>
            <x-input-label for="marital_status" :value="__('Marital Status')" />
            <select id="marital_status" name="marital_status" class="form-control" autocomplete="off">
                <option value="" disabled {{ $msCurrent ? '' : 'selected' }}>Select Marital Status</option>
                @foreach($maritalStatuses as $ms)
                    @php $selected = $norm($msCurrent) === $norm($ms); @endphp
                    <option value="{{ $ms }}" {{ $selected ? 'selected' : '' }}>
                        {{ $ms }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
        </div>


        <!-- Birthdate -->
        @php
            // format to Y-m-d for type=date
            $birthVal = session()->hasOldInput() ? old('birthdate') : ( $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '' );
        @endphp

        <div>
            <x-input-label for="birthdate" :value="__('Birthdate')" />
            <input id="birthdate" name="birthdate" type="date" class="form-control mt-1 block w-full" value="{{ $birthVal }}" autocomplete="off" />
            <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
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
