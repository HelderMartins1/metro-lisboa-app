<?php

Route::get('/{any}', function () {
    return view('main');
})->where('any', '^(?!api).*$');
