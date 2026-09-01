<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request): void
    {
        $user = Session::get('user');
        if (!$user) {
            if ($request->isJson()) {
                Response::error('Unauthenticated access. Please log in.', [], 401);
            } else {
                Session::setFlash('error', 'Please log in to access this page.');
                Response::redirect('/login');
            }
        }
    }
}
