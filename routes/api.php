<?php
header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type, x-csrf-token, api-key' );

use Illuminate\Http\Request;

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

Route::middleware('cors')->post('/wakimart-member', 'Api\WakimartController@storeMember');
Route::middleware('cors')->post('/update-order-status', 'Api\OnlineSideController@updateOrderStatus');
Route::middleware('cors')->post('/getOrderIsDirectUpgrade', 'Api\OnlineSideController@getOrderIsDirectUpgrade');