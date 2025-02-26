<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-dark text-white p-3">
  <div class="container d-flex justify-content-between">
    <h2>Shopping Cart</h2>
    <button class="btn btn-warning" onclick="viewCart()">🛒 View Cart (<span id="cart-count">0</span>)</button>
  </div>
</header>

<main class="container py-5">
  <h3>Makanan</h3>
  <div class="row" id="food-list"></div>
  
  <h3 class="mt-4">Minuman</h3>
  <div class="row" id="drink-list"></div>
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
        <p class="mt-3">Total: $<span id="cart-total">0.00</span></p>
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
  let products = {
    makanan: [
      { name: "Burger", price: 5.00, img: "https://via.placeholder.com/150" },
      { name: "Pizza", price: 8.00, img: "https://via.placeholder.com/150" }
    ],
    minuman: [
      { name: "Soda", price: 2.00, img: "https://via.placeholder.com/150" },
      { name: "Juice", price: 3.50, img: "https://via.placeholder.com/150" }
    ]
  };

  function renderProducts() {
    let foodList = document.getElementById('food-list');
    let drinkList = document.getElementById('drink-list');

    function createProductCard(product) {
      return `<div class="col-md-4">
        <div class="card">
          <img src="${product.img}" class="card-img-top" alt="${product.name}">
          <div class="card-body">
            <h5 class="card-title">${product.name}</h5>
            <p class="card-text">$${product.price.toFixed(2)}</p>
            <button class="btn btn-danger" onclick="updateCart('${product.name}', ${product.price}, -1)">-</button>
            <span id="qty-${product.name}">0</span>
            <button class="btn btn-primary" onclick="updateCart('${product.name}', ${product.price}, 1)">+</button>
          </div>
        </div>
      </div>`;
    }

    foodList.innerHTML = products.makanan.map(createProductCard).join('');
    drinkList.innerHTML = products.minuman.map(createProductCard).join('');
  }

  function updateCart(name, price, change) {
    let existing = cart.find(item => item.name === name);
    if (existing) {
      existing.qty += change;
      if (existing.qty <= 0) cart = cart.filter(item => item.name !== name);
    } else if (change > 0) {
      cart.push({ name, price, qty: 1 });
    }
    document.getElementById(`qty-${name}`).textContent = existing ? existing.qty : 0;
    document.getElementById('cart-count').textContent = cart.reduce((sum, item) => sum + item.qty, 0);
  }

  function viewCart() {
    let cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = '';
    let total = 0;
    cart.forEach(item => {
      total += item.price * item.qty;
      let li = document.createElement('li');
      li.textContent = `${item.name} x${item.qty} - $${(item.price * item.qty).toFixed(2)}`;
      li.classList.add('list-group-item');
      cartItems.appendChild(li);
    });
    document.getElementById('cart-total').textContent = total.toFixed(2);
    new bootstrap.Modal(document.getElementById('cartModal')).show();
  }

  function orderNow() {
    if (cart.length === 0) {
      alert('Your cart is empty!');
      return;
    }
    alert('Thank you for your order!');
    cart = [];
    document.getElementById('cart-count').textContent = 0;
    renderProducts();
  }

  renderProducts();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
