<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	User::create(array(
	        'name'     => 'Administrator',
	        'email'    => 'root@root.com',
	        'password' => Hash::make('root@123'),
            'is_active' => 1
        ));
        
    }
}
