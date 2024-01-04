<?php

namespace Nitg\NitgTranslate\Controllers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class TranslateExportController
{
    public function export()
    {
        ini_set('memory_limit', '-1');
        try {
            $file = self::getXmlBody();
            self::getTranslatableModels()?->map(function ($model) use (&$file) {
                $model::query()->with('translate')->get(['id', self::getTranslatableFields($model)])
                    ->map(function($item) use ($model, &$file) {
                        self::setTranslate($item, $model, $file);
                    });
            });
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }

        $response = Response::make($file->asXML(), 200);
        $response->header('Content-Type', 'text/xml');
        $response->header('Content-disposition','attachment; filename="'.self::getFileName().'"');

        return $response;
    }

    public static function getTranslatableModels()
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                return sprintf('%s%s',
                    Container::getInstance()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));
            })->filter(function ($class) {
                $valid = false;
                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract() && in_array('App\Traits\Translatable', $reflection->getTraitNames());
                }
                return $valid;
            });

        return $models->values();
    }

    /**
     * @throws \Exception
     */
    public static function getTranslatableFields($model): string
    {
        if ((new ($model))->translatable) {
            return implode(',', (new ($model))->translatable);
        } else {
            throw new \Exception('You must specify translatable fields in model:'. $model);
        }
    }

    private static function getTranslatableLanguage(): string
    {
        return request()->get('lang', 'de');
    }

    private static function getFileName(): string
    {
        return config('app.name').'_'.self::getTranslatableLanguage().'.xlf';
    }

    private static function getXmlBody()
    {
        $xlif = new \SimpleXMLElement('<xliff/>');
        $xlif->addAttribute('version', "1.2");
        $xlif->addAttribute('xmlns', "urn:oasis:names:tc:xliff:document:1.2");
        $file = $xlif->addChild('file');
        $file->addAttribute('source-language', 'en-US');
        $file->addAttribute('target-language', self::getTranslatableLanguage());
        $file->addAttribute('datatype', 'plaintext');
        $file->addAttribute('original', 'ng2.template');
        $file->addChild('body');

        return $xlif;
    }

    private static function setTranslate($item, $model, $file)
    {
        $body = $file->xpath('/xliff/file/body');
        $unit = $body[0]->addChild('trans-unit');
        $unit->addAttribute('id', $model.'-'.$item->id);
        $unit->addAttribute('datatype', 'html');
        $unit->addChild('source', htmlspecialchars($item->{self::getTranslatableFields($model)}));
        $target = $unit->addChild('target', htmlspecialchars($item->translate?->where('lang', self::getTranslatableLanguage())->first()?->value) ?? null);
        $target->addAttribute('state', $item->translate?->first()?->value ? 'translated' : 'needs-l10n');
    }
}
