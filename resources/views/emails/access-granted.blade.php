<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selamat! {{ $transaction->package->name }} Sudah Bisa Diakses</title>
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
        max-width: 95%; /* Adjust for smaller screens */
        margin: 0 auto;
        box-sizing: border-box;
    }
    .header {
      text-align: center;
      padding: 20px 0;
      background-color: #ffffff;
      color: white;
    }
    .header img {
      width: 225px;
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
      width: 100%;
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
      border: 1px solid #e5e5e5;
      text-align: left;
      padding: 8px; /* Reduce padding for smaller screens */
        word-break: break-word;
    }
    .order-details table th {
      background-color: #f1f1f1;
      font-weight: 500;
    }
    .order-details table td {
      background-color: #ffffff;
    }

    .header-img {
      text-align: center;
      padding: 20px 0;
      background-color: #FFFFFF;
      color: white;
      margin-top: 10px;
    }
    .header-img img {
      width: 225px;
      margin-bottom: 10px;
    }
    .header-img h1 {
      margin: 0;
      font-size: 24px;
      font-weight: 700;
    }

    .header-bot {
      text-align: center;
      padding: 20px 0;
      background-color: #0BA7E3;
      color: white;
      margin-top: 10px;
    }
    .header-bot img {
      width: 225px;
      margin-bottom: 10px;
    }
    .header-bot h1 {
      margin: 0;
      font-size: 24px;
      font-weight: 700;
    }
    .footer {
        text-align: center;
      padding: 20px 0;
      background-color: #0BA7E3;
      color: white;
    }

    /* Stack table rows on smaller screens */
    @media (max-width: 400px) {
        .container {
            padding: 10px; /* Reduce padding for smaller screens */
        }
        .order-details table {
            display: block;
            overflow-x: auto; /* Enable horizontal scrolling if needed */
        }
        .order-details table thead, .order-details table tbody, .order-details table tr, .order-details table th, .order-details table td {
            display: block; /* Make each cell full-width */
        }
        .order-details table th {
            text-align: left;
        }
        .order-details table td {
            text-align: right; /* Align values for better readability */
        }

        .footer {
            padding: 10px; /* Reduce padding for smaller screens */
            font-size: 12px; /* Optionally adjust font size */
            text-align: center;
            width: auto;
        }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
        <img src="{{ $message->embed(public_path('images/logo/logo-gascpns.png')) }}" alt="Logo Bisnis Anda" width="200">
    </div>
    <div class="content">
    <h1>Selamat!</h1>
      <p>Hai, <strong>{{ $user->name }}</strong>!</p>
      <p>Yeay! Paketmu <strong>{{ $transaction->package->name }}</strong> sudah bisa kamu akses. Sekarang kamu sudah bisa mengakses tryout dan materi di Dashboard kamu.</p>
      <div class="order-details">
        <h2>Detail Paket</h2>
        <table>
          <tr>
            <th>Paket Transaksi</th>
            <td><strong>{{ $transaction->package->name }}</strong></td>
          </tr>
        </table>
      </div>

      <div class="order-details">
        <h2>Detail Pembeli</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <td><strong>{{ $transaction->invoice_code }}</strong></td>
            </tr>
            <tr>
                <th>Nama Pembeli</th>
                <td><strong>{{ $transaction->studentTransaction->name }}</strong></td>
            </tr>
            <tr>
                <th>Email Pembeli</th>
                <td><strong>{{ $transaction->studentTransaction->email }}</strong></td>
            </tr>
            <tr>
                <th>Paket Transaksi</th>
                <td><strong>{{ $transaction->package->name }}</strong></td>
            </tr>
            <tr>
                <th>Total Pembayaran</th>
                <td><strong>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
      </div>
      {{-- cheers --}}
    <h4 style="margin-top: 20px;">Cheers,</h4>
    <h4>Tim {{ config('app.name') }}</h4>
    </div>

    <div class="header-img">
        <img src="{{ $message->embed(public_path('images/logo/success.jpg')) }}" alt="Logo Bisnis Anda" width="400">
    </div>

    <div class="header-bot">
      &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
  </div>
</body>
</html>
