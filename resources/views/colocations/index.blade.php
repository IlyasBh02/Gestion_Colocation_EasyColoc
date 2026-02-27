<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Colocations') }}
            </h2>
            @if(!Auth::user()->hasActiveMembership())
                <a href="{{ route('colocations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    + New Colocation
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($colocations as $colocation)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $colocation->name }}</h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800">
                                    Owner: {{ $colocation->owner->name }}
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mt-2 line-clamp-3">
                                {{ $colocation->description ?? 'No description provided.' }}
                            </p>
                            <div class="mt-6 flex items-center justify-between">
                                <a href="{{ route('colocations.show', $colocation) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium transition duration-150">
                                    View Details →
                                </a>
                                
                                @can('update', $colocation)
                                    <div class="flex space-x-2">
                                        <a href="{{ route('colocations.edit', $colocation) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-5-5l5 5m0 0l-5 5m5-5H12" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('colocations.destroy', $colocation) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this colocation?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 p-12 text-center rounded-lg shadow-sm border border-dashed border-gray-300 dark:border-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No colocations found</h3>
                        <p class="mt-1 text-gray-500 dark:text-gray-400">Get started by creating a new colocation.</p>
                        @if(!Auth::user()->hasActiveMembership())
                            <div class="mt-6">
                                <a href="{{ route('colocations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    + Create First Colocation
                                </a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
