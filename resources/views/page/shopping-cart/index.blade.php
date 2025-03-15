<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Menu</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($dataArrayCategories as $category)
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                        <img src="{{ $category['pathImage'] ?: 'https://via.placeholder.com/150' }}" 
                             class="w-full h-40 object-cover rounded-md mb-2" 
                             alt="{{ $category['name'] }}">

                        <h4 class="text-center font-semibold text-white">{{ $category['name'] }}</h4>

                        <button class="dropdown-button bg-blue-500 text-white px-4 py-2 mt-2 w-full rounded">Lihat Produk</button>
                        <div class="dropdown-content hidden mt-2 p-2 bg-white dark:bg-gray-900 rounded shadow-lg">
                            @if (!empty($category['products']))
                                @foreach ($category['products'] as $product)
                                    <div class="flex justify-between items-center p-2 border-b border-gray-300 dark:border-gray-600">
                                        <div>
                                            <img src="{{ $product['pathImage'] ?: 'https://via.placeholder.com/100' }}" 
                                                 class="w-16 h-16 object-cover rounded-md mr-2" 
                                                 alt="{{ $product['name'] }}">
                                            <p class="font-medium text-black dark:text-white">{{ $product['name'] }}</p>
                                            <p class="text-sm text-gray-400">
                                                Rp {{ number_format((float) $product['price'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button class="bg-red-500 text-white px-2 py-1 rounded" 
                                                    onclick="updateCart('{{ $product['id'] }}', '{{ $product['name'] }}', {{ $product['price'] }}, -1)">-</button>
                                            <span id="qty-{{ $product['id'] }}" class="text-black dark:text-white">0</span>
                                            <button class="bg-blue-500 text-white px-2 py-1 rounded" 
                                                    onclick="updateCart('{{ $product['id'] }}', '{{ $product['name'] }}', {{ $product['price'] }}, 1)">+</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500 dark:text-gray-300 text-center">Tidak ada produk</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-gray-200 dark:bg-gray-600 rounded-lg">
                    <h3 class="font-semibold text-white">Cart Summary</h3>
                    <ul id="cart-list" class="mt-2"></ul>
                    <button class="bg-green-500 text-white px-4 py-2 rounded mt-4" onclick="checkout()">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            loadCart();
            
            document.querySelectorAll('.dropdown-button').forEach(button => {
                button.addEventListener('click', function () {
                    let dropdown = this.nextElementSibling;
                    dropdown.classList.toggle('hidden');
                });
            });
        });

        function updateCart(id, name, price, change) {
            let cart = JSON.parse(localStorage.getItem("shoppingCart")) || [];
            let index = cart.findIndex(item => item.id === id);
        
            if (index !== -1) {
                cart[index].qty += change;
                if (cart[index].qty <= 0) cart.splice(index, 1);
            } else if (change > 0) {
                cart.push({ id, name, price, qty: 1 });
            }
        
            localStorage.setItem("shoppingCart", JSON.stringify(cart));
            loadCart();
        }

        function loadCart() {
            let cart = JSON.parse(localStorage.getItem("shoppingCart")) || [];
            let cartList = document.getElementById("cart-list");
            cartList.innerHTML = "";
        
            cart.forEach(item => {
                let li = document.createElement("li");
                li.textContent = `${item.name} (x${item.qty}) - Rp ${(item.price * item.qty).toLocaleString()}`;
                cartList.appendChild(li);
                
                let qtySpan = document.getElementById(`qty-${item.id}`);
                if (qtySpan) qtySpan.textContent = item.qty;
            });
        }

        function checkout() {
            alert("Checkout berhasil! Terima kasih telah berbelanja.");
            localStorage.removeItem("shoppingCart");
            loadCart();
        }
    </script>
</x-app-layout>