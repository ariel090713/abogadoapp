<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; max-width: 100%; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1E3A8A; padding: 30px; text-align: center;">
                            <img src="https://lawyerstorage-public.s3.ap-southeast-2.amazonaws.com/abogadomo-logo.png" alt="AbogadoMo Logo" style="width: 80px; height: 80px; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold; letter-spacing: 1px;">AbogadoMo</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="color: #374151; font-size: 16px; line-height: 1.6;">
                                {!! $messageContent !!}
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 14px;">
                                Thank you for being a valued subscriber!
                            </p>
                            <a href="{{ url('/') }}" style="display: inline-block; padding: 12px 24px; background-color: #1E3A8A; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; margin-bottom: 20px;">
                                Visit AbogadoMo
                            </a>
                            <p style="margin: 20px 0 10px 0; color: #9ca3af; font-size: 12px;">
                                If you wish to unsubscribe from our newsletter, click the link below.
                            </p>
                            <a href="{{ $unsubscribeUrl }}" style="color: #B91C1C; text-decoration: underline; font-size: 12px;">
                                Unsubscribe
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
