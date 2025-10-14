<nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('photobooth') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('photobooth')" :active="request()->routeIs('photobooth')">
                        {{ __('Photobooth') }}
                    </x-nav-link>
                    <x-nav-link :href="route('gallery')" :active="request()->routeIs('gallery')">
                        {{ __('Gallery') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>