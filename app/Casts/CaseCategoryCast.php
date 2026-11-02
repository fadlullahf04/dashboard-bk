<?php

namespace App\Casts;

use App\Enums\CaseCategory;
use App\Models\CaseCategoryItem;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<CaseCategoryItem, CaseCategoryItem|CaseCategory|string>
 */
class CaseCategoryCast implements CastsAttributes
{
    /**
     * Cast the given value into a CaseCategoryItem object with ->value, ->badgeClasses(), ->barColorClass().
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): CaseCategoryItem
    {
        $name = $value instanceof CaseCategory ? $value->value : (string) $value;

        return CaseCategoryItem::resolveByName($name);
    }

    /**
     * Prepare the given value for storage as a string.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if ($value instanceof CaseCategory) {
            return $value->value;
        }

        if ($value instanceof CaseCategoryItem) {
            return $value->name;
        }

        return (string) $value;
    }
}

