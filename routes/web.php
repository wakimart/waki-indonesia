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
use App\RajaOngkir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get("/accNotif", "ReferenceController@accNotif")
    ->name("acc_notif_reference");

Auth::routes(['verify' => true]);
Route::resource('gcalendar', 'gCalendarController');
Route::get('oauth', ['as' => 'oauthCallback', 'uses' => 'gCalendarController@oauth'])->name('oauthCallback');
Route::get('/term_cond', 'IndexController@termNCondition')->name('term_cond');

Route::get('/', 'IndexController@index')->name('index');
Route::get('/product_category/{id}', 'CategoryProductController@index')->name('product_category');
Route::get('/single_product/{id}', 'ProductController@index')->name('single_product');
Route::get('/firebase','FirebaseController@index');

// Send Contact Us Form
Route::post('/sendcontactform', 'IndexController@sendContactForm')->name('send_contactForm');

//Personal Homecare
Route::get('/personal-homecare/{id}', 'PersonalHomecareController@phForm')->name('personal_homecare');
Route::get('/thankyou-personal-homecare/{id}', 'PersonalHomecareController@thankyouForm')->name('thankyou_ph');

//Service Product
Route::get('/service', 'ServiceController@indexUser')->name('service');
Route::get('/trackservice/{id}', 'ServiceController@trackService')->name('track_service');

//DO Register
Route::get('/deliveryorder', 'DeliveryOrderController@index')->name('delivery_order');
Route::post('/deliveryorder', 'DeliveryOrderController@store')->name('store_delivery_order');
Route::get('/register-success', 'DeliveryOrderController@successorder')->name('successorder');
Route::get('/templistregwaki1995', 'DeliveryOrderController@listDeliveryOrder')->name('listDeliveryOrder');

//Order
// Route::get('/order', 'OrderController@index')->name('add_order');
// Route::post('/order', 'OrderController@store')->name('store_order');
Route::get('/order-success', 'OrderController@successorder')->name('order_success');
//Route::get('/templistorderwaki1995', 'OrderController@listOrder')->name('list_order');

//Home service
Route::get('/homeservice', 'HomeServiceController@index')->name('add_homeServices');
Route::post('/homeservice', 'HomeServiceController@store')->name('store_home_service');
Route::get('/homeservice-success', 'HomeServiceController@successRegister')->name('homeServices_success');

//Program Refrence
Route::get('/referenceuntung', 'SubmissionController@untungBiayaIklan')->name('refrence_untung');
Route::get('/referencesehat', 'SubmissionController@referenceSehat')->name('refrence_sehat');

//WAKi Di Rumah Aja
Route::get('/wakidirumahaja', 'RegistrationPromotionController@index')->name('landing_waki');
Route::post('/wakidirumahaja', 'RegistrationPromotionController@store')->name('store_registrationPromotion');

//fetching data - data
Route::get('/fetchCso', 'CsoController@fetchCso')->name('fetchCso');
Route::get('/fetchCsoById', 'CsoController@fetchCsoById')->name('fetchCsoById');
Route::get('/fetchCsoByIdBranch/{branch}', 'CsoController@fetchCsoByIdBranch')->name('fetchCsoByIdBranch');
Route::get('/fetchBranchById', 'BranchController@fetchBranchById')->name('fetchBranchById');
Route::get("/fetchProvince", function () {
    return RajaOngkir::FetchProvince();
})->name("fetchProvince");
Route::get('/fetchCity/{province}', function ($province) {
    return RajaOngkir::FetchCity($province);
})->name('fetchCity');

Route::get('/fetchDistrict/{city}', function ($city) {
	$kotaOrKab = array("Kota ", "Kabupaten ");
	$city = str_replace($kotaOrKab, '', $city);
    return RajaOngkir::FetchDistrict($city);
})->name('fetchDistrict');
Route::get("/fetchSouvenir", "SouvenirController@fetchSouvenir")->name("fetchSouvenir");
Route::get("/fetchPrize", "PrizeController@fetchPrize")->name("fetchPrize");
Route::get("/fetchProductService", "ProductServiceController@fetchProductService")->name("fetchProductService");

Route::get("/changeStatusHS", "SubmissionController@firstRunStatus");

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
    Route::get('fetchcityapi/{province}', function ($province) {
        return RajaOngkir::FetchCityApi($province);
    }); //fetching all city from province
	Route::get('fetchallcityapi/{province}', function ($province) {
		return RajaOngkir::FetchAllCityApi($province);
	});
	Route::get('fetchdistrictapi/{city}', function ($city) {
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
        Route::post("/add/geolocation", "UserGeolocationController@addApi_2"); // Upload geolocation data
        Route::post("/add/start-geolocation", "UserGeolocationController@addStartImageApi");
        Route::get("/fetch/status-presence", "UserGeolocationController@fetchStatusPresence");
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

    // Submission form API
    Route::group(["prefix" => "submission"], function () {
        // Create submission API
        Route::post("add", "SubmissionController@addApi");

        // Show submission list API
        Route::post("list_submission", "SubmissionController@listApi");

        // View detail submission API
        Route::post("detail", "SubmissionController@detailApi");

        // Update submission API
        Route::post("update", "SubmissionController@updateApi");

        // Delete submission API
        Route::post("delete", "SubmissionController@deleteApi");
    });

    // Reference API
    Route::group(["prefix" => "reference"], function () {
        // Create reference API
        Route::post("/add/mgm", "ReferenceController@storeReferenceMGM");
        Route::post("/add/referensi", "ReferenceController@storeReferensi");

        // List reference API
        Route::post("list_reference", "ReferenceController@listApi");

        // Update reference API
        Route::post("/update/mmg", "ReferenceController@updateMGMApi");
        Route::post("/update/referensi", "ReferenceController@updateApi");
    });

    // Promo API
    Route::group(["prefix" => "promo"], function () {
        // List promo API
        Route::post("fetch_promo", "PromoController@fetchPromoList");
    });

    // Souvenir API
    Route::group(["prefix" => "souvenir"], function () {
        Route::post("fetch_souvenir", "SouvenirController@fetchSouvenir");
    });

    // Prize API
    Route::group(["prefix" => "prize"], function () {
        Route::post("fetch_prize", "PrizeController@fetchPrize");
    });
});

Auth::routes(['verify' => true]);
Route::group(['prefix' => 'cms-admin'], function () {
    Route::get('/', function () {
        if(Auth::guard()->check()){
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login');
        }
    });

    //show login form
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    //login cek nya
    Route::post('/login', 'Auth\LoginController@login')->name('admin_login');
    //logout usernya
    Route::get('/logout', 'Auth\LoginController@logoutUser')->name('admin_logout');
    //show login agreement
    Route::post('/loginagreement', 'Auth\LoginController@updateUserAgreement')->name('loginagreement');
    //dashboard
    Route::get('/dashboard', 'DashboardController@index')
        ->name('dashboard');

    Route::get("/dashboard-hs", "DashboardController@countHS")
        ->name("dashboard_hs");

    // Frontend CMS
    Route::get('/frontend-cms', 'AlbumController@create')
        ->name('add_album')
        ->middleware('can:browse-frontendcms');
    // Add Frontend CMS
    Route::post("/frontend-cms/store/album", "AlbumController@store")
        ->name("store_frontendcms_album");
    Route::post("/frontend-cms/add/image", "AlbumController@addNewImageGallery")
        ->name("store_frontendcms_image");
    Route::post("/frontend-cms/add/video", "FrontendCmsController@storeVideoGallery")
        ->name("store_frontendcms_video");
    // Update Frontend CMS
    Route::get("/frontend-cms/edit/album", "AlbumController@edit")
        ->name("edit_album");
    Route::post("/frontend-cms/update/image", "AlbumController@updateImageGallery")
        ->name("update_frontendcms_image");
    Route::post("frontend-cms/update/video", "AlbumController@updateVideoGallery")
        ->name("update_frontendcms_video");
    // Delete Frontend CMS
    Route::post("frontend-cms/delete/image", "FrontendCmsController@destroyImage")
        ->name("delete_frontendcms_image");
    Route::post("frontend-cms/delete/video", "FrontendCmsController@destroyVideo")
        ->name("delete_frontendcms_video");

    // Add Frontend CMS Event
    Route::post("/frontend-cms/store/event", "EventController@store")
            ->name("store_event");

    //change password admin
    Route::post('/changePassword','UserAdminController@changePassword')
            ->name('changePassword');
    //Check change password admin
    Route::post('/checkChangePassword', 'UserAdminController@checkChangePassword')
            ->name('check-change-password');

    //refrence
    Route::get('/fetchDetailPerReference/{reference}', 'SubmissionController@fetchDetailPerReference')->name('fetchDetailPerReference');

    //fetchperpromo
    Route::get('/fetchDetailPromo/{promo}', 'OrderController@fetchDetailPromo')->name('fetchDetailPromo');

    Route::group(['prefix' => 'useradmin', 'middleware' => 'auth'], function() {
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
        Route::get('/add_regispromo', 'RegistrationPromotionController@admin_AddRegistrationPromo')
	    	->name('add_regispromo')
	    	->middleware('can:add-deliveryorder');
        Route::match(['put', 'patch'],'/store_regispromo', 'RegistrationPromotionController@admin_StoreRegistrationPromo')
            ->name('store_regispromo')
            ->middleware('can:add-deliveryorder');
        Route::get('/detail_regispromo/{id}', 'RegistrationPromotionController@admin_DetailRegistrationPromo')
            ->name('detail_regispromo')
            ->middleware('can:edit-deliveryorder');
        Route::get('/edit_regispromo/{id}/edit', 'RegistrationPromotionController@admin_EditRegistrationPromo')
            ->name('edit_regispromo')
            ->middleware('can:edit-deliveryorder');
        Route::match(['put', 'patch'],'/update_regispromo/{id}', 'RegistrationPromotionController@admin_UpdateRegistrationPromo')
            ->name('update_regispromo')
            ->middleware('can:edit-deliveryorder');
        Route::delete('/delete_regispromo/{id}', 'RegistrationPromotionController@admin_DeleteRegistrationPromo')
            ->name('delete_regispromo')
            ->middleware('can:delete-deliveryorder');
        	    
    });

    Route::group(['prefix' => 'order', 'middleware' => 'auth'], function() {
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
        //Update Order Status
        Route::post('/update_status_order', 'OrderController@updateStatusOrder')
            ->name('update_status_order')
            ->middleware('can:change-status_order');
        //Store Order Payment
        Route::post('/store_order_payment', 'OrderController@storeOrderPayment')
            ->name('store_order_payment')
            ->middleware('can:edit-order');
        //Edit Order Payment
        Route::post('/edit_order_payment', 'OrderController@editOrderPayment')
            ->name('edit_order_payment')
            ->middleware('can:edit-order');
        //Update Order Payment
        Route::post('/update_order_payment', 'OrderController@updateOrderPayment')
            ->name('update_order_payment')
            ->middleware('can:edit-order');
        //Update Order Payment Status
        Route::post('/update_status_order_payment', 'OrderController@updateStatusOrderPayment')
            ->name('update_status_order_payment')
            ->middleware('can:change-status_payment');
        //Delete Order Payment
        Route::post('/delete_order_payment', 'OrderController@deleteOrderPayment')
            ->name('delete_order_payment')
            ->middleware('can:edit-order');
        //Delete Order
        Route::post('/{OrderNya}', 'OrderController@delete')
            ->name('delete_order');
        //Export to XLS By Date
        Route::get('/report-to-xls-by-date', 'OrderController@export_to_xls')
                ->name('order_export-to-xls');

        // List Order (Khusus untuk Submission)
        Route::get("/list_order_submission", "OrderController@ListOrderforSubmission")
            ->name("list_order_submission");

        // Fetch Customer Data By MPC Number
        Route::get("/fetch-customer", "OrderController@fetchCustomer")
            ->name("fetch_customer_by_mpc");

        // Order Report //
        //List Order Report
        Route::get('/list_order_report', 'OrderController@admin_ListOrderReport')
            ->name('admin_list_order_report')
            ->middleware('can:browse-order_report');
        //List Order Report By Branch
        Route::get('/list_order_report_branch', 'OrderController@admin_ListOrderReportBranch')
            ->name('admin_list_order_report_branch')
            ->middleware('can:browse-order_report_branch');
        //List Order Report By CSO
        Route::get('/list_order_report_cso', 'OrderController@admin_ListOrderReportCso')
            ->name('admin_list_order_report_cso')
            ->middleware('can:browse-order_report_cso');

        // Export Order Report
        Route::get('/export_order_report', 'OrderController@admin_ExportOrderReport')
            ->name('admin_export_order_report')
            ->middleware('can:browse-order_report');
        Route::get('/export_order_report_branch', 'OrderController@admin_ExportOrderReportBranch')
            ->name('admin_export_order_report_branch')
            ->middleware('can:browse-order_report_branch');
        Route::get('/export_order_report_cso', 'OrderController@admin_ExportOrderReportCso')
            ->name('admin_export_order_report_cso')
            ->middleware('can:browse-order_report_cso');
    });

    Route::group(['prefix' => 'homeservice', 'middleware' => 'auth'], function() {
        //notif acc cancel hs
        Route::post("/accNotifHomeservice", "HomeServiceController@accNotif")
            ->name("acc_cancel_notif_homeservice");
        //notif acc Reschedule hs
        Route::post("/accRescNotifHomeservice", "HomeServiceController@accRescheduleNotif")
            ->name("acc_reschedule_notif_homeservice");

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
        //Export to XLS Compare
        Route::get('/export-to-xls-compare', 'HomeServiceController@export_to_xls_compare')
                ->name('homeservice_export-to-xls-compare');

        // List Home Service (Revisi)
        Route::get("/list_new", "HomeServiceController@listHomeServiceNew")
            ->name("list_homeservice_new");

        // Print home service daily data count per month
        Route::post("/homeservice_print_data_count", "HomeServiceController@printAppointmentCount")
            ->name("homeservice_print_data_count");

        // Print home service data detail on a selected day
        Route::post("/homeservice_print_appointment", "HomeServiceController@printDayData")
            ->name("homeservice_print_appointment");

        // List Home Service (Khusus untuk Submission)
        Route::get("/list_hs_submission", "HomeServiceController@ListHSforSubmission")
            ->name("list_hs_submission");

        Route::get("/track", "UserGeolocationController@show")
            ->name("track_homeservice");

        Route::get("/fetch/geolocation", "UserGeolocationController@fetchGeolocationData")
            ->name("fetch_geolocation");

        Route::get("/fetch/presence", "UserGeolocationController@fetchPresenceImage")
            ->name("fetch_geolocation_presence");

        // Area Home Service
        Route::get('/list_area_homeservice', 'HomeServiceController@list_areaHomeService')
            ->name('list_area_homeservice')
            ->middleware('can:browse-area_home_service');
        Route::post('/print_area_list_hs', 'HomeServiceController@printAreaListHs')
            ->name('print_area_list_hs')
            ->middleware('can:browse-area_home_service');
    });

    Route::group(['prefix' => 'service','middleware' => 'auth'], function() {
        //Add Form Service
        Route::get('/', 'ServiceController@create')
            ->name('add_service')
            ->middleware('can:add-service');
        //Store Service
        Route::post('/', 'ServiceController@store')
            ->name('store_service')
            ->middleware('can:add-service');
        //List Service
        Route::get('/list', 'ServiceController@index')
            ->name('list_service')
            ->middleware('can:browse-service');
        //Detail Service
        Route::get("/detail/{id}", "ServiceController@show")
            ->name("detail_service")
            ->middleware('can:detail-service');
        // Update/change upgrade status
        Route::post("/updatestatus", "ServiceController@updateStatus")
            ->name("update_service_status")
            ->middleware('can:detail-service');
        //Edit Service
        Route::get('/edit/', 'ServiceController@edit')
            ->name('edit_service')
            ->middleware('can:edit-service');
        //Update Service
        Route::post('/update/', 'ServiceController@update')
            ->name('update_service')
            ->middleware('can:edit-service');

        // Delete service
        Route::post("/delete", "ServiceController@destroy")
            ->name("delete_service");
    });

    Route::group(['prefix' => 'product_service', 'middleware' => 'auth'], function() {
        //List Task Product Service
        Route::get('/list', 'ProductServiceController@index')
            ->name('list_taskservice')
            ->middleware('can:browse-service');
        //Edit Product Service
        Route::get('/edit/', 'ProductServiceController@edit')
            ->name('edit_taskservice')
            ->middleware('can:edit-service');
        //Edit Product Service (Upgrade)
        Route::get('/edit_upgrade/', 'ProductServiceController@editUpgrade')
            ->name('edit_taskupgrade')
            ->middleware('can:edit-service');
        //Update Product Service
        Route::post('/update/', 'ProductServiceController@update')
            ->name('update_taskservice')
            ->middleware('can:edit-service');
        //Update Product Service (Upgrade)
        Route::post('/update_upgrade/', 'ProductServiceController@updateUpgrade')
            ->name('update_taskupgrade')
            ->middleware('can:edit-service');

        Route::post("/update/failrepair", "ProductServiceController@updateFailRepair")
            ->name("update_fail_repair")
            ->middleware("can:edit-service");
    });
    
    Route::group(['prefix' => 'technician_schedule', 'middleware' => 'auth'], function() {
        //Add Technician Schedule
        Route::get('/', 'TechnicianScheduleController@create')
            ->name('add_technician_schedule')
            ->middleware('can:add-technician_schedule');
        //Store Technician Schedule
        Route::post('/', 'TechnicianScheduleController@store')
            ->name('store_technician_schedule')
            ->middleware('can:add-technician_schedule');
        // List Technician Schedule
        Route::get('/list', 'TechnicianScheduleController@index')
            ->name('list_technician_schedule')
            ->middleware('can:browse-technician_schedule');
        // Print Technician Schedule daily data count per month
        Route::post("/technicianschedule_print_data_count", "TechnicianScheduleController@printAppointmentCount")
        ->name("technicianschedule_print_data_count");
        // Print Technician Schedule data detail on a selected day
        Route::post("/technicianschedule_print_appointment", "TechnicianScheduleController@printDayData")
            ->name("technicianschedule_print_appointment");
        //Detail Technician Schedule
        Route::get("/detail/", "TechnicianScheduleController@show")
            ->name("detail_technician_schedule")
            ->middleware('can:detail-technician_schedule');
        //Edit Technician Schedule
        Route::get('/edit/', 'TechnicianScheduleController@edit')
            ->name('edit_technician_schedule')
            ->middleware('can:edit-technician_schedule');
        //Update Technician Schedule
        Route::post('/update/', 'TechnicianScheduleController@update')
            ->name('update_technician_schedule')
            ->middleware('can:edit-technician_schedule');
        // Delete Technician Schedule
        Route::post("/delete", "TechnicianScheduleController@destroy")
            ->name("delete_technician_schedule")
            ->middleware('can:delete-technician_schedule');

        //Export to XLS
        Route::get('/export-to-xls', 'TechnicianScheduleController@export_to_xls')
            ->name('technician_schedule_export-to-xls');
    });

    Route::group(['prefix' => 'sparepart', 'middleware' => 'auth'], function() {
        // Add Sparepart
        Route::get('/add', 'SparepartController@create')
            ->name('add_sparepart')
            ->middleware('can:add-sparepart');

        // Store Sparepart
        Route::post('/store', 'SparepartController@store')
            ->name('store_sparepart')
            ->middleware('can:add-sparepart');

        // List sparepart
        Route::get("/list", "SparepartController@index")
            ->name("list_sparepart");

        // Edit sparepart page
        Route::get("/edit/", "SparepartController@edit")
            ->name("edit_sparepart");

        // Update sparepart
        Route::post("/update/", "SparepartController@update")
            ->name("update_sparepart");

        // Delete sparepart
        Route::post("/delete/", "SparepartController@destroy")
            ->name("delete_sparepart");
    });

    Route::group(['prefix' => 'absent_off', 'middleware' => 'auth'], function() {
        //Add Absent Off
        Route::get('/', 'AbsentOffController@create')
            ->name('add_absent_off')
            ->middleware('can:add-absent_off');
        //Store Absent Off
        Route::post('/', 'AbsentOffController@store')
            ->name('store_absent_off')
            ->middleware('can:add-absent_off');
        //List Absent Off
        Route::get('/list', 'AbsentOffController@index')
            ->name('list_absent_off')
            ->middleware('can:browse-absent_off');
        //Print Absent Off daily data count per month
        Route::post("/absentoff_print_data_count", "AbsentOffController@printAppointmentCount")
            ->name("absentoff_print_data_count");
        //Print Absent Off data detail on a selected day
        Route::post("/absentoff_print_appointment", "AbsentOffController@printDayData")
            ->name("absentoff_print_appointment");
        //Detail Absent Off
        Route::get("/detail/", "AbsentOffController@show")
            ->name("detail_absent_off")
            ->middleware('can:detail-absent_off');
        Route::post("/detail_response", "AbsentOffController@detailResponse")
            ->name("detail_response_absent_off")
            ->middleware('can:detail-absent_off');
        //Edit Absent Off
        Route::get('/edit/', 'AbsentOffController@edit')
            ->name('edit_absent_off')
            ->middleware('can:edit-absent_off');
        //Update Absent Off
        Route::post('/update/', 'AbsentOffController@update')
            ->name('update_absent_off')
            ->middleware('can:edit-absent_off');
        //Delete Absent Off
        Route::post("/delete", "AbsentOffController@destroy")
            ->name("delete_absent_off")
            ->middleware('can:delete-absent_off');

        //List Acc Absent Off
        Route::get('/list_acc', 'AbsentOffController@indexAcc')
            ->name('list_acc_absent_off')
            ->middleware('can:browse-acc_absent_off');
        //Update Acc Absent Of
        Route::post("/update_acc", "AbsentOffController@updateAcc")
            ->name("update_acc_absent_off")
            ->middleware('can:acc-absent_off');
    });


    Route::group(['prefix' => 'cso', 'middleware' => 'auth'], function() {
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

    Route::group(['prefix' => 'branch', 'middleware' => 'auth'], function() {
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

    Route::group(['prefix' => 'category_products', 'middleware' => 'auth'], function() {
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

    Route::group(['prefix' => 'product', 'middleware' => 'auth'], function() {
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
        Route::post('/delete', 'ProductController@destroy')
            ->name('delete_product');
    });

    Route::group(['prefix' => 'stock', 'middleware' => 'auth'], function() {
        //List Stock
        Route::get('/list', 'StockController@index')
            ->name('list_stock')
            ->middleware('can:browse-product');
    });

    Route::group(['prefix' => 'promo', 'middleware' => 'auth'], function(){
    	// Add Form Promo
    	Route::get('/', 'PromoController@create')
	    	->name('add_promo')
	    	->middleware('can:add-promo');

	    // Create Promo
	    Route::post('/', 'PromoController@store')
	    	->name('store_promo')
	    	->middleware('can:add-promo');

	    // List Promo
	    Route::get('/list', 'PromoController@index')
	    	->name('list_promo')
	    	->middleware('can:browse-promo');

	    // Edit Promo
	    Route::get('/edit/', 'PromoController@edit')
	    	->name('edit_promo')
	    	->middleware('can:edit-promo');

	    // Update Promo
	    Route::post('/update/', 'PromoController@update')
	    	->name('update_promo')
	    	->middleware('can:edit-promo');

	    // Delete Promo
	    Route::post('/{PromoNya}', 'PromoController@delete')
	    	->name('delete_promo');

        // Fetch List Promo
        Route::get("/fetchPromo", "PromoController@fetchPromoList")
            ->name("fetch_promo_dropdown");
    });

    Route::group(['prefix' => 'type_customer', 'middleware' => 'auth'], function() {
        //Add Form Type Customer
        Route::get('/', 'TypeCustomerController@create')
            ->name('add_type_customer')
            ->middleware('can:add-type_customer');

        //Create Type Customer
        Route::post('/', 'TypeCustomerController@store')
            ->name('store_type_customer')
            ->middleware('can:add-type_customer');

        //List Type Customer
        Route::get('/list', 'TypeCustomerController@index')
            ->name('list_type_customer')
            ->middleware('can:browse-type_customer');

        //Edit Type Customer
        Route::get('/edit/', 'TypeCustomerController@edit')
            ->name('edit_type_customer')
            ->middleware('can:edit-type_customer');

        //Update Type Customer
        Route::post('/update/', 'TypeCustomerController@update')
            ->name('update_type_customer')
            ->middleware('can:edit-type_customer');

        //Delete Type Customer
        Route::post('/delete', 'TypeCustomerController@destroy')
            ->name('delete_type_customer')
            ->middleware('can:delete-type_customer');
    });

    Route::group(['prefix' => 'bank', 'middleware' => 'auth'], function() {
        //Add Form Type Customer
        Route::get('/', 'BankController@create')
            ->name('add_bank')
            ->middleware('can:add-bank');

        //Create Type Customer
        Route::post('/', 'BankController@store')
            ->name('store_bank')
            ->middleware('can:add-bank');

        //List Type Customer
        Route::get('/list', 'BankController@index')
            ->name('list_bank')
            ->middleware('can:browse-bank');

        //Edit Type Customer
        Route::get('/edit/', 'BankController@edit')
            ->name('edit_bank')
            ->middleware('can:edit-bank');

        //Update Type Customer
        Route::post('/update/', 'BankController@update')
            ->name('update_bank')
            ->middleware('can:edit-bank');

        //Delete Type Customer
        Route::post('/delete', 'BankController@destroy')
            ->name('delete_bank')
            ->middleware('can:delete-bank');
    });

    Route::group(['prefix' => 'bank-account', 'middleware' => 'auth'], function() {
        Route::get('/', 'BankController@createBankAccount')->name('add_bank_account')->middleware('can:add-bank');
        Route::post('/', 'BankController@storeBankAccount')->name('store_bank_account')->middleware('can:add-bank');
        Route::get('/list', 'BankController@indexBankAccount')->name('list_bank_account')->middleware('can:browse-bank');
        Route::get('/edit/{id}', 'BankController@editBankAccount')->name('edit_bank_account')->middleware('can:edit-bank');
        Route::post('/update/', 'BankController@updateBankAccount')->name('update_bank_account')->middleware('can:edit-bank');
        Route::post('/delete', 'BankController@destroyBankAccount')->name('delete_bank_account')->middleware('can:delete-bank');
        Route::get('/get-bank-account-by-bank/{id}', 'BankController@getBankAccountData')->name('get_bank_account');
    });

    Route::group(['prefix' => 'credit-card', 'middleware' => 'auth'], function() {
        Route::get('/', 'BankController@createCreditCard')->name('add_credit_card')->middleware('can:add-bank');
        Route::post('/', 'BankController@storeCreditCard')->name('store_credit_card')->middleware('can:add-bank');
        Route::get('/list', 'BankController@indexCreditCard')->name('list_credit_card')->middleware('can:browse-bank');
        Route::get('/edit/{id}', 'BankController@editCreditCard')->name('edit_credit_card')->middleware('can:edit-bank');
        Route::post('/update/', 'BankController@updateCreditCard')->name('update_credit_card')->middleware('can:edit-bank');
        Route::post('/delete', 'BankController@destroyCreditCard')->name('delete_credit_card')->middleware('can:delete-bank');
        Route::get('/get-credit-card-data/{id}', 'BankController@getCreditCardData')->name('get_credit_card');
        Route::get('/get-bank-account-data/{id}', 'BankController@getBankAccountDataFromPaymentModal')->name('get_bank_account_from_payment_modal');
    });

    Route::group(['prefix' => 'data_sourcing', 'middleware' => 'auth'], function() {
        //Add Form Data Sourcing
        Route::get('/', 'DataSourcingController@create')
            ->name('add_data_sourcing')
            ->middleware('can:add-data_sourcing');

        //Create Data Sourcing
        Route::post('/', 'DataSourcingController@store')
            ->name('store_data_sourcing')
            ->middleware('can:add-data_sourcing');

        //List Data Sourcing
        Route::get('/list', 'DataSourcingController@index')
            ->name('list_data_sourcing')
            ->middleware('can:browse-data_sourcing');

        //Edit Data Sourcing
        Route::get('/edit/', 'DataSourcingController@edit')
            ->name('edit_data_sourcing')
            ->middleware('can:edit-data_sourcing');

        //Update Data Sourcing
        Route::post('/update/', 'DataSourcingController@update')
            ->name('update_data_sourcing')
            ->middleware('can:edit-data_sourcing');

        //Delete Data Sourcing
        Route::post('/delete', 'DataSourcingController@destroy')
            ->name('delete_data_sourcing')
            ->middleware('can:delete-data_sourcing');

        //Export XLS Data Sourcing
        Route::get('/export_to_xls', 'DataSourcingController@export_to_xls')
            ->name('export_data_sourcing')
            ->middleware('can:browse-data_sourcing');

        // Form Import Data Sourcing
        Route::get('/import_data_sourcing', 'DataSourcingController@importDataSourcing')
            ->name('import_data_sourcing')
            ->middleware('can:add-data_sourcing');
        
        // Create Import Data Sourcing
        Route::post('/import_data_sourcing', 'DataSourcingController@storeImportDataSourcing')
            ->name('store_import_data_sourcing')
            ->middleware('can:add-data_sourcing');
    });

    Route::group(['prefix' => 'data_therapy', 'middleware' => 'auth'], function() {
        //Add Form Data Therapy
        Route::get('/', 'DataTherapyController@create')
            ->name('add_data_therapy')
            ->middleware('can:add-data_therapy');

        //Create Data Therapy
        Route::post('/', 'DataTherapyController@store')
            ->name('store_data_therapy')
            ->middleware('can:add-data_therapy');

        //List Data Therapy
        Route::get('/list', 'DataTherapyController@index')
            ->name('list_data_therapy')
            ->middleware('can:browse-data_therapy');
        
        //Detail Data Therapy
        Route::get('/detail', 'DataTherapyController@show')
            ->name('detail_data_therapy')
            ->middleware('can:detail-data_therapy');

        //Edit Data Therapy
        Route::get('/edit/', 'DataTherapyController@edit')
            ->name('edit_data_therapy')
            ->middleware('can:edit-data_therapy');

        //Update Data Therapy
        Route::post('/update/', 'DataTherapyController@update')
            ->name('update_data_therapy')
            ->middleware('can:edit-data_therapy');

        //Delete Data Therapy
        Route::post('/delete', 'DataTherapyController@destroy')
            ->name('delete_data_therapy')
            ->middleware('can:delete-data_therapy');
        
        //Export XLS Data Therapy
        Route::get('/export_to_xls', 'DataTherapyController@export_to_xls')
            ->name('export_data_therapy')
            ->middleware('can:browse-data_therapy');

    });

    Route::group(["prefix" => "submission_form", "middleware" => "auth"], function () {
        // Convert Link_HS in ReferenceSouvenirs to JSON
        Route::get("/converths", "SubmissionController@convertHsToForeign")
            ->name("submission_convert_hs");
        // Create submission form page
        Route::get("/mgm", "SubmissionController@createMGM")
            ->name("add_submission_mgm");

        Route::get("/reference", "SubmissionController@createReference")
            ->name("add_submission_reference");

        Route::get("/takeaway", "SubmissionController@createTakeaway")
            ->name("add_submission_takeaway");

        // Process new submission form
        Route::post("/mgm", "SubmissionController@storeMGM")
            ->name("store_submission_mgm");

        Route::post("/reference", "SubmissionController@storeReference")
            ->name("store_submission_reference");

        Route::post("/takeaway", "SubmissionController@storeTakeaway")
            ->name("store_submission_takeaway");

        // Show submission list
        Route::get("/list", "SubmissionController@index")
            ->name("list_submission_form");

        // Show detail of submission
        Route::get("/detail", "SubmissionController@show")
            ->name("detail_submission_form");

        // Edit submission form page
        Route::get("/edit/", "SubmissionController@edit")
            ->name("edit_submission_form");

        // Process submission form edit
        Route::post("/update/mgm", "SubmissionController@updateMGM")
            ->name("update_submission_mgm");

        Route::post("/update/referensi", "SubmissionController@updateReferensi")
            ->name("update_submission_referensi");

        Route::post("/update/status-referensi", "SubmissionController@updateStatusReferensi")
            ->name("update_submission_status_referensi");

        Route::post("/update/takeaway", "SubmissionController@updateTakeaway")
            ->name("update_submission_takeaway");

        // Process submission form delete
        Route::post("/delete/", "SubmissionController@destroy")
            ->name("delete_submission_form");

        // Submission MGM Query with prize as parameter
        Route::get("/get/mgm2", "SubmissionController@queryNewSubmissionMGM")
            ->name("query_new_submission_mgm");
    });

    Route::group(["prefix" => "reference", "middleware" => "auth"], function () {
        Route::post("/mgm", "ReferenceController@storeReferenceMGM")
            ->name("store_reference_mgm");

        Route::post("/referensi", "ReferenceController@storeReferensi")
            ->name("store_reference_referensi");

        // List reference
        Route::get("/list", "ReferenceController@index")
            ->name("list_reference");

        // Update reference
        Route::post("/update", "ReferenceController@update")
            ->name("update_reference");

        Route::post("/update/mgm", "ReferenceController@updateReferenceMGM")
            ->name("update_reference_mgm");

        Route::post("/delete", "ReferenceController@destroy")
            ->name("delete_reference");

        Route::post("/accNotif", "ReferenceController@accNotif")
            ->name("acc_notif_reference");

        // add online signature
        Route::post("/online_signature", "ReferenceController@addOnlineSignature")
            ->name("online_signature.add");
    });

    Route::group(["prefix" => "acceptance", "middleware" => "auth"], function () {
        // Create acceptance page
        Route::get("/", "AcceptanceController@create")
            ->name("add_acceptance_form");

        // Process new acceptance
        Route::post("/", "AcceptanceController@store")
            ->name("store_acceptance_form");

        // Show acceptance list
        Route::get("/list", "AcceptanceController@list")
            ->name("list_acceptance_form");

        // Show detail of acceptance
        Route::get("/detail/{id}", "AcceptanceController@detail")
            ->name("detail_acceptance_form");

        // Edit acceptance page
        Route::get("/edit/{id}", "AcceptanceController@edit")
            ->name("edit_acceptance_form");

        // Process acceptance edit
        Route::post("/update/", "AcceptanceController@update")
            ->name("update_acceptance_form");

        // Process acceptance delete
        Route::post("/{id}", "AcceptanceController@destroy")
            ->name("delete_acceptance_form");

        //export xls
        Route::get('/export-acceptance-to-xls-by-date', 'AcceptanceController@export_to_xls_byDate')
                ->name('acceptance_export-to-xls-by-date');

        // add bill do
        Route::post("/bill_do/{id}", "AcceptanceController@addBillOrder")
            ->name("add_bill_do");

    });

    Route::group(["prefix" => "upgrade", "middleware" => "auth"], function () {
        // List new upgrade form page
        Route::get("/list_new", "UpgradeController@indexNew")
            ->name("list_new_upgrade_form");

        // Create upgrade form page
        Route::get("/new/{id}", "UpgradeController@create")
            ->name("add_upgrade_form");

        // Process new upgrade form
        Route::post("/", "UpgradeController@store")
            ->name("store_upgrade_form");

        // Show upgrade list
        Route::get("/list", "UpgradeController@list")
            ->name("list_upgrade_form");

        // Show detail of upgrade
        Route::get("/detail/{id}", "UpgradeController@detail")
            ->name("detail_upgrade_form");

        // Edit upgrade form page
        Route::get("/edit/", "UpgradeController@edit")
            ->name("edit_upgrade_form");

        // Process upgrade form edit
        Route::post("/update/", "UpgradeController@update")
            ->name("update_upgrade_form");

        // Update/change upgrade status
        Route::post("/updatestatus", "UpgradeController@updateStatus")
            ->name("update_upgrade_status");

        // Process upgrade form delete
        Route::post("/{id}", "UpgradeController@destroy")
            ->name("delete_upgrade_form");
    });

    Route::group(["prefix" => "souvenir", "middleware" => "auth"], function () {
        // List souvenir
        Route::get("/list", "SouvenirController@index")
            ->name("list_souvenir");

        // Create souvenir
        Route::get("/add", "SouvenirController@create")
            ->name("add_souvenir");

        // Store souvenir
        Route::post("/add", "SouvenirController@store")
            ->name("store_souvenir");

        // Edit souvenir
        Route::get("/edit", "SouvenirController@edit")
            ->name("edit_souvenir");

        // Update souvenir
        Route::post("/update", "SouvenirController@update")
            ->name("update_souvenir");

        // Delete souvenir
        Route::post("/delete", "SouvenirController@destroy")
            ->name("delete_souvenir");
    });

    Route::group(["prefix" => "prize", "middleware" => "auth"], function () {
        // List prize
        Route::get("/list", "PrizeController@index")
            ->name("list_prize");

        // Create prize
        Route::get("/add", "PrizeController@create")
            ->name("add_prize");

        // Store prize
        Route::post("/add", "PrizeController@store")
            ->name("store_prize");

        // Edit prize
        Route::get("/edit", "PrizeController@edit")
            ->name("edit_prize");

        // Update prize
        Route::post("/update", "PrizeController@update")
            ->name("update_prize");

        // Delete prize
        Route::post("/delete", "PrizeController@destroy")
            ->name("delete_prize");
    });

    Route::group(["prefix" => "personal-homecare", "middleware" => "auth"], function () {
        Route::get("add-product", "PersonalHomecareProductController@create")
            ->name("add_phc_product")
            ->middleware('can:add-phc-product');
        Route::post("store-product", "PersonalHomecareProductController@store")
            ->name("store_phc_product")
            ->middleware("can:add-phc-product");
        Route::get("get-product-increment", "PersonalHomecareProductController@getProductIncrement")
            ->name("get_phc_product_increment");
        Route::get("list-product", 'PersonalHomecareProductController@index')
            ->name("list_phc_product")
            ->middleware('can:browse-phc-product');
        Route::get("detail-product/{id}", 'PersonalHomecareProductController@show')
            ->name("detail_phc_product")
            ->middleware('can:browse-phc-product');
        Route::get("edit-product/{id}", "PersonalHomecareProductController@edit")
            ->name("edit_phc_product")
            ->middleware("can:edit-phc-product");
        Route::post("update-product", "PersonalHomecareProductController@update")
            ->name("update_phc_product")
            ->middleware("can:edit-phc-product");
        Route::post("delete-product", "PersonalHomecareProductController@destroy")
            ->name("delete_phc_product");

        Route::get("add", "PersonalHomecareController@create")
            ->name("add_personal_homecare")
            ->middleware('can:add-personal-homecare');
        Route::post("store", "PersonalHomecareController@store")
            ->name("store_personal_homecare")
            ->middleware("can:add-personal-homecare");
        Route::get("get-product", "PersonalHomecareController@getPhcProduct")
            ->name("get_phc_product");
        Route::get("check-phone", "PersonalHomecareController@checkPhone")
            ->name("check_phc_phone");
        Route::get("list-all", 'PersonalHomecareController@index')
            ->name("list_all_phc");
        Route::get("list-approved", 'PersonalHomecareController@listApproved')
            ->name("list_approved_phc")
            ->middleware("can:browse-personal-homecare");
        Route::get("edit/{id}", "PersonalHomecareController@edit")
            ->name("edit_personal_homecare")
            ->middleware("can:edit-personal-homecare");
        Route::post("update", "PersonalHomecareController@update")
            ->name("update_personal_homecare")
            ->middleware("can:edit-personal-homecare");
        Route::post("update/status", "PersonalHomecareController@updateStatus")
            ->name("update_personal_homecare_status");
        Route::post("update/checklist-in", "PersonalHomecareController@updateChecklistIn")
            ->name("update_personal_homecare_checklist_in");
        Route::get("detail/{id}", "PersonalHomecareController@detail")
            ->name("detail_personal_homecare")
            ->middleware("can:detail-personal-homecare");
        Route::post("acc_cancel", "PersonalHomecareController@accCancel")
            ->name("acc_cancel_personal_homecare");
        Route::post("delete", "PersonalHomecareController@destroy")
            ->name("delete_personal_homecare");

        Route::post("extendPersonalHomecare", "PersonalHomecareController@extendPersonalHomecare")
            ->name("extend_personal_homecare");
        Route::post("reschedulePersonalHomecare", "PersonalHomecareController@reschedulePersonalHomecare")
            ->name("reschedule_personal_homecare");
    });

    Route::view('faq_agreement', 'admin.faq_agreement')->name('faq_agreement');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
