<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Role::query()->truncate();

        $admin = Role::create([
        	'name' => 'HEAD-ADMIN',
            'slug' => 'head-admin',
        	'permissions' => json_encode([
                //dashboard
                'show-dashboard' => true,

                //front end cms
                'browse-frontendcms' => true,

                //delivery order
                'add-deliveryorder' => true,
                'browse-deliveryorder' => true,
                'detail-deliveryorder' => true,
        		'edit-deliveryorder' => true,
                'delete-deliveryorder' => true,

                //Order
                'add-order' => true,
                'browse-order' => true,
                'detail-order' => true,
        		'edit-order' => true,
                'delete-order' => true,

                //Update Order Status
                'change-status_order'=> true,
                'change-status_order_process'=> true,
                'change-status_order_delivery' => true,
                'change-status_order_success' => true,
                'change-status_order_reject' => true,

                //Update Order Payment Status
                'change-status_payment' => true,
                'change-status_payment_verified' => true,
                'change-status_payment_rejected' => true,

                //Order Report
                'browse-order_report' => true,
                'browse-order_report_branch' => true,
                'browse-order_report_cso' => true,
                'browse-total_sale' => true,

                // Financial Routine
                "add-financial_routine" => true,
                "browse-financial_routine" => true,
                "detail-financial_routine" => true,
                "edit-financial_routine" => true,
                "delete-financial_routine" => true,

                //Home Service
                'add-home_service' => true,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => true,
                'delete-home_service' => true,
                'acc-view-home_service' => true,
                'acc-reschedule-home_service' => true,
                'acc-cancel-home_service' => true,

                //Area Home Service
                'browse-area_home_service' => true,

                //Absent Off
                'add-absent_off' => true,
                'browse-absent_off' => true,
                'detail-absent_off' => true,
                'edit-absent_off' => true,
                'delete-absent_off' => true,
                'browse-acc_absent_off' => true,
                'acc-view-spv_absent_off' => true,
                'acc-view-coor_absent_off' => true,
                'acc-absent_off' => true,
                'acc-spv_absent_off' => true,
                'acc-reject_spv_absent_off' => true,
                'acc-coor_absent_off' => true,
                'acc-reject_coor_absent_off' => true,

                //CSO
                'add-cso' => true,
                'browse-cso' => true,
        		'edit-cso' => true,
                'delete-cso' => true,

                //Branch
                'add-branch' => true,
                'browse-branch' => true,
        		'edit-branch' => true,
                'delete-branch' => true,

                //Category
        		'add-category' => true,
                'browse-category' => true,
        		'edit-category' => true,
                'delete-category' => true,

                //Product
        		'add-product' => true,
                'browse-product' => true,
        		'edit-product' => true,
                'delete-product' => true,

                //Promo
        		'add-promo' => true,
                'browse-promo' => true,
        		'edit-promo' => true,
                'delete-promo' => true,

                //report

                //user admin
                'add-user' => true,
                'browse-user' => true,
        		'edit-user' => true,
                'delete-user' => true,

                //app version
                'add-app' => true,
                'browse-app' => true,
        		'edit-app' => true,
                'delete-app' => true,

                // Acceptance (Head Admin)
                "add-acceptance" => true,
                "browse-acceptance" => true,
                "detail-acceptance" => true,
                "edit-acceptance" => true,
                "change-status-approval-acceptance" => true,
                "change-status-complete-acceptance" => true,
                "change-status-reject-acceptance" => true,
                "delete-acceptance" => true,

                // Upgrade (Head Admin)
                "browse-upgrade" => true,
                "detail-upgrade" => true,
                "edit-upgrade" => true,
                "change-status-approval-upgrade" => true,
                "change-status-process-upgrade" => true,
                "change-status-repaired-upgrade" => true,
                "change-status-complete-upgrade" => true,
                "change-status-reject-upgrade" => true,
                "delete-upgrade" => true,

                // Service (Head Admin)
                "add-service" => true,
                "browse-service" => true,
                "detail-service" => true,
                "edit-service" => true,
                "change-status-process-service" => true,
                "change-status-repaired-service" => true,
                "change-status-qc-service" => true,
                "change-status-delivery-service" => true,
                "change-status-complete-service" => true,
                "delete-service" => true,

                // Technician Schedule (Head Admin)
                "add-technician_schedule" => true,
                "browse-technician_schedule" => true,
                "detail-technician_schedule" => true,
                "edit-technician_schedule" => true,
                "delete-technician_schedule" => true,

                // Submission (Head Admin)
                "add-submission" => true,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => true,
                "delete-submission" => true,

                // Sparepart (Head Admin)
                "add-sparepart" => true,
                "browse-sparepart" => true,
                "edit-sparepart" => true,
                "delete-sparepart" => true,

                // Reference (Head Admin)
                "edit-reference-status" => true,

                // Personal Homecare Product (Head Admin)
                "add-phc-product" => true,
                "browse-phc-product" => true,
                "edit-phc-product" => true,
                "delete-phc-product" => true,

                // Personal Homecare (Head Admin)
                "add-personal-homecare" => false,
                "browse-personal-homecare" => true,
                "edit-personal-homecare" => false,
                "detail-personal-homecare" => true,
                "delete-personal-homecare" => false,
                "change-status-checkin-personalhomecare" => true,
                "change-status-checkout-personalhomecare" => true,
                "change-status-verified-personalhomecare" => true,
                "acc-reschedule-personalhomecare" => true,
                "acc-extend-personalhomecare" => true,
                "change-status-product-personalhomecare" => true,

                //Type Customer (Head Admin)
        		'add-type_customer' => true,
                'browse-type_customer' => true,
        		'edit-type_customer' => true,
                'delete-type_customer' => true,

                //Data Sourcing (Head Admin)
                'add-data_sourcing' => true,
                'browse-data_sourcing' => true,
                'edit-data_sourcing' => true,
                'delete-data_sourcing' => true,

                //Data Therapy (Head Admin)
                'add-data_therapy' => true,
                'browse-data_therapy' => true,
                'detail-data_therapy' => true,
                'edit-data_therapy' => true,
                'delete-data_therapy' => true,

                //Bank
                'add-bank' => true,
                'browse-bank' => true,
        		'edit-bank' => true,
                'delete-bank' => true,
            ]),
        ]);

        $admin = Role::create([
        	'name' => 'ADMIN',
            'slug' => 'admin',
        	'permissions' => json_encode([
                //dashboard
                'show-dashboard' => true,

                //front end cms
                'browse-frontendcms' => true,

                //delivery order
                'add-deliveryorder' => true,
                'browse-deliveryorder' => true,
                'detail-deliveryorder' => true,
        		'edit-deliveryorder' => true,
                'delete-deliveryorder' => true,

                //Order
                'add-order' => true,
                'browse-order' => true,
                'detail-order' => true,
        		'edit-order' => true,
                'delete-order' => true,

                //Update Order Status
                'change-status_order'=> false,
                'change-status_order_process'=> false,
                'change-status_order_delivery' => false,
                'change-status_order_success' => false,
                'change-status_order_reject' => false,

                //Update Order Payment Status
                'change-status_payment' => false,
                'change-status_payment_verified' => false,
                'change-status_payment_rejected' => false,

                //Order Report
                'browse-order_report' => false,
                'browse-order_report_branch' => false,
                'browse-order_report_cso' => false,
                'browse-total_sale' => false,

                // Financial Routine
                "add-financial_routine" => false,
                "browse-financial_routine" => false,
                "detail-financial_routine" => false,
                "edit-financial_routine" => false,
                "delete-financial_routine" => false,

                //Home Service
                'add-home_service' => true,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => true,
                'delete-home_service' => true,
                'acc-view-home_service' => true,
                'acc-reschedule-home_service' => false,
                'acc-cancel-home_service' => false,

                //Area Home Service
                'browse-area_home_service' => true,

                //Absent Off
                'add-absent_off' => true,
                'browse-absent_off' => true,
                'detail-absent_off' => true,
                'edit-absent_off' => true,
                'delete-absent_off' => true,
                'browse-acc_absent_off' => true,
                'acc-view-spv_absent_off' => false,
                'acc-view-coor_absent_off' => false,
                'acc-absent_off' => false,
                'acc-spv_absent_off' => false,
                'acc-reject_spv_absent_off' => false,
                'acc-coor_absent_off' => false,
                'acc-reject_coor_absent_off' => false,

                //CSO
                'add-cso' => true,
                'browse-cso' => true,
        		'edit-cso' => true,
                'delete-cso' => true,

                //Branch
                'add-branch' => true,
                'browse-branch' => true,
        		'edit-branch' => true,
                'delete-branch' => true,

                //Category
        		'add-category' => true,
                'browse-category' => true,
        		'edit-category' => true,
                'delete-category' => true,

                //Product
        		'add-product' => true,
                'browse-product' => true,
        		'edit-product' => true,
                'delete-product' => true,

                //Promo
        		'add-promo' => true,
                'browse-promo' => true,
        		'edit-promo' => true,
                'delete-promo' => true,

                //report

                //user admin
                'add-user' => false,
                'browse-user' => false,
        		'edit-user' => false,
                'delete-user' => false,

                //app version
                'add-app' => true,
                'browse-app' => true,
        		'edit-app' => true,
                'delete-app' => true,

                // Acceptance (Admin)
                "add-acceptance" => false,
                "browse-acceptance" => true,
                "detail-acceptance" => true,
                "edit-acceptance" => false,
                "change-status-approval-acceptance" => false,
                "change-status-complete-acceptance" => false,
                "change-status-reject-acceptance" => false,
                "delete-acceptance" => false,

                // Upgrade (Admin)
                "browse-upgrade" => false,
                "detail-upgrade" => false,
                "edit-upgrade" => false,
                "change-status-approval-upgrade" => false,
                "change-status-process-upgrade" => false,
                "change-status-repaired-upgrade" => false,
                "change-status-complete-upgrade" => false,
                "change-status-reject-upgrade" => false,
                "delete-upgrade" => false,

                // Service (Admin)
                "add-service" => false,
                "browse-service" => true,
                "detail-service" => true,
                "edit-service" => false,
                "change-status-process-service" => false,
                "change-status-repaired-service" => false,
                "change-status-qc-service" => false,
                "change-status-delivery-service" => false,
                "change-status-complete-service" => false,
                "delete-service" => false,

                // Technician Schedule (Admin)
                "add-technician_schedule" => true,
                "browse-technician_schedule" => true,
                "detail-technician_schedule" => true,
                "edit-technician_schedule" => true,
                "delete-technician_schedule" => true,

                // Submission (Admin)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,

                // Sparepart (Admin)
                "add-sparepart" => true,
                "browse-sparepart" => true,
                "edit-sparepart" => true,
                "delete-sparepart" => true,

                // Reference (Admin)
                "edit-reference-status" => true,

                // Personal Homecare Product (Admin)
                "add-phc-product" => false,
                "browse-phc-product" => true,
                "edit-phc-product" => false,
                "delete-phc-product" => false,

                // Personal Homecare (Admin)
                "add-personal-homecare" => false,
                "browse-personal-homecare" => true,
                "edit-personal-homecare" => false,
                "detail-personal-homecare" => true,
                "delete-personal-homecare" => false,
                "change-status-checkin-personalhomecare" => false,
                "change-status-checkout-personalhomecare" => false,
                "change-status-verified-personalhomecare" => false,
                "acc-reschedule-personalhomecare" => false,
                "acc-extend-personalhomecare" => false,
                "change-status-product-personalhomecare" => false,

                //Type Customer (Admin)
        		'add-type_customer' => true,
                'browse-type_customer' => true,
        		'edit-type_customer' => true,
                'delete-type_customer' => true,

                //Data Sourcing (Admin)
                'add-data_sourcing' => true,
                'browse-data_sourcing' => true,
                'edit-data_sourcing' => true,
                'delete-data_sourcing' => true,

                //Data Therapy (Admin)
                'add-data_therapy' => true,
                'detail-data_therapy' => true,
                'browse-data_therapy' => true,
                'edit-data_therapy' => true,
                'delete-data_therapy' => true,

                //Bank
                'add-bank' => false,
                'browse-bank' => false,
                'edit-bank' => false,
                'delete-bank' => false,                
        	]),
        ]);

        $admin = Role::create([
            'name' => 'CSO',
            'slug' => 'cso',
            'permissions' => json_encode([
                //dashboard
                'show-dashboard' => false,

                //front end cms
                'browse-frontendcms' => false,

                //delivery order
                'add-deliveryorder' => true,
                'browse-deliveryorder' => true,
                'detail-deliveryorder' => true,
                'edit-deliveryorder' => true,
                'delete-deliveryorder' => true,

                //Order
                'add-order' => true,
                'browse-order' => true,
                'detail-order' => true,
                'edit-order' => true,
                'delete-order' => true,

                //Update Order Status
                'change-status_order'=> false,
                'change-status_order_process'=> false,
                'change-status_order_delivery' => false,
                'change-status_order_success' => false,
                'change-status_order_reject' => false,

                //Update Order Payment Status
                'change-status_payment' => false,
                'change-status_payment_verified' => false,
                'change-status_payment_rejected' => false,

                //Order Report
                'browse-order_report' => false,
                'browse-order_report_branch' => false,
                'browse-order_report_cso' => false,
                'browse-total_sale' => false,

                // Financial Routine
                "add-financial_routine" => false,
                "browse-financial_routine" => false,
                "detail-financial_routine" => false,
                "edit-financial_routine" => false,
                "delete-financial_routine" => false,

                //Home Service
                'add-home_service' => true,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => true,
                'delete-home_service' => true,
                'acc-view-home_service' => false,
                'acc-reschedule-home_service' => false,
                'acc-cancel-home_service' => false,

                //Area Home Service
                'browse-area_home_service' => false,

                //Absent Off
                'add-absent_off' => true,
                'browse-absent_off' => true,
                'detail-absent_off' => true,
                'edit-absent_off' => true,
                'delete-absent_off' => true,
                'browse-acc_absent_off' => true,
                'acc-view-spv_absent_off' => false,
                'acc-view-coor_absent_off' => false,
                'acc-absent_off' => false,
                'acc-spv_absent_off' => false,
                'acc-reject_spv_absent_off' => false,
                'acc-coor_absent_off' => false,
                'acc-reject_coor_absent_off' => false,

                //CSO
                'add-cso' => false,
                'browse-cso' => false,
                'edit-cso' => false,
                'delete-cso' => false,

                //Branch
                'add-branch' => false,
                'browse-branch' => false,
                'edit-branch' => false,
                'delete-branch' => false,

                //Category
                'add-category' => false,
                'browse-category' => false,
                'edit-category' => false,
                'delete-category' => false,

                //Product
                'add-product' => false,
                'browse-product' => false,
                'edit-product' => false,
                'delete-product' => false,

                //Promo
                'add-promo' => false,
                'browse-promo' => false,
                'edit-promo' => false,
                'delete-promo' => false,

                //report

                //user admin
                'add-user' => false,
                'browse-user' => false,
                'edit-user' => false,
                'delete-user' => false,

                // Acceptance (CSO)
                "add-acceptance" => true,
                "browse-acceptance" => true,
                "detail-acceptance" => true,
                "edit-acceptance" => true,
                "change-status-approval-acceptance" => false,
                "change-status-complete-acceptance" => false,
                "change-status-reject-acceptance" => false,
                "delete-acceptance" => true,

                // Upgrade (CSO)
                "browse-upgrade" => false,
                "detail-upgrade" => false,
                "edit-upgrade" => false,
                "change-status-approval-upgrade" => false,
                "change-status-process-upgrade" => false,
                "change-status-repaired-upgrade" => false,
                "change-status-complete-upgrade" => false,
                "change-status-reject-upgrade" => false,
                "delete-upgrade" => false,

                // Service (CSO)
                "add-service" => false,
                "browse-service" => false,
                "detail-service" => false,
                "edit-service" => false,
                "change-status-process-service" => false,
                "change-status-repaired-service" => false,
                "change-status-qc-service" => false,
                "change-status-delivery-service" => false,
                "change-status-complete-service" => false,
                "delete-service" => false,

                // Technician Schedule (CSO)
                "add-technician_schedule" => false,
                "browse-technician_schedule" => false,
                "detail-technician_schedule" => false,
                "edit-technician_schedule" => false,
                "delete-technician_schedule" => false,

                // Submission (CSO)
                "add-submission" => true,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,

                // Sparepart (CSO)
                "add-sparepart" => false,
                "browse-sparepart" => false,
                "edit-sparepart" => false,
                "delete-sparepart" => false,

                // Reference (CSO)
                "edit-reference-status" => false,

                // Personal Homecare Product (CSO)
                "add-phc-product" => false,
                "browse-phc-product" => true,
                "edit-phc-product" => false,
                "delete-phc-product" => false,

                // Personal Homecare (CSO)
                "add-personal-homecare" => true,
                "browse-personal-homecare" => true,
                "edit-personal-homecare" => true,
                "detail-personal-homecare" => true,
                "delete-personal-homecare" => true,
                "change-status-checkin-personalhomecare" => false,
                "change-status-checkout-personalhomecare" => false,
                "change-status-verified-personalhomecare" => false,
                "acc-reschedule-personalhomecare" => false,
                "acc-extend-personalhomecare" => false,
                "change-status-product-personalhomecare" => false,

                //Type Customer (CSO)
        		'add-type_customer' => false,
                'browse-type_customer' => false,
        		'edit-type_customer' => false,
                'delete-type_customer' => false,

                //Data Sourcing (CSO)
                'add-data_sourcing' => false,
                'browse-data_sourcing' => false,
                'edit-data_sourcing' => false,
                'delete-data_sourcing' => false,

                //Data Therapy (CSO)
                'add-data_therapy' => false,
                'browse-data_therapy' => false,
                'detail-data_therapy' => false,
                'edit-data_therapy' => false,
                'delete-data_therapy' => false,

                //Bank
                'add-bank' => false,
                'browse-bank' => false,
                'edit-bank' => false,
                'delete-bank' => false,                
                
            ]),
        ]);

        $admin = Role::create([
            'name' => 'BRANCH',
            'slug' => 'branch',
            'permissions' => json_encode([
                //dashboard
                'show-dashboard' => false,

                //front end cms
                'browse-frontendcms' => false,

                //delivery order
                'add-deliveryorder' => true,
                'browse-deliveryorder' => true,
                'detail-deliveryorder' => true,
                'edit-deliveryorder' => true,
                'delete-deliveryorder' => true,

                //Order
                'add-order' => true,
                'browse-order' => true,
                'detail-order' => true,
                'edit-order' => true,
                'delete-order' => true,

                //Update Order Status
                'change-status_order'=> false,
                'change-status_order_process'=> false,
                'change-status_order_delivery' => false,
                'change-status_order_success' => false,
                'change-status_order_reject' => false,
                
                //Update Order Payment Status
                'change-status_payment' => false,
                'change-status_payment_verified' => false,
                'change-status_payment_rejected' => false,
                
                //Order Report
                'browse-order_report' => false,
                'browse-order_report_branch' => false,
                'browse-order_report_cso' => false,
                'browse-total_sale' => false,

                // Financial Routine
                "add-financial_routine" => false,
                "browse-financial_routine" => false,
                "detail-financial_routine" => false,
                "edit-financial_routine" => false,
                "delete-financial_routine" => false,

                //Home Service
                'add-home_service' => true,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => true,
                'delete-home_service' => true,
                'acc-view-home_service' => false,
                'acc-reschedule-home_service' => false,
                'acc-cancel-home_service' => false,

                //Area Home Service
                'browse-area_home_service' => false,

                //Absent Off
                'add-absent_off' => true,
                'browse-absent_off' => true,
                'detail-absent_off' => true,
                'edit-absent_off' => true,
                'delete-absent_off' => true,
                'browse-acc_absent_off' => true,
                'acc-view-spv_absent_off' => false,
                'acc-view-coor_absent_off' => false,
                'acc-absent_off' => false,
                'acc-spv_absent_off' => false,
                'acc-reject_spv_absent_off' => false,
                'acc-coor_absent_off' => false,
                'acc-reject_coor_absent_off' => false,

                //CSO
                'add-cso' => false,
                'browse-cso' => false,
                'edit-cso' => false,
                'delete-cso' => false,

                //Branch
                'add-branch' => false,
                'browse-branch' => false,
                'edit-branch' => false,
                'delete-branch' => false,

                //Category
                'add-category' => false,
                'browse-category' => false,
                'edit-category' => false,
                'delete-category' => false,

                //Product
                'add-product' => false,
                'browse-product' => false,
                'edit-product' => false,
                'delete-product' => false,

                //Promo
                'add-promo' => false,
                'browse-promo' => false,
                'edit-promo' => false,
                'delete-promo' => false,

                //report

                //user admin
                'add-user' => false,
                'browse-user' => false,
                'edit-user' => false,
                'delete-user' => false,

                // Acceptance (Branch)
                "add-acceptance" => true,
                "browse-acceptance" => true,
                "detail-acceptance" => true,
                "edit-acceptance" => true,
                "change-status-approval-acceptance" => false,
                "change-status-complete-acceptance" => false,
                "change-status-reject-acceptance" => false,
                "delete-acceptance" => true,

                // Upgrade (Branch)
                "browse-upgrade" => false,
                "detail-upgrade" => false,
                "edit-upgrade" => false,
                "change-status-approval-upgrade" => false,
                "change-status-process-upgrade" => false,
                "change-status-repaired-upgrade" => false,
                "change-status-complete-upgrade" => false,
                "change-status-reject-upgrade" => false,
                "delete-upgrade" => false,

                // Service (Branch)
                "add-service" => false,
                "browse-service" => false,
                "detail-service" => false,
                "edit-service" => false,
                "change-status-process-service" => false,
                "change-status-repaired-service" => false,
                "change-status-qc-service" => false,
                "change-status-delivery-service" => false,
                "change-status-complete-service" => false,
                "delete-service" => false,

                // Technician Schedule (Branch)
                "add-technician_schedule" => false,
                "browse-technician_schedule" => false,
                "detail-technician_schedule" => false,
                "edit-technician_schedule" => false,
                "delete-technician_schedule" => false,

                // Submission (Branch)
                "add-submission" => true,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => true,
                "delete-submission" => true,

                // Sparepart (Branch)
                "add-sparepart" => false,
                "browse-sparepart" => false,
                "edit-sparepart" => false,
                "delete-sparepart" => false,

                // Reference (Branch)
                "edit-reference-status" => false,

                // Personal Homecare Product (Branch)
                "add-phc-product" => false,
                "browse-phc-product" => true,
                "edit-phc-product" => false,
                "delete-phc-product" => false,

                // Personal Homecare (Branch)
                "add-personal-homecare" => true,
                "browse-personal-homecare" => true,
                "edit-personal-homecare" => true,
                "detail-personal-homecare" => true,
                "delete-personal-homecare" => true,
                "change-status-checkin-personalhomecare" => false,
                "change-status-checkout-personalhomecare" => false,
                "change-status-verified-personalhomecare" => false,
                "acc-reschedule-personalhomecare" => false,
                "acc-extend-personalhomecare" => false,
                "change-status-product-personalhomecare" => false,

                //Type Customer (Branch)
        		'add-type_customer' => false,
                'browse-type_customer' => false,
        		'edit-type_customer' => false,
                'delete-type_customer' => false,

                //Data Sourcing (Branch)
                'add-data_sourcing' => false,
                'browse-data_sourcing' => false,
                'edit-data_sourcing' => false,
                'delete-data_sourcing' => false,

                //Data Therapy (Branch)
                'add-data_therapy' => false,
                'browse-data_therapy' => false,
                'detail-data_therapy' => false,
                'edit-data_therapy' => false,
                'delete-data_therapy' => false,

                //Bank
                'add-bank' => false,
                'browse-bank' => false,
                'edit-bank' => false,
                'delete-bank' => false, 
            ]),
        ]);

        $admin = Role::create([
            'name' => 'AREA-MANAGER',
            'slug' => 'area-manager',
            'permissions' => json_encode([
                //dashboard
                'show-dashboard' => false,

                //front end cms
                'browse-frontendcms' => false,

                //delivery order
                'add-deliveryorder' => false,
                'browse-deliveryorder' => true,
                'detail-deliveryorder' => true,
                'edit-deliveryorder' => false,
                'delete-deliveryorder' => false,

                //Order
                'add-order' => false,
                'browse-order' => true,
                'detail-order' => true,
                'edit-order' => false,
                'delete-order' => false,

                //Update Order Status
                'change-status_order'=> false,
                'change-status_order_process'=> false,
                'change-status_order_delivery' => false,
                'change-status_order_success' => false,
                'change-status_order_reject' => false,

                //Update Order Payment Status
                'change-status_payment' => false,
                'change-status_payment_verified' => false,
                'change-status_payment_rejected' => false,

                //Order Report
                'browse-order_report' => false,
                'browse-order_report_branch' => false,
                'browse-order_report_cso' => false,
                'browse-total_sale' => false,

                // Financial Routine
                "add-financial_routine" => false,
                "browse-financial_routine" => false,
                "detail-financial_routine" => false,
                "edit-financial_routine" => false,
                "delete-financial_routine" => false,

                //Home Service
                'add-home_service' => false,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => false,
                'delete-home_service' => false,
                'acc-view-home_service' => true,
                'acc-reschedule-home_service' => true,
                'acc-cancel-home_service' => true,

                //Area Home Service
                'browse-area_home_service' => true,

                //Absent Off
                'add-absent_off' => true,
                'browse-absent_off' => true,
                'detail-absent_off' => true,
                'edit-absent_off' => true,
                'delete-absent_off' => true,
                'browse-acc_absent_off' => true,
                'acc-view-spv_absent_off' => false,
                'acc-view-coor_absent_off' => false,
                'acc-absent_off' => false,
                'acc-spv_absent_off' => false,
                'acc-reject_spv_absent_off' => false,
                'acc-coor_absent_off' => false,
                'acc-reject_coor_absent_off' => false,

                //CSO
                'add-cso' => false,
                'browse-cso' => false,
                'edit-cso' => false,
                'delete-cso' => false,

                //Branch
                'add-branch' => false,
                'browse-branch' => false,
                'edit-branch' => false,
                'delete-branch' => false,

                //Category
                'add-category' => false,
                'browse-category' => false,
                'edit-category' => false,
                'delete-category' => false,

                //Product
                'add-product' => false,
                'browse-product' => false,
                'edit-product' => false,
                'delete-product' => false,

                //Promo
                'add-promo' => false,
                'browse-promo' => false,
                'edit-promo' => false,
                'delete-promo' => false,

                //report

                //user admin
                'add-user' => false,
                'browse-user' => false,
                'edit-user' => false,
                'delete-user' => false,

                // Acceptance (Area Manager)
                "add-acceptance" => false,
                "browse-acceptance" => true,
                "detail-acceptance" => true,
                "edit-acceptance" => false,
                "change-status-approval-acceptance" => false,
                "change-status-complete-acceptance" => false,
                "change-status-reject-acceptance" => false,
                "delete-acceptance" => false,

                // Upgrade (Area Manager)
                "browse-upgrade" => false,
                "detail-upgrade" => false,
                "edit-upgrade" => false,
                "change-status-approval-upgrade" => false,
                "change-status-process-upgrade" => false,
                "change-status-repaired-upgrade" => false,
                "change-status-complete-upgrade" => false,
                "change-status-reject-upgrade" => false,
                "delete-upgrade" => false,

                // Service (Area Manager)
                "add-service" => false,
                "browse-service" => false,
                "detail-service" => false,
                "edit-service" => false,
                "change-status-process-service" => false,
                "change-status-repaired-service" => false,
                "change-status-qc-service" => false,
                "change-status-delivery-service" => false,
                "change-status-complete-service" => false,
                "delete-service" => false,

                // Technician Schedule (Area Manager)
                "add-technician_schedule" => false,
                "browse-technician_schedule" => false,
                "detail-technician_schedule" => false,
                "edit-technician_schedule" => false,
                "delete-technician_schedule" => false,

                // Submission (Area Manager)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => true,
                "delete-submission" => true,

                // Sparepart (Area Manager)
                "add-sparepart" => false,
                "browse-sparepart" => false,
                "edit-sparepart" => false,
                "delete-sparepart" => false,

                // Reference (Area Manager)
                "edit-reference-status" => false,

                // Personal Homecare Product (Area Manager)
                "add-phc-product" => false,
                "browse-phc-product" => true,
                "edit-phc-product" => false,
                "delete-phc-product" => false,

                // Personal Homecare (Area Manager)
                "add-personal-homecare" => false,
                "browse-personal-homecare" => true,
                "edit-personal-homecare" => false,
                "detail-personal-homecare" => true,
                "delete-personal-homecare" => false,
                "change-status-checkin-personalhomecare" => false,
                "change-status-checkout-personalhomecare" => false,
                "change-status-verified-personalhomecare" => true,
                "acc-reschedule-personalhomecare" => true,
                "acc-extend-personalhomecare" => true,
                "change-status-product-personalhomecare" => false,

                //Type Customer (Area Manager)
        		'add-type_customer' => false,
                'browse-type_customer' => false,
        		'edit-type_customer' => false,
                'delete-type_customer' => false,

                //Data Sourcing (Area Manager)
                'add-data_sourcing' => false,
                'browse-data_sourcing' => false,
                'edit-data_sourcing' => false,
                'delete-data_sourcing' => false,

                //Data Therapy (Area Manager)
                'add-data_therapy' => false,
                'browse-data_therapy' => false,
                'detail-data_therapy' => false,
                'edit-data_therapy' => false,
                'delete-data_therapy' => false,

                //Bank
                'add-bank' => false,
                'browse-bank' => false,
                'edit-bank' => false,
                'delete-bank' => false, 
            ]),
        ]);

        $admin = Role::create([
            'name' => 'HEAD-MANAGER',
            'slug' => 'head-manager',
            'permissions' => json_encode([
                //dashboard
                'show-dashboard' => false,

                //front end cms
                'browse-frontendcms' => false,

                //delivery order
                'add-deliveryorder' => false,
                'browse-deliveryorder' => true,
                'detail-deliveryorder' => true,
                'edit-deliveryorder' => false,
                'delete-deliveryorder' => false,

                //Order
                'add-order' => false,
                'browse-order' => true,
                'detail-order' => true,
                'edit-order' => false,
                'delete-order' => false,

                //Update Order Status
                'change-status_order'=> false,
                'change-status_order_process'=> false,
                'change-status_order_delivery' => false,
                'change-status_order_success' => false,
                'change-status_order_reject' => false,

                //Update Order Payment Status
                'change-status_payment' => false,
                'change-status_payment_verified' => false,
                'change-status_payment_rejected' => false,

                //Order Report
                'browse-order_report' => false,
                'browse-order_report_branch' => false,
                'browse-order_report_cso' => false,
                'browse-total_sale' => false,

                // Financial Routine
                "add-financial_routine" => false,
                "browse-financial_routine" => false,
                "detail-financial_routine" => false,
                "edit-financial_routine" => false,
                "delete-financial_routine" => false,

                //Home Service
                'add-home_service' => false,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => false,
                'delete-home_service' => false,
                'acc-view-home_service' => true,
                'acc-reschedule-home_service' => false,
                'acc-cancel-home_service' => false,

                //Area Home Service
                'browse-area_home_service' => true,

                //Absent Off
                'add-absent_off' => true,
                'browse-absent_off' => true,
                'detail-absent_off' => true,
                'edit-absent_off' => true,
                'delete-absent_off' => true,
                'browse-acc_absent_off' => true,
                'acc-view-spv_absent_off' => false,
                'acc-view-coor_absent_off' => false,
                'acc-absent_off' => false,
                'acc-spv_absent_off' => false,
                'acc-reject_spv_absent_off' => false,
                'acc-coor_absent_off' => false,
                'acc-reject_coor_absent_off' => false,

                //CSO
                'add-cso' => false,
                'browse-cso' => false,
                'edit-cso' => false,
                'delete-cso' => false,

                //Branch
                'add-branch' => false,
                'browse-branch' => false,
                'edit-branch' => false,
                'delete-branch' => false,

                //Category
                'add-category' => false,
                'browse-category' => false,
                'edit-category' => false,
                'delete-category' => false,

                //Product
                'add-product' => false,
                'browse-product' => false,
                'edit-product' => false,
                'delete-product' => false,

                //Promo
                'add-promo' => false,
                'browse-promo' => false,
                'edit-promo' => false,
                'delete-promo' => false,

                //report

                //user admin
                'add-user' => false,
                'browse-user' => false,
                'edit-user' => false,
                'delete-user' => false,

                // Acceptance (Head Manager)
                "add-acceptance" => false,
                "browse-acceptance" => true,
                "detail-acceptance" => true,
                "edit-acceptance" => false,
                "change-status-approval-acceptance" => true,
                "change-status-complete-acceptance" => true,
                "change-status-reject-acceptance" => true,
                "delete-acceptance" => false,

                // Upgrade (Head Manager)
                "browse-upgrade" => true,
                "detail-upgrade" => true,
                "edit-upgrade" => true,
                "change-status-approval-upgrade" => true,
                "change-status-process-upgrade" => false,
                "change-status-repaired-upgrade" => false,
                "change-status-complete-upgrade" => false,
                "change-status-reject-upgrade" => false,
                "delete-upgrade" => true,

                // Service (Head Manager)
                "add-service" => false,
                "browse-service" => true,
                "detail-service" => true,
                "edit-service" => false,
                "change-status-process-service" => false,
                "change-status-repaired-service" => false,
                "change-status-qc-service" => true,
                "change-status-delivery-service" => false,
                "change-status-complete-service" => false,
                "delete-service" => false,

                // Technician Schedule (Head Manager)
                "add-technician_schedule" => false,
                "browse-technician_schedule" => false,
                "detail-technician_schedule" => false,
                "edit-technician_schedule" => false,
                "delete-technician_schedule" => false,

                // Submission (Head Manager)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,

                // Sparepart (Head Manager)
                "add-sparepart" => false,
                "browse-sparepart" => false,
                "edit-sparepart" => false,
                "delete-sparepart" => false,

                // Reference (Head Manager)
                "edit-reference-status" => false,

                // Personal Homecare Product (Head Manager)
                "add-phc-product" => false,
                "browse-phc-product" => true,
                "edit-phc-product" => false,
                "delete-phc-product" => false,

                // Personal Homecare (Head Manager)
                "add-personal-homecare" => false,
                "browse-personal-homecare" => true,
                "edit-personal-homecare" => false,
                "detail-personal-homecare" => true,
                "delete-personal-homecare" => false,
                "change-status-checkin-personalhomecare" => false,
                "change-status-checkout-personalhomecare" => false,
                "change-status-verified-personalhomecare" => true,
                "acc-reschedule-personalhomecare" => true,
                "acc-extend-personalhomecare" => true,
                "change-status-product-personalhomecare" => false,

                //Type Customer (Head Manager)
        		'add-type_customer' => false,
                'browse-type_customer' => false,
        		'edit-type_customer' => false,
                'delete-type_customer' => false,

                //Data Sourcing (Head Manager)
                'add-data_sourcing' => false,
                'browse-data_sourcing' => false,
                'edit-data_sourcing' => false,
                'delete-data_sourcing' => false,

                //Data Therapy (Head Manager)
                'add-data_therapy' => false,
                'browse-data_therapy' => false,
                'detail-data_therapy' => false,
                'edit-data_therapy' => false,
                'delete-data_therapy' => false,

                //Bank
                'add-bank' => false,
                'browse-bank' => false,
                'edit-bank' => false,
                'delete-bank' => false, 
            ]),
        ]);

        $admin = Role::create([
            'name' => 'ADMIN-MANAGEMENT',
            'slug' => 'admin-management',
            'permissions' => json_encode([
                //dashboard
                'show-dashboard' => false,

                //front end cms
                'browse-frontendcms' => false,

                //delivery order
                'add-deliveryorder' => false,
                'browse-deliveryorder' => true,
                'detail-deliveryorder' => true,
                'edit-deliveryorder' => false,
                'delete-deliveryorder' => false,

                //Order
                'add-order' => false,
                'browse-order' => true,
                'detail-order' => true,
                'edit-order' => false,
                'delete-order' => false,

                //Update Order Status
                'change-status_order'=> false,
                'change-status_order_process'=> false,
                'change-status_order_delivery' => false,
                'change-status_order_success' => false,
                'change-status_order_reject' => false,

                //Update Order Payment Status
                'change-status_payment' => false,
                'change-status_payment_verified' => false,
                'change-status_payment_rejected' => false,

                //Order Report
                'browse-order_report' => false,
                'browse-order_report_branch' => false,
                'browse-order_report_cso' => false,
                'browse-total_sale' => false,

                // Financial Routine
                "add-financial_routine" => false,
                "browse-financial_routine" => false,
                "detail-financial_routine" => false,
                "edit-financial_routine" => false,
                "delete-financial_routine" => false,

                //Home Service
                'add-home_service' => false,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => false,
                'delete-home_service' => false,
                'acc-view-home_service' => true,
                'acc-reschedule-home_service' => false,
                'acc-cancel-home_service' => false,

                //Area Home Service
                'browse-area_home_service' => true,

                //Absent Off
                'add-absent_off' => true,
                'browse-absent_off' => true,
                'detail-absent_off' => true,
                'edit-absent_off' => true,
                'delete-absent_off' => true,
                'browse-acc_absent_off' => true,
                'acc-view-spv_absent_off' => false,
                'acc-view-coor_absent_off' => false,
                'acc-absent_off' => false,
                'acc-spv_absent_off' => false,
                'acc-reject_spv_absent_off' => false,
                'acc-coor_absent_off' => false,
                'acc-reject_coor_absent_off' => false,

                //CSO
                'add-cso' => false,
                'browse-cso' => false,
                'edit-cso' => false,
                'delete-cso' => false,

                //Branch
                'add-branch' => false,
                'browse-branch' => false,
                'edit-branch' => false,
                'delete-branch' => false,

                //Category
                'add-category' => false,
                'browse-category' => false,
                'edit-category' => false,
                'delete-category' => false,

                //Product
                'add-product' => false,
                'browse-product' => false,
                'edit-product' => false,
                'delete-product' => false,

                //Promo
                'add-promo' => false,
                'browse-promo' => false,
                'edit-promo' => false,
                'delete-promo' => false,

                //report

                //user admin
                'add-user' => false,
                'browse-user' => false,
                'edit-user' => false,
                'delete-user' => false,

                //app version
                'add-app' => true,
                'browse-app' => true,
        		'edit-app' => true,
                'delete-app' => true,

                // Acceptance (Admin Management)
                "add-acceptance" => false,
                "browse-acceptance" => true,
                "detail-acceptance" => true,
                "edit-acceptance" => false,
                "change-status-approval-acceptance" => false,
                "change-status-complete-acceptance" => false,
                "change-status-reject-acceptance" => false,
                "delete-acceptance" => false,

                // Upgrade (Admin Management)
                "browse-upgrade" => true,
                "detail-upgrade" => true,
                "edit-upgrade" => false,
                "change-status-approval-upgrade" => true,
                "change-status-process-upgrade" => true,
                "change-status-repaired-upgrade" => false,
                "change-status-complete-upgrade" => true,
                "change-status-reject-upgrade" => true,
                "delete-upgrade" => true,

                // Service (Admin Management)
                "add-service" => true,
                "browse-service" => true,
                "detail-service" => true,
                "edit-service" => true,
                "change-status-process-service" => true,
                "change-status-repaired-service" => true,
                "change-status-qc-service" => true,
                "change-status-delivery-service" => true,
                "change-status-complete-service" => true,
                "delete-service" => true,

                // Technician Schedule (Admin Management)
                "add-technician_schedule" => true,
                "browse-technician_schedule" => true,
                "detail-technician_schedule" => true,
                "edit-technician_schedule" => true,
                "delete-technician_schedule" => true,

                // Submission (Admin Management)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,

                // Sparepart (Admin Management)
                "add-sparepart" => true,
                "browse-sparepart" => true,
                "edit-sparepart" => true,
                "delete-sparepart" => true,

                // Reference (Admin Management)
                "edit-reference-status" => false,

                // Personal Homecare Product (Admin Management)
                "add-phc-product" => true,
                "browse-phc-product" => true,
                "edit-phc-product" => true,
                "delete-phc-product" => true,

                // Personal Homecare (Admin Management)
                "add-personal-homecare" => false,
                "browse-personal-homecare" => true,
                "edit-personal-homecare" => true,
                "detail-personal-homecare" => true,
                "delete-personal-homecare" => true,
                "change-status-checkin-personalhomecare" => false,
                "change-status-checkout-personalhomecare" => false,
                "change-status-verified-personalhomecare" => true,
                "acc-reschedule-personalhomecare" => true,
                "acc-extend-personalhomecare" => true,
                "change-status-product-personalhomecare" => false,

                //Type Customer (Admin Management)
        		'add-type_customer' => false,
                'browse-type_customer' => false,
        		'edit-type_customer' => false,
                'delete-type_customer' => false,

                //Data Sourcing (Admin Management)
                'add-data_sourcing' => false,
                'browse-data_sourcing' => false,
                'edit-data_sourcing' => false,
                'delete-data_sourcing' => false,

                //Data Therapy (Admin Management)
                'add-data_therapy' => false,
                'browse-data_therapy' => false,
                'detail-data_therapy' => false,
                'edit-data_therapy' => false,
                'delete-data_therapy' => false,

                //Bank
                'add-bank' => false,
                'browse-bank' => false,
                'edit-bank' => false,
                'delete-bank' => false, 
            ]),
        ]);
    }
}
