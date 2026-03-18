<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AIRadiologyService
{
    protected string $apiKey;
    protected string $apiHost;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.ai_radiology.key', '');
        $this->apiHost = config('services.ai_radiology.host', 'ai-radiology-reporting-x-ray-interpretation-api.p.rapidapi.com');
        $this->baseUrl = 'https://' . $this->apiHost;
    }

    /**
     * Analyze an X-ray image via URL.
     */
    public function analyzeFromUrl(string $imageUrl, string $message = '', string $language = 'en'): array
    {
        if (empty($this->apiKey)) {
            return $this->errorResponse('AI Radiology API key is not configured.');
        }

        if (empty($message)) {
            $message = $language === 'ar'
                ? 'فحص شامل للأشعة وتحديد أي نتائج غير طبيعية'
                : 'Perform a comprehensive analysis and check for any abnormalities';
        }

        try {
            $response = Http::withHeaders([
                'x-rapidapi-key' => $this->apiKey,
                'x-rapidapi-host' => $this->apiHost,
            ])->timeout(60)->get($this->baseUrl . '/check', [
                'imageUrl' => $imageUrl,
                'message' => $message,
                'language' => $language,
                'noqueue' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data,
                    'status_code' => $response->status(),
                ];
            }

            Log::warning('AI Radiology API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $this->errorResponse(
                'API returned status ' . $response->status(),
                $response->status()
            );
        } catch (\Exception $e) {
            Log::error('AI Radiology API exception', [
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Analyze an uploaded image file.
     * Stores temporarily, generates a public URL, then calls the API.
     */
    public function analyzeFromFile($file, string $message = '', string $language = 'en'): array
    {
        $path = $file->store('ai-radiology-temp', 'public');
        $publicUrl = url(Storage::url($path));

        $result = $this->analyzeFromUrl($publicUrl, $message, $language);

        // Keep the file for reference — will be cleaned up by the controller if needed
        $result['temp_file_path'] = $path;

        return $result;
    }

    /**
     * Check if the service is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get remaining API quota info from last response headers (if available).
     */
    public function getQuotaInfo(): array
    {
        return [
            'configured' => $this->isConfigured(),
            'host' => $this->apiHost,
        ];
    }

    protected function errorResponse(string $message, int $statusCode = 0): array
    {
        return [
            'success' => false,
            'error' => $message,
            'status_code' => $statusCode,
            'data' => null,
        ];
    }
}
