<?php

namespace Nitg\NitgTranslate\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;

class TranslateCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        if ($model->relationLoaded('translate')) {
            $lang = request()->header('localization');

            $translate = Arr::first(Arr::where($model->getRelation('translate')->toArray(), function($item) use ($lang) {
                return $item['lang'] === $lang;
            }));

            if ($translate) {
                $value = $translate['value'];
            }

            if (config('nitg-translate.hide')) $model->makeHidden('translate');
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        return $value;
    }
}
