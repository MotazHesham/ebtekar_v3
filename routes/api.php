<?php


use App\Http\Controllers\FedexController;
use Illuminate\Support\Facades\Route;

Route::any('fedex-webhook',[FedexController::class,'webhook'])->name('fedex.webhook');