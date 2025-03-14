<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-gray-700">Nama Produk</label>
                        <input type="text" name="name" value="{{ $product->name }}" class="w-full border px-4 py-2">
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-700">Harga</label>
                        <input type="number" name="price" value="{{ $product->price }}" class="w-full border px-4 py-2">
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-700">Gambar</label>
                        @if($product->path_image)
                            <img src="{{ asset('storage/'.$product->path_image) }}" class="w-24 h-24 object-cover">
                        @endif
                        <input type="file" name="image" class="w-full border px-4 py-2">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
