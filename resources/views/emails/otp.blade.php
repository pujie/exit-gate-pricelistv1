<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Login</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; padding: 40px; border-radius: 8px; border: 1px solid #e0e0e0; }
        .header { text-align: center; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #2d3748; }
        .content { color: #4a5568; line-height: 1.6; text-align: center; }
        .otp-code { font-size: 32px; font-weight: bold; color: #4a90e2; letter-spacing: 5px; margin: 30px 0; padding: 20px; background: #f8fafc; border: 2px dashed #cbd5e0; display: inline-block; }
        .footer { text-align: center; font-size: 12px; color: #a0aec0; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Pricelist App</div>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Anda menerima email ini karena ada permintaan login ke akun Anda. Gunakan kode verifikasi di bawah ini untuk melanjutkan:</p>
            
            <div class="otp-code">{{ $code }}</div>
            
            <p>Kode ini berlaku selama <strong>15 menit</strong>. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
        </div>
        <div class="footer">
            &copy; 2026 Pricelist Digital Team. All rights reserved.
        </div>
    </div>
</body>
</html>