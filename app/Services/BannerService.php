<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Banner;

class BannerService
{
    private Banner $bannerModel;
    private string $uploadDir;

    public function __construct()
    {
        $this->bannerModel = new Banner();
        $this->uploadDir = dirname(__DIR__, 2) . '/public/uploads/banners/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function getAllBanners(): array
    {
        return $this->bannerModel->getAllBanners();
    }

    public function getActiveBanners(): array
    {
        return $this->bannerModel->getActiveBanners();
    }

    public function getBanner(int $id): ?array
    {
        $banner = $this->bannerModel->find($id);
        return $banner ?: null;
    }

    public function createBanner(array $data, array $file): string|int|bool
    {
        if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
            $data['image_path'] = $this->uploadImage($file);
        } else {
            throw new \Exception("La imagen del banner es obligatoria.");
        }

        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1' ? 1 : 0;
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        return $this->bannerModel->create($data);
    }

    public function updateBanner(int $id, array $data, array $file = []): bool
    {
        if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
            $old = $this->getBanner($id);
            $data['image_path'] = $this->uploadImage($file);
            
            if ($old && !empty($old['image_path'])) {
                $oldPath = dirname(__DIR__, 2) . '/public' . $old['image_path'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == '1' ? 1 : 0;
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        return $this->bannerModel->update($id, $data);
    }

    public function deleteBanner(int $id): bool
    {
        $old = $this->getBanner($id);
        if ($old && !empty($old['image_path'])) {
            $oldPath = dirname(__DIR__, 2) . '/public' . $old['image_path'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        return $this->bannerModel->delete($id);
    }

    private function uploadImage(array $file): string
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        
        // Clean original name for SEO (only alphanumeric and dashes)
        $safeName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $originalName), '-'));
        if (empty($safeName)) {
            $safeName = 'banner';
        }
        
        // Append a short hash to prevent collisions
        $filename = $safeName . '-' . substr(uniqid(), -5) . '.' . $extension;
        
        $destination = $this->uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return '/uploads/banners/' . $filename;
        }
        
        throw new \Exception("Error al subir la imagen.");
    }
}
