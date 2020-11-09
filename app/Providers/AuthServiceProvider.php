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
    }
}
