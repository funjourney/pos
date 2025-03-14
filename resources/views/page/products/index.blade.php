<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Produk</a>

                <table class="mt-4 w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border px-4 py-2">Nama</th>
                            <th class="border px-4 py-2">Harga</th>
                            <th class="border px-4 py-2">Gambar</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="border">
                                <td class="px-4 py-2">{{ $product->name }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    @if($product->path_image)
                                        <img src="{{ asset('storage/'.$product->path_image) }}" class="w-16 h-16 object-cover" alt="Product Image">
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-blue-500">Detail</a> |
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-yellow-500">Edit</a> |
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
