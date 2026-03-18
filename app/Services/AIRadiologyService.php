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
     * Analyze an X-ray image via URL (downloads then sends as file).
     */
    public function analyzeFromUrl(string $imageUrl, string $message = '', string $language = 'en'): array
    {
        if (empty($this->apiKey)) {
            return $this->errorResponse('AI Radiology API key is not configured.');
        }

        $message = $this->resolveMessage($message, $language);

        try {
            // Try with imageUrl query param first
            $queryString = http_build_query([
                'imageUrl' => $imageUrl,
                'message' => $message,
                'language' => $language,
                'noqueue' => '1',
            ]);

            $response = Http::withHeaders([
                'x-rapidapi-key' => $this->apiKey,
                'x-rapidapi-host' => $this->apiHost,
            ])->timeout(120)->post($this->baseUrl . '/check?' . $queryString);

            if ($response->successful()) {
                return $this->successResponse($response->json());
            }

            // If 400 (image not found), try downloading and sending as file
            if ($response->status() === 400) {
                $imageContent = @file_get_contents($imageUrl);
                if ($imageContent) {
                    return $this->analyzeFromContent($imageContent, 'xray.jpg', $message, $language);
                }
            }

            return $this->logAndError($response, $imageUrl);
        } catch (\Exception $e) {
            Log::error('AI Radiology API exception', ['message' => $e->getMessage()]);
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Analyze an uploaded file directly (sends as multipart).
     */
    public function analyzeFromFile($file, string $message = '', string $language = 'en'): array
    {
        if (empty($this->apiKey)) {
            return $this->errorResponse('AI Radiology API key is not configured.');
        }

        $message = $this->resolveMessage($message, $language);

        try {
            $imageContent = file_get_contents($file->getRealPath());
            $filename = $file->getClientOriginalName() ?: 'xray.jpg';

            return $this->analyzeFromContent($imageContent, $filename, $message, $language);
        } catch (\Exception $e) {
            Log::error('AI Radiology file exception', ['message' => $e->getMessage()]);
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Analyze from a storage path (existing patient file).
     */
    public function analyzeFromStorage(string $storagePath, string $message = '', string $language = 'en'): array
    {
        if (empty($this->apiKey)) {
            return $this->errorResponse('AI Radiology API key is not configured.');
        }

        $message = $this->resolveMessage($message, $language);

        try {
            $imageContent = Storage::disk('public')->get($storagePath);
            if (!$imageContent) {
                return $this->errorResponse('File not found in storage.');
            }

            $filename = basename($storagePath);
            return $this->analyzeFromContent($imageContent, $filename, $message, $language);
        } catch (\Exception $e) {
            Log::error('AI Radiology storage exception', ['message' => $e->getMessage()]);
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Core method: send image content as multipart file upload.
     */
    protected function analyzeFromContent(string $imageContent, string $filename, string $message, string $language): array
    {
        $queryString = http_build_query([
            'message' => $message,
            'language' => $language,
            'noqueue' => '1',
        ]);

        $response = Http::withHeaders([
            'x-rapidapi-key' => $this->apiKey,
            'x-rapidapi-host' => $this->apiHost,
        ])->timeout(120)
          ->attach('image', $imageContent, $filename)
          ->post($this->baseUrl . '/check?' . $queryString);

        if ($response->successful()) {
            return $this->successResponse($response->json());
        }

        Log::warning('AI Radiology API error (file upload)', [
            'status' => $response->status(),
            'body' => substr($response->body(), 0, 500),
        ]);

        return $this->errorResponse('API returned status ' . $response->status(), $response->status());
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    protected function resolveMessage(string $message, string $language): string
    {
        if (empty($message)) {
            return $language === 'ar'
                ? 'فحص شامل للأشعة وتحديد أي نتائج غير طبيعية'
                : 'Perform a comprehensive analysis and check for any abnormalities';
        }
        return $message;
    }

    protected function successResponse(array $data): array
    {
        return ['success' => true, 'data' => $data, 'status_code' => 200];
    }

    protected function logAndError($response, string $imageUrl): array
    {
        Log::warning('AI Radiology API error', [
            'status' => $response->status(),
            'body' => substr($response->body(), 0, 500),
            'imageUrl' => $imageUrl,
        ]);
        return $this->errorResponse('API returned status ' . $response->status(), $response->status());
    }

    protected function errorResponse(string $message, int $statusCode = 0): array
    {
        return ['success' => false, 'error' => $message, 'status_code' => $statusCode, 'data' => null];
    }
}
