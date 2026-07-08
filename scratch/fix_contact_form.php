<?php
require 'vendor/autoload.php';

$db = App\Core\Database::getConnection();

$stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'custom_pages'");
$stmt->execute();
$value = $stmt->fetchColumn();

if ($value) {
    $pages = json_decode($value, true);
    $updated = false;
    
    foreach ($pages as &$page) {
        if ($page['slug'] === 'contacto' || stripos($page['content'], 'Escríbenos') !== false) {
            
            // Reemplazar el bloque de formulario completo
            $count = 0;
            $page['content'] = preg_replace(
                '/<form[^>]*>.*?<\/form>/is',
                '<form method="POST" action="/contacto/enviar" class="space-y-4">
                <input type="hidden" name="_csrf_token" value="{{csrf_token}}">
                <input type="text" name="hp_website" class="hidden" style="display:none;" autocomplete="off" tabindex="-1">
                <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Nombre" required>
                <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Email" required>
                <textarea rows="4" name="message" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Mensaje" required></textarea>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors">Enviar</button>
            </form>',
                $page['content'],
                -1,
                $count
            );
            
            if ($count > 0) {
                $updated = true;
                echo "Updated form in page '{$page['title']}'\n";
            }
        }
    }
    
    if ($updated) {
        $newJson = json_encode($pages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $updateStmt = $db->prepare("UPDATE settings SET setting_value = :val WHERE setting_key = 'custom_pages'");
        $updateStmt->execute(['val' => $newJson]);
        echo "Successfully updated custom_pages in DB.\n";
    } else {
        echo "No pages needed updating or regex didn't match.\n";
    }
}
