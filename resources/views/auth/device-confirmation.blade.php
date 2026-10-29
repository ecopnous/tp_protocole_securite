<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Confirmer l’appareil</title></head>
<body style="margin:0;background:#0f172a;color:#f8fafc;font-family:Arial,sans-serif;padding:32px 16px">
    <main style="max-width:520px;margin:10vh auto;background:#1e293b;border:1px solid #334155;border-radius:18px;padding:30px">
        <h1 style="margin-top:0">Confirmez cette activité</h1>
        <p style="line-height:1.6;color:#cbd5e1">
            @if ($intent === 'deny')
                Confirmez le signalement pour révoquer immédiatement la session de cet appareil et l’oublier.
            @else
                Confirmez que cette connexion vient de vous pour terminer la vérification de cet appareil.
            @endif
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:24px">
            @if ($intent === 'deny')
                <form method="POST" action="{{ $denyUrl }}">@csrf<button style="border:0;border-radius:9px;background:#dc2626;color:white;padding:12px 16px;font-weight:bold">Révoquer et oublier cet appareil</button></form>
            @else
                <form method="POST" action="{{ $confirmUrl }}">@csrf<button style="border:0;border-radius:9px;background:#059669;color:white;padding:12px 16px;font-weight:bold">Oui, c’était moi</button></form>
            @endif
        </div>
    </main>
</body>
</html>
