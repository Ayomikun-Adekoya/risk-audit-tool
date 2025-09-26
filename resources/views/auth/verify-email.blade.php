<x-auth-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking the link we sent you? If you didn’t receive the email, we will gladly send you another.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <div class="flex items-center justify-between mt-4">
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>

            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </a>
        </div>
    </form>
</x-auth-layout>
