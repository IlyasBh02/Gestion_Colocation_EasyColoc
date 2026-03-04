<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Expense Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $expense->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $expense->category->icon }} {{ $expense->category->name }} • {{ $expense->date->format('M d, Y') }}</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-4">${{ number_format($expense->amount, 2) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Paid by {{ $expense->payer->name }}</p>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Payment Shares</h4>
                    <div class="space-y-3">
                        @foreach($expense->shares as $share)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-indigo-600 dark:bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($share->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $share->user->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($share->amount, 2) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @if($share->is_paid)
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-sm font-bold">Paid</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full text-sm font-bold">Unpaid</span>
                                        @if(auth()->id() === $share->user_id || auth()->id() === $ownerId)
                                            <form action="{{ route('expense-shares.pay', $share->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                                                    Mark as Paid
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('colocations.show', $expense->colocation_id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        ← Back to Colocation
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
