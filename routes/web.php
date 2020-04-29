<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index')->name('index');
Route::get('/product_category', 'CategoryProductController@index')->name('product_category');
Route::get('/single_product', 'ProductController@index')->name('single_product');

//DO Register
Route::get('/deliveryorder', 'DeliveryOrderController@index')->name('delivery_order');
Route::post('/deliveryorder', 'DeliveryOrderController@store')->name('store_delivery_order');
Route::get('/register-success', 'DeliveryOrderController@successorder')->name('successorder');
Route::get('/fetchCso', 'DeliveryOrderController@fetchCso')->name('fetchCso');
Route::get('/templistregwaki1995', 'DeliveryOrderController@listDeliveryOrder')->name('listDeliveryOrder');

//Order 
Route::get('/order', 'OrderController@index')->name('add_order');
Route::post('/order', 'OrderController@store')->name('store_order');
Route::get('/order-success', 'OrderController@successorder')->name('order_success');
//Route::get('/templistorderwaki1995', 'OrderController@listOrder')->name('list_order');

Auth::routes(['verify' => true]);
Route::group(['prefix' => 'cms-admin'], function () {
	Route::get('/', function () {
		if(Auth::guard()->check()){
			return redirect()->route('dashboard');
		}
		else {
			return redirect()->route('login');
		}
	});

	//show login form
	Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
	//login cek nya
    Route::post('/login', 'Auth\LoginController@login')->name('admin_login');
    //logout usernya
    Route::get('/logout', 'Auth\LoginController@logoutUser')->name('admin_logout');
    //dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    //frontendcms
    Route::get('/frontend-cms', 'FrontendCmsController@index')->name('frontend_cms');

    Route::group(['prefix' => 'delivery_order', 'middleware' => 'auth'], function(){
    	//Add Form DO
	    Route::get('/', 'DeliveryOrderController@admin_AddDeliveryOrder')
	    	->name('add_deliveryorder');
	    //Create DO
	    Route::post('/', 'DeliveryOrderController@admin_StoreDeliveryOrder')
	    	->name('store_deliveryorder');
	    //List DO
	    Route::get('/list', 'DeliveryOrderController@admin_ListDeliveryOrder')
	    	->name('list_deliveryorder');
	    //Detail DO
	    Route::get('/detail', 'DeliveryOrderController@admin_DetailDeliveryOrder')
	    	->name('detail_deliveryorder');
	    //Edit DO
	    Route::get('/edit/', 'DeliveryOrderController@edit')
	    	->name('edit_deliveryorder');
	    //Update DO
	    Route::post('/update/', 'DeliveryOrderController@update')
	    	->name('update_deliveryorder');
	   	//Delete DO
	    Route::post('/{deliveryOrderNya}', 'DeliveryOrderController@delete')
	    	->name('delete_deliveryorder');
    });

    Route::group(['prefix' => 'order', 'middleware' => 'auth'], function(){
    	//Add Form Order
    	Route::get('/', 'OrderController@admin_AddOrder')
	    	->name('admin_add_order');
	    //Create Order
	    Route::post('/', 'OrderController@admin_StoreOrder')
	    	->name('admin_store_order');
	    //List Order
	    Route::get('/list', 'OrderController@admin_ListOrder')
	    	->name('admin_list_order');
	    //Detail Order
	    Route::get('/detail', 'OrderController@admin_DetailOrder')
	    	->name('detail_order');
	    //Edit Order
	    Route::get('/edit/', 'OrderController@edit')
	    	->name('edit_order');
	    //Update Order
	    Route::post('/update/', 'OrderController@update')
	    	->name('update_order');
	    //Delete Order
	    Route::post('/{OrderNya}', 'OrderController@delete')
	    	->name('delete_order');
    });

    Route::group(['prefix' => 'cso', 'middleware' => 'auth'], function(){
    	//Add Form CSO
    	Route::get('/', 'CsoController@create')
	    	->name('add_cso');
	    //Create CSO
	    Route::post('/', 'CsoController@store')
	    	->name('store_cso');
	    //List CSO
	    Route::get('/list', 'CsoController@index')
	    	->name('list_cso');
	    //Edit CSO
	    Route::get('/edit/', 'CsoController@edit')
	    	->name('edit_cso');
	    //Update CSO
	    Route::post('/update/', 'CsoController@update')
	    	->name('update_cso');
	    //Delete CSO
	    Route::post('/{OrderNya}', 'CsoController@delete')
	    	->name('delete_cso');
    });

    Route::group(['prefix' => 'branch', 'middleware' => 'auth'], function(){
    	//Add Form Branch
    	Route::get('/', 'BranchController@create')
	    	->name('add_branch');
	    //Create Branch
	    Route::post('/', 'BranchController@store')
	    	->name('store_branch');
	    //List Branch
	    Route::get('/list', 'BranchController@index')
	    	->name('list_branch');
	    //Edit Branch
	    Route::get('/edit/', 'BranchController@edit')
	    	->name('edit_branch');
	    //Update Branch
	    Route::post('/update/', 'BranchController@update')
	    	->name('update_branch');
	    //Delete Branch
	    Route::post('/{BranchNya}', 'BranchController@delete')
	    	->name('delete_branch');
    });

    Route::group(['prefix' => 'category_products', 'middleware' => 'auth'], function(){
    	//Add Form CategoryProduct
    	Route::get('/', 'CategoryProductController@create')
	    	->name('add_category');
	    //Create CategoryProduct
	    Route::post('/', 'CategoryProductController@store')
	    	->name('store_category');
	    //List CategoryProduct
	    Route::get('/list', 'CategoryProductController@admin_ListCategoryProduct')
	    	->name('list_category');
	    //Edit CategoryProduct
	    Route::get('/edit/', 'CategoryProductController@edit')
	    	->name('edit_category');
	    //Update CategoryProduct
	    Route::post('/update/', 'CategoryProductController@update')
	    	->name('update_category');
	    //Delete CategoryProduct
	    Route::post('/{CategoryProductNya}', 'CategoryProductController@delete')
	    	->name('delete_category');
    });
    

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
