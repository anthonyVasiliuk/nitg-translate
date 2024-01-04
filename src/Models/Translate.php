<?php

namespace NITG\NitgTranslate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;


/**
 * Class Translate.
 *
 * @OA\Schema(
 *     title="Translate model",
 *     description="Translate model",
 *     required={"lang","value","translatable_id","translatable_type"},
 *     @OA\Property(property="lang", type="string", description="Translate lang", example="en"),
 *     @OA\Property(property="value", type="string", description="Translate text", example="translate to lang"),
 *     @OA\Property(property="translatable_id", type="integer", description="ID of translatable item", example="1"),
 *     @OA\Property(property="translatable_type", type="string", description="Type of entity", example="faq_page_item"),
 *     @OA\Property(property="created_at", type="datetime", description="created at", readOnly=true),
 *     @OA\Property(property="updated_at", type="datetime", description="updated at", readOnly=true),
 * )
 * @property string $lang
 * @property string $value
 * @property integer $translatable_id
 * @property string $translatable_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Translate extends Model
{
    use HasFactory;

    protected $fillable = [
        'lang',
        'value',
        'translatable_id',
        'translatable_type',
    ];

    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }
}
