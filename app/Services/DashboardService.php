<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\DashboardPreference;
use App\Models\DashboardMetric;

class DashboardService
{
    private DashboardPreference $preferenceModel;
    private DashboardMetric $metricModel;

    public function __construct()
    {
        $this->preferenceModel = new DashboardPreference();
        $this->metricModel = new DashboardMetric();
    }

    public function getUserPreferences(int $userId): array
    {
        $prefs = $this->preferenceModel->where('user_id', $userId);
        if (empty($prefs)) {
            return [
                'dark_mode' => false,
                'layout_json' => null
            ];
        }
        return $prefs[0];
    }

    public function saveUserPreferences(int $userId, array $data): bool
    {
        $existing = $this->preferenceModel->where('user_id', $userId);
        if (empty($existing)) {
            return $this->preferenceModel->create([
                'user_id' => $userId,
                'dark_mode' => $data['dark_mode'] ?? 0,
                'layout_json' => isset($data['layout_json']) ? json_encode($data['layout_json']) : null
            ]);
        }
        
        return $this->preferenceModel->update($existing[0]['id'], [
            'dark_mode' => $data['dark_mode'] ?? $existing[0]['dark_mode'],
            'layout_json' => isset($data['layout_json']) ? json_encode($data['layout_json']) : $existing[0]['layout_json']
        ]);
    }
}
