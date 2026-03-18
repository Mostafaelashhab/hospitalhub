<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DrugInfoService
{
    protected string $apiKey;
    protected string $apiHost;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.drug_info.key', '');
        $this->apiHost = config('services.drug_info.host', 'drug-info-and-price-history.p.rapidapi.com');
        $this->baseUrl = 'https://' . $this->apiHost;
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Search for drug info by name. Results cached for 24h.
     */
    public function lookup(string $drugName): array
    {
        if (empty($this->apiKey)) {
            return ['success' => false, 'error' => 'API key not configured', 'data' => null];
        }

        $cacheKey = 'drug_info_' . md5(strtolower(trim($drugName)));

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($drugName) {
            try {
                $response = Http::withHeaders([
                    'x-rapidapi-key' => $this->apiKey,
                    'x-rapidapi-host' => $this->apiHost,
                ])->timeout(30)->get($this->baseUrl . '/1/druginfo', [
                    'drug' => $drugName,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data)) {
                        return ['success' => true, 'data' => $data, 'error' => null];
                    }
                    return ['success' => false, 'error' => 'no_results', 'data' => null];
                }

                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'rate_limit', 'data' => null];
                }

                Log::warning('Drug Info API error', [
                    'status' => $response->status(),
                    'drug' => $drugName,
                    'body' => substr($response->body(), 0, 500),
                ]);

                return ['success' => false, 'error' => 'api_error_' . $response->status(), 'data' => null];
            } catch (\Exception $e) {
                Log::error('Drug Info API exception', ['message' => $e->getMessage(), 'drug' => $drugName]);
                return ['success' => false, 'error' => $e->getMessage(), 'data' => null];
            }
        });
    }

    /**
     * Clear cached result for a specific drug.
     */
    public function clearCache(string $drugName): void
    {
        Cache::forget('drug_info_' . md5(strtolower(trim($drugName))));
    }
}
