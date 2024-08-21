<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ImportOpenFood extends Command
{
    protected $signature = 'app:import-openfood';
    protected $description = 'Import products from Open Food Facts';
    protected $importLimit = 100; 

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $indexUrl = 'https://challenges.coode.sh/food/data/json/index.txt';
        $files = explode("\n", Http::get($indexUrl)->body());

        foreach ($files as $file) {
            $url = "https://challenges.coode.sh/food/data/json/{$file}";
            $this->info("Importing file: {$url}");
            $this->processFileInChunks($url);
        }

        Cache::put('last_cron_run', Carbon::now()->format('Y-m-d H:i:s'));

        $this->info('Import completed');
    }

    protected function processFileInChunks($url)
    {
        $tempFile = $this->downloadFile($url);
        $jsonStream = gzopen($tempFile, 'rb');

        if ($jsonStream === false) {
            return;
        }

        $bufferSize = 8192; 
        $buffer = '';
        $importedCount = 0;

        while (!gzeof($jsonStream) && $importedCount < $this->importLimit) {
            $buffer .= gzread($jsonStream, $bufferSize);
            $importedCount += $this->processBuffer($buffer, $importedCount);
        }

        gzclose($jsonStream);
        unlink($tempFile); 
    }

    protected function downloadFile($url)
    {
        $response = Http::get($url);
        $gzData = $response->body();
        $tempFile = tempnam(sys_get_temp_dir(), 'openfood');
        file_put_contents($tempFile, $gzData);

        return $tempFile;
    }

    protected function processBuffer(&$buffer, $importedCount)
    {
        $lines = explode("\n", $buffer);
        $processedCount = 0;

        foreach ($lines as $line) {
            if (empty(trim($line)) || !$this->isJson($line)) {
                continue;
            }

            $productData = json_decode($line, true);

            if ($this->importProducts($productData)) {
                $processedCount++;
                if ($processedCount >= $this->importLimit - $importedCount) {
                    break;
                }
            }
        }

        $buffer = '';

        return $processedCount;
    }

    protected function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    protected function importProducts(array $productData)
    {
        if (empty($productData['code'])) {
            return false;
        }

        $productData = array_merge($productData, [
            'created_datetime' => $this->formatDate($productData['created_datetime'] ?? null),
            'last_modified_datetime' => $this->formatDate($productData['last_modified_datetime'] ?? null),
            'created_t' => $this->formatTimestamp($productData['created_t'] ?? null),
            'last_modified_t' => $this->formatTimestamp($productData['last_modified_t'] ?? null),
            'serving_quantity' => $this->formatFloat($productData['serving_quantity'] ?? null),
            'energy_kcal_100g' => $this->formatFloat($productData['energy_kcal_100g'] ?? null),
            'fat_100g' => $this->formatFloat($productData['fat_100g'] ?? null),
            'nutriscore_score' => $this->formatInt($productData['nutriscore_score'] ?? null),
            'nutriscore_grade' => $this->formatString($productData['nutriscore_grade'] ?? null),
            'categories' => $this->formatString($productData['categories'] ?? null),
            'ingredients_text' => $this->formatString($productData['ingredients_text'] ?? null),
            'image_url' => $this->formatString($productData['image_url'] ?? null),
            'status' => 'published',
            'imported_t' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Product::updateOrCreate(
            ['code' => $productData['code']],
            $productData
        );

        return true;
    }

    protected function formatDate($date)
    {
        return $this->formatWithExceptionHandling($date, fn($date) => Carbon::parse($date)->format('Y-m-d H:i:s'));
    }

    protected function formatTimestamp($timestamp)
    {
        return $this->formatWithExceptionHandling($timestamp, fn($timestamp) => Carbon::createFromTimestamp($timestamp)->format('Y-m-d H:i:s'));
    }

    protected function formatFloat($value)
    {
        return is_numeric($value) ? (float) $value : null;
    }

    protected function formatInt($value)
    {
        return is_numeric($value) ? (int) $value : null;
    }

    protected function formatString($value)
    {
        return is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : null;
    }

    private function formatWithExceptionHandling($value, $callback)
    {
        try {
            return $value ? $callback($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
