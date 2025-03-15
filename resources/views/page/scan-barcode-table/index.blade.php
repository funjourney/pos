<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scan Barcode Table</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      overflow-y: auto; /* Aktifkan scroll vertical */
    }
    header {
      z-index: 1050; /* Pastikan navbar di atas elemen lain */
    }
    main {
      margin-top: 80px; /* Tambahkan jarak antara navbar dan card */
    }
    .card {
      max-width: 420px; /* Card lebih kecil */
      width: 100%;
      z-index: 1; /* Pastikan card tidak menutupi tombol */
      position: relative;
    }
    #reader {
      overflow: hidden;
      width: 100%;
      display: none;
    }
  </style>
</head>
<body class="bg-light d-flex flex-column align-items-center justify-content-center min-vh-100 pt-5">
  
  <header class="w-100 bg-white shadow-sm py-3 position-fixed top-0 start-0">
    <div class="container d-flex justify-content-end">
      @if (Route::has('login'))
        <nav class="nav">
          @auth
            <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary me-2">Dashboard</a>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary me-2">Log in</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
            @endif
          @endauth
        </nav>
      @endif
    </div>
  </header>

  <main class="container d-flex flex-column align-items-center">
    <div class="card p-4">
      <h1 class="text-center mb-4 fs-5">Scan Barcode untuk Login</h1>
      <button id="startScanner" class="btn btn-primary w-100 mb-3">Izinkan Akses Kamera</button>
      <div id="reader"></div>
      <button id="manualLogin" class="btn btn-secondary w-100 mt-3">Lanjutkan Tanpa Scan</button>
    </div>
  </main>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Sertakan file html5-qrcode secara lokal -->
  <script src="/js/html5-qrcode.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let scannerActive = false;
      let qrCodeReader = null;

      function setupScanner() {
        const startScannerBtn = document.getElementById("startScanner");
        const manualLoginBtn = document.getElementById("manualLogin");
        const readerElement = document.getElementById("reader");

        startScannerBtn.addEventListener("click", function() {
          if (scannerActive) return;
          scannerActive = true;
          readerElement.style.display = "block";

          navigator.mediaDevices.getUserMedia({ video: true })
            .then(() => {
              qrCodeReader = new Html5Qrcode("reader");

              qrCodeReader.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 200 },
                function(decodedText) {
                  console.log("✅ QR Code terbaca:", decodedText);
                  try {
                    const cleanedString = decodedText.slice(1, -1);
                    let jsonObject = JSON.parse(cleanedString);
                    handleLogin(jsonObject);
                  } catch (error) {
                    console.error("❌ Error parsing QR Code:", error);
                    alert("Kode QR tidak valid. Harap gunakan kode yang benar.");
                  }
                },
                function(errorMessage) {
                  console.warn("⚠️ Kesalahan pemindaian QR:", errorMessage);
                }
              ).catch(err => {
                console.error("❌ Gagal mengakses kamera:", err);
                alert("Tidak dapat mengakses kamera. Periksa izin browser atau perangkat Anda.");
              });
            })
            .catch(error => {
              console.error("❌ Kamera tidak dapat diakses:", error);
              alert("Izin kamera ditolak atau tidak tersedia di perangkat ini.");
            });
        });

        manualLoginBtn.addEventListener("click", function() {
          // handleLogin({ email: "table01@example.com", password: "password" });
            window.location.href = "/shopping-cart";
        });
      }

      function handleLogin(jsonObject) {
        console.log("🔄 Mengirim data login...", jsonObject);

        fetch("/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content
          },
          body: JSON.stringify({ email: jsonObject.email, password: jsonObject.password })
        })
        .then(response => response.json())
        .then(data => {
          if (data.message) {
            console.log("✅ Login berhasil! Redirecting...");
            window.location.href = "/shopping-cart";
          } else {
            console.error("❌ Login gagal.");
            alert("Login gagal! Periksa kembali data Anda.");
          }
        })
        .catch(error => {
          console.error("❌ Error saat login:", error);
          alert("Terjadi kesalahan saat login. Coba lagi.");
        });
      }

      if (typeof Html5Qrcode !== "undefined") {
        console.log("✅ Html5Qrcode berhasil dimuat secara lokal!");
        setupScanner();
      } else {
        console.error("❌ Library Html5Qrcode tidak terdeteksi. Pastikan file sudah diunduh dengan benar.");
      }
    });
  </script>
</body>
</html>