<?php

namespace Nitg\NitgTranslate\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;
use Nitg\NitgTranslate\Models\Translate;

trait StoreTranslate
{

    public function store($entityId, $entityType, $lang, $value): void
    {
        Translate::query()->updateOrCreate([
            'translatable_id'   => $entityId,
            'translatable_type' => Relation::getMorphedModel($entityType),
            'lang'              => $lang,
        ],[
            'translatable_id'   => $entityId,
            'translatable_type' => Relation::getMorphedModel($entityType),
            'lang'              => $lang,
            'value'             => $value,
        ]);
    }
}
