import React, { useState } from "react";
import "bootstrap/dist/css/bootstrap.min.css";

const PaymentPage: React.FC = () => {
    const [paymentMethod, setPaymentMethod] = useState<string>("cashier");

    const handlePaymentMethodChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
        setPaymentMethod(event.target.value);
    };

    const payNow = () => {
        window.location.href = "/process";
    };

    return (
        <div>
            <header className="bg-light text-white p-3">
                <div className="container d-flex justify-content-between align-items-center">
                    <h2 
                    style={{
                      color: "#82868A",
                      borderColor: "#979899",
                      height: "auto"
                    }}>Payment Details</h2>
                    <div>
                        <a className="btn me-2">Table 01</a>
                        <a href="/shopping-cart" className="btn btn-secondary me-2">← Back to Cart</a>
                        <form action="/logout" method="POST" className="d-inline">
                            <button type="submit" className="btn btn-danger">🚪 Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main className="container py-5">
                {/* <h2 className="mb-4">Payment Details</h2> */}
                <form action="/process-payment" method="POST" encType="multipart/form-data">
                    <div className="mb-3">
                        <label htmlFor="tableNumber" className="form-label">Table Number</label>
                        <input type="number" className="form-control" id="tableNumber" name="tableNumber" required />
                    </div>

                    <div className="mb-3">
                        <label htmlFor="paymentMethod" className="form-label">Payment Method</label>
                        <select className="form-select" id="paymentMethod" name="paymentMethod" value={paymentMethod} onChange={handlePaymentMethodChange} required>
                            <option value="cashier">Bayar di Kasir</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="virtual_bank">Virtual Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    {paymentMethod === "bank_transfer" && (
                        <div className="mb-3">
                            <label htmlFor="bankList" className="form-label">Pilih Bank</label>
                            <select className="form-select" id="bankList" name="bankList">
                                <option value="bca">BCA</option>
                                <option value="bni">BNI</option>
                                <option value="bri">BRI</option>
                            </select>
                            <label htmlFor="paymentProof" className="form-label mt-2">Upload Bukti Transfer</label>
                            <input type="file" className="form-control" id="paymentProof" name="paymentProof" />
                        </div>
                    )}

                    {paymentMethod === "ewallet" && (
                        <div className="mb-3">
                            <label htmlFor="ewalletList" className="form-label">Pilih E-Wallet</label>
                            <select className="form-select" id="ewalletList" name="ewalletList">
                                <option value="gopay">GoPay</option>
                                <option value="ovo">OVO</option>
                                <option value="dana">DANA</option>
                            </select>
                            <input type="text" className="form-control mt-2" id="ewalletNotes" name="ewalletNotes" placeholder="Masukkan nomor E-Wallet atau keterangan" />
                        </div>
                    )}

                    {paymentMethod === "virtual_bank" && (
                        <div className="mb-3">
                            <label htmlFor="virtualBankList" className="form-label">Pilih Virtual Bank</label>
                            <select className="form-select" id="virtualBankList" name="virtualBankList">
                                <option value="permata">Permata</option>
                                <option value="cimb">CIMB Niaga</option>
                                <option value="maybank">Maybank</option>
                            </select>
                            <input type="text" className="form-control mt-2" id="virtualBankNotes" name="virtualBankNotes" placeholder="Masukkan nomor Virtual Account atau keterangan" />
                        </div>
                    )}

                    {paymentMethod === "qris" && (
                        <div className="mb-3 text-center">
                            <label className="form-label">Scan QR Code untuk Pembayaran</label>
                            <div className="d-flex justify-content-center">
                                <img src="https://media.perkakasku.id/image/qrperkakasku.jpeg" alt="QRIS Barcode" className="img-fluid" style={{ maxWidth: "250px" }} />
                            </div>
                            <p className="mt-2">Silakan scan QRIS untuk menyelesaikan pembayaran.</p>
                        </div>
                    )}

                    <div className="mb-3">
                        <label htmlFor="totalAmount" className="form-label">Total Amount</label>
                        <input type="text" className="form-control" id="totalAmount" name="totalAmount" value="Rp 0" readOnly />
                    </div>

                    <button type="submit" className="btn btn-success" onClick={payNow}>Pay Now</button>
                </form>
            </main>
        </div>
    );
};

export default PaymentPage;
