<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\DashboardTask;

class TaskService
{
    private DashboardTask $taskModel;

    public function __construct()
    {
        $this->taskModel = new DashboardTask();
    }

    public function getPendingTasks(): array
    {
        return $this->taskModel->where('status', 'pending');
    }

    public function resolveTask(int $taskId): bool
    {
        return $this->taskModel->update($taskId, ['status' => 'resolved']);
    }
}
