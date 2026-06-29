<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Role;
use App\Core\Session;
use App\Middleware\GuestMiddleware;

class AuthController extends Controller
{
    public function showLogin()
    {
        (new GuestMiddleware())->execute();

        $settingModel = new \App\Models\Setting();
        $login_logo = $settingModel->get('login_logo', '');
        $site_logo = $settingModel->get('site_logo', '');

        // Generar Math CAPTCHA e invalidar el anterior
        $num1 = random_int(1, 9);
        $num2 = random_int(1, 9);
        Session::set('captcha_answer', $num1 + $num2);
        // Marcar el captcha como no usado (se invalida tras cada intento POST)
        Session::set('captcha_used', false);

        return $this->render('auth/login', [
            'login_logo' => $login_logo,
            'site_logo' => $site_logo,
            'captcha_question' => "$num1 + $num2"
        ]);
    }

    public function login()
    {
        (new GuestMiddleware())->execute();
        (new \App\Middleware\RateLimitMiddleware(5, 60))->execute();

        $email    = $this->request->post('email');
        $password = $this->request->post('password');
        $captcha  = $this->request->post('captcha');

        if (!$email || !$password || $captcha === null) {
            Session::flash('error', 'Por favor, completa todos los campos.');
            return $this->redirect('/login');
        }

        // Invalidar CAPTCHA tras cada intento (evita reutilización)
        if (Session::get('captcha_used', true) || (int)$captcha !== Session::get('captcha_answer')) {
            Session::set('captcha_used', true);
            // Delay constante para no revelar si el CAPTCHA fue el problema
            usleep(random_int(200000, 400000));
            Session::flash('error', 'Credenciales o CAPTCHA inválidos. Recarga la página e inténtalo de nuevo.');
            return $this->redirect('/login');
        }
        Session::set('captcha_used', true);

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Delay constante independiente del resultado para prevenir timing attacks
        // y enumeración de cuentas vía tiempo de respuesta
        $start = microtime(true);

        $isValid = false;
        if ($user && empty($user['is_locked'])) {
            $isValid = password_verify($password, $user['password']);
        }

        // Completar hasta ~300ms totales independientemente del resultado
        $elapsed = (microtime(true) - $start) * 1e6;
        $target  = 300000; // 300ms en microsegundos
        if ($elapsed < $target) {
            usleep((int)($target - $elapsed));
        }

        if ($isValid) {
            $userModel->resetFailedAttempts($user['id']);

            // Prevenir Session Fixation: regenerar ID antes de escribir datos sensibles
            session_regenerate_id(true);

            Session::set('user_id', $user['id']);
            Session::set('user_role_id', $user['role_id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::set('role_id', $user['role_id']);

            $roleModel = new Role();
            $permissions = $roleModel->getPermissions($user['role_id']);
            Session::set('user_permissions', $permissions);

            // Rotar CSRF token tras autenticación
            Session::rotateCsrfToken();

            Session::flash('success', '¡Bienvenido, ' . $user['name'] . '!');
            return $this->redirect('/admin/dashboard');
        }

        // Mensaje genérico: no revelar si el email existe, si está bloqueado,
        // ni cuántos intentos quedan
        if ($user && empty($user['is_locked'])) {
            $attempts = ($user['failed_attempts'] ?? 0) + 1;
            if ($attempts >= 3) {
                $userModel->lockUser($user['id']);
            } else {
                $userModel->incrementFailedAttempts($user['id']);
            }
        }

        Session::flash('error', 'Credenciales inválidas o cuenta bloqueada. Contacta al administrador si el problema persiste.');
        return $this->redirect('/login');
    }

    public function logout()
    {
        Session::destroy();
        header('Location: /login');
        exit;
    }
}
