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
	        'phone'    => '08989898989',
	        'password' => Hash::make('root@123'),
	        'status'    => 0,
	        'ip_address'    => '127.0.0.1',
            'is_active' => 1
        ));
        User::create(array(
            'name'     => 'Publisher',
            'email'    => 'publisher@publisher.com',
            'phone'    => '08989898989',
            'password' => Hash::make('publisher@123'),
            'status'    => 1,
            'ip_address'    => '127.0.0.1',
            'is_active' => 1
        ));
        User::create(array(
            'name'     => 'Editor',
            'email'    => 'editor@editor.com',
            'phone'    => '08989898989',
            'password' => Hash::make('editor@123'),
            'status'    => 2,
            'ip_address' => '127.0.0.1',
            'is_active' => 1
        ));
        User::create(array(
            'name'     => 'Client',
            'email'    => 'client@client.com',
            'phone'    => '08989898989',
            'password' => Hash::make('client@123'),
            'status'    => 3,
            'ip_address' => '127.0.0.1',
            'is_active' => 1
        ));
    }
}
