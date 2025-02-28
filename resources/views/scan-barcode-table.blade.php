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
        function loginUser(redirectUrl) {
            document.getElementById("result").innerText = "Memproses login, harap tunggu...";
                
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.content : '';
                
            fetch("{{ url('/login') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    email: "guest@example.com",
                    password: "password"
                })
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = redirectUrl; // Langsung redirect tanpa parsing JSON
                } else {
                    document.getElementById("result").innerText = "Login gagal, silakan coba lagi.";
                }
            })
            .catch(error => {
                document.getElementById("result").innerText = "Error: " + error.message;
            });
        }


        document.getElementById("requestCamera").addEventListener("click", function() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                document.getElementById("result").innerText = "Browser tidak mendukung akses kamera";
                return;
            }

            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    document.getElementById("requestCamera").style.display = "none";
                    document.getElementById("reader").style.display = "block";

                    let html5QrCode = new Html5Qrcode("reader");
                    html5QrCode.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: 250 },
                        (decodedText, decodedResult) => {
                            if (decodedText === "VALID_BARCODE") {
                                loginUser("{{ url('/shopping-cart') }}");
                            } else {
                                document.getElementById("result").innerText = "Barcode tidak valid";
                            }
                        }
                    );
                })
                .catch(function(err) {
                    document.getElementById("result").innerText = "Akses kamera ditolak: " + err.message;
                });
        });

        document.getElementById("skipScan").addEventListener("click", function() {
            loginUser("{{ url('/shopping-cart') }}");
        });
    </script>
</body>
</html>
