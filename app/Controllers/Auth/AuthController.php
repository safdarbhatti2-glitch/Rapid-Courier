<?php

namespace App\Controllers\Auth;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Database;
use App\Core\View;
use App\Services\AuditService;

class AuthController
{
    public function showLogin(Request $request): void
    {
        View::render('auth.login', ['title' => 'Login — Antigravity Express UAE'], 'auth');
    }

    public function login(Request $request): void
    {
        $email    = trim($request->input('email', ''));
        $password = $request->input('password', '');

        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Please provide both email and password.');
            Response::redirect('/login');
        }

        $user = Database::fetchOne("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?", [$email]);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Session::setFlash('error', 'Invalid email or password.');
            Response::redirect('/login');
        }

        if ($user['status'] !== 'active') {
            Session::setFlash('error', 'Your account has been deactivated. Please contact support.');
            Response::redirect('/login');
        }

        // Fetch customer profile if customer role
        $customerId = null;
        if ($user['role_name'] === 'customer') {
            $cust = Database::fetchOne("SELECT id FROM customers WHERE user_id = ? OR email = ?", [$user['id'], $user['email']]);
            $customerId = $cust['id'] ?? null;
        }

        // Regenerate Session ID to prevent session fixation
        Session::regenerate();

        $sessionUser = [
            'id'          => $user['id'],
            'role_id'     => $user['role_id'],
            'role_name'   => $user['role_name'],
            'name'        => $user['name'],
            'email'       => $user['email'],
            'phone'       => $user['phone'],
            'customer_id' => $customerId
        ];

        Session::set('user', $sessionUser);

        // Update last login
        Database::execute("UPDATE users SET last_login_at = NOW() WHERE id = ?", [$user['id']]);

        AuditService::log('user_login', 'user', $user['id']);

        if ($user['role_name'] === 'customer') {
            Response::redirect('/customer');
        } else {
            Response::redirect('/admin');
        }
    }

    public function showRegister(Request $request): void
    {
        View::render('auth.register', ['title' => 'Customer Registration — Antigravity Express UAE'], 'auth');
    }

    public function register(Request $request): void
    {
        $name     = trim($request->input('name', ''));
        $email    = trim(strtolower($request->input('email', '')));
        $phone    = trim($request->input('phone', ''));
        $company  = trim($request->input('company_name', ''));
        $password = $request->input('password', '');
        $confirm  = $request->input('password_confirmation', '');

        if (empty($name) || empty($email) || empty($password) || empty($phone)) {
            Session::setFlash('error', 'Please fill in all required fields.');
            Response::redirect('/register');
        }

        if ($password !== $confirm) {
            Session::setFlash('error', 'Passwords do not match.');
            Response::redirect('/register');
        }

        if (strlen($password) < 8) {
            Session::setFlash('error', 'Password must be at least 8 characters long.');
            Response::redirect('/register');
        }

        $existing = Database::fetchOne("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            Session::setFlash('error', 'An account with this email address already exists.');
            Response::redirect('/register');
        }

        Database::beginTransaction();

        try {
            // Customer role ID
            $role = Database::fetchOne("SELECT id FROM roles WHERE name = 'customer'");
            $roleId = $role['id'] ?? 7;

            $hash = password_hash($password, PASSWORD_DEFAULT);

            Database::execute("INSERT INTO users (role_id, name, email, phone, password_hash, status) VALUES (?, ?, ?, ?, ?, 'active')", [
                $roleId, $name, $email, $phone, $hash
            ]);
            $userId = Database::lastInsertId();

            $custType = !empty($company) ? 'corporate' : 'individual';
            Database::execute("INSERT INTO customers (user_id, customer_type, company_name, contact_name, email, phone, status) VALUES (?, ?, ?, ?, ?, ?, 'active')", [
                $userId, $custType, $company ?: null, $name, $email, $phone
            ]);

            Database::commit();

            AuditService::log('user_register', 'user', $userId);

            Session::setFlash('success', 'Registration successful! You may now log in.');
            Response::redirect('/login');

        } catch (\Exception $e) {
            Database::rollBack();
            Session::setFlash('error', 'Registration failed. Please try again.');
            Response::redirect('/register');
        }
    }

    public function logout(Request $request): void
    {
        AuditService::log('user_logout', 'user');
        Session::destroy();
        Response::redirect('/login');
    }
}
