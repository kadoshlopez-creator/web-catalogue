<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Catálogo Web</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-[400px] bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-8">
            <!-- Logo -->
            <div class="mb-6">
                <?php $logoUrl = !empty($login_logo) ? $login_logo : (!empty($site_logo) ? $site_logo : null); ?>
                <?php if ($logoUrl): ?>
                    <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars($site_name ?? 'Logo') ?>" class="h-8 object-contain">
                <?php else: ?>
                    <div class="text-xl font-bold text-gray-900"><?= htmlspecialchars($site_name ?? 'Vitrino') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-6">
                <h1 class="text-[22px] font-bold text-gray-900 mb-1">Iniciar sesión</h1>
                <p class="text-[13px] text-gray-600">Continuar a <?= htmlspecialchars($site_name ?? 'Vitrino') ?></p>
            </div>

            <?php
            use App\Core\Session;
            $error = Session::getFlash('error');
            if ($error): 
            ?>
                <div class="mb-6 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-[13px] flex items-start">
                    <svg class="w-4 h-4 mr-2 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form class="space-y-4" action="/login" method="POST">
                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
                
                <div>
                    <label for="email" class="block text-[13px] font-medium text-gray-700 mb-1.5">Correo electrónico</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full px-3 py-2.5 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-[13px] shadow-sm transition-shadow"
                        placeholder="tu-correo@ejemplo.com">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-[13px] font-medium text-gray-700">Contraseña</label>
                    </div>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-3 py-2.5 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-[13px] shadow-sm transition-shadow"
                        placeholder="••••••••">
                </div>

                <!-- CAPTCHA -->
                <div>
                    <label for="captcha" class="block text-[13px] font-medium text-gray-700 mb-1.5">
                        Verificación de seguridad: ¿Cuánto es <span class="font-bold text-blue-700"><?= htmlspecialchars($captcha_question ?? '') ?></span>?
                    </label>
                    <input type="number" id="captcha" name="captcha" required 
                        class="w-full px-3 py-2.5 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-[13px] shadow-sm transition-shadow"
                        placeholder="Tu respuesta">
                </div>

                <button type="submit" class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-md transition-colors duration-200 text-[14px]">
                    Ingresar
                </button>
            </form>

            <div class="mt-8 border-t border-gray-100 pt-6">
                <div class="flex gap-4 text-[11px] text-gray-500">
                    <a href="#" class="hover:underline">Ayuda</a>
                    <a href="#" class="hover:underline">Privacidad</a>
                    <a href="#" class="hover:underline">Términos</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
