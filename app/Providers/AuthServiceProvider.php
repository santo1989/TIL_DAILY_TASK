<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();



        // $roles = [
        //     'Admin','General','HR','HR_Supervisor','Welfare','Welfare_Supervisor','IE','IE_Supervisor','TIL_Administrator','TIL_Supervisor' ,'Compliance','Compliance_Supervisor','Payroll_Admin','Payroll_Supervisor' ,'Store','Store_Supervisor','Time_Section','Time_Section_Supervisor'
        // ];
        // foreach ($roles as $index => $roleName) {
        //     Gate::define($roleName, function (User $user) use ($index) {
        //         return $user->role_id == ($index + 1);
        //     });
        // }

        
        // Define gates based on role IDs
        Gate::define('Admin', function (User $user) {
            return $user->role_id == 1;
        });

        Gate::define('General', function (User $user) {
            return $user->role_id == 2;
        });

        Gate::define('HR', function (User $user) {
            return $user->role_id == 3;
        });

        Gate::define('HR_Supervisor', function (User $user) {
            return $user->role_id == 4;
        });

        Gate::define('Welfare', function (User $user) {
            return $user->role_id == 5;
        });

        Gate::define('Welfare_Supervisor', function (User $user) {
            return $user->role_id == 6;
        });

        Gate::define('IE', function (User $user) {
            return $user->role_id == 7;
        });

        Gate::define('IE_Supervisor', function (User $user) {
            return $user->role_id == 8;
        });

        Gate::define('TIL_Administrator', function (User $user) {
            return $user->role_id == 9;
        });

        Gate::define('TIL_Supervisor', function (User $user) {
            return $user->role_id == 10;
        });

        Gate::define('Compliance', function (User $user) {
            return $user->role_id == 11;
        });

        Gate::define('Compliance_Supervisor', function (User $user) {
            return $user->role_id == 12;
        });

        Gate::define('Payroll_Admin', function (User $user) {
            return $user->role_id == 13;
        });

        Gate::define('Payroll_Supervisor', function (User $user) {
            return $user->role_id == 14;
        });

        Gate::define('Store', function (User $user) {
            return $user->role_id == 15;
        });

        Gate::define('Store_Supervisor', function (User $user) {
            return $user->role_id == 16;
        });

        Gate::define('Time_Section', function (User $user) {
            return $user->role_id == 17;
        });

        Gate::define('Time_Section_Supervisor', function (User $user) {
            return $user->role_id == 18;
        });
    
        
     }
}
