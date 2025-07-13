<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center">
            {{ __('Create Category') }}
        </h2>
    </x-slot>

    <div class="py-10 flex justify-center mt-6">
        <div class="w-full max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow sm:rounded-lg p-6 text-white">
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-300">Category Name</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            placeholder="Enter category name"
                            required
                            class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>


                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-white mr-4">Cancel</a>
                        <x-primary-button>{{ __('Create') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
