<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugInteraction extends Model
{
    protected $fillable = [
        'drug_a', 'drug_b', 'severity',
        'description_en', 'description_ar',
        'recommendation_en', 'recommendation_ar',
    ];

    public static function check(array $drugNames): array
    {
        $interactions = [];
        $normalized = array_map('strtolower', $drugNames);

        for ($i = 0; $i < count($normalized); $i++) {
            for ($j = $i + 1; $j < count($normalized); $j++) {
                $found = static::where(function ($q) use ($normalized, $i, $j) {
                    $q->where(function ($q2) use ($normalized, $i, $j) {
                        $q2->whereRaw('LOWER(drug_a) = ?', [$normalized[$i]])
                            ->whereRaw('LOWER(drug_b) = ?', [$normalized[$j]]);
                    })->orWhere(function ($q2) use ($normalized, $i, $j) {
                        $q2->whereRaw('LOWER(drug_a) = ?', [$normalized[$j]])
                            ->whereRaw('LOWER(drug_b) = ?', [$normalized[$i]]);
                    });
                })->first();

                if ($found) {
                    $interactions[] = $found;
                }
            }
        }

        return $interactions;
    }
}
