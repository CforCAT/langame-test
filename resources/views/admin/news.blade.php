<x-admin-layout>
    <h1>News</h1>
    <table class="table-auto border-collapse border-spacing-0 border border-slate-500">
        <thead>
        <tr>
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Title</th>
            <th class="p-2 border">Link</th>
            <th class="p-2 border">Provider</th>
            <th class="p-2 border">Published</th>
        </tr>
        </thead>
        <tbody>
        @forelse($news as $n)
            <tr>
                <td class="p-2 border">{{ $n->id }}</td>
                <td class="p-2 border">{{ \Illuminate\Support\Str::limit($n->title) }}</td>
                <td class="p-2 border"><a href="{{ $n->link }}" target="_blank">Link</a></td>
                <td class="p-2 border">{{ $n->provider }}</td>
                <td class="p-2 border">{{ $n->published_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-2">Empty</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $news->links() }}
</x-admin-layout>
