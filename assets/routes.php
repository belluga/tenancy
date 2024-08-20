<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/tenancy/assets/{path?}', 'Belluga\Tenancy\Controllers\TenantAssetsController@asset')
    ->where('path', '(.*)')
    ->name('belluga.tenancy.asset');
