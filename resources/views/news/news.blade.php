@extends('dash')

@section('konten')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Berita</h1>
        <a href="{{ route('news.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            + Tambah Berita
        </a>
    </div>

    {{-- Search bar --}}
    <form method="GET" class="mb-6">
        <input type="text" name="search" placeholder="Cari judul atau isi berita..."
               value="{{ request('search') }}"
               class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                <tr>
                    <th class="px-6 py-3 border-b">Judul</th>
                    <th class="px-6 py-3 border-b">Kategori</th>
                    <th class="px-6 py-3 border-b">Status</th>
                    <th class="px-6 py-3 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse($news as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 border-b">{{ $item->title }}</td>
                    <td class="px-6 py-4 border-b">{{ $item->kategori->nama ?? '-' }}</td>
                    <td class="px-6 py-4 border-b">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $item->approve === 'y' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $item->approve === 'y' ? 'Disetujui' : 'Menunggu' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 border-b">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('news.edit', $item->id) }}"
                               class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                Edit
                            </a>
                            <form action="{{ route('news.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 text-sm font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada berita ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $news->withQueryString()->links('pagination::tailwind') }}
    </div>
</div>
@endsection
