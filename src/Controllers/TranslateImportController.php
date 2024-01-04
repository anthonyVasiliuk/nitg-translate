<?php

namespace NITG\NitgTranslate\Controllers;

use Illuminate\Http\Request;
use Matecat\XliffParser\XliffParser;
use NITG\NitgTranslate\Models\Translate;

class TranslateImportController
{
    public function import(Request $request): \Illuminate\Http\JsonResponse
    {
        $parser = new XliffParser();
        try {
            $parsed = $parser->xliffToArray($request->file('file')->getContent());
            $parsed = array_shift($parsed['files']);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }

        foreach ($parsed['trans-units'] as $row) {
            [$model, $id] = explode('-', $row['attr']['id']);
            if($row['target']['attr']['state'] == 'translated' && filled($row['target']['raw-content'])) {
                Translate::query()->updateOrCreate([
                    'lang' => $parsed['attr']['target-language'],
                    'value' => $row['target']['raw-content'],
                    'translatable_id' => $id,
                    'translatable_type' => $model,
                ],[
                    'lang' => $parsed['attr']['target-language'],
                    'value' => $row['target']['raw-content'],
                    'translatable_id' => $id,
                    'translatable_type' => $model,
                ]);
            }
        }

        return response()->json(['message' => 'import done']);
    }
}
