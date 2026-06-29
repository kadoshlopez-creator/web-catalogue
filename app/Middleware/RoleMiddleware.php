<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Session;

class RoleMiddleware implements Middleware
{
    protected string $requiredPermission;

    public function __construct(string $requiredPermission = '')
    {
        $this->requiredPermission = $requiredPermission;
    }

    public function execute(): void
    {
        if (!Session::has('user_id')) {
            Session::flash('error', 'Debes iniciar sesión para acceder a esta área.');
            header('Location: /login');
            exit;
        }

        if (empty($this->requiredPermission)) {
            return;
        }

        $userPermissions = Session::get('user_permissions', []);

        // Super admin: tiene acceso a todo con {"all": true}
        if (!empty($userPermissions['all']) && $userPermissions['all'] === true) {
            return;
        }

        // Permisos específicos: {"products": true, "settings.manage": true, ...}
        if (
            isset($userPermissions[$this->requiredPermission]) &&
            $userPermissions[$this->requiredPermission] === true
        ) {
            return;
        }

        Session::flash('error', 'No tienes permiso para acceder a esta sección.');
        header('Location: /admin/dashboard');
        exit;
    }
}
