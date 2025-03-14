<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label class="block text-gray-700">Nama Produk</label>
                        <input type="text" name="name" class="w-full border px-4 py-2">
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-700">Harga</label>
                        <input type="number" name="price" class="w-full border px-4 py-2">
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-700">Gambar</label>
                        <input type="file" name="image" class="w-full border px-4 py-2">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
