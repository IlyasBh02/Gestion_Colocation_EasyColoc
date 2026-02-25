<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $colocation->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('colocations.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Back to list
                </a>
                @can('update', $colocation)
                    <a href="{{ route('colocations.edit', $colocation) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Edit Colocation
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        <div class="lg:col-span-2">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">About this Colocation</h3>
                            <div class="prose max-w-none text-gray-600">
                                {{ $colocation->description ?? 'No description available for this colocation.' }}
                            </div>
                            
                            <div class="mt-12">
                                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Members
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-100 flex items-center space-x-4">
                                        <div class="h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($colocation->owner->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $colocation->owner->name }}</p>
                                            <p class="text-xs text-indigo-600 uppercase font-bold tracking-wider">Owner</p>
                                        </div>
                                    </div>
                                    
                                    @forelse($colocation->members as $member)
                                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 flex items-center space-x-4">
                                            <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $member->name }}</p>
                                                <p class="text-xs text-gray-500">Member</p>
                                            </div>
                                        </div>
                                    @empty
                                        @if(Auth::id() !== $colocation->owner_id)
                                            <div class="col-span-full">
                                                <p class="text-gray-500 italic">No other members yet.</p>
                                            </div>
                                        @endif
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h4 class="font-bold text-gray-900 mb-4 uppercase text-xs tracking-widest">Details</h4>
                                <ul class="space-y-4">
                                    <li class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Created:</span>
                                        <span class="font-medium text-gray-900">{{ $colocation->created_at->format('M d, Y') }}</span>
                                    </li>
                                    <li class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Status:</span>
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wider">Active</span>
                                    </li>
                                </ul>
                            </div>
                            
                            @if(Auth::id() !== $colocation->owner_id && !$colocation->members->contains(Auth::user()))
                                <form action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition duration-150 shadow-md">
                                        Join this Colocation
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
