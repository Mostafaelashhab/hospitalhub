<?php

namespace App\Console\Commands;

use App\Models\Drug;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportDrugs extends Command
{
    protected $signature = 'drugs:import {--page-size=48} {--skip-images} {--category=1}';
    protected $description = 'Import drugs from PharmacyMarts API';

    private string $apiBase = 'https://api.pharmacymarts.com/v2/drugs';

    public function handle(): int
    {
        $pageSize = (int) $this->option('page-size');
        $skipImages = $this->option('skip-images');
        $category = $this->option('category');

        // Ensure storage directory exists
        if (!$skipImages) {
            Storage::disk('public')->makeDirectory('drugs');
        }

        // First request to get total count
        $this->info('Fetching total drug count...');
        $response = Http::timeout(30)->get($this->apiBase, [
            'page_number' => 0,
            'page_size' => 1,
            'category' => $category,
        ]);

        if (!$response->successful() || !$response->json('success')) {
            $this->error('Failed to connect to API');
            return 1;
        }

        $totalDrugs = $response->json('data.totalDrugs');
        $totalPages = (int) ceil($totalDrugs / $pageSize);

        $this->info("Total drugs: {$totalDrugs} | Pages: {$totalPages} | Page size: {$pageSize}");

        $bar = $this->output->createProgressBar($totalPages);
        $bar->start();

        $imported = 0;
        $skipped = 0;
        $imagesFetched = 0;

        for ($page = 0; $page < $totalPages; $page++) {
            try {
                $response = Http::timeout(30)->retry(3, 1000)->get($this->apiBase, [
                    'page_number' => $page,
                    'page_size' => $pageSize,
                    'category' => $category,
                ]);

                if (!$response->successful()) {
                    $this->warn("\nFailed to fetch page {$page}, skipping...");
                    $bar->advance();
                    continue;
                }

                $drugs = $response->json('data.drugs', []);

                foreach ($drugs as $drug) {
                    $localImage = null;

                    // Download image
                    if (!$skipImages && !empty($drug['image_url'])) {
                        $localImage = $this->downloadImage($drug['image_url'], $drug['id']);
                        if ($localImage) $imagesFetched++;
                    }

                    Drug::updateOrCreate(
                        ['external_id' => $drug['id']],
                        [
                            'name' => trim($drug['name']),
                            'name_ar' => trim($drug['name_ar'] ?? ''),
                            'is_drug' => $drug['is_drug'] ?? true,
                            'price' => $drug['price'] ?? 0,
                            'image' => $localImage,
                            'category_name' => $drug['category']['name'] ?? null,
                            'category_name_ar' => $drug['category']['name_ar'] ?? null,
                        ]
                    );

                    $imported++;
                }
            } catch (\Exception $e) {
                $this->warn("\nError on page {$page}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! Imported: {$imported} | Images: {$imagesFetched}");

        return 0;
    }

    private function downloadImage(string $url, int $drugId): ?string
    {
        try {
            // Check if already downloaded
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = "drugs/{$drugId}.{$extension}";

            if (Storage::disk('public')->exists($filename)) {
                return $filename;
            }

            $response = Http::timeout(15)->get($url);

            if ($response->successful()) {
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
            // Silent fail for images
        }

        return null;
    }
}
