<?php
namespace System\Controllers;

use Psr\Http\Message\ResponseInterface;
use System\Config\Globals;
use System\Core\Language;

class I18n {
    public function index(?string $prefix = null): ResponseInterface {
        $systemToken = $this->systemToken();
        if ($systemToken === null) {
            return response_json(['error' => 'system_i18n_disabled'], 404);
        }

        if (!$this->hasValidToken($systemToken)) {
            return response_json(['error' => 'forbidden'], 403);
        }

        $requestedPrefix = $_GET['prefix'] ?? $prefix ?? '';
        if (!is_string($requestedPrefix) || trim($requestedPrefix) === '') {
            return response_json(['error' => 'missing_prefix'], 400);
        }

        $lang = $_GET['lang'] ?? null;
        if (!is_string($lang) || trim($lang) === '') {
            $lang = null;
        }

        $normalizedPrefix = Language::normalizePrefix($requestedPrefix);
        $translations = Language::getByPrefix($normalizedPrefix, $lang);

        return response_json([
            'lang' => Language::currentLang(),
            'prefix' => $normalizedPrefix,
            'translations' => $translations,
        ]);
    }

    private function systemToken(): ?string {
        $token = Globals::env('SYSTEM_TOKEN');
        if (!is_string($token)) {
            return null;
        }

        $token = trim($token);
        return $token === '' ? null : $token;
    }

    private function hasValidToken(string $systemToken): bool {
        $requestToken = $this->requestToken();
        if ($requestToken === null) {
            return false;
        }

        return hash_equals($systemToken, $requestToken);
    }

    private function requestToken(): ?string {
        $headerToken = $_SERVER['HTTP_X_SYSTEM_TOKEN'] ?? null;
        if (is_string($headerToken) && trim($headerToken) !== '') {
            return trim($headerToken);
        }

        $authorization = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? null;

        if (!is_string($authorization)) {
            return null;
        }

        return preg_match('/^\s*Bearer\s+(.+?)\s*$/i', $authorization, $matches)
            ? $matches[1]
            : null;
    }
}
