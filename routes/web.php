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
Route::resource('gcalendar', 'gCalendarController');
Route::get('oauth', ['as' => 'oauthCallback', 'uses' => 'gCalendarController@oauth'])->name('oauthCallback');
Route::get('/term_cond', 'IndexController@termNCondition')->name('term_cond');

Route::get('/', 'IndexController@index')->name('index');
Route::get('/product_category/{id}', 'CategoryProductController@index')->name('product_category');
Route::get('/single_product/{id}', 'ProductController@index')->name('single_product');
Route::get('/firebase','FirebaseController@index');
//DO Register
Route::get('/deliveryorder', 'DeliveryOrderController@index')->name('delivery_order');
Route::post('/deliveryorder', 'DeliveryOrderController@store')->name('store_delivery_order');
Route::get('/register-success', 'DeliveryOrderController@successorder')->name('successorder');
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

//WAKi Di Rumah Aja
Route::get('/wakidirumahaja', 'RegistrationPromotionController@index')->name('landing_waki');
Route::post('/wakidirumahaja', 'RegistrationPromotionController@store')->name('store_registrationPromotion');

//fetching data - data
Route::get('/fetchCso', 'CsoController@fetchCso')->name('fetchCso');
Route::get('/fetchCsoById', 'CsoController@fetchCsoById')->name('fetchCsoById');
Route::get('/fetchCsoByIdBranch/{branch}', 'CsoController@fetchCsoByIdBranch')->name('fetchCsoByIdBranch');
Route::get('/fetchBranchById', 'BranchController@fetchBranchById')->name('fetchBranchById');
Route::get('/fetchCity/{province}', function ($province) {
		return RajaOngkir::FetchCity($province);
	})->name('fetchCity');

Route::get('/fetchDistrict/{city}', function ($city) {
	$kotaOrKab = array("Kota ", "Kabupaten ");
	$city = str_replace($kotaOrKab, '', $city);
		return RajaOngkir::FetchDistrict($city);
	})->name('fetchDistrict');


//KHUSUS WEB SERVICE APPS (for non CSRF)
Route::group(['prefix' => 'api-apps'], function () {
    Route::post('login','Auth\LoginController@loginApi'); //login
	Route::post('loginqr','Auth\LoginController@loginQRApi'); //login QR
	Route::post('logout','Auth\LoginController@logoutApi'); //logout
    Route::get('fetchbranch', 'BranchController@fetchBranchApi'); //fetching all active branch
    Route::get('fetchcso/{branchId}', 'CsoController@fetchCsoApi'); //fetching all active Cso by branch
    Route::get('fetchPromosApi', 'DeliveryOrderController@fetchPromosApi'); //fetching all promo
	Route::get('fetchBanksApi', 'OrderController@fetchBanksApi'); //fetching all banks
	Route::post('fetchCSOFIlter', 'HomeServiceController@fetchCSOFIlter');
	Route::post('addVersion', 'VersionController@storeVersion');
	Route::get('listVersion', 'VersionController@listVersion');
	Route::get('/fetchAllTypeHS', 'HomeServiceController@listAllTypeHS');
	Route::get('fetchKnowFromApi', 'OrderController@fetchKnowFromApi'); //fetching all know from
    Route::get('fetchprovinceapi', function () {
			return RajaOngkir::FetchProvinceApi();
		}); //fetching all province
    Route::get('fetchcityapi/{province}',function ($province) {
			return RajaOngkir::FetchCityApi($province);
		}); //fetching all city from province
	Route::get('fetchallcityapi/{province}',function ($province) {
		return RajaOngkir::FetchAllCityApi($province);
	});
	Route::get('fetchdistrictapi/{city}',function ($city) {
		return RajaOngkir::FetchAllDistrictAPI($city);
		}); //fetching all district from province
	Route::group(['prefix' => 'homeservice'], function () {
	    Route::post('add','HomeServiceController@addApi'); //add home service
		Route::post('update','HomeServiceController@updateApi'); //update home service
		Route::post('reportHomeService','HomeServiceController@reportHomeService'); //reportHomeService home service
	    Route::post('delete','HomeServiceController@deleteApi'); //delete home service
	    Route::post('list','HomeServiceController@listApi'); //list home service
		Route::get('view/{id}','HomeServiceController@viewApi'); //view home service
		Route::get('reportHomeService/{id}', 'HomeServiceController@singleReportHomeService'); //get reportHomeService home service
	});

	Route::group(['prefix' => 'register'], function () {
	    Route::post('add','DeliveryOrderController@addApi'); //add register DO
	    Route::post('list','DeliveryOrderController@listApi'); //list register DO
		Route::post('update','DeliveryOrderController@updateApi'); //update register DO
		Route::get('view/{id}','DeliveryOrderController@viewApi'); //view single register DO
		Route::post('delete','DeliveryOrderController@deleteApi'); //delete register DO
	});

	Route::group(['prefix' => 'order'], function () {
	    Route::post('add','OrderController@addApi'); //add order
		Route::post('list','OrderController@listApi'); //list order
		Route::post('update','OrderController@updateApi'); //update order
		Route::get('view/{id}','OrderController@viewApi'); //view single order
		Route::post('delete','OrderController@deleteApi'); //delete order
	});

	Route::group(['prefix' => 'acceptance'], function () {
	    Route::post('add','AcceptanceController@addApi'); //add acceptance
		Route::post('list','AcceptanceController@listApi'); //list acceptance
		Route::post('update','AcceptanceController@updateApi'); //update acceptance
		Route::get('view/{id}','AcceptanceController@viewApi'); //view single acceptance
		Route::post('delete','AcceptanceController@deleteApi'); //delete acceptance
	});
});

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


	    //WAKi Di Rumah Aja
	    Route::get('/list_regispromo', 'RegistrationPromotionController@admin_ListRegistrationPromo')
	    	->name('list_regispromo')
	    	->middleware('can:browse-deliveryorder');
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
		//Export to XLS By Date
        Route::get('/report-to-xls-by-date', 'OrderController@export_to_xls')
                ->name('order_export-to-xls');
    });

    Route::group(['prefix' => 'homeservice', 'middleware' => 'auth'], function(){
		//Add Form home service
    	Route::get('/', 'HomeServiceController@indexAdmin')
	    	->name('admin_add_homeService')
	    	->middleware('can:add-home_service');
		//Add Home Service
	    Route::post('/', 'HomeServiceController@admin_addHomeService')
	    	->name('admin_store_homeService')
	    	->middleware('can:add-home_service');
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
        //Export to XLS
        Route::get('/export-to-xls', 'HomeServiceController@export_to_xls')
				->name('homeservice_export-to-xls');
		//Export to XLS By Date
        Route::get('/export-to-xls-by-date', 'HomeServiceController@export_to_xls_byDate')
                ->name('homeservice_export-to-xls-by-date');
    });

    Route::group(['prefix' => 'service','middleware' => 'auth'], function(){
    	//Add Form Service
    	Route::get('/', 'ServiceController@create')
	    	->name('add_service')
	    	->middleware('can:add-order');
	    //Store Service
	    Route::post('/', 'ServiceController@store')
	    	->name('store_service')
	    	->middleware('can:add-order');
    });

    Route::group(['prefix' => 'sparepart','middleware' => 'auth'], function(){
    	//Add Form Service
    	Route::get('/', 'SparepartController@create')
	    	->name('add_sparepart')
	    	->middleware('can:add-order');
	    //Store Service
	    Route::post('/', 'SparepartController@store')
	    	->name('store_sparepart')
	    	->middleware('can:add-order');
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
	    Route::get('/list', 'CsoController@admin_ListCso')
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


	Route::group(['prefix' => 'appVersion', 'middleware' => 'auth'], function(){
    	//Add Form App Version
    	Route::get('/', 'VersionController@create')
	    	->name('add_appVersion')
	    	->middleware('can:add-app');
	    //Create App Version
	    Route::post('/', 'VersionController@store')
	    	->name('store_appVersion')
	    	->middleware('can:add-app');
	    //List App Version
	    Route::get('/list', 'VersionController@index')
	    	->name('list_appVersion')
	    	->middleware('can:browse-app');
	    //Edit App Version
	    Route::get('/edit/', 'VersionController@edit')
	    	->name('edit_app')
	    	->middleware('can:edit-app');
	    //Update Branch
	    Route::post('/update/', 'VersionController@update')
	    	->name('update_app')
	    	->middleware('can:edit-app');
	    //Delete Branch
	    Route::post('/{AppNya}', 'VersionController@delete')
	    	->name('delete_app');
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
