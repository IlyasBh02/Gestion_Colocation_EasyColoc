<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $colocation->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                @if($pendingInvitation)
                    <div class="mb-6 p-6 bg-blue-50 dark:bg-blue-900/30 border-2 border-blue-500 rounded-lg animate-fadeInUp">
                        <h3 class="text-xl font-bold text-blue-900 dark:text-blue-100 mb-3">🎉 You have a pending invitation!</h3>
                        <p class="text-blue-800 dark:text-blue-200 mb-4">You've been invited to join this colocation.</p>
                        <div class="flex space-x-3">
                            <a href="{{ route('invitations.accept', $pendingInvitation->token) }}" 
                               class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                                ✅ Accept Invitation
                            </a>
                            <a href="{{ route('invitations.refuse', $pendingInvitation->token) }}" 
                               class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition">
                                ❌ Refuse Invitation
                            </a>
                        </div>
                    </div>
                @endif

                @if($isMember)
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-500 rounded-lg">
                        <p class="text-green-800 dark:text-green-200">✅ You are a member of this colocation.</p>
                        <a href="{{ route('colocations.show') }}" class="text-green-600 dark:text-green-400 hover:underline font-semibold">
                            Go to Dashboard →
                        </a>
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">About this Colocation</h3>
                        <div class="prose max-w-none text-gray-600 dark:text-gray-400">
                            {{ $colocation->description ?? 'No description available.' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">👤 Owner</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $colocation->owner->name }}</p>
                        </div>

                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">👥 Members</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $colocation->members->count() + 1 }} members</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('colocations.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                            ← Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
