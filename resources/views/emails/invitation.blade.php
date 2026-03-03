<!DOCTYPE html>
<html>
<head>
    <title>Invitation to Colocation</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #4F46E5; }
        .btn { display: inline-block; padding: 12px 24px; margin: 10px 5px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-accept { background-color: #10B981; color: white; }
        .btn-refuse { background-color: #EF4444; color: white; }
        .token { background: #F3F4F6; padding: 10px; border-radius: 5px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏠 You've been invited!</h1>
        <p><strong>{{ $inviterName }}</strong> has invited you to join the colocation:</p>
        <h2>{{ $colocationName }}</h2>
        
        <div style="margin: 30px 0;">
            <a href="{{ route('invitations.accept', $token) }}" class="btn btn-accept">✅ Accept Invitation</a>
            <a href="{{ route('invitations.refuse', $token) }}" class="btn btn-refuse">❌ Refuse Invitation</a>
        </div>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #E5E7EB;">
        
        <p><strong>Or use this token manually:</strong></p>
        <div class="token">{{ $token }}</div>
        
        <p style="margin-top: 20px; font-size: 12px; color: #6B7280;">
            Direct link: <a href="{{ route('invitations.accept', $token) }}">{{ route('invitations.accept', $token) }}</a>
        </p>
    </div>
</body>
</html>
