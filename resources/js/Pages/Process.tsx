import React, { useEffect, useState } from "react";
import "bootstrap/dist/css/bootstrap.min.css";

interface Process {
    product_name: string;
    img: string;
    category_name: string;
    quantity: number;
    price: number;
    total: number;
    payment_method: string;
    payment_status: string;
    order_status: string;
}

const categories = [
    {
        name: "Makanan",
        listProducts: [
            { name: "Burger", price: 25000, img: "https://img.freepik.com/free-photo/burger_1339-1550.jpg" },
            { name: "Pizza", price: 18000, img: "https://img.freepik.com/free-photo/hawaiian-pizza_1203-2455.jpg" },
            { name: "Fried Rice", price: 18000, img: "https://img.freepik.com/free-photo/stir-fried-chili-paste-chicken-with-rice-fried-eggs-white-plate-wooden-table_1150-28443.jpg" },
            { name: "Fried Chicken", price: 18000, img: "https://img.freepik.com/free-photo/close-up-fried-chicken-drumsticks_23-2148682835.jpg" }
        ]
    },
    {
        name: "Minuman",
        listProducts: [
            { name: "Soda", price: 15000, img: "https://img.freepik.com/free-photo/tasty-bubble-tea-drinks-arrangement_23-2149870687.jpg" },
            { name: "Milk", price: 35000, img: "https://img.freepik.com/free-photo/glass-with-milk-chocolate_23-2148937237.jpg" },
            { name: "Orange Juice", price: 15000, img: "https://img.freepik.com/premium-photo/glass-orange-juice_106857-98.jpg" },
            { name: "Manggo Juice", price: 15000, img: "https://img.freepik.com/free-photo/mango-shake-fresh-tropical-fruit-smoothies_501050-963.jpg" }
        ]
    }
];

const generateProcesses = (): Process[] => {
    return categories.flatMap(category =>
        category.listProducts.map(product => {
            const quantity = Math.floor(Math.random() * 3) + 1;
            return {
                product_name: product.name,
                category_name: category.name,
                quantity,
                price: product.price,
                total: product.price * quantity,
                payment_method: ["bank_transfer", "ewallet", "cashier"][Math.floor(Math.random() * 3)],
                payment_status: ["pending", "paid", "failed"][Math.floor(Math.random() * 3)],
                order_status: ["processing", "completed", "canceled"][Math.floor(Math.random() * 3)],
                img: product.img
            };
        })
    );
};

const formatRupiah = (number: number) => {
    return 'Rp ' + number.toLocaleString('id-ID');
};

const ProcessPage: React.FC = () => {
    const [processes, setProcesses] = useState<Process[]>([]);

    useEffect(() => {
        setProcesses(generateProcesses());
        setTimeout(() => {
            setProcesses(prev => prev.map(p => p.order_status === "processing" ? { ...p, order_status: "completed" } : p));
        }, 2000);
    }, []);

    return (
        <div>
            <header className="bg-light text-white p-3">
                <div className="container d-flex justify-content-between align-items-center">
                    <h2 
                    style={{
                      color: "#82868A",
                      borderColor: "#979899",
                      height: "auto"
                    }}>Process Details</h2>
                    <div>
                        <a className="btn me-2">Table 01</a>
                        <a href="/shopping-cart" className="btn btn-secondary me-2">← Back to Cart</a>
                        <form action="/logout" method="POST" className="d-inline">
                            <button type="submit" className="btn btn-danger">🚪 Logout</button>
                        </form>
                    </div>
                </div>
            </header>
            <div className="container mt-5">
                {/* <h2 className="mb-4">Process Details</h2> */}
                <table className="table table-bordered">
                    <thead className="table-secondary">
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
                    <tbody>
                        {processes.map((process, index) => (
                            <tr key={index}>
                                <td>{process.product_name}</td>
                                <td><img src={process.img} width="50" height="50" className="rounded" alt={process.product_name} /></td>
                                <td>{process.category_name}</td>
                                <td>{process.quantity}</td>
                                <td>{formatRupiah(process.price)}</td>
                                <td>{formatRupiah(process.total)}</td>
                                <td>{process.payment_method.charAt(0).toUpperCase() + process.payment_method.slice(1)}</td>
                                <td>
                                    <span className={`badge ${process.payment_status === "pending" ? "bg-warning" : process.payment_status === "paid" ? "bg-success" : "bg-danger"}`}>
                                        {process.payment_status === "pending" ? "Menunggu Pembayaran" : process.payment_status === "paid" ? "Berhasil Dibayar" : "Gagal Dibayar"}
                                    </span>
                                </td>
                                <td>
                                    <span className={`badge ${process.order_status === "processing" ? "bg-primary" : process.order_status === "completed" ? "bg-success" : "bg-danger"}`}>
                                        {process.order_status === "processing" ? "Diproses" : process.order_status === "completed" ? "Selesai" : "Dibatalkan"}
                                    </span>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default ProcessPage;