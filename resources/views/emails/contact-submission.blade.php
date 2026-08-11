@php
    $eyebrow = $source->email_eyebrow ?: 'Contact Bridge';
    $heading = $source->email_heading ?: 'New message received';
    $intro = $source->email_intro ?: 'A visitor submitted a contact form from '.$submission['website_origin'].'.';
    $footer = $source->email_footer ?: 'This message was securely routed by Contact Bridge.';
    $preview = $preview ?? false;
    $assetUrl = function (?string $path) use ($preview) {
        if (! $path) {
            return null;
        }

        $path = \Illuminate\Support\Facades\Storage::disk('public')->url($path);

        return $preview ? $path : url($path);
    };
    $headerImage = $assetUrl($source->email_header_image_path);
    $logo = $assetUrl($source->email_logo_path);
    $headerColor = $source->email_header_color ?: '#111b36';
    $accentColor = $source->email_accent_color ?: '#276ef1';
    $backgroundColor = $source->email_background_color ?: '#f4f7fb';
@endphp
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>{{ $heading }}</title></head>
<body style="margin:0;background:{{ $backgroundColor }};color:#172033;font-family:Arial,Helvetica,sans-serif;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:{{ $backgroundColor }};padding:32px 12px;"><tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#fff;border:1px solid #e4eaf2;border-radius:18px;overflow:hidden;">
    <tr><td @if($headerImage) background="{{ $headerImage }}" @endif style="background-color:{{ $headerColor }};@if($headerImage) background-image:linear-gradient(rgba(17,27,54,.78),rgba(17,27,54,.88)),url('{{ $headerImage }}');background-size:cover;background-position:center;@endif padding:32px 36px;color:#fff;">
        @if($logo)<img src="{{ $logo }}" alt="{{ $eyebrow }} logo" style="display:block;max-width:160px;max-height:52px;width:auto;height:auto;margin:0 0 18px;">@endif
        <div style="font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#bcd0f5;">{{ $eyebrow }}</div>
        <h1 style="margin:12px 0 0;font-size:26px;line-height:1.25;">{{ $heading }}</h1>
        <p style="margin:10px 0 0;color:#e0e9fb;font-size:14px;line-height:1.55;">{{ $intro }}</p>
    </td></tr>
    <tr><td style="padding:32px 36px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr><td style="padding:0 0 18px;color:#70809a;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Sender</td></tr>
        <tr><td style="padding:0 0 8px;font-size:20px;font-weight:bold;">{{ $submission['first_name'] }} {{ $submission['last_name'] }}</td></tr>
        <tr><td style="padding:0 0 24px;font-size:14px;"><a href="mailto:{{ $submission['email'] }}" style="color:{{ $accentColor }};text-decoration:none;">{{ $submission['email'] }}</a></td></tr>
        <tr><td style="border-top:1px solid #e4eaf2;padding-top:24px;color:#70809a;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Product of interest</td></tr>
        <tr><td style="padding:8px 0 24px;font-size:15px;">{{ $submission['product'] }}</td></tr>
        <tr><td style="border-top:1px solid #e4eaf2;padding-top:24px;color:#70809a;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Message</td></tr>
        <tr><td style="padding-top:10px;font-size:15px;line-height:1.7;white-space:pre-line;">{{ $submission['message'] }}</td></tr>
    </table></td></tr>
    <tr><td style="padding:20px 36px;background:#f8fafc;color:#8491a7;font-size:12px;line-height:1.55;">{{ $footer }}<br>Origin: {{ $submission['website_origin'] }} &middot; Recipient: {{ $submission['recipient'] }} &middot; {{ $submission['submitted_at'] }}</td></tr>
</table>
</td></tr></table>
</body>
</html>
