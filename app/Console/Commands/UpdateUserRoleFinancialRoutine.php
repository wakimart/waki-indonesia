<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class UpdateUserRoleFinancialRoutine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:financial-routine-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'for update user role (financial routine)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();

        foreach($users as $user){
            $permissions = json_decode($user->permissions, true);
            $permissions['browse-total_sale'] = false;
            $permissions['add-financial_routine'] = false;
            $permissions['browse-financial_routine'] = false;
            $permissions['detail-financial_routine'] = false;
            $permissions['edit-financial_routine'] = false;
            $permissions['delete-financial_routine'] = false;
            $user->permissions = json_encode($permissions);
            $user->update();
            $bar->advance();
        }

        $bar->finish();
        echo "\noperation completed.\n";
    }
}
