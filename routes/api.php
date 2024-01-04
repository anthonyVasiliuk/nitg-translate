<?php

use Nitg\NitgTranslate\Controllers\TranslateExportController;
use Nitg\NitgTranslate\Controllers\TranslateImportController;
use Illuminate\Support\Facades\Route;

Route::get('/translate-export', [TranslateExportController::class, 'export'])->middleware('auth:api');
Route::post('/translate-import', [TranslateImportController::class, 'import'])->middleware('auth:api');
