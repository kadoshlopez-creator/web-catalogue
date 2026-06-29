<?php
/**
 * Simple test runner for SEO Module
 */
require __DIR__ . '/../vendor/autoload.php';

use App\Services\SlugService;
use App\Repositories\CategoryRepository;
use App\Validators\SeoValidator;
use App\DTOs\SeoDTO;
use App\Services\SeoService;
use App\Repositories\CategorySlugHistoryRepository;

class SeoModuleTest
{
    private int $passed = 0;
    private int $failed = 0;

    public function run()
    {
        echo "Iniciando tests del módulo SEO...\n\n";

        $this->testSlugSanitization();
        $this->testValidator();
        $this->testSeoScoreCalculation();

        echo "\nTests finalizados. Pasados: {$this->passed}, Fallidos: {$this->failed}\n";
    }

    private function assertEqual($expected, $actual, string $testName)
    {
        if ($expected === $actual) {
            echo "✅ [PASS] $testName\n";
            $this->passed++;
        } else {
            echo "❌ [FAIL] $testName (Expected: '$expected', Got: '$actual')\n";
            $this->failed++;
        }
    }

    private function assertTrue($condition, string $testName)
    {
        if ($condition) {
            echo "✅ [PASS] $testName\n";
            $this->passed++;
        } else {
            echo "❌ [FAIL] $testName\n";
            $this->failed++;
        }
    }

    private function testSlugSanitization()
    {
        // Mocking repo is hard without PHPUnit, but we only need it for generateUniqueSlug
        // We can test sanitize() directly which is pure.
        
        // Use an anonymous class for mocking
        $mockRepo = new class extends CategoryRepository {
            public function __construct() {} // Override to avoid DB connection
            public function findBySlug(string $slug, ?int $excludeId = null): ?array { return null; }
        };

        $slugService = new SlugService($mockRepo);

        $this->assertEqual('mi-categoria-genial', $slugService->sanitize('Mi Categoría Genial!!!'), 'Sanitizar mayúsculas, tildes y símbolos');
        $this->assertEqual('nino-y-ninas', $slugService->sanitize('Niño y Niñas'), 'Sanitizar eñes');
        $this->assertEqual('hola-mundo', $slugService->sanitize('Hola 🌍 Mundo 😊'), 'Sanitizar emojis');
        $this->assertEqual('espacios-dobles', $slugService->sanitize('espacios   dobles'), 'Sanitizar espacios dobles');
    }

    private function testValidator()
    {
        $validator = new SeoValidator();

        // Valid DTO
        $dto1 = new SeoDTO([
            'slug' => 'valid-slug',
            'meta_title' => 'Título correcto',
            'canonical_url' => 'https://misitio.com/valid'
        ]);
        $this->assertTrue(empty($validator->validate($dto1)), 'DTO válido no debe tener errores');

        // Invalid DTO
        $dto2 = new SeoDTO([
            'meta_title' => str_repeat('A', 61), // Excede 60
            'canonical_url' => 'no-es-una-url'
        ]);
        $errors = $validator->validate($dto2);
        
        $this->assertTrue(isset($errors['meta_title']), 'Valida máximo de caracteres en Title');
        $this->assertTrue(isset($errors['canonical_url']), 'Valida formato de URL en Canonical');
    }

    private function testSeoScoreCalculation()
    {
        $mockRepo = new class extends CategoryRepository { public function __construct() {} };
        $mockHistory = new class extends CategorySlugHistoryRepository { public function __construct() {} };
        $slugService = new SlugService($mockRepo);
        
        $seoService = new SeoService($slugService, $mockRepo, $mockHistory);

        $score = $seoService->calculateSeoScore([
            'meta_title' => 'Título ideal de 50 caracteres para SEO en Google!!', // ~50 chars -> 20 pts
            'meta_description' => str_repeat('A', 130), // ~130 chars -> 20 pts
            'canonical_url' => 'https://ejemplo.com' // -> 10 pts
        ]);

        $this->assertEqual(50, $score, 'Cálculo de puntaje SEO correcto (20+20+10)');
    }
}

(new SeoModuleTest())->run();
