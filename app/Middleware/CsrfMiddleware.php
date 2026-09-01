<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request): void
    {
        $method = $request->getMethod();
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return;
        }

        $token = $request->input('_token') ?? $request->getHeader('X-CSRF-TOKEN');

        if (!Session::verifyCsrfToken($token)) {
            if ($request->isJson()) {
                Response::error('Invalid or missing CSRF security token.', [], 403);
            } else {
                Session::setFlash('error', 'Security token expired. Please try submitting the form again.');
                Response::redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
        }
    }
}
