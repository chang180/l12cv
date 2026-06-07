<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Resume Download Features
    |--------------------------------------------------------------------------
    |
    | Controls visibility of PDF, DOCX, and batch export download buttons on
    | the public resume page. Routes remain registered for future re-enable.
    |
    */

    'downloads_enabled' => env('RESUME_DOWNLOADS_ENABLED', false),

];
