<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class RoleMiddleware
{
    protected array $allowedRoles = [];

    public function __construct(array $allowedRoles = ['admin', 'super_admin'])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(Request $request): void
    {
        $user = Session::get('user');
        if (!$user) {
            if ($request->isJson()) {
                Response::error('Unauthorized access.', [], 401);
            } else {
                Response::redirect('/login');
            }
        }

        $userRole = $user['role_name'] ?? '';

        if ($userRole === 'super_admin') {
            return; // Super admin bypasses all role checks
        }

        if (!in_array($userRole, $this->allowedRoles)) {
            if ($request->isJson()) {
                Response::error('Forbidden. Insufficient role permissions.', [], 403);
            } else {
                Session::setFlash('error', 'Access denied. You do not have permission to view this section.');
                Response::redirect('/customer');
            }
        }
    }
}
