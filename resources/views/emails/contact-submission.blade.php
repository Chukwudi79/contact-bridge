<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New contact form submission</title>
</head>
<body style="margin:0;background:#f4f7fb;color:#172033;font-family:Arial,Helvetica,sans-serif;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb;padding:32px 12px;">
    <tr><td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#fff;border:1px solid #e4eaf2;border-radius:18px;overflow:hidden;">
            <tr><td style="background:#111b36;padding:32px 36px;color:#fff;">
                <div style="font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#9fb4d8;">Contact bridge</div>
                <h1 style="margin:12px 0 0;font-size:26px;line-height:1.25;">New message received</h1>
                <p style="margin:10px 0 0;color:#cbd7ec;font-size:14px;">A visitor submitted a contact form from {{ $submission['website_origin'] }}.</p>
            </td></tr>
            <tr><td style="padding:32px 36px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr><td style="padding:0 0 18px;color:#70809a;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Sender</td></tr>
                    <tr><td style="padding:0 0 8px;font-size:20px;font-weight:bold;">{{ $submission['first_name'] }} {{ $submission['last_name'] }}</td></tr>
                    <tr><td style="padding:0 0 24px;font-size:14px;"><a href="mailto:{{ $submission['email'] }}" style="color:#276ef1;text-decoration:none;">{{ $submission['email'] }}</a></td></tr>
                    <tr><td style="border-top:1px solid #e4eaf2;padding-top:24px;color:#70809a;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Product of interest</td></tr>
                    <tr><td style="padding:8px 0 24px;font-size:15px;">{{ $submission['product'] }}</td></tr>
                    <tr><td style="border-top:1px solid #e4eaf2;padding-top:24px;color:#70809a;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Message</td></tr>
                    <tr><td style="padding-top:10px;font-size:15px;line-height:1.7;white-space:pre-line;">{{ $submission['message'] }}</td></tr>
                </table>
            </td></tr>
            <tr><td style="padding:20px 36px;background:#f8fafc;color:#8491a7;font-size:12px;">Origin: {{ $submission['website_origin'] }} &nbsp;·&nbsp; Recipient: {{ $submission['recipient'] }} &nbsp;·&nbsp; {{ $submission['submitted_at'] }}</td></tr>
        </table>
    </td></tr>
</table>
</body>
</html>
