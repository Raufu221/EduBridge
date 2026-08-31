<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Platform Pricing Boundaries
    |--------------------------------------------------------------------------
    |
    | These values define the minimum and maximum price an instructor can set
    | for a course. Used in both backend validation and frontend help text.
    |
    */
    'min_course_price' => env('MIN_COURSE_PRICE', 500),
    'max_course_price' => env('MAX_COURSE_PRICE', 15000),
];
