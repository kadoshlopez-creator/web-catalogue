<?php
/**
 * SeoHealthCard Component
 */
?>
<div class="flex flex-col items-center justify-center space-y-4" x-data="{ seoData: {score: 0} }" x-init="fetch('/admin/dashboard/metrics').then(r => r.json()).then(d => seoData = d)">
    
    <!-- Circular Progress indicator -->
    <div class="relative w-32 h-32">
        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
            <circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-gray-100" stroke-width="3"></circle>
            <circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-green-500" stroke-width="3" stroke-dasharray="100" :stroke-dashoffset="100 - seoData.seoScore" stroke-linecap="round"></circle>
        </svg>
        <div class="absolute inset-0 flex items-center justify-center flex-col">
            <span class="text-3xl font-bold text-gray-800" x-text="seoData.seoScore + '%'"></span>
            <span class="text-xs text-gray-500">Salud General</span>
        </div>
    </div>
    
    <div class="w-full mt-4 space-y-2">
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Meta Titles faltantes</span>
            <span class="font-medium text-red-500">3</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Meta Descriptions faltantes</span>
            <span class="font-medium text-orange-500">5</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Imágenes sin Alt</span>
            <span class="font-medium text-yellow-500">12</span>
        </div>
    </div>
</div>
