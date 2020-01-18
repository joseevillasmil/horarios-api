<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'joseevillasmil@hotmail.es',
            'name' => 'Administrador',
            'email' => 'joseevillasmil@hotmail.es',
            'password' => bcrypt('21166465'),
            'perfil' => 'administrador'
        ]);
    }
}
