<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

    	//Creamos el usuario Admin General. En el API si se ha ejecutado el AdminSeeder el usuario estará creado.
    	DB::table('users')->delete(); 
		DB::table('users')->insert([
			'email' => 'admin@admin.admin',
			'password'=>bcrypt('Admin'),
			'category'=>'1',
			'password_modificada'=>true
		]);


    }
}
