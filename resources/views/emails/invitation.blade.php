<!DOCTYPE html>
<html>
<head>
    <title>Invitation to Colocation</title>
</head>
<body>
    <h1>You've been invited!</h1>
    <p>You have been invited to join the colocation: <strong>{{ $invitation->colocation->name }}</strong></p>
    <p>{{ $invitation->colocation->description }}</p>
    <p>
        <a href="{{ route('invitations.accept', $invitation->token) }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Accept Invitation
        </a>
    </p>
    <p>Or copy this link: {{ route('invitations.accept', $invitation->token) }}</p>
</body>
</html>
