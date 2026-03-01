<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Colocation') }}: {{ $colocation->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('colocations.update', $colocation) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <x-input-label for="name" :value="__('Colocation Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $colocation->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $colocation->address)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="max_members" :value="__('Maximum Members')" />
                                <x-text-input id="max_members" name="max_members" type="number" min="2" max="20" class="mt-1 block w-full" :value="old('max_members', $colocation->max_members)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('max_members')" />
                            </div>

                            <div>
                                <x-input-label for="monthly_rent" :value="__('Monthly Rent (Optional)')" />
                                <x-text-input id="monthly_rent" name="monthly_rent" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('monthly_rent', $colocation->monthly_rent)" />
                                <x-input-error class="mt-2" :messages="$errors->get('monthly_rent')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description', $colocation->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Colocation') }}</x-primary-button>
                            <a href="{{ route('colocations.index') }}" class="text-sm text-gray-600 hover:text-gray-900 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
