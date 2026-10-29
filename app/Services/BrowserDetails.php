<?php

namespace App\Services;

class BrowserDetails
{
    /** @return array{browser: string, platform: string, device: string} */
    public function fromUserAgent(?string $userAgent): array
    {
        $agent = $userAgent ?? '';
        $browser = match (true) {
            preg_match('/Edg(?:e|A|iOS)?\/([\d.]+)/i', $agent, $match) === 1 => 'Microsoft Edge '.$match[1],
            preg_match('/(?:OPR|Opera)\/([\d.]+)/i', $agent, $match) === 1 => 'Opera '.$match[1],
            preg_match('/(?:Chrome|CriOS)\/([\d.]+)/i', $agent, $match) === 1 => 'Google Chrome '.$match[1],
            preg_match('/Firefox\/([\d.]+)/i', $agent, $match) === 1 => 'Firefox '.$match[1],
            preg_match('/Version\/([\d.]+).*Safari/i', $agent, $match) === 1 => 'Safari '.$match[1],
            default => $agent !== '' ? 'Navigateur non identifié' : 'Navigateur inconnu',
        };

        $platform = match (true) {
            preg_match('/Windows NT 10/i', $agent) === 1 => 'Windows 10/11',
            preg_match('/Windows NT 6\.3/i', $agent) === 1 => 'Windows 8.1',
            preg_match('/Android/i', $agent) === 1 => 'Android',
            preg_match('/iPhone|iPad|iPod/i', $agent) === 1 => 'iOS/iPadOS',
            preg_match('/Mac OS X/i', $agent) === 1 => 'macOS',
            preg_match('/Linux/i', $agent) === 1 => 'Linux',
            default => 'Système non identifié',
        };

        $device = match (true) {
            preg_match('/iPhone/i', $agent) === 1 => 'iPhone',
            preg_match('/iPad/i', $agent) === 1 => 'iPad',
            preg_match('/Android/i', $agent) === 1 && preg_match('/Mobile/i', $agent) === 1 => 'Téléphone Android',
            preg_match('/Android/i', $agent) === 1 => 'Appareil Android',
            default => 'Ordinateur',
        };

        return ['browser' => $browser, 'platform' => $platform, 'device' => $device];
    }
}
