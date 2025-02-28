<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>process</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between align-items-center">
      <h2>Process</h2>
      <div>
        <a href="javascript:history.back()" class="btn btn-secondary me-2">← Back to Previous Page</a>
        <form action="/logout" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-danger">🚪 Logout</button>
        </form>
      </div>
    </div>
  </header>
  
    <div class="container mt-5">
        <h2 class="mb-4">Process Details</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Status Pembayaran</th>
                    <th>Status Pemesanan</th>
                </tr>
            </thead>
            <tbody id="process-table">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>

    <script>
        const categories = [
            {
                name: "Makanan",
                img: "https://img.freepik.com/premium-photo/traditional-japanese-meal-with-fried-chicken-pork-cutlets-soup_1007521-47245.jpg",
                listProducts: [
                    { name: "Burger", price: 25000, img: "https://img.freepik.com/free-photo/burger_1339-1550.jpg" },
                    { name: "Pizza", price: 18000, img: "https://img.freepik.com/free-photo/hawaiian-pizza_1203-2455.jpg" },
                    { name: "Fried Rice", price: 18000, img: "https://img.freepik.com/free-photo/stir-fried-chili-paste-chicken-with-rice-fried-eggs-white-plate-wooden-table_1150-28443.jpg" },
                    { name: "Fried Chicken", price: 18000, img: "https://img.freepik.com/free-photo/close-up-fried-chicken-drumsticks_23-2148682835.jpg" }
                ]
            },
            {
                name: "Minuman",
                img: "https://img.freepik.com/premium-photo/cup-hot-tea-drink-tea_87720-32695.jpg",
                listProducts: [
                    { name: "Soda", price: 15000, img: "https://img.freepik.com/free-photo/tasty-bubble-tea-drinks-arrangement_23-2149870687.jpg" },
                    { name: "Milk", price: 35000, img: "https://img.freepik.com/free-photo/glass-with-milk-chocolate_23-2148937237.jpg" },
                    { name: "Orange Juice", price: 15000, img: "https://img.freepik.com/premium-photo/glass-orange-juice_106857-98.jpg" },
                    { name: "Manggo Juice", price: 15000, img: "https://img.freepik.com/free-photo/mango-shake-fresh-tropical-fruit-smoothies_501050-963.jpg" }
                ]
            }
        ];

        // Generate dummy processs
        function generateprocesss() {
            let processs = [];
            categories.forEach(category => {
                category.listProducts.forEach(product => {
                    processs.push({
                        product_name: product.name,
                        category_name: category.name,
                        quantity: Math.floor(Math.random() * 3) + 1, // Random 1-3
                        price: product.price,
                        total: product.price * (Math.floor(Math.random() * 3) + 1),
                        payment_method: ["bank_transfer", "ewallet", "cashier"][Math.floor(Math.random() * 3)],
                        payment_status: ["pending", "paid", "failed"][Math.floor(Math.random() * 3)],
                        order_status: ["processing", "completed", "canceled"][Math.floor(Math.random() * 3)],
                        img: product.img
                    });
                });
            });
            return processs;
        }

        const processs = generateprocesss();

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function renderprocesss() {
            const tableBody = document.getElementById('process-table');
            processs.forEach(process => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${process.product_name}</td>
                    <td><img src="${process.img}" width="50" height="50" class="rounded"></td>
                    <td>${process.category_name}</td>
                    <td>${process.quantity}</td>
                    <td>${formatRupiah(process.price)}</td>
                    <td>${formatRupiah(process.total)}</td>
                    <td>${process.payment_method.charAt(0).toUpperCase() + process.payment_method.slice(1)}</td>
                    <td>
                        ${process.payment_status === 'pending' ? '<span class="badge bg-warning">Menunggu Pembayaran</span>' :
                          process.payment_status === 'paid' ? '<span class="badge bg-success">Berhasil Dibayar</span>' :
                          '<span class="badge bg-danger">Gagal Dibayar</span>'}
                    </td>
                    <td>
                        ${process.order_status === 'processing' ? '<span class="badge bg-primary">Diproses</span>' :
                          process.order_status === 'completed' ? '<span class="badge bg-success">Selesai</span>' :
                          '<span class="badge bg-danger">Dibatalkan</span>'}
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
        function updateOrderStatus() {
          setTimeout(() => {
              document.querySelectorAll('.badge.bg-primary').forEach(badge => {
                  badge.classList.remove('bg-primary');
                  badge.classList.add('bg-success');
                  badge.textContent = 'Selesai';
              });
          }, 2000);
        }

    document.addEventListener('DOMContentLoaded', () => {
        renderprocesss();
        updateOrderStatus();
    });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
