import React from "react";
import "bootstrap/dist/css/bootstrap.min.css";

interface Ingredient {
  name: string;
  category: string;
  quantity: number;
  unit: string;
  price: number;
  supplier: string;
  lastRestocked: string;
}

const ingredients: Ingredient[] = [
  { name: "Flour", category: "Dry Goods", quantity: 50, unit: "kg", price: 10000, supplier: "Supplier A", lastRestocked: "2025-02-20" },
  { name: "Eggs", category: "Dairy", quantity: 200, unit: "pcs", price: 2000, supplier: "Supplier B", lastRestocked: "2025-02-22" },
  { name: "Milk", category: "Dairy", quantity: 100, unit: "liters", price: 15000, supplier: "Supplier C", lastRestocked: "2025-02-25" },
  { name: "Sugar", category: "Dry Goods", quantity: 80, unit: "kg", price: 12000, supplier: "Supplier A", lastRestocked: "2025-02-18" }
];

const formatRupiah = (number: number): string => {
  return "Rp " + number.toLocaleString("id-ID");
};

const InventoryPage: React.FC = () => {
  return (
    <div>

      <header className="bg-light text-white p-3">
          <div className="container d-flex justify-content-between align-items-center">
              <h2 
              style={{
                color: "#82868A",
                borderColor: "#979899",
                height: "auto"
              }}>Ingredient Inventory</h2>
              <div>
                  <a href="/shopping-cart" className="btn btn-secondary me-2">← Back to Cart</a>
                  <form action="/logout" method="POST" className="d-inline">
                      <button type="submit" className="btn btn-danger">🚪 Logout</button>
                  </form>
              </div>
          </div>
      </header>

      <div className="container mt-5">
        {/* <h2 className="mb-4">Ingredient Inventory</h2> */}
        <table className="table table-bordered">
          <thead className="table-secondary">
            <tr>
              <th>Ingredient</th>
              <th>Category</th>
              <th>Stock Quantity</th>
              <th>Unit</th>
              <th>Price per Unit</th>
              <th>Supplier</th>
              <th>Last Restocked</th>
            </tr>
          </thead>
          <tbody>
            {ingredients.map((ingredient, index) => (
              <tr key={index}>
                <td>{ingredient.name}</td>
                <td>{ingredient.category}</td>
                <td>{ingredient.quantity}</td>
                <td>{ingredient.unit}</td>
                <td>{formatRupiah(ingredient.price)}</td>
                <td>{ingredient.supplier}</td>
                <td>{ingredient.lastRestocked}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default InventoryPage;
