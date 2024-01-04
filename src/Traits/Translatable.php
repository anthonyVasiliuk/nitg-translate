<?php

namespace Nitg\NitgTranslate\Traits;


use Nitg\NitgTranslate\Models\Translate;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Translatable
{
    public function translate(): MorphMany
    {
        return $this->morphMany(Translate::class, 'translatable');
    }
}
