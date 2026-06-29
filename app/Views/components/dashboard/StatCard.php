<?php
/**
 * StatCard Component
 * 
 * @param string $title
 * @param string|int $value
 * @param string $icon (SVG string)
 * @param string $color (Tailwind color class prefix, e.g., 'blue', 'green')
 * @param string $trend (Optional, e.g., '+12%')
 */
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1 relative overflow-hidden group">
    <div class="absolute inset-0 bg-gradient-to-br from-white to-gray-50 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    <div class="p-3 rounded-lg bg-<?= $color ?? 'blue' ?>-50 text-<?= $color ?? 'blue' ?>-600 mr-4 relative z-10">
        <?= $icon ?? '' ?>
    </div>
    <div class="relative z-10 w-full flex justify-between items-center">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1"><?= htmlspecialchars($title ?? '') ?></p>
            <h3 class="text-2xl font-bold text-gray-900"><?= $value ?? '0' ?></h3>
        </div>
        <?php if (!empty($trend)): ?>
            <div class="text-xs font-semibold px-2 py-1 rounded-full <?= strpos($trend, '+') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= htmlspecialchars($trend) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
