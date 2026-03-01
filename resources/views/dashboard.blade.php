<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent leading-tight">
                {{ __('Dashboard Overview') }}
            </h2>
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400 bg-white/50 dark:bg-gray-800/50 px-4 py-2 rounded-full border border-gray-200/50 dark:border-gray-700/50 shadow-sm backdrop-blur-sm">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="animate-fadeInUp glass border-l-4 border-l-emerald-500 p-4 rounded-xl flex items-center space-x-3 shadow-lg">
                    <div class="flex-shrink-0 bg-emerald-500/20 p-2 rounded-lg">
                        <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Status Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- User Card -->
                <div class="glass p-6 rounded-3xl hover-lift group animate-fadeInUp" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-tr from-indigo-500 to-purple-600 p-3 rounded-2xl shadow-lg ring-4 ring-indigo-500/10 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-500 dark:text-indigo-400">User Profile</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Auth::user()->email }}</p>
                </div>

                <!-- Role Card -->
                <div class="glass p-6 rounded-3xl hover-lift group animate-fadeInUp" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-tr from-emerald-500 to-teal-600 p-3 rounded-2xl shadow-lg ring-4 ring-emerald-500/10 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-500 dark:text-emerald-400">Account Role</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white capitalize">{{ Auth::user()->role }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Access Level: {{ Auth::user()->role === 'admin' ? 'Full' : 'Standard' }}</p>
                </div>

                <!-- Membership Card -->
                <div class="glass p-6 rounded-3xl hover-lift group animate-fadeInUp" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-tr from-blue-500 to-cyan-600 p-3 rounded-2xl shadow-lg ring-4 ring-blue-500/10 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-500 dark:text-blue-400">Membership</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if(Auth::user()->hasActiveMembership())
                            <span class="flex h-3 w-3 rounded-full bg-emerald-500"></span>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Active Member</h3>
                        @else
                            <span class="flex h-3 w-3 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Not Enrolled</h3>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Colocation Status</p>
                </div>
            </div>

            <!-- Quick Actions Overhaul -->
            <div class="glass p-8 rounded-[2rem] animate-fadeInUp shadow-2xl relative overflow-hidden" style="animation-delay: 0.4s">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>
                
                <div class="relative items-center mb-8">
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Quick Actions</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Everything you need to manage your smart living</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative">
                    <!-- Action Link -->
                    <a href="{{ route('colocations.index') }}" class="group p-6 bg-white/40 dark:bg-gray-800/40 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 hover:shadow-xl hover:-translate-y-2">
                        <div class="bg-indigo-500/10 p-4 rounded-xl inline-block mb-4 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500">
                            <svg class="h-8 w-8 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">Browse Colocs</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Explore available colocations and find your next home.</p>
                        <div class="mt-4 flex items-center text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                            <span>Explore now</span>
                            <svg class="ml-2 h-4 w-4 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                    </a>

                    @if(!Auth::user()->hasActiveMembership())
                        <!-- Action Link -->
                        <a href="{{ route('colocations.create') }}" class="group p-6 bg-white/40 dark:bg-gray-800/40 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 hover:shadow-xl hover:-translate-y-2">
                            <div class="bg-emerald-500/10 p-4 rounded-xl inline-block mb-4 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <svg class="h-8 w-8 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-lg">Start New Coloc</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Create your own space and invite your best friends.</p>
                            <div class="mt-4 flex items-center text-emerald-600 dark:text-emerald-400 font-bold text-sm">
                                <span>Create space</span>
                                <svg class="ml-2 h-4 w-4 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </div>
                        </a>
                    @endif

                    <!-- Action Link -->
                    <a href="{{ route('profile.edit') }}" class="group p-6 bg-white/40 dark:bg-gray-800/40 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 hover:shadow-xl hover:-translate-y-2">
                        <div class="bg-blue-500/10 p-4 rounded-xl inline-block mb-4 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500">
                            <svg class="h-8 w-8 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">Settings</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Manage your account, security, and preferences.</p>
                        <div class="mt-4 flex items-center text-blue-600 dark:text-blue-400 font-bold text-sm">
                            <span>Manage account</span>
                            <svg class="ml-2 h-4 w-4 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
