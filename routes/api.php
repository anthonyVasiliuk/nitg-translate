<?php

use App\Http\Controllers\Translate\TranslateImportController;
use NITG\NitgTranslate\Controllers\TranslateExportController;
use Illuminate\Support\Facades\Route;

Route::get('/translate-export', [TranslateExportController::class, 'export'])->middleware('auth:api');
Route::post('/translate-import', [TranslateImportController::class, 'import'])->middleware('auth:api');
