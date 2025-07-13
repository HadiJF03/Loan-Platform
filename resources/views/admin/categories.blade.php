<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center">
            {{ __('Categories Management') }}
        </h2>
    </x-slot>

    <div class="py-10 flex justify-center mt-6">
        <div class="min-w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 text-white">
                        <thead class="bg-gray-900 text-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Description</th>
                                <th class="px-6 py-3 text-right text-sm font-medium uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->description ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-400 hover:underline">Edit</a>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Are you sure?');">
                                                @csrf @method('DELETE')
                                                <x-danger-button>Delete</x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.categories.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold py-2 px-4 rounded">
                    + Add New Category
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
