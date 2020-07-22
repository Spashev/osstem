<?php

namespace App\Console\Commands;

use App\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:superuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create super user "login:admin@gmail.com, password:1q2w3e4r"';

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
     * @return int
     */
    public function handle()
    {
        try{
            $user = User::create([
                'email' => 'admin@gmail.com',
                'name' => 'Super User',
                'password' => Hash::make('1q2w3e4r')
            ]);

            $user->assignRole('admin');
            $this->info('Super user created successfully!');
        }
        catch (Exception $e){
            $this->error('Superuser alredy created!');
        }
        return 0;
    }
}
