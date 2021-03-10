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

                // Submission (Head Admin)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,
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

                // Submission (Admin)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,
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

                // Submission (CSO)
                "add-submission" => true,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,
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

                // Submission (Branch)
                "add-submission" => true,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => true,
                "delete-submission" => true,
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

                // Submission (Area Manager)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => true,
                "delete-submission" => true,
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

                // Submission (Head Manager)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,
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

                // Submission (Admin Management)
                "add-submission" => false,
                "browse-submission" => true,
                "browse-reference" => true,
                "detail-submission" => true,
                "edit-submission" => false,
                "delete-submission" => false,
            ]),
        ]);
    }
}
