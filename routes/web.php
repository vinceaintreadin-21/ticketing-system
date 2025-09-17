<?php

use Illuminate\Support\Facades\Route;

Rout::get('/{any}', function() {
    return file_get_contents(public_path('index.html'));
});
