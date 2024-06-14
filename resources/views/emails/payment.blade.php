<!-- Blade Template -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Pembelian</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .header {
      text-align: center;
      padding: 20px 0;
      background-color: #ffffff;
      color: white;
    }
    .header img {
      width: 150px;
      margin-bottom: 10px;
    }
    .header h1 {
      margin: 0;
      font-size: 24px;
      font-weight: 700;
    }
    .content {
      padding: 20px;
    }
    .content p {
      color: #333333;
      line-height: 1.6;
      margin: 10px 0;
    }
    .order-details {
      margin: 20px 0;
      background-color: #f9f9f9;
      padding: 15px;
      border-radius: 8px;
    }
    .order-details h2 {
      color: #0BA7E3;
      margin-bottom: 10px;
      font-size: 20px;
    }
    .order-details table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    .order-details table th,
    .order-details table td {
      padding: 12px;
      border: 1px solid #e5e5e5;
      text-align: left;
    }
    .order-details table th {
      background-color: #f1f1f1;
      font-weight: 500;
    }
    .order-details table td {
      background-color: #ffffff;
    }
    .footer {
      text-align: center;
      padding: 20px;
      background-color: #0BA7E3;
      color: white;
      border-top: 5px solid #0BA7E3;
      font-size: 14px;
    }
    .btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #0BA7E3;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      margin-top: 20px;
    }
    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="{{ $message->embed(public_path('images/logo/logo-gascpns.png')) }}" alt="Logo Bisnis Anda" width="200">
    </div>
    <div class="content">
    <h1>Konfirmasi Pembelian</h1>
      <p>Hai, <strong>{{ $user->name }}</strong>!</p>
        <p>Berikut adalah detail pembelianmu:</p>
      <div class="order-details">
        <h2>Detail Pembelian</h2>
        <table>
          <tr>
            <th>Order ID</th>
            <td><strong>{{ $transaction->invoice_code }}</strong></td>
          </tr>
          <tr>
            <th>Paket Transaksi</th>
            <td><strong>{{ $transaction->package->name }}</strong></td>
          </tr>
          <tr>
            <th>Nomor Pembayaran</th>
            <td>
                <strong>
                    {{ $transaction->payment_number }}
                </strong>
            </td>
          </tr>
          <tr>
            <th>Pembayaran</th>
            <td><strong>{{ $transaction->payment_channel }} - {{ $transaction->payment_method }}</strong></td>
          </tr>
          <tr>
            <th>Total Pembayaran</th>
            <td><strong>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
          </tr>
          <tr>
            <th>Batas Bayar</th>
            <td>
                <strong>
                {{ \Carbon\Carbon::parse($transaction->payment_expired)->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB
                </strong>
            </td>
          </tr>
        </table>
      </div>
      <p>Mohon segera lakukan pembayaran sebelum batas waktu habis.</p>
      <p>Terima Kasih! Semoga lolos CPNS tahun ini, ya!</p>
    </div>
    <div class="footer">
      &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
  </div>
</body>
</html>
