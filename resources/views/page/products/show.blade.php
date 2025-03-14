<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl">{{ $product->name }}</h3>
                <p class="text-gray-600">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                
                @if($product->path_image)
                    <img src="{{ asset('storage/'.$product->path_image) }}" class="w-48 h-48 object-cover mt-4">
                @endif

                <div class="mt-4">
                    <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
