<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPostPolicies();

        //
    }

    public function registerPostPolicies()
    {
        //-- DASHBOARD --//
        Gate::define('show-dashboard', function ($user) {
            return $user->hasAccess(['show-dashboard']);
        });

        //-- FRONT END CMS --//
        Gate::define('browse-frontendcms', function ($user) {
            return $user->hasAccess(['browse-frontendcms']);
        });

        //-- DELIVERY ORDER --//
        Gate::define('add-deliveryorder', function ($user) {
            return $user->hasAccess(['add-deliveryorder']);
        });
        Gate::define('browse-deliveryorder', function ($user) {
            return $user->hasAccess(['browse-deliveryorder']);
        });
        Gate::define('detail-deliveryorder', function ($user) {
            return $user->hasAccess(['detail-deliveryorder']);
        });
        Gate::define('edit-deliveryorder', function ($user) {
            return $user->hasAccess(['edit-deliveryorder']);
        });
        Gate::define('delete-deliveryorder', function ($user) {
            return $user->hasAccess(['delete-deliveryorder']);
        });

        //-- ORDER --//
        Gate::define('add-order', function ($user) {
            return $user->hasAccess(['add-order']);
        });
        Gate::define('browse-order', function ($user) {
            return $user->hasAccess(['browse-order']);
        });
        Gate::define('detail-order', function ($user) {
            return $user->hasAccess(['detail-order']);
        });
        Gate::define('edit-order', function ($user) {
            return $user->hasAccess(['edit-order']);
        });
        Gate::define('delete-order', function ($user) {
            return $user->hasAccess(['delete-order']);
        });

        //-- UPDATE ORDER STATUS --//
        Gate::define('change-status_order', function ($user) {
            return $user->hasAccess(['change-status_order']);
        });
        Gate::define('change-status_order_process', function ($user) {
            return $user->hasAccess(['change-status_order_process']);
        });
        Gate::define('change-status_order_delivery', function ($user) {
            return $user->hasAccess(['change-status_order_delivery']);
        });
        Gate::define('change-status_order_success', function ($user) {
            return $user->hasAccess(['change-status_order_success']);
        });
        Gate::define('change-status_order_reject', function ($user) {
            return $user->hasAccess(['change-status_order_reject']);
        });

        //-- UPDATE ORDER STATUS --//
        Gate::define('change-status_payment', function ($user) {
            return $user->hasAccess(['change-status_payment']);
        });
        Gate::define('change-status_payment_verified', function ($user) {
            return $user->hasAccess(['change-status_payment_verified']);
        });
        Gate::define('change-status_payment_rejected', function ($user) {
            return $user->hasAccess(['change-status_payment_rejected']);
        });

        //-- ORDER REPORT --//
        Gate::define('browse-order_report', function ($user) {
            return $user->hasAccess(['browse-order_report']);
        });
        Gate::define('browse-order_report_branch', function ($user) {
            return $user->hasAccess(['browse-order_report_branch']);
        });
        Gate::define('browse-order_report_cso', function ($user) {
            return $user->hasAccess(['browse-order_report_cso']);
        });

        //-- HOME SERVICE --//
        Gate::define('add-home_service', function ($user) {
            return $user->hasAccess(['add-home_service']);
        });
        Gate::define('browse-home_service', function ($user) {
            return $user->hasAccess(['browse-home_service']);
        });
        Gate::define('detail-home_service', function ($user) {
            return $user->hasAccess(['detail-home_service']);
        });
        Gate::define('edit-home_service', function ($user) {
            return $user->hasAccess(['edit-home_service']);
        });
        Gate::define('delete-home_service', function ($user) {
            return $user->hasAccess(['delete-home_service']);
        });
        Gate::define('acc-view-home_service', function ($user) {
            return $user->hasAccess(['acc-view-home_service']);
        });
        Gate::define('acc-reschedule-home_service', function ($user) {
            return $user->hasAccess(['acc-reschedule-home_service']);
        });
        Gate::define('acc-cancel-home_service', function ($user) {
            return $user->hasAccess(['acc-cancel-home_service']);
        });
        Gate::define('view-phone-home_service', function ($user) {
            return $user->hasAccess(['view-phone-home_service']);
        });
        Gate::define('view-type-home_service', function ($user) {
            return $user->hasAccess(['view-type-home_service']);
        });

        //-- AREA HOME SERVICE --//
        Gate::define('browse-area_home_service', function ($user) {
            return $user->hasAccess(['browse-area_home_service']);
        });

        //-- Absent Off --//
        Gate::define('add-absent_off', function ($user) {
            return $user->hasAccess(['add-absent_off']);
        });
        Gate::define('browse-absent_off', function ($user) {
            return $user->hasAccess(['browse-absent_off']);
        });
        Gate::define('detail-absent_off', function ($user) {
            return $user->hasAccess(['detail-absent_off']);
        });
        Gate::define('edit-absent_off', function ($user) {
            return $user->hasAccess(['edit-absent_off']);
        });
        Gate::define('delete-absent_off', function ($user) {
            return $user->hasAccess(['delete-absent_off']);
        });
        Gate::define('browse-acc_absent_off', function ($user) {
            return $user->hasAccess(['browse-acc_absent_off']);
        });
        Gate::define('acc-view-spv_absent_off', function ($user) {
            return $user->hasAccess(['acc-view-spv_absent_off']);
        });
        Gate::define('acc-view-coor_absent_off', function ($user) {
            return $user->hasAccess(['acc-view-coor_absent_off']);
        });
        Gate::define('acc-absent_off', function ($user) {
            return $user->hasAccess(['acc-absent_off']);
        });
        Gate::define('acc-spv_absent_off', function ($user) {
            return $user->hasAccess(['acc-spv_absent_off']);
        });
        Gate::define('acc-reject_spv_absent_off', function ($user) {
            return $user->hasAccess(['acc-reject_spv_absent_off']);
        });
        Gate::define('acc-coor_absent_off', function ($user) {
            return $user->hasAccess(['acc-coor_absent_off']);
        });
        Gate::define('acc-reject_coor_absent_off', function ($user) {
            return $user->hasAccess(['acc-reject_coor_absent_off']);
        });

        //-- CSO --//
        Gate::define('add-cso', function ($user) {
            return $user->hasAccess(['add-cso']);
        });
        Gate::define('browse-cso', function ($user) {
            return $user->hasAccess(['browse-cso']);
        });
        Gate::define('edit-cso', function ($user) {
            return $user->hasAccess(['edit-cso']);
        });
        Gate::define('delete-cso', function ($user) {
            return $user->hasAccess(['delete-cso']);
        });

        //-- BRANCH --//
        Gate::define('add-branch', function ($user) {
            return $user->hasAccess(['add-branch']);
        });
        Gate::define('browse-branch', function ($user) {
            return $user->hasAccess(['browse-branch']);
        });
        Gate::define('edit-branch', function ($user) {
            return $user->hasAccess(['edit-branch']);
        });
        Gate::define('delete-branch', function ($user) {
            return $user->hasAccess(['delete-branch']);
        });

        //-- CATEGORY --//
        Gate::define('add-category', function ($user) {
            return $user->hasAccess(['add-category']);
        });
        Gate::define('browse-category', function ($user) {
            return $user->hasAccess(['browse-category']);
        });
        Gate::define('edit-category', function ($user) {
            return $user->hasAccess(['edit-category']);
        });
        Gate::define('delete-category', function ($user) {
            return $user->hasAccess(['delete-category']);
        });

        //-- PRODUCT --//
        Gate::define('add-product', function ($user) {
            return $user->hasAccess(['add-product']);
        });
        Gate::define('browse-product', function ($user) {
            return $user->hasAccess(['browse-product']);
        });
        Gate::define('edit-product', function ($user) {
            return $user->hasAccess(['edit-product']);
        });
        Gate::define('delete-product', function ($user) {
            return $user->hasAccess(['delete-product']);
        });

        //-- PROMO --//
        Gate::define('add-promo', function ($user) {
            return $user->hasAccess(['add-promo']);
        });
        Gate::define('browse-promo', function ($user) {
            return $user->hasAccess(['browse-promo']);
        });
        Gate::define('edit-promo', function ($user) {
            return $user->hasAccess(['edit-promo']);
        });
        Gate::define('delete-promo', function ($user) {
            return $user->hasAccess(['delete-promo']);
        });

        //-- TYPE CUSTOMER --//
        Gate::define('add-type_customer', function ($user) {
            return $user->hasAccess(['add-type_customer']);
        });
        Gate::define('browse-type_customer', function ($user) {
            return $user->hasAccess(['browse-type_customer']);
        });
        Gate::define('edit-type_customer', function ($user) {
            return $user->hasAccess(['edit-type_customer']);
        });
        Gate::define('delete-type_customer', function ($user) {
            return $user->hasAccess(['delete-type_customer']);
        });

        //-- BANK --//
        Gate::define('add-bank', function ($user) {
            return $user->hasAccess(['add-bank']);
        });
        Gate::define('browse-bank', function ($user) {
            return $user->hasAccess(['browse-bank']);
        });
        Gate::define('edit-bank', function ($user) {
            return $user->hasAccess(['edit-bank']);
        });
        Gate::define('delete-bank', function ($user) {
            return $user->hasAccess(['delete-bank']);
        });

        //-- DATA SOURCING --//
        Gate::define('add-data_sourcing', function ($user) {
            return $user->hasAccess(['add-data_sourcing']);
        });
        Gate::define('browse-data_sourcing', function ($user) {
            return $user->hasAccess(['browse-data_sourcing']);
        });
        Gate::define('edit-data_sourcing', function ($user) {
            return $user->hasAccess(['edit-data_sourcing']);
        });
        Gate::define('delete-data_sourcing', function ($user) {
            return $user->hasAccess(['delete-data_sourcing']);
        });

        //-- DATA Therapy --//
        Gate::define('add-data_therapy', function ($user) {
            return $user->hasAccess(['add-data_therapy']);
        });
        Gate::define('browse-data_therapy', function ($user) {
            return $user->hasAccess(['browse-data_therapy']);
        });
        Gate::define('detail-data_therapy', function ($user) {
            return $user->hasAccess(['detail-data_therapy']);
        });
        Gate::define('edit-data_therapy', function ($user) {
            return $user->hasAccess(['edit-data_therapy']);
        });
        Gate::define('delete-data_therapy', function ($user) {
            return $user->hasAccess(['delete-data_therapy']);
        });

        //-- REPORT --//

        //-- USER ADMIN --//
        Gate::define('add-user', function ($user) {
            return $user->hasAccess(['add-user']);
        });
        Gate::define('browse-user', function ($user) {
            return $user->hasAccess(['browse-user']);
        });
        Gate::define('edit-user', function ($user) {
            return $user->hasAccess(['edit-user']);
        });
        Gate::define('delete-user', function ($user) {
            return $user->hasAccess(['delete-user']);
        });

        //-- APP VERSION --//
        Gate::define('add-app', function ($user) {
            return $user->hasAccess(['add-app']);
        });
        Gate::define('browse-app', function ($user) {
            return $user->hasAccess(['browse-app']);
        });
        Gate::define('edit-app', function ($user) {
            return $user->hasAccess(['edit-app']);
        });
        Gate::define('delete-app', function ($user) {
            return $user->hasAccess(['delete-app']);
        });

        //-- COMPANY INFO --//
        Gate::define('browse-company_info', function ($user) {
            return $user->hasAccess(['browse-company_info']);
        });

        //-- ACCEPTANCE --//
        // Add acceptance
        Gate::define("add-acceptance", function ($user) {
            return $user->hasAccess(["add-acceptance"]);
        });
        // Browse acceptance
        Gate::define("browse-acceptance", function ($user) {
            return $user->hasAccess(["browse-acceptance"]);
        });
        // View detail acceptance
        Gate::define("detail-acceptance", function ($user) {
            return $user->hasAccess(["detail-acceptance"]);
        });
        // Edit acceptance
        Gate::define("edit-acceptance", function ($user) {
            return $user->hasAccess(["edit-acceptance"]);
        });
        // Change status approval acceptance
        Gate::define("change-status-approval-acceptance", function ($user) {
            return $user->hasAccess(["change-status-approval-acceptance"]);
        });
        // Change status complete acceptance
        Gate::define("change-status-complete-acceptance", function ($user) {
            return $user->hasAccess(["change-status-complete-acceptance"]);
        });
        // Change status reject acceptance
        Gate::define("change-status-reject-acceptance", function ($user) {
            return $user->hasAccess(["change-status-reject-acceptance"]);
        });
        // Delete acceptance
        Gate::define("delete-acceptance", function ($user) {
            return $user->hasAccess(["delete-acceptance"]);
        });

        //-- UPGRADE --//
        // Browse upgrade
        Gate::define("browse-upgrade", function ($user) {
            return $user->hasAccess(["browse-upgrade"]);
        });
        // View detail upgrade
        Gate::define("detail-upgrade", function ($user) {
            return $user->hasAccess(["detail-upgrade"]);
        });
        // Edit upgrade
        Gate::define("edit-upgrade", function ($user) {
            return $user->hasAccess(["edit-upgrade"]);
        });
        // Change status approval upgrade
        Gate::define("change-status-approval-upgrade", function ($user) {
            return $user->hasAccess(["change-status-approval-upgrade"]);
        });
        // Change status process upgrade
        Gate::define("change-status-process-upgrade", function ($user) {
            return $user->hasAccess(["change-status-process-upgrade"]);
        });
        // Change status process upgrade
        Gate::define("change-status-repaired-upgrade", function ($user) {
            return $user->hasAccess(["change-status-repaired-upgrade"]);
        });
        // Change status complete upgrade
        Gate::define("change-status-complete-upgrade", function ($user) {
            return $user->hasAccess(["change-status-complete-upgrade"]);
        });
        // Change status reject upgrade
        Gate::define("change-status-reject-upgrade", function ($user) {
            return $user->hasAccess(["change-status-reject-upgrade"]);
        });
        // Delete upgrade
        Gate::define("delete-upgrade", function ($user) {
            return $user->hasAccess(["delete-upgrade"]);
        });

        //-- SERVICE --//
        // Add service
        Gate::define("add-service", function ($user) {
            return $user->hasAccess(["add-service"]);
        });
        // Browse service
        Gate::define("browse-service", function ($user) {
            return $user->hasAccess(["browse-service"]);
        });
        // Detail service
        Gate::define("detail-service", function ($user) {
            return $user->hasAccess(["detail-service"]);
        });
        // Edit service
        Gate::define("edit-service", function ($user) {
            return $user->hasAccess(["edit-service"]);
        });
        // Change status process service
        Gate::define("change-status-process-service", function ($user) {
            return $user->hasAccess(["change-status-process-service"]);
        });
        // Change status repaired service
        Gate::define("change-status-repaired-service", function ($user) {
            return $user->hasAccess(["change-status-repaired-service"]);
        });
        // Change status QC service
        Gate::define("change-status-qc-service", function ($user) {
            return $user->hasAccess(["change-status-qc-service"]);
        });
        // Change status delivery order
        Gate::define("change-status-delivery-service", function ($user) {
            return $user->hasAccess(["change-status-delivery-service"]);
        });
        // Change status compelte service
        Gate::define("change-status-complete-service", function ($user) {
            return $user->hasAccess(["change-status-complete-service"]);
        });
        // Delete service
        Gate::define("delete-service", function ($user) {
            return $user->hasAccess(["delete-service"]);
        });

        //-- Technician Schedule --//
        // Add Technician Schedule
        Gate::define("add-technician_schedule", function ($user) {
            return $user->hasAccess(["add-technician_schedule"]);
        });
        // Browse Technician Schedule
        Gate::define("browse-technician_schedule", function ($user) {
            return $user->hasAccess(["browse-technician_schedule"]);
        });
        // View detail Technician Schedule
        Gate::define("detail-technician_schedule", function ($user) {
            return $user->hasAccess(["detail-technician_schedule"]);
        });
        // Edit Technician Schedule
        Gate::define("edit-technician_schedule", function ($user) {
            return $user->hasAccess(["edit-technician_schedule"]);
        });
        // Delete Technician Schedule
        Gate::define("delete-technician_schedule", function ($user) {
            return $user->hasAccess(["delete-technician_schedule"]);
        });

        //-- SUBMISSION --//
        // Add submission
        Gate::define("add-submission", function ($user) {
            return $user->hasAccess(["add-submission"]);
        });
        // Browse submission
        Gate::define("browse-submission", function ($user) {
            return $user->hasAccess(["browse-submission"]);
        });
        // Browse reference
        Gate::define("browse-reference", function ($user) {
            return $user->hasAccess(["browse-reference"]);
        });
        // View detail submission
        Gate::define("detail-submission", function ($user) {
            return $user->hasAccess(["detail-submission"]);
        });
        // Edit submission
        Gate::define("edit-submission", function ($user) {
            return $user->hasAccess(["edit-submission"]);
        });
        // Delete submission
        Gate::define("delete-submission", function ($user) {
            return $user->hasAccess(["delete-submission"]);
        });

        //-- SUBMISSION Video & Photo --//
        // Add Submission Video & Photo
        Gate::define("add-submission_video_photo", function ($user) {
            return $user->hasAccess(["add-submission_video_photo"]);
        });
        // Browse Submission Video & Photo
        Gate::define("browse-submission_video_photo", function ($user) {
            return $user->hasAccess(["browse-submission_video_photo"]);
        });
        // View detail Submission Video & Photo
        Gate::define("detail-submission_video_photo", function ($user) {
            return $user->hasAccess(["detail-submission_video_photo"]);
        });
        // Edit Submission Video & Photo
        Gate::define("edit-submission_video_photo", function ($user) {
            return $user->hasAccess(["edit-submission_video_photo"]);
        });
        // Delete Submission Video & Photo
        Gate::define("delete-submission_video_photo", function ($user) {
            return $user->hasAccess(["delete-submission_video_photo"]);
        });
        // Change Status Approved Submission Video & Photo
        Gate::define("change-status-approved-submission_video_photo", function ($user) {
            return $user->hasAccess(["change-status-approved-submission_video_photo"]);
        });
        // Change Status Rejected Submission Video & Photo
        Gate::define("change-status-rejected-submission_video_photo", function ($user) {
            return $user->hasAccess(["change-status-rejected-submission_video_photo"]);
        });

        //-- SUBMISSION Vide & Photo Detail --//
        // Add Submission Video & Photo Detail
        Gate::define("add-submission_video_photo_detail", function ($user) {
            return $user->hasAccess(["add-submission_video_photo_detail"]);
        });
        // View detail Submission Video & Photo Detail
        Gate::define("detail-submission_video_photo_detail", function ($user) {
            return $user->hasAccess(["detail-submission_video_photo_detail"]);
        });
        // Edit Submission Video & Photo Detail
        Gate::define("edit-submission_video_photo_detail", function ($user) {
            return $user->hasAccess(["edit-submission_video_photo_detail"]);
        });
        // Delete Submission Video & Photo Detail
        Gate::define("delete-submission_video_photo_detail", function ($user) {
            return $user->hasAccess(["delete-submission_video_photo_detail"]);
        });
        // Change Status Approved Submission Video & Photo Detail
        Gate::define("change-status-approved-submission_video_photo_detail", function ($user) {
            return $user->hasAccess(["change-status-approved-submission_video_photo_detail"]);
        });
        // Change Status Rejected Submission Video & Photo Detail
        Gate::define("change-status-rejected-submission_video_photo_detail", function ($user) {
            return $user->hasAccess(["change-status-rejected-submission_video_photo_detail"]);
        });

        //-- SPAREPART --//
        // Add sparepart
        Gate::define("add-sparepart", function ($user) {
            return $user->hasAccess(["add-sparepart"]);
        });
        // Browse sparepart
        Gate::define("browse-sparepart", function ($user) {
            return $user->hasAccess(["browse-sparepart"]);
        });
        // Edit sparepart
        Gate::define("edit-sparepart", function ($user) {
            return $user->hasAccess(["edit-sparepart"]);
        });
        // Delete sparepart
        Gate::define("delete-sparepart", function ($user) {
            return $user->hasAccess(["delete-sparepart"]);
        });

        //-- REFERENCE --//
        // Edit reference status
        Gate::define("edit-reference-status", function ($user) {
            return $user->hasAccess(["edit-reference-status"]);
        });

        //-- PERSONAL HOMECARE PRODUCT --//
        // Add
        Gate::define("add-phc-product", function ($user) {
            return $user->hasAccess(["add-phc-product"]);
        });
        // Browse
        Gate::define("browse-phc-product", function ($user) {
            return $user->hasAccess(["browse-phc-product"]);
        });
        // Edit
        Gate::define("edit-phc-product", function ($user) {
            return $user->hasAccess(["edit-phc-product"]);
        });
        // Delete
        Gate::define("delete-phc-product", function ($user) {
            return $user->hasAccess(["delete-phc-product"]);
        });

        //-- PERSONAL HOMECARE --//
        // Add
        Gate::define("add-personal-homecare", function ($user) {
            return $user->hasAccess(["add-personal-homecare"]);
        });
        // Browse
        Gate::define("browse-personal-homecare", function ($user) {
            return $user->hasAccess(["browse-personal-homecare"]);
        });
        // View
        Gate::define("detail-personal-homecare", function ($user) {
            return $user->hasAccess(["detail-personal-homecare"]);
        });
        // Edit
        Gate::define("edit-personal-homecare", function ($user) {
            return $user->hasAccess(["edit-personal-homecare"]);
        });
        // Delete
        Gate::define("delete-personal-homecare", function ($user) {
            return $user->hasAccess(["delete-personal-homecare"]);
        });
        // Approve status
        Gate::define("change-status-checkin-personalhomecare", function ($user) {
            return $user->hasAccess(["change-status-checkin-personalhomecare"]);
        });
        // Reject status
        Gate::define("change-status-checkout-personalhomecare", function ($user) {
            return $user->hasAccess(["change-status-checkout-personalhomecare"]);
        });
        // Verified status
        Gate::define("change-status-verified-personalhomecare", function ($user) {
            return $user->hasAccess(["change-status-verified-personalhomecare"]);
        });
        // Reschedule status
        Gate::define("acc-reschedule-personalhomecare", function ($user) {
            return $user->hasAccess(["acc-reschedule-personalhomecare"]);
        });
        // Extend status
        Gate::define("acc-extend-personalhomecare", function ($user) {
            return $user->hasAccess(["acc-extend-personalhomecare"]);
        });
        // Product status
        Gate::define("change-status-product-personalhomecare", function ($user) {
            return $user->hasAccess(["change-status-product-personalhomecare"]);
        });

        //-- PUBLIC HOMECARE --//
        // Add
        Gate::define("add-public-homecare", function ($user) {
            return $user->hasAccess(["add-public-homecare"]);
        });
        // Browse
        Gate::define("browse-public-homecare", function ($user) {
            return $user->hasAccess(["browse-public-homecare"]);
        });
        // View
        Gate::define("detail-public-homecare", function ($user) {
            return $user->hasAccess(["detail-public-homecare"]);
        });
        // Edit
        Gate::define("edit-public-homecare", function ($user) {
            return $user->hasAccess(["edit-public-homecare"]);
        });
        // Delete
        Gate::define("delete-public-homecare", function ($user) {
            return $user->hasAccess(["delete-public-homecare"]);
        });
        // Approve status
        Gate::define("change-status-checkin-publichomecare", function ($user) {
            return $user->hasAccess(["change-status-checkin-publichomecare"]);
        });
        // Reject status
        Gate::define("change-status-checkout-publichomecare", function ($user) {
            return $user->hasAccess(["change-status-checkout-publichomecare"]);
        });
        // Verified status
        Gate::define("change-status-verified-publichomecare", function ($user) {
            return $user->hasAccess(["change-status-verified-publichomecare"]);
        });
        // Product status
        Gate::define("change-status-product-publichomecare", function ($user) {
            return $user->hasAccess(["change-status-product-publichomecare"]);
        });
    }
}
