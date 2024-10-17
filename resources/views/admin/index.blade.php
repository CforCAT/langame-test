<x-admin-layout>
    <h1>Users</h1>
    <table class="table-auto border-collapse border-spacing-0 border border-slate-500">
        <thead>
        <tr>
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Phone</th>
            <th class="p-2 border">Name</th>
            <th class="p-2 border">Registered</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td class="p-2 border">{{ $user->id }}</td>
                <td class="p-2 border">{{ $user->phone }}</td>
                <td class="p-2 border">{{ $user->name }}</td>
                <td class="p-2 border">{{ $user->registered_at === null ? 'NO' : 'YES' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-2">Empty</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $users->links() }}
</x-admin-layout>
