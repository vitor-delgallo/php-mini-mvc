<?php
namespace System\Controllers;

use Psr\Http\Message\ResponseInterface;
use System\Core\Language;
use System\Core\Response;

class I18n {
    public function index(?string $prefix = null): ResponseInterface {
        $requestedPrefix = $_GET['prefix'] ?? $prefix ?? '';
        if (!is_string($requestedPrefix) || trim($requestedPrefix) === '') {
            return Response::json(['error' => 'missing_prefix'], 400);
        }

        $lang = $_GET['lang'] ?? null;
        if (!is_string($lang) || trim($lang) === '') {
            $lang = null;
        }

        $normalizedPrefix = Language::normalizePrefix($requestedPrefix);
        $translations = Language::getByPrefix($normalizedPrefix, $lang);

        return Response::json([
            'lang' => Language::currentLang(),
            'prefix' => $normalizedPrefix,
            'translations' => $translations,
        ]);
    }
}
