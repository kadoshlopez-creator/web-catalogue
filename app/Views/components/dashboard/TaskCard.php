<?php
/**
 * TaskCard Component
 */
?>
<div class="space-y-3" x-data="{ tasks: [] }" x-init="fetch('/admin/dashboard/tasks').then(r => r.json()).then(d => tasks = d)">
    <template x-if="tasks.length === 0">
        <div class="text-center py-6 text-gray-500 text-sm">
            No hay tareas pendientes en este momento.
        </div>
    </template>
    <template x-for="task in tasks" :key="task.id">
        <div class="p-4 bg-white border border-gray-100 rounded-lg shadow-sm flex justify-between items-center hover:border-blue-200 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full" :class="{
                    'bg-red-500': task.priority === 'critical',
                    'bg-orange-500': task.priority === 'high',
                    'bg-yellow-400': task.priority === 'medium',
                    'bg-blue-400': task.priority === 'low'
                }"></div>
                <div>
                    <p class="text-sm font-medium text-gray-800" x-text="task.message"></p>
                    <p class="text-xs text-gray-400" x-text="task.type"></p>
                </div>
            </div>
            <button class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors">
                Resolver
            </button>
        </div>
    </template>
</div>
