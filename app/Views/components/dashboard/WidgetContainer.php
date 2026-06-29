<?php
/**
 * WidgetContainer Component
 * 
 * @param string $id Widget identifier
 * @param string $title Widget title
 * @param string $content Content inside the widget
 * @param string $colSpan Tailwind column span (e.g., 'col-span-1', 'col-span-2')
 */
?>
<div id="<?= htmlspecialchars($id ?? 'widget-'.uniqid()) ?>" class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col <?= $colSpan ?? 'col-span-1' ?> transition-all hover:shadow-md dashboard-widget" data-widget-id="<?= htmlspecialchars($id ?? '') ?>">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center cursor-move handle">
        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($title ?? 'Widget') ?></h3>
        <button class="text-gray-400 hover:text-gray-600 transition-colors" title="Opciones">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
        </button>
    </div>
    <div class="p-6 flex-1 overflow-auto">
        <?= $content ?? '' ?>
    </div>
</div>
