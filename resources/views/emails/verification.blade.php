<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 20px auto;">
        <tr>
            <td align="center" style="padding: 20px;">
                <img src="{{ $message->embed(public_path('images/logo/logo-gascpns.png')) }}" alt="Logo Bisnis Anda" width="200">
            </td>
        </tr>
        <tr>
            <td style="background-color: #ffffff; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                <h1 style="color: #333333; text-align: center;">Verifikasi Email Anda</h1>

                <p style="line-height: 1.6;">Halo <strong>{{ $user->name }}</strong>,</p>

                <p style="line-height: 1.6;">Terima kasih telah mendaftar! Untuk menyelesaikan proses pendaftaran, silakan masukkan kode verifikasi berikut:</p>

                <div style="font-size: 24px; font-weight: bold; text-align: center; margin: 20px 0; padding: 10px; background-color: #f0f0f0; border-radius: 5px;">
                    {{ $otp }}
                </div>

                <p style="line-height: 1.6;">Kode verifikasi ini berlaku selama 15 menit.</p>

                <p style="line-height: 1.6;">Jika Anda tidak meminta kode ini, abaikan saja email ini.</p>

                <p style="text-align: center; margin-top: 20px; font-size: 12px; color: #777777;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
