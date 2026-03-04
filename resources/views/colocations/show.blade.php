<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $colocation->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('colocations.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    Back to list
                </a>
                @can('update', $colocation)
                    <a href="{{ route('colocations.edit', $colocation) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                        Edit Colocation
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        <div class="lg:col-span-2">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">About this Colocation</h3>
                            <div class="space-y-3 mb-6">
                                @if($colocation->address)
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-indigo-500 dark:text-indigo-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $colocation->address }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center space-x-6">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-500 dark:text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $colocation->members->count() }} / {{ $colocation->max_members }} members</span>
                                    </div>
                                    @if($colocation->monthly_rent)
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-indigo-500 dark:text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-gray-700 dark:text-gray-300">${{ number_format($colocation->monthly_rent, 2) }}/month</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="prose max-w-none text-gray-600 dark:text-gray-400">
                                {{ $colocation->description ?? 'No description available for this colocation.' }}
                            </div>
                            
                            <div class="mt-12">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Expenses
                                </h3>

                                <div class="mb-4 flex items-center justify-between">
                                    <form method="GET" class="flex items-center space-x-2">
                                        <input type="month" name="month" value="{{ $month }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Filter</button>
                                    </form>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Total: <span class="font-bold text-gray-900 dark:text-gray-100">${{ number_format($expenses->sum('amount'), 2) }}</span>
                                    </div>
                                </div>

                                @if($colocation->members->contains(Auth::user()) || $colocation->owner_id === Auth::id())
                                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800">
                                        <form action="{{ route('expenses.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                            @csrf
                                            <div>
                                                <select name="category_id" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                                    <option value="">Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->icon }} {{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <input type="text" name="description" placeholder="Description" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <div class="space-y-2">
                                    @forelse($expenses as $expense)
                                        <a href="{{ route('expenses.show', $expense->id) }}" class="block">
                                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 transition flex items-center justify-between">
                                                <div class="flex items-center space-x-4 flex-1">
                                                    <div class="text-2xl">{{ $expense->category->icon }}</div>
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $expense->title }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $expense->category->name }} • {{ $expense->payer->name }} • {{ $expense->date->format('M d, Y') }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-bold text-gray-900 dark:text-gray-100">${{ number_format($expense->amount, 2) }}</p>
                                                    </div>
                                                </div>
                                                @if($expense->payer_id === Auth::id() || $colocation->owner_id === Auth::id())
                                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="ml-4" onsubmit="return confirm('Delete this expense?');" onclick="event.stopPropagation();">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </a>
                                    @empty
                                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                            <p>No expenses for this month.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="mt-12">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Members
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg border border-indigo-100 dark:border-indigo-800 flex items-center space-x-4">
                                        <div class="h-10 w-10 bg-indigo-600 dark:bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($colocation->owner->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-gray-100">{{ $colocation->owner->name }}</p>
                                            <p class="text-xs text-indigo-600 dark:text-indigo-400 uppercase font-bold tracking-wider">Owner</p>
                                        </div>
                                    </div>
                                    
                                    @forelse($colocation->members->where('id', '!=', $colocation->owner_id) as $member)
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600 flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 dark:text-gray-100">{{ $member->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Member</p>
                                                </div>
                                            </div>
                                            @if(Auth::id() === $colocation->owner_id)
                                                <form action="{{ route('colocations.removeMember', [$colocation, $member->id]) }}" method="POST" onsubmit="return confirm('Remove this member?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @empty
                                        @if(Auth::id() !== $colocation->owner_id)
                                            <div class="col-span-full">
                                                <p class="text-gray-500 dark:text-gray-400 italic">No other members yet.</p>
                                            </div>
                                        @endif
                                    @endforelse
                                </div>
                            </div>

                            <div class="mt-12">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Balances & Settlements
                                </h3>

                                <div class="mb-6">
                                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Member Balances</h4>
                                    <div class="space-y-2">
                                        @foreach($balances as $balance)
                                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $balance['user']->name }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Paid: ${{ number_format($balance['total_paid'], 2) }} | Share: ${{ number_format($balance['share'], 2) }} | Unpaid: ${{ number_format($balance['unpaid'], 2) }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        @if($balance['balance'] > 0.01)
                                                            <p class="font-bold text-green-600 dark:text-green-400">+${{ number_format($balance['balance'], 2) }}</p>
                                                            <p class="text-xs text-green-600 dark:text-green-400">To receive</p>
                                                        @elseif($balance['balance'] < -0.01)
                                                            <p class="font-bold text-red-600 dark:text-red-400">-${{ number_format(abs($balance['balance']), 2) }}</p>
                                                            <p class="text-xs text-red-600 dark:text-red-400">To pay</p>
                                                        @else
                                                            <p class="font-bold text-gray-600 dark:text-gray-400">$0.00</p>
                                                            <p class="text-xs text-gray-600 dark:text-gray-400">Settled</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if(count($settlements) > 0)
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Suggested Payments</h4>
                                        <div class="space-y-2">
                                            @foreach($settlements as $settlement)
                                                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800 flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $settlement['from']->name }}</span>
                                                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                        </svg>
                                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $settlement['to']->name }}</span>
                                                    </div>
                                                    <span class="font-bold text-blue-600 dark:text-blue-400">${{ number_format($settlement['amount'], 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="p-6 text-center bg-green-50 dark:bg-green-900/30 rounded-lg border border-green-100 dark:border-green-800">
                                        <p class="text-green-700 dark:text-green-400 font-semibold">✓ All balances are settled!</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-6">
                            @if(Auth::id() === $colocation->owner_id)
                                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl border border-gray-100 dark:border-gray-600">
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 mb-4 uppercase text-xs tracking-widest">Manage Invitations</h4>
                                    
                                    @if(session('success'))
                                        <div class="mb-4 text-sm text-green-600 dark:text-green-400 font-medium">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    
                                    @if(session('error'))
                                        <div class="mb-4 text-sm text-red-600 dark:text-red-400 font-medium">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <form action="{{ route('invitations.store', $colocation) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <x-input-label for="email" :value="__('Invite by Email')" />
                                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" placeholder="friend@example.com" required />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                        <x-primary-button class="w-full justify-center">
                                            {{ __('Send Invitation') }}
                                        </x-primary-button>
                                    </form>

                                    <div class="mt-8">
                                        <h5 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Pending Invitations</h5>
                                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                            @forelse($colocation->invitations()->where('status', 'pending')->get() as $invitation)
                                                <li class="py-3 flex justify-between items-center">
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $invitation->email }}</span>
                                                    <span class="text-[10px] bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full font-bold uppercase">Pending</span>
                                                </li>
                                            @empty
                                                <li class="py-3 text-sm text-gray-400 dark:text-gray-500 italic">No pending invitations.</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            
                            @if(Auth::id() !== $colocation->owner_id && $colocation->members->contains(Auth::user()))
                                <div class="bg-red-50 dark:bg-red-900/30 p-6 rounded-xl border border-red-100 dark:border-red-800">
                                    <h4 class="font-bold text-red-900 dark:text-red-300 mb-4 uppercase text-xs tracking-widest">Leave Colocation</h4>
                                    <p class="text-sm text-red-700 dark:text-red-400 mb-4">You can leave this colocation at any time.</p>
                                    <form action="{{ route('colocations.leave', $colocation) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this colocation?');">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 dark:bg-red-700 text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 dark:hover:bg-red-800 transition duration-150 shadow-md">
                                            Leave Colocation
                                        </button>
                                    </form>
                                </div>
                            @endif
                            
                            @if(Auth::id() !== $colocation->owner_id && !$colocation->members->contains(Auth::user()))
                                <div class="bg-indigo-50 dark:bg-indigo-900/30 p-6 rounded-xl border border-indigo-100 dark:border-indigo-800">
                                    <h4 class="font-bold text-indigo-900 dark:text-indigo-300 mb-4 uppercase text-xs tracking-widest">Wanna Join?</h4>
                                    <p class="text-sm text-indigo-700 dark:text-indigo-400 mb-4">You must be invited by the owner to join this colocation.</p>
                                    
                                    @php
                                        $myInvitation = $colocation->invitations()->where('email', Auth::user()->email)->where('status', 'pending')->first();
                                    @endphp

                                    @if($myInvitation)
                                        <a href="{{ route('invitations.accept', $myInvitation->token) }}" class="block w-full text-center bg-indigo-600 dark:bg-indigo-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition duration-150 shadow-md">
                                            Accept Invitation
                                        </a>
                                    @else
                                        <button disabled class="w-full bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 font-bold py-3 px-6 rounded-lg cursor-not-allowed">
                                            Waiting for Invitation
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
