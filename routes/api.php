<?php

use App\Aggregates\TicketAggregate;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/purchase', function (Request $request) {
    $request
        ->validate(
            ['type' => 'required|in:10_entries,season'],
            ['type.in' => 'Must be one of: :values']
        );


    $uuid = Str::uuid()->toString();

    TicketAggregate::retrieve($uuid)
        ->purchaseTicket($request->input('type'))
        ->persist();

    return response(['uuid' => $uuid], 201);
});
