<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class GuestMiddleware
{
    public function handle(Request $request): void
    {
        $user = Session::get('user');
        if ($user) {
            if ($user['role_name'] === 'customer') {
                Response::redirect('/customer');
            } else {
                Response::redirect('/admin');
            }
        }
    }
}
