<?php

use Illuminate\Support\Facades\Route;
use Lupennat\BetterLens\Http\Controllers\BetterLensController;

// Lenses...

Route::get('/{resource}/lens/{lens}', [BetterLensController::class, 'show']);
