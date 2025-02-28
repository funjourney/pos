<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between align-items-center">
      <h2>Inventory</h2>
      <div>
        <a href="/process" class="btn btn-secondary me-2">Process Menu</a>
        <form action="/logout" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-danger">🚪 Logout</button>
        </form>
      </div>
    </div>
  </header>
  
    <div class="container mt-5">
        <h2 class="mb-4">Ingredient Inventory</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Ingredient</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Stock Quantity</th>
                    <th>Unit</th>
                    <th>Price per Unit</th>
                    <th>Supplier</th>
                    <th>Last Restocked</th>
                </tr>
            </thead>
            <tbody id="inventory-table">
                <!-- Data will be filled by JavaScript -->
            </tbody>
        </table>
    </div>

    <script>
        const ingredients = [
            { name: "Flour", category: "Dry Goods", quantity: 50, unit: "kg", price: 10000, supplier: "Supplier A", lastRestocked: "2025-02-20" },
            { name: "Eggs", category: "Dairy", quantity: 200, unit: "pcs", price: 2000, supplier: "Supplier B", lastRestocked: "2025-02-22" },
            { name: "Milk", category: "Dairy", quantity: 100, unit: "liters", price: 15000, supplier: "Supplier C", lastRestocked: "2025-02-25" },
            { name: "Sugar", category: "Dry Goods", quantity: 80, unit: "kg", price: 12000, supplier: "Supplier A", lastRestocked: "2025-02-18" }
        ];

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function renderInventory() {
            const tableBody = document.getElementById('inventory-table');
            ingredients.forEach(ingredient => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${ingredient.name}</td>
                    <td>${ingredient.category}</td>
                    <td>${ingredient.quantity}</td>
                    <td>${ingredient.unit}</td>
                    <td>${formatRupiah(ingredient.price)}</td>
                    <td>${ingredient.supplier}</td>
                    <td>${ingredient.lastRestocked}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderInventory();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>