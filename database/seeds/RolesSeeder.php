<?php

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

                //Home Service
                'add-home_service' => true,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => true,
                'delete-home_service' => true,

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

                //Home Service
                'add-home_service' => true,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => true,
                'delete-home_service' => true,

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

                //Home Service
                'add-home_service' => true,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => true,
                'delete-home_service' => true,

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

                //Home Service
                'add-home_service' => true,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => true,
                'delete-home_service' => true,

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

                //Home Service
                'add-home_service' => false,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => false,
                'delete-home_service' => false,

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

                //Home Service
                'add-home_service' => false,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => false,
                'delete-home_service' => false,

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

                //Home Service
                'add-home_service' => false,
                'browse-home_service' => true,
                'detail-home_service' => true,
                'edit-home_service' => false,
                'delete-home_service' => false,

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
            ]),
        ]);
    }
}
