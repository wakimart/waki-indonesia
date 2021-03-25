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
    }
}
