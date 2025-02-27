<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .category-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      margin-right: 10px;
    }
    .product-img {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }
  </style>
</head>
<body>

<header class="bg-dark text-white p-3">
  <div class="container d-flex justify-content-between align-items-center">
    {{-- <h2>Shopping Cart</h2> --}}
    <div>
      <button class="btn btn-warning me-2" onclick="viewCart()">🛒 View Cart (<span id="cart-count">0</span>)</button>
      <form action="/logout" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">🚪 Logout</button>
      </form>
    </div>
  </div>
</header>

<main class="container py-5">
  <div class="col-md-12 mb-5">
    <h2 class="mb-4">Shopping</h2>
    <div class="accordion" id="productAccordion"></div>
  </div>
</main>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Shopping Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul id="cart-items" class="list-group"></ul>
        <p class="mt-3">Total: Rp<span id="cart-total">0</span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" onclick="orderNow()">Order Now</button>
      </div>
    </div>
  </div>
</div>

<script>
  let cart = [];
  let categories = [
    {
      name: "Makanan",
      img: "https://img.freepik.com/premium-photo/traditional-japanese-meal-with-fried-chicken-pork-cutlets-soup_1007521-47245.jpg",
      listProducts: [
        { name: "Burger", price: 25.000, img: "https://img.freepik.com/free-photo/burger_1339-1550.jpg" },
        { name: "Pizza", price: 18.000, img: "https://img.freepik.com/free-photo/hawaiian-pizza_1203-2455.jpg" },
        { name: "Fried Rice", price: 18.000, img: "https://img.freepik.com/free-photo/stir-fried-chili-paste-chicken-with-rice-fried-eggs-white-plate-wooden-table_1150-28443.jpg" },
        { name: "Fried Chicken", price: 18.000, img: "https://img.freepik.com/free-photo/close-up-fried-chicken-drumsticks_23-2148682835.jpg" }
      ]
    },
    {
      name: "Minuman",
      img: "https://img.freepik.com/premium-photo/cup-hot-tea-drink-tea_87720-32695.jpg",
      listProducts: [
        { name: "Soda", price: 15.000, img: "https://img.freepik.com/free-photo/tasty-bubble-tea-drinks-arrangement_23-2149870687.jpg" },
        { name: "Milk", price: 3.5000, img: "https://img.freepik.com/free-photo/glass-with-milk-chocolate_23-2148937237.jpg" },
        { name: "Orange Juice", price: 15.000, img: "https://img.freepik.com/premium-photo/glass-orange-juice_106857-98.jpg" },
        { name: "Manggo Juice", price: 15.000, img: "https://img.freepik.com/free-photo/mango-shake-fresh-tropical-fruit-smoothies_501050-963.jpg" }
      ]
    }
  ];

  function renderProducts() {
    let container = document.getElementById('productAccordion');
    container.innerHTML = '';

    categories.forEach((category, index) => {
        let categoryId = `category-${index}`;
        let categoryHtml = `
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-${categoryId}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#collapse-${categoryId}" aria-expanded="false" aria-controls="collapse-${categoryId}">
                        <img src="${category.img}" class="category-img"> ${category.name}
                    </button>
                </h2>
                <div id="collapse-${categoryId}" class="accordion-collapse collapse" aria-labelledby="heading-${categoryId}" 
                    data-bs-parent="#productAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            ${category.listProducts.map(product => `
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <img src="${product.img}" class="product-img card-img-top" alt="${product.name}">
                                        <div class="card-body">
                                            <h5 class="card-title">${product.name}</h5>
                                            <p class="card-text">Rp ${product.price.toFixed(3)}</p>
                                            <button class="btn btn-danger mx-2" onclick="updateCart('${product.name}', ${product.price.toFixed(3)}, -1)">-</button>
                                            <span id="qty-${product.name}">0</span>
                                            <button class="btn btn-primary mx-2" onclick="updateCart('${product.name}', ${product.price.toFixed(3)}, 1)">+</button>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += categoryHtml;
    });
  }

  function updateCart(name, price, change) {
    let existing = cart.find(item => item.name === name);
    
    if (existing) {
        existing.qty += change;
        if (existing.qty <= 0) {
            cart = cart.filter(item => item.name !== name);
        }
    } else if (change > 0) {
        existing = { name, price, qty: 1 }; // Buat objek sebelum push
        cart.push(existing);
    }

    // Pastikan elemen selalu diperbarui
    let quantityElement = document.getElementById(`qty-${name}`);
    quantityElement.textContent = existing ? existing.qty : 0;
    
    document.getElementById('cart-count').textContent = cart.reduce((sum, item) => sum + item.qty, 0);
}


  function viewCart() {
    let cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = '';
    let total = 0;
    cart.forEach(item => {
      total += item.price * item.qty;
      let li = document.createElement('li');
      li.textContent = `${item.name} x${item.qty} - Rp${(item.price * item.qty).toFixed(3)}`;
      li.classList.add('list-group-item');
      cartItems.appendChild(li);
    });
    document.getElementById('cart-total').textContent = total.toFixed(3);
    new bootstrap.Modal(document.getElementById('cartModal')).show();
  }

  function orderNow() {
    if (cart.length === 0) {
      alert('Your cart is empty!');
      return;
    }

    // Simpan data pesanan sebelum mengosongkan cart
    let orderData = [...cart];
    
    // Kosongkan cart
    cart = [];
    document.getElementById('cart-count').textContent = 0;
    
    // Render ulang produk agar quantity reset
    renderProducts();

    // Pindah
    window.location.href = '/payment';    
  }

  renderProducts();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
