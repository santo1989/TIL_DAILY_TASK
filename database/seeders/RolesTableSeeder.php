<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //before running this seeder, make sure to off the gate key  in AuthServiceProvider.php

        //1
        Role::create([
            'name' => 'Admin'
        ]);

        //2
        Role::create([
            'name' => 'General'
        ]);

        //3
        Role::create([
            'name' => 'HR'
        ]);

        //4
        Role::create([
            'name' => 'HR_Supervisor'
        ]);


        //5
        Role::create([
            'name' => 'Welfare'
        ]);

        //6
        Role::create([
            'name' => 'Welfare_Supervisor'
        ]);

        //7
        Role::create([
            'name' => 'IE'
        ]);

        //8
        Role::create([
            'name' => 'IE_Supervisor'
        ]);

        //9
        Role::create([
            'name' => 'TIL_Administrator'
        ]);

        //10
        Role::create([
            'name' => 'TIL_Supervisor'
        ]);

        //11
        Role::create([
            'name' => 'Compliance'
        ]);

        //12
        Role::create([
            'name' => 'Compliance_Supervisor'
        ]);
 

        
    }
}
