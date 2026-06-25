<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi Atur Ulang Kata Sandi</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f5f7; -webkit-text-size-adjust: none; text-size-adjust: none;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f5f7; padding: 40px 14px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 480px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);">
                    
                    <tr>
                        <td style="background-color: #16a34a; padding: 35px 25px; text-align: center;">
                            <div style="color: rgba(255, 255, 255, 0.8); font-size: 22px; font-weight: bold; letter-spacing: 2px; margin-bottom: 6px;">
                                {{ getSetting('nama_aplikasi') }}
                            </div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">Atur Ulang Kata Sandi</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 35px 30px; color: #1f2937; font-size: 14px; line-height: 1.6;">
                            <p style="margin-top: 0; margin-bottom: 12px; font-weight: bold; color: #111827; font-size: 15px;">Halo,</p>
                            <p style="margin-bottom: 25px; color: #4b5563;">Anda menerima email ini karena ada permintaan untuk mengatur ulang kata sandi akun Anda di sistem kami.</p>
                            
                            <div style="background-color: #f0fdf4; border: 1px dashed #16a34a; border-radius: 14px; padding: 24px; text-align: center; margin-bottom: 25px;">
                                <p style="margin-top: 0; margin-bottom: 10px; font-size: 11px; color: #15803d; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px;">Kode Verifikasi Anda</p>
                                <h2 style="margin: 0; font-size: 36px; font-weight: bold; color: #16a34a; letter-spacing: 6px; font-family: 'Courier New', Courier, monospace;">{{ $token }}</h2>
                            </div>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border-radius: 8px; padding: 12px 15px;">
                                <tr>
                                    <td style="font-size: 12px; color: #6b7280; line-height: 1.5;">
                                        <strong style="color: #dc2626;">Penting:</strong> Kode ini hanya berlaku selama <strong style="color: #111827;">15 menit</strong>. Jika Anda tidak melakukan permintaan ini, abaikan email ini dengan aman.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 25px; text-align: center; border-top: 1px solid #f3f4f6;">
                            <p style="margin: 0; font-size: 11px; color: #9ca3af; font-weight: 500;">
                                &copy; {{ date('Y') }} {{ getSetting('judul_website') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>