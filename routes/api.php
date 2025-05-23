<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigurationController;

Route::match(['get', 'post'], '/download/{filename}', [ConfigurationController::class, 'download'])->name('download');
