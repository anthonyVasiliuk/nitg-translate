# nitg-translate
NITG Laravel models translations package

# Installation

``composer config 'repositories.nitg/nitg-translate' git https://github.com/anthonyVasiliuk/nitg-translate``

``composer install nitg/nitg-translate``

# Usage

run migrations ``php artisan migrate``

use ``Translatable`` Trait in desirable model,

add ``$translatable = ['fields to translate', ...];`` in model

# Routes

``GET /tranaslate-export?lang={lang}`` : generates ``xlf`` file
with all fields to translate. File name is ``{APP_NAME}_{lang}.xlf``

parameter {lang} default lang is 'de'

``POST /translate-import`` : request should be form-data 
with parameter ``file`` with translated ``xlf`` file
