<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OneTimePrint
{
    public static function issue(int $userId, int $id, string $model): string
    {
        $token = Str::random(48);
        Cache::put(self::cacheKey($token), [
            'user_id' => $userId,
            'id' => $id,
            'model' => $model,
        ], now()->addMinutes(10));

        return $token;
    }

    /**
     * Playlist print links carry a one-time token. The first load consumes it.
     * Reopening that URL (Ctrl+Shift+T restores the closed tab) must not print again.
     * Requests without a token keep the existing print behavior.
     */
    public static function blocked(Request $request, int $id, string $model)
    {
        if (! $request->filled('once')) {
            return null;
        }

        $payload = Cache::pull(self::cacheKey($request->query('once')));
        $valid = is_array($payload)
            && (int) ($payload['user_id'] ?? 0) === (int) Auth::id()
            && (int) ($payload['id'] ?? 0) === $id
            && ($payload['model'] ?? '') === $model;

        if ($valid) {
            return null;
        }

        return response()
            ->view('admin.playlists.print_expired')
            ->withHeaders(self::noStoreHeaders());
    }

    public static function response(string $view, array $data, Request $request)
    {
        if (! $request->filled('once')) {
            return view($view, $data);
        }

        $data['oneTimePrint'] = true;

        return response()
            ->view($view, $data)
            ->withHeaders(self::noStoreHeaders());
    }

    public static function noStoreHeaders(): array
    {
        return [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];
    }

    private static function cacheKey(string $token): string
    {
        return 'playlist-print:' . $token;
    }
}
