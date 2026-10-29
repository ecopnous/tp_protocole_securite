<!doctype html>
<html lang="fr">
<body style="margin:0;background:#f1f5f9;color:#0f172a;font-family:Arial,sans-serif;padding:32px 16px">
    <main style="max-width:560px;margin:0 auto;background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:28px">
        <h1 style="font-size:22px;margin:0 0 16px">{{ $title }}</h1>
        <p style="line-height:1.6">Nous avons détecté une activité depuis un nouvel appareil :</p>
        <ul style="line-height:1.8;padding-left:20px">
            <li>{{ $device }} · {{ $browser }}</li>
            <li>{{ $platform }}</li>
            <li>Adresse IP : {{ $ip_address }}</li>
        </ul>
        <p style="line-height:1.6">Confirmez si cette activité vient de vous. Si vous ne la reconnaissez pas, révoquez immédiatement cet appareil.</p>
        <p style="margin:26px 0">
            <a href="{{ $confirmUrl }}" style="display:inline-block;background:#047857;color:#fff;text-decoration:none;padding:12px 18px;border-radius:8px;font-weight:bold">Oui, c’était moi</a>
            <a href="{{ $denyUrl }}" style="display:inline-block;background:#b91c1c;color:#fff;text-decoration:none;padding:12px 18px;border-radius:8px;font-weight:bold;margin-left:8px">Ce n’était pas moi</a>
        </p>
        <p style="font-size:12px;color:#64748b">Ces liens expirent après 24 heures. Si vous n’êtes pas à l’origine de cette demande, choisissez « Ce n’était pas moi ».</p>
    </main>
</body>
</html>
