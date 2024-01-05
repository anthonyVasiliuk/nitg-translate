<?php

namespace Nitg\NitgTranslate\Traits;


use Illuminate\Database\Eloquent\Builder;
use Nitg\NitgTranslate\Models\Translate;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Translatable
{
    public function translate(): MorphMany
    {
        return $this->morphMany(Translate::class, 'translatable');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('locale', function (Builder $builder) {
            $lang = request()->header('localization');
            if ($lang && $lang !== 'en') {
                $builder->with('translate');
            }
        });
    }
}
