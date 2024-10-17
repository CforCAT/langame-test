<x-admin-layout>
    <h1>Codes</h1>
    <table class="table-auto border-collapse border-spacing-0 border border-slate-500">
        <thead>
        <tr>
            <th class="p-2 border">User</th>
            <th class="p-2 border">Code</th>
            <th class="p-2 border">Used</th>
            <th class="p-2 border">Issued at</th>
            <th class="p-2 border">Expired at</th>
        </tr>
        </thead>
        <tbody>
        @forelse($codes as $code)
            <tr>
                <td class="p-2 border">{{ $code->user->phone }}</td>
                <td class="p-2 border">{{ $code->code }}</td>
                <td class="p-2 border">{{ $code->verified ? 'YES' : 'NO' }}</td>
                <td class="p-2 border">{{ $code->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="p-2 border">{{ $code->expires_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-2">Empty</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $codes->links() }}
</x-admin-layout>
