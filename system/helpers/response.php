<?php

use \System\Core\Response;
use \Psr\Http\Message\ResponseInterface;

function response_redirect(string $uri = '', string $method = 'auto', ?int $code = null): ResponseInterface {
    return Response::redirect($uri, $method, $code);
}

function response_html(string $html, int $status = 200): ResponseInterface {
    return Response::html($html, $status);
}

function response_json(array|string $data, int $status = 200): ResponseInterface {
    return Response::json($data, $status);
}

function response_xml(string $xml, int $status = 200): ResponseInterface {
    return Response::xml($xml, $status);
}