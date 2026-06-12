<?php

use App\Http\Controllers\SubscriptionController;

Route::post('/webhook/stripe', [SubscriptionController::class, 'webhook']);
