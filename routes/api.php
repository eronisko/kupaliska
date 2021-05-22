<?php

use App\Aggregates\TicketAggregate;
use App\Exceptions\TicketDenied;
use App\Http\Requests\TicketScanRequest;
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
            ['type' => 'required|in:1_entry,10_entries,season'],
            ['type.in' => 'Must be one of: :values']
        );


    $uuid = Str::uuid()->toString();

    TicketAggregate::retrieve($uuid)
        ->purchaseTicket($request->input('type'))
        ->persist();

    return response(['ticket_uuid' => $uuid], 201);
});

Route::post('/enter', function (TicketScanRequest $request) {
    try {
        TicketAggregate::retrieve($request->input('ticket_uuid'))
            ->enter($request->input('pool'))
            ->persist();

        return response(['admit' => true]);
    } catch (TicketDenied $e) {
        return response([
            'admit' => false,
            'message' => $e->getMessage(),
        ]);
    }

});

Route::post('/exit', function (TicketScanRequest $request) {
    TicketAggregate::retrieve($request->input('ticket_uuid'))
        ->exit($request->input('pool'))
        ->persist();

    return response(['admit' => true]);
});
