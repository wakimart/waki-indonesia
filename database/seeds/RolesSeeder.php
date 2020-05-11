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
        	]),
        ]);
    }
}
