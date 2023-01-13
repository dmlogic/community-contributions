<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $property->address }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Property Information') }}
                        </h2>
                    </header>

                    <form method="post" action="{{ route('property.update', $property->id) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="number" :value="__('Number')" />
                            <x-text-input id="number" name="number" type="text" maxlenth="3" class="mt-1 block w-full" :value="old('number', $property->number)" required autofocus autocomplete="number" />
                            <x-input-error class="mt-2" :messages="$errors->get('number')" />
                        </div>

                        <div>
                            <x-input-label for="street" :value="__('Street')" />
                            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street', $property->street)" required autocomplete="street" />
                            <x-input-error class="mt-2" :messages="$errors->get('street')" />
                        </div>

                        <div>
                            <x-input-label for="town" :value="__('Town')" />
                            <x-text-input id="town" name="town" type="text" class="mt-1 block w-full" :value="old('town', $property->town)" required autocomplete="town" />
                            <x-input-error class="mt-2" :messages="$errors->get('town')" />
                        </div>

                        <div>
                            <x-input-label for="postcode" :value="__('Postcode')" />
                            <x-text-input id="postcode" name="postcode" type="text" class="mt-1 block w-full" :value="old('postcode', $property->postcode)" required autocomplete="postcode" />
                            <x-input-error class="mt-2" :messages="$errors->get('postcode')" />
                        </div>


                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'property-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </section>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
