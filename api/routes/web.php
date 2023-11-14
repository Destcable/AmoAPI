<?php

use Api\App\Controllers\AmoController;
use Api\App\Controllers\AuthController;
use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::post('/v1/amo/auth', [AuthController::class, 'firstAuth']);
SimpleRouter::post('/v1/amo/record', [AmoController::class, 'create']);