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

Auth::routes(['verify' => true]);

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

//Home service
Route::get('/homeservice', 'HomeServiceController@index')->name('add_homeServices');
Route::post('/homeservice', 'HomeServiceController@store')->name('store_home_service');
Route::get('/homeservice-success', 'HomeServiceController@successRegister')->name('homeServices_success');

//fetching cso and branch by id
Route::get('/fetchCsoById', 'CsoController@fetchCsoById')->name('fetchCsoById');
Route::get('/fetchBranchById', 'BranchController@fetchBranchById')->name('fetchBranchById');


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
    Route::get('/dashboard', 'DashboardController@index')
    	->name('dashboard');
    //frontendcms
    Route::get('/frontend-cms', 'FrontendCmsController@index')
    	->name('index_frontendcms')
    	->middleware('can:browse-frontendcms');
    //add frontendcms
    Route::post('/frontend-cms', 'FrontendCmsController@store')
	    	->name('store_frontendcms');
    //update frontendcms
    Route::post('/frontend-cms/update', 'FrontendCmsController@update')
	    	->name('update_frontendcms');
	    	
	//change password admin    
	Route::post('/changePassword','UserAdminController@changePassword')
			->name('changePassword');
    //Check change password admin    
    Route::post('/checkChangePassword', 'UserAdminController@checkChangePassword')
    		->name('check-change-password');

	Route::group(['prefix' => 'useradmin', 'middleware' => 'auth'], function(){
		//Add Form UserAdmin
		Route::get('/', 'UserAdminController@create')
	    	->name('add_useradmin')
	    	->middleware('can:add-user');
	    //Add Form UserAdmin
		Route::post('/', 'UserAdminController@store')
	    	->name('store_useradmin')
	    	->middleware('can:add-user');
	    //List UserAdmin
	    Route::get('/list', 'UserAdminController@index')
	    	->name('list_useradmin')
	    	->middleware('can:browse-user');
	    //Edit UserAdmin
	    Route::get('/edit', 'UserAdminController@edit')
	    	->name('edit_useradmin')
	    	->middleware('can:edit-user');
	    //Update UserAdmin
	    Route::post('/update', 'UserAdminController@update')
	    	->name('update_useradmin')
	    	->middleware('can:edit-user');
	    //Delete UserAdmin
	    Route::post('/{userAdminNya}', 'UserAdminController@destroy')
	    	->name('delete_useradmin');
	    //get user image
        Route::get('file/{file}', 'UserAdminController@serveImages')
                ->name('avatar_useradmin');
	});

    Route::group(['prefix' => 'delivery_order', 'middleware' => 'auth'], function(){
    	//Add Form DO
	    Route::get('/', 'DeliveryOrderController@admin_AddDeliveryOrder')
	    	->name('add_deliveryorder')
	    	->middleware('can:add-deliveryorder');
	    //Create DO
	    Route::post('/', 'DeliveryOrderController@admin_StoreDeliveryOrder')
	    	->name('store_deliveryorder')
	    	->middleware('can:add-deliveryorder');
	    //List DO
	    Route::get('/list', 'DeliveryOrderController@admin_ListDeliveryOrder')
	    	->name('list_deliveryorder')
	    	->middleware('can:browse-deliveryorder');
	    //Detail DO
	    Route::get('/detail', 'DeliveryOrderController@admin_DetailDeliveryOrder')
	    	->name('detail_deliveryorder')
	    	->middleware('can:detail-deliveryorder');
	    //Edit DO
	    Route::get('/edit/', 'DeliveryOrderController@edit')
	    	->name('edit_deliveryorder')
	    	->middleware('can:edit-deliveryorder');
	    //Update DO
	    Route::post('/update/', 'DeliveryOrderController@update')
	    	->name('update_deliveryorder')
	    	->middleware('can:edit-deliveryorder');
	   	//Delete DO
	    Route::post('/{deliveryOrderNya}', 'DeliveryOrderController@delete')
	    	->name('delete_deliveryorder');
    });

    Route::group(['prefix' => 'order', 'middleware' => 'auth'], function(){
    	//Add Form Order
    	Route::get('/', 'OrderController@admin_AddOrder')
	    	->name('admin_add_order')
	    	->middleware('can:add-order');
	    //Create Order
	    Route::post('/', 'OrderController@admin_StoreOrder')
	    	->name('admin_store_order')
	    	->middleware('can:add-order');
	    //List Order
	    Route::get('/list', 'OrderController@admin_ListOrder')
	    	->name('admin_list_order')
	    	->middleware('can:browse-order');
	    //Detail Order
	    Route::get('/detail', 'OrderController@admin_DetailOrder')
	    	->name('detail_order')
	    	->middleware('can:detail-order');
	    //Edit Order
	    Route::get('/edit/', 'OrderController@edit')
	    	->name('edit_order')
	    	->middleware('can:edit-order');
	    //Update Order
	    Route::post('/update/', 'OrderController@update')
	    	->name('update_order')
	    	->middleware('can:edit-order');
	    //Delete Order
	    Route::post('/{OrderNya}', 'OrderController@delete')
	    	->name('delete_order');
    });

    Route::group(['prefix' => 'homeservice', 'middleware' => 'auth'], function(){
	    //List Home Service
	    Route::get('/list', 'HomeServiceController@admin_ListHomeService')
	    	->name('admin_list_homeService')
	    	->middleware('can:browse-home_service');
	    //Edit
        Route::get('/edit/', 'HomeServiceController@edit')
                ->name('edit_homeService')
		    	->middleware('can:edit-home_service');
	    //View
        Route::get('/detail/', 'HomeServiceController@edit')
                ->name('detail_homeService')
		    	->middleware('can:detail-home_service');
	    //Update
        Route::post('/update/', 'HomeServiceController@update')
                ->name('update_homeService')
		    	->middleware('can:edit-home_service');
    });

    Route::group(['prefix' => 'cso', 'middleware' => 'auth'], function(){
    	//Add Form CSO
    	Route::get('/', 'CsoController@create')
	    	->name('add_cso')
	    	->middleware('can:add-cso');
	    //Create CSO
	    Route::post('/', 'CsoController@store')
	    	->name('store_cso')
	    	->middleware('can:add-cso');
	    //List CSO
	    Route::get('/list', 'CsoController@index')
	    	->name('list_cso')
	    	->middleware('can:browse-cso');
	    //Edit CSO
	    Route::get('/edit/', 'CsoController@edit')
	    	->name('edit_cso')
	    	->middleware('can:edit-cso');
	    //Update CSO
	    Route::post('/update/', 'CsoController@update')
	    	->name('update_cso')
	    	->middleware('can:edit-cso');
	    //Delete CSO
	    Route::post('/{OrderNya}', 'CsoController@delete')
	    	->name('delete_cso');
    });

    Route::group(['prefix' => 'branch', 'middleware' => 'auth'], function(){
    	//Add Form Branch
    	Route::get('/', 'BranchController@create')
	    	->name('add_branch')
	    	->middleware('can:add-branch');
	    //Create Branch
	    Route::post('/', 'BranchController@store')
	    	->name('store_branch')
	    	->middleware('can:add-branch');
	    //List Branch
	    Route::get('/list', 'BranchController@index')
	    	->name('list_branch')
	    	->middleware('can:browse-branch');
	    //Edit Branch
	    Route::get('/edit/', 'BranchController@edit')
	    	->name('edit_branch')
	    	->middleware('can:edit-branch');
	    //Update Branch
	    Route::post('/update/', 'BranchController@update')
	    	->name('update_branch')
	    	->middleware('can:edit-branch');
	    //Delete Branch
	    Route::post('/{BranchNya}', 'BranchController@delete')
	    	->name('delete_branch');
    });

    Route::group(['prefix' => 'category_products', 'middleware' => 'auth'], function(){
    	//Add Form CategoryProduct
    	Route::get('/', 'CategoryProductController@create')
	    	->name('add_category')
	    	->middleware('can:add-category');
	    //Create CategoryProduct
	    Route::post('/', 'CategoryProductController@store')
	    	->name('store_category')
	    	->middleware('can:add-category');
	    //List CategoryProduct
	    Route::get('/list', 'CategoryProductController@admin_ListCategoryProduct')
	    	->name('list_category')
	    	->middleware('can:browse-category');
	    //Edit CategoryProduct
	    Route::get('/edit/', 'CategoryProductController@edit')
	    	->name('edit_category')
	    	->middleware('can:edit-category');
	    //Update CategoryProduct
	    Route::post('/update/', 'CategoryProductController@update')
	    	->name('update_category')
	    	->middleware('can:edit-category');
	    //Delete CategoryProduct
	    Route::post('/{CategoryProductNya}', 'CategoryProductController@delete')
	    	->name('delete_category');
    });

    Route::group(['prefix' => 'product', 'middleware' => 'auth'], function(){
    	//Add Form Product
    	Route::get('/', 'ProductController@create')
	    	->name('add_product')
	    	->middleware('can:add-product');
	    //Create Product
	    Route::post('/', 'ProductController@store')
	    	->name('store_product')
	    	->middleware('can:add-product');
	    //List Product
	    Route::get('/list', 'ProductController@admin_ListProduct')
	    	->name('list_product')
	    	->middleware('can:browse-product');
	    //Edit Product
	    Route::get('/edit/', 'ProductController@edit')
	    	->name('edit_product')
	    	->middleware('can:edit-product');
	    //Update Product
	    Route::post('/update/', 'ProductController@update')
	    	->name('update_product')
	    	->middleware('can:edit-product');
	    //Delete Product
	    Route::post('/{ProductNya}', 'ProductController@delete')
	    	->name('delete_product');
    });

    Route::group(['prefix' => 'promo', 'middleware' => 'auth'], function(){
    	//Add Form Promo
    	Route::get('/', 'PromoController@create')
	    	->name('add_promo')
	    	->middleware('can:add-promo');
	    //Create Promo
	    Route::post('/', 'PromoController@store')
	    	->name('store_promo')
	    	->middleware('can:add-promo');
	    //List Promo
	    Route::get('/list', 'PromoController@index')
	    	->name('list_promo')
	    	->middleware('can:browse-promo');
	    //Edit Promo
	    Route::get('/edit/', 'PromoController@edit')
	    	->name('edit_promo')
	    	->middleware('can:edit-promo');
	    //Update Promo
	    Route::post('/update/', 'PromoController@update')
	    	->name('update_promo')
	    	->middleware('can:edit-promo');
	    //Delete Promo
	    Route::post('/{PromoNya}', 'PromoController@delete')
	    	->name('delete_promo');
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
