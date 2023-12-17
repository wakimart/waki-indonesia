<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-csrf-token, api-key' );
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

Route::group(['middleware' => 'cors'], function() {
    Route::post('/end-point-for-check-status-network', 'Api\OfflineSideController@networkEndPoint');
    Route::post('/replicate-order-data', 'Api\OfflineSideController@replicateOrderData');
    Route::post('/replicate-order-payment-data', 'Api\OfflineSideController@replicateOrderPaymentData');
    Route::post('/update-order-data', 'Api\OfflineSideController@updateOrderData');
    Route::post('/update-order-detail-data', 'Api\OfflineSideController@updateOrderDetailData');

    Route::post('/replicate-cso-data', 'Api\OfflineSideController@replicateCSOData');
    Route::post('/update-cso-data', 'Api\OfflineSideController@updateCSOData');
    Route::post('/delete-cso-data', 'Api\OfflineSideController@deleteCSOData');
});