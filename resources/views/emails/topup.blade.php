<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Topup</title>
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
    <h1>Konfirmasi Topup</h1>
      <p>Hai, <strong>{{ $user->name }}</strong>!</p>
        <p>Berikut adalah detail pembelianmu:</p>
      <div class="order-details">
        <h2>Detail Pembelian</h2>
        <table>
          <tr>
            <th>Order ID</th>
            <td><strong>{{ $transaction->id }}</strong></td>
          </tr>
          <tr>
            <th>Topup</th>
            <td><strong>{{ $transaction->amount }}</strong></td>
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
      {{-- cheers --}}
    <h4 style="margin-top: 20px;">Cheers,</h4>
    <h4>Tim {{ config('app.name') }}</h4>
    <div class="header-bot">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
  </div>
</body>
</html>
