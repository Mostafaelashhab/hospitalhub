<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'name_ar',
        'generic_name',
        'manufacturer',
        'drug_class',
        'description',
        'indications',
        'dosage',
        'side_effects',
        'contraindications',
        'interactions',
        'warnings',
        'pregnancy_category',
        'storage_info',
        'is_drug',
        'price',
        'image',
        'category_name',
        'category_name_ar',
        'api_raw_data',
        'api_fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'is_drug' => 'boolean',
            'price' => 'decimal:2',
            'api_raw_data' => 'array',
            'api_fetched_at' => 'datetime',
        ];
    }

    /**
     * Import or update a drug from API response data.
     */
    public static function importFromApi(string $searchName, array $apiData): self
    {
        $name = $apiData['name'] ?? $apiData['brand_name'] ?? $searchName;

        $drug = static::where('name', $name)->first();

        $fields = [
            'name' => $name,
            'generic_name' => static::extractField($apiData, ['generic_name', 'active_ingredient', 'active_ingredients']),
            'manufacturer' => static::extractField($apiData, ['manufacturer', 'company', 'labeler']),
            'drug_class' => static::extractField($apiData, ['drug_class', 'pharmacologic_class', 'pharm_class']),
            'description' => static::extractField($apiData, ['description', 'purpose', 'summary']),
            'indications' => static::extractField($apiData, ['indications', 'indications_and_usage', 'uses']),
            'dosage' => static::extractField($apiData, ['dosage', 'dosage_and_administration', 'dose']),
            'side_effects' => static::extractField($apiData, ['side_effects', 'adverse_reactions', 'adverse_effects']),
            'contraindications' => static::extractField($apiData, ['contraindications', 'do_not_use']),
            'interactions' => static::extractField($apiData, ['interactions', 'drug_interactions']),
            'warnings' => static::extractField($apiData, ['warnings', 'warnings_and_cautions', 'boxed_warning']),
            'pregnancy_category' => static::extractField($apiData, ['pregnancy_category', 'pregnancy']),
            'storage_info' => static::extractField($apiData, ['storage', 'storage_and_handling']),
            'image' => $apiData['image'] ?? null,
            'api_raw_data' => $apiData,
            'api_fetched_at' => now(),
        ];

        // Extract price
        if (!empty($apiData['price'])) {
            $priceStr = is_string($apiData['price']) ? preg_replace('/[^0-9.]/', '', $apiData['price']) : $apiData['price'];
            if (is_numeric($priceStr)) {
                $fields['price'] = (float) $priceStr;
            }
        }

        if ($drug) {
            $drug->update($fields);
        } else {
            $fields['is_drug'] = true;
            $fields['external_id'] = $apiData['id'] ?? $apiData['product_id'] ?? null;
            $fields['category_name'] = $apiData['drug_class'] ?? $apiData['category'] ?? null;
            $drug = static::create($fields);
        }

        return $drug;
    }

    /**
     * Extract a field value from API data, trying multiple possible keys.
     */
    protected static function extractField(array $data, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (!empty($data[$key])) {
                $val = $data[$key];
                if (is_array($val)) {
                    return implode("\n", array_map(fn($v) => is_string($v) ? $v : json_encode($v), $val));
                }
                return (string) $val;
            }
        }
        return null;
    }
}
