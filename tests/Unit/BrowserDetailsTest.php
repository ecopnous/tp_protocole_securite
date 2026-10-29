<?php

use App\Services\BrowserDetails;

test('browser details are parsed from the user agent actually received', function () {
    $details = (new BrowserDetails)->fromUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36');

    expect($details)->toBe([
        'browser' => 'Google Chrome 156.0.0.0',
        'platform' => 'Windows 10/11',
        'device' => 'Ordinateur',
    ]);
});

test('unknown user agents are not presented as fabricated browser versions', function () {
    $details = (new BrowserDetails)->fromUserAgent('CustomClient/2.1');

    expect($details['browser'])->toBe('Navigateur non identifié')
        ->and($details['platform'])->toBe('Système non identifié');
});
