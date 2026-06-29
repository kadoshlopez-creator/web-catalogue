<?php
/**
 * ActivityTimeline Component
 */
?>
<div class="space-y-4">
    <!-- Template for skeleton loading -->
    <template x-if="loadingActivity">
        <div class="animate-pulse space-y-4">
            <div class="flex items-center gap-4"><div class="w-3 h-3 bg-gray-300 rounded-full"></div><div class="h-4 bg-gray-300 rounded w-3/4"></div></div>
            <div class="flex items-center gap-4"><div class="w-3 h-3 bg-gray-300 rounded-full"></div><div class="h-4 bg-gray-300 rounded w-1/2"></div></div>
        </div>
    </template>

    <div x-show="!loadingActivity" class="relative border-l border-gray-200 ml-3 space-y-6">
        <!-- Static examples for now, will be populated by JS/Alpine later -->
        <div class="mb-6 ml-6">
            <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
            </span>
            <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-900">Admin actualizó Producto XYZ</h3>
            <time class="block mb-2 text-xs font-normal leading-none text-gray-400">Hace 10 minutos</time>
        </div>
        <div class="mb-6 ml-6">
            <span class="absolute flex items-center justify-center w-6 h-6 bg-green-100 rounded-full -left-3 ring-8 ring-white">
                <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            </span>
            <h3 class="mb-1 text-sm font-semibold text-gray-900">Backup completado exitosamente</h3>
            <time class="block mb-2 text-xs font-normal leading-none text-gray-400">Hace 2 horas</time>
        </div>
    </div>
</div>
