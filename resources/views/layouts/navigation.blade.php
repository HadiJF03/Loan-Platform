<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Primary Navigation -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @auth
                        @switch(auth()->user()->role)
                            @case('pledger')
                                <x-nav-link :href="route('pledges.index')" :active="request()->routeIs('pledges.*')">
                                    {{ __('My Pledges') }}
                                </x-nav-link>
                                <x-nav-link :href="route('offers.index')" :active="request()->routeIs('offers.index')">
                                    {{ __('My Offers') }}
                                </x-nav-link>
                                <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">
                                    {{ __('Transactions') }}
                                </x-nav-link>
                                @break

                            @case('pledgee')
                                <x-nav-link :href="route('pledges.browse')" :active="request()->routeIs('pledges.browse')">
                                    {{ __('Browse Pledges') }}
                                </x-nav-link>
                                <x-nav-link :href="route('offers.index')" :active="request()->routeIs('offers.index')">
                                    {{ __('My Offers') }}
                                </x-nav-link>
                                <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">
                                    {{ __('Transactions') }}
                                </x-nav-link>
                                @break

                            @case('root')
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('admin.dashboard')">
                                    {{ __('Admin Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.pledges.index')" :active="request()->routeIs('admin.pledges.*')">
                                    {{ __('Pledges Management') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                                    {{ __('Categories') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.offers.index')" :active="request()->routeIs('admin.offers.*')">
                                    {{ __('Offers Management') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')">
                                    {{ __('Transactions Management') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                    {{ __('Users Management') }}
                                </x-nav-link>
                                @break
                        @endswitch
                    @endauth
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 ...">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="..." clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 ...">
                    <svg class="h-6 w-6" ...>
                        <path :class="{ 'hidden': open, 'inline-flex': ! open }" d="..." />
                        <path :class="{ 'hidden': ! open, 'inline-flex': open }" d="..." />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @switch(auth()->user()->role)
                    @case('pledger')
                        <x-responsive-nav-link :href="route('pledges.index')" :active="request()->routeIs('pledges.*')">
                            {{ __('My Pledges') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('offers.index')" :active="request()->routeIs('offers.index')">
                            {{ __('My Offers') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">
                            {{ __('Transactions') }}
                        </x-responsive-nav-link>
                        @break

                    @case('pledgee')
                        <x-responsive-nav-link :href="route('pledges.browse')" :active="request()->routeIs('pledges.browse')">
                            {{ __('Browse Pledges') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('offers.index')" :active="request()->routeIs('offers.index')">
                            {{ __('My Offers') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">
                            {{ __('Transactions') }}
                        </x-responsive-nav-link>
                        @break

                    @case('root')
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Admin Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.pledges.index')" :active="request()->routeIs('admin.pledges.*')">
                            {{ __('Pledges Management') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            {{ __('Categories') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.offers.index')" :active="request()->routeIs('admin.offers.*')">
                            {{ __('Offers Management') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')">
                            {{ __('Transactions Management') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Users Management') }}
                        </x-responsive-nav-link>
                        @break
                @endswitch
            @endauth
        </div>

        <!-- Mobile User Info -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
