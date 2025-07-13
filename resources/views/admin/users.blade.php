<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center">
            {{ __('Users Management') }}
        </h2>
    </x-slot>

    <div class="py-10 flex justify-center mt-6">
        <div class="min-w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="min-w-full bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="min-w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 text-white">
                        <thead class="bg-gray-900 text-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Mobile</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Email</th>
                                <th class="px-6 py-3 text-right text-sm font-medium uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->mobile_number ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($user->role) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if (Auth::id() !== $user->id)
                                            <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button>Delete</x-danger-button>
                                            </form>
                                        @else
                                            <span class="text-sm text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (method_exists($users, 'links'))
                        <div class="mt-4 px-4 text-white">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
