<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Barcode Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h1 class="text-center mb-4">Scan Barcode untuk Login</h1>
        <button id="requestCamera" class="btn btn-primary w-100 mb-3">Izinkan Akses Kamera</button>
        <div id="reader" style="width: 100%; display: none;"></div>
        <div id="result" class="text-center mt-3 text-danger"></div>
        
        <!-- Tombol Lewati -->
        <button id="skipScan" class="btn btn-secondary w-100 mt-3">Lanjutkan Lewati Proses Scan</button>
    </div>

    <script>
        let scannerActive = false;
        let html5QrCode;

        async function startScanner() {
            if (scannerActive) return;
            scannerActive = true;

            console.log("🔹 Memulai scanner...");
            document.getElementById("requestCamera").style.display = "none";
            document.getElementById("reader").style.display = "block";
            document.getElementById("result").innerText = "Arahkan barcode ke kamera...";

            html5QrCode = new Html5Qrcode("reader");
            try {
                await html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    async (decodedText, decodedResult) => {
                        console.log("🔹 Barcode Terdeteksi:", decodedText);

                        // Hentikan scanner sebelum redirect
                        await html5QrCode.stop();
                        console.log("✅ Scanning dihentikan.");

                        document.getElementById("result").innerText = "Barcode: " + decodedText;

                        // Redirect setelah membaca barcode
                        loginUser("{{ url('/shopping-cart') }}");
                    }
                );
            } catch (err) {
                console.error("❌ Gagal memulai scanner:", err);
                document.getElementById("result").innerText = "Gagal memulai scanner: " + err.message;
            }
        }

        async function loginUser(redirectUrl) {
            console.log("🔹 Login User dipanggil. Redirect ke:", redirectUrl);
            document.getElementById("result").innerText = "Memproses login, harap tunggu...";

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.content : '';

            console.log("🔹 CSRF Token:", csrfToken);

            try {
                const response = await fetch("{{ url('/login') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    credentials: "include",
                    body: JSON.stringify({
                        email: "guest@example.com",
                        password: "password"
                    })
                });

                if (response.ok) {
                    console.log("✅ Login berhasil, mengarahkan ke:", redirectUrl);
                    window.location.href = redirectUrl;
                } else {
                    console.error("❌ Login gagal.");
                    document.getElementById("result").innerText = "Login gagal, silakan coba lagi.";
                }
            } catch (error) {
                console.error("❌ Error saat login:", error);
                document.getElementById("result").innerText = "Error: " + error.message;
            }
        }

        
        //     .then(response => {
        //         console.log("🔹 Status Response Login:", response.status);
        //         return response.json().catch(() => ({})); // Menangani jika tidak ada JSON
        //     })
        //     .then(data => {
        //         console.log("🔹 Response Data:", data);
        //         if (data.success || data.status === "success") {
        //             console.log("✅ Login berhasil! Redirecting...");
        //             setTimeout(() => {
        //                 console.log("🔹 Redirect dilakukan sekarang...");
        //                 window.location.href = redirectUrl;
        //             }, 500);
        //         } else {
        //             console.error("❌ Login gagal. Response:", data);
        //             document.getElementById("result").innerText = "Login gagal, silakan coba lagi.";
        //         }
        //     })
        //     .catch(error => {
        //         console.error("❌ Error saat login:", error);
        //         document.getElementById("result").innerText = "Error: " + error.message;
        //     });
        // }

        document.getElementById("requestCamera").addEventListener("click", startScanner);
        
        document.getElementById("skipScan").addEventListener("click", function() {
            console.log("🔹 Tombol Lewati diklik. Redirect ke shopping cart...");
            loginUser("{{ url('/shopping-cart') }}");
        });
    </script>
</body>
</html>
