import React, { useState, useEffect } from "react";
import { ExecuteLocalStorage } from "../tools/LocalStorage";
import { HttpRequest } from "../tools/HttpRequest";
import "bootstrap/dist/css/bootstrap.min.css";
import { Html5Qrcode } from "html5-qrcode";

const localStorageHandler = new ExecuteLocalStorage();
const apiClient = new HttpRequest();

const BarcodeScanner: React.FC = () => {
    const [scannerActive, setScannerActive] = useState(false);
    const [qrCodeReader, setQrCodeReader] = useState<Html5Qrcode | null>(null);
    const [scanResult, setScanResult] = useState<string | null>(null);
    const [apiResponse, setApiResponse] = useState<any>(null);

    useEffect(() => {
        localStorageHandler.WriteJSON("testKey", { message: "Hello, LocalStorage!" });
        localStorageHandler.ReadJSON<{ message: string }>("testKey").then(storedData => {
            if (storedData) {
                console.log("LocalStorage Data:", storedData.message);
            }
        }).catch(error => console.error("LocalStorage error:", error));
    }, []);

    const startScanner = async () => {
        if (scannerActive) return;
        setScannerActive(true);
        const reader = new Html5Qrcode("reader");
        setQrCodeReader(reader);
        try {
            await reader.start(
                { facingMode: "environment" }, // Kamera yang digunakan
                { fps: 10, qrbox: 250 }, // Konfigurasi scanner
                async (decodedText) => {  // Callback sukses
                    console.log("🔹 Barcode Terdeteksi:", decodedText);
                    setScanResult(decodedText);
                    await reader.stop();
                    setScannerActive(false);
                    loginUser(decodedText);
                },
                (errorMessage) => {  // 🔹 Tambahkan callback error di sini
                    console.warn("⚠️ Error QR Code:", errorMessage);
                }
            );            
        } catch (error) {
            console.error("❌ Gagal memulai scanner:", error);
        }
    };

    const loginUser = async (barcodeData: string) => {
        console.log("🔹 Login User dipanggil dengan barcode:", barcodeData);
        try {
            const response = await apiClient.POST<{ message: string }>("/login", {
                email: "guest@example.com",
                password: "password",
            });
            if (response) {
                console.log("✅ Login berhasil, redirecting...");
                window.location.href = "/shopping-cart";
            } else {
                console.error("❌ Login gagal.");
            }
        } catch (error: any) {
            console.error("❌ Error saat login:", error.message);
        }
    };

    return (
        <div className="d-flex justify-content-center align-items-center vh-100 bg-light">
            <div className="card shadow p-4" style={{ maxWidth: "400px", width: "100%" }}>
                <h1 className="text-center mb-4">Scan Barcode untuk Login</h1>
                {!scannerActive && (
                    <button className="btn btn-primary w-100 mb-3" onClick={startScanner}>
                        Izinkan Akses Kamera
                    </button>
                )}
                <div id="reader" style={{ width: "100%", display: scannerActive ? "block" : "none" }}></div>
                <div className="text-center mt-3 text-danger">
                    {scanResult ? `Barcode: ${scanResult}` : "Arahkan barcode ke kamera..."}
                </div>
                <button className="btn btn-secondary w-100 mt-3" onClick={() => loginUser("/shopping-cart")}>
                    Lanjutkan Tanpa Scan
                </button>
            </div>
        </div>
    );
};

export default BarcodeScanner;
