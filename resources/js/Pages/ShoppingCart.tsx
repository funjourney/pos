import React, { useEffect, useState } from "react";
import "bootstrap/dist/css/bootstrap.min.css";

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

const ShoppingCart: React.FC = () => {
    const [cart, setCart] = useState<{ name: string; price: number; qty: number }[]>([]);
    const [openCategory, setOpenCategory] = useState<string | null>(null);
  
    useEffect(() => {
      const storedCart = localStorage.getItem("shoppingCart");
      if (storedCart) {
        setCart(JSON.parse(storedCart));
      }
    }, []);
  
    useEffect(() => {
      localStorage.setItem("shoppingCart", JSON.stringify(cart));
    }, [cart]);
  
    const toggleCategory = (categoryName: string) => {
      setOpenCategory(openCategory === categoryName ? null : categoryName);
    };
  
    const updateCart = (name: string, price: number, change: number) => {
      setCart((prevCart) => {
        const updatedCart = prevCart.map((item) =>
          item.name === name ? { ...item, qty: item.qty + change } : item
        ).filter(item => item.qty > 0);
        
        if (!updatedCart.some(item => item.name === name) && change > 0) {
          updatedCart.push({ name, price, qty: 1 });
        }
        return updatedCart;
      });
    };
  
    const checkout = () => {
      alert("Checkout berhasil! Terima kasih telah berbelanja.");
      setCart([]);
      localStorage.removeItem("shoppingCart");
    };
  
    return (
      <div className="container mt-4" style={{ maxWidth: "95%" }}>
  
        {/* Gunakan Bootstrap Grid */}
        <div className="row">
            
          {/* Bagian Cart Summary, lebar 4 kolom */}
          <div className="col-md-3">
            <div className="card p-3">
              <h2>Cart Summary</h2>
              <ul className="list-group">
                {cart.length === 0 ? <li className="list-group-item">Cart is empty</li> : cart.map((item, idx) => (
                  <li key={idx} className="list-group-item d-flex justify-content-between align-items-center">
                    {item.name} (x{item.qty})
                    <span>Rp {(item.price * item.qty).toLocaleString()}</span>
                  </li>
                ))}
              </ul>
              {cart.length > 0 && (
                <button className="btn btn-success mt-3" onClick={checkout}>Checkout</button>
              )}
            </div>
          </div>

          {/* Bagian produk, lebar 8 kolom */}
          <div className="col-md-9">
            <h1>Shopping Cart</h1>
              {categories.map((category, index) => (
                <div key={index} className="mb-3 text-center" style={{ marginTop: "25px" }}>
                  <button
                    className="btn btn-outline-light w-100 text-center p-3 d-block custom-btn"
                    style={{
                      color: "#a0a5ab",
                      borderColor: "#a0a5ab",
                      height: "auto"
                    }}
                    onClick={() => toggleCategory(category.name)}
                  >
                    <img src={category.img} className="mb-2" alt={category.name} style={{ width: "100%", height: "150px", objectFit: "cover" }} />
                    <span className="fs-4 d-block">{category.name} {openCategory === category.name ? "▲" : "▼"}</span>
                  </button>
                  {openCategory === category.name && (
                    <div className="mt-2">
                      <div className="row">
                        {category.listProducts.map((product, idx) => (
                          <div className="col-md-4 mb-4" key={idx}>
                            <div className="card">
                              <img src={product.img} className="card-img-top" alt={product.name} style={{ width: "100%", height: "150px", objectFit: "cover" }} />
                              <div className="card-body text-center">
                                <h5 className="card-title">{product.name}</h5>
                                <p className="card-text">Rp {product.price.toLocaleString()}</p>
                                <button className="btn btn-danger mx-2" onClick={() => updateCart(product.name, product.price, -1)}>-</button>
                                <span>{cart.find((item) => item.name === product.name)?.qty || 0}</span>
                                <button className="btn btn-primary mx-2" onClick={() => updateCart(product.name, product.price, 1)}>+</button>
                              </div>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}
                </div>
              ))}
          </div>
  
        </div>
      </div>
    );
  };
  
  export default ShoppingCart;
  