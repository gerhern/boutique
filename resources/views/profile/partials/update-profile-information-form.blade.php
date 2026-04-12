{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<section>
    <header>
        <h3 class="text-lg font-bold text-content-primary italic">
            {{ __('Profile Information') }}
        </h3>
        <p class="mt-1 text-sm text-content-disabled">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-ui.input
                name="name"
                label="Full Name"
                type="text"
                :value="old('name', $user->name)"
                required
                autofocus
            />
        </div>

        <div>
            <x-ui.input
                name="email"
                label="Email Address"
                type="email"
                :value="old('email', $user->email)"
                required
            />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-accent/5 rounded-lg border border-accent/10">
                    <p class="text-xs text-accent font-medium">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline hover:text-accent-dark transition">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-ui.button type="submit">
                {{ __('Save Changes') }}
            </x-ui.button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-state-success font-medium">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
