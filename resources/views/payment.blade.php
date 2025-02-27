<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<header class="bg-dark text-white p-3">
  <div class="container d-flex justify-content-between align-items-center">
    <h2>Payment</h2>
    <div>
      <a href="/shopping-cart" class="btn btn-secondary me-2">← Back to Cart</a>
      <form action="/logout" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">🚪 Logout</button>
      </form>
    </div>
  </div>
</header>

<main class="container py-5">
  <h2 class="mb-4">Payment Details</h2>
  <form action="/process-payment" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-3">
      <label for="fullName" class="form-label">Full Name</label>
      <input type="text" class="form-control" id="fullName" name="fullName" required>
    </div>
    
    <div class="mb-3">
      <label for="paymentMethod" class="form-label">Payment Method</label>
      <select class="form-select" id="paymentMethod" name="paymentMethod" onchange="togglePaymentOptions()" required>
        <option value="cashier">Bayar di Kasir</option>
        <option value="bank_transfer">Transfer Bank</option>
        <option value="ewallet">E-Wallet</option>
        <option value="virtual_bank">Virtual Bank</option>
        <option value="qris">QRIS</option>
      </select>
    </div>
    
    <div id="bankTransferOptions" class="mb-3 d-none">
      <label for="bankList" class="form-label">Pilih Bank</label>
      <select class="form-select" id="bankList" name="bankList">
        <option value="bca">BCA</option>
        <option value="bni">BNI</option>
        <option value="bri">BRI</option>
      </select>
      <label for="paymentProof" class="form-label mt-2">Upload Bukti Transfer</label>
      <input type="file" class="form-control" id="paymentProof" name="paymentProof">
    </div>
    
    <div id="ewalletOptions" class="mb-3 d-none">
      <label for="ewalletList" class="form-label">Pilih E-Wallet</label>
      <select class="form-select" id="ewalletList" name="ewalletList">
        <option value="gopay">GoPay</option>
        <option value="ovo">OVO</option>
        <option value="dana">DANA</option>
      </select>
      <input type="text" class="form-control mt-2" id="ewalletNotes" name="ewalletNotes" placeholder="Masukkan nomor E-Wallet atau keterangan">
    </div>
    
    <div id="virtualBankOptions" class="mb-3 d-none">
      <label for="virtualBankList" class="form-label">Pilih Virtual Bank</label>
      <select class="form-select" id="virtualBankList" name="virtualBankList">
        <option value="permata">Permata</option>
        <option value="cimb">CIMB Niaga</option>
        <option value="maybank">Maybank</option>
      </select>
      <input type="text" class="form-control mt-2" id="virtualBankNotes" name="virtualBankNotes" placeholder="Masukkan nomor Virtual Account atau keterangan">
    </div>
    
    <div id="qrisOptions" class="mb-3 d-none text-center">
      <label class="form-label">Scan QR Code untuk Pembayaran</label>
      <div>
        <img src="https://media.perkakasku.id/image/qrperkakasku.jpeg" alt="QRIS Barcode" class="img-fluid" style="max-width: 250px;">
      </div>
      <p class="mt-2">Silakan scan QRIS untuk menyelesaikan pembayaran.</p>
    </div>
    
    <div class="mb-3">
      <label for="totalAmount" class="form-label">Total Amount</label>
      <input type="text" class="form-control" id="totalAmount" name="totalAmount" value="Rp 0" readonly>
    </div>
    
    <button type="submit" class="btn btn-success" onclick="payNow()">Pay Now</button>
  </form>
</main>

<script>
  function togglePaymentOptions() {
    let method = document.getElementById('paymentMethod').value;
    document.getElementById('bankTransferOptions').classList.toggle('d-none', method !== 'bank_transfer');
    document.getElementById('ewalletOptions').classList.toggle('d-none', method !== 'ewallet');
    document.getElementById('virtualBankOptions').classList.toggle('d-none', method !== 'virtual_bank');
    document.getElementById('qrisOptions').classList.toggle('d-none', method !== 'qris');
  }

  function payNow() {
    window.location.href = '/process';    
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
