<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{

    public function run()
    {
        User::create([
            'role_id' => 1,
            'name' => 'Admin',
            'emp_id' => '0001',
            'email' => 'admin@ntg.com.bd',
            'email_verified_at' => now(),
            'picture' => 'avatar.png',
            'dob' => '1989-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '9',
            'designation_id' => '10',
            'password' => bcrypt('12345678'),
            'password_text' => '12345678', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);
        User::create([
            'role_id' => 2,
            'name' => 'Bashar',
            'emp_id' => '00422',
            'email' => 'bashar@ntg.com.bd',
            'email_verified_at' => now(),
            'picture' => 'avatar.png',
            'dob' => '1984-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '6',
            'mobile' => '01810157700',
            'password' => bcrypt('bashar@078'),
            'password_text' => 'bashar@078', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]); 

        User::create([
            'role_id' => 13,
            'name' => 'Md.Mustafizur Rahman',
            'emp_id' => 'til-admin01',
            'email' => 'mustafiz@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role_id' => 5,
            'name' => 'Ela',
            'emp_id' => 'til-Wel01',
            'email' => 'ela@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role_id' => 5,
            'name' => 'Chobi',
            'emp_id' => 'til-Wel02',
            'email' => 'chobi@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role_id' => 5,
            'name' => 'Sabina',
            'emp_id' => 'til-Wel03',
            'email' => 'sabina@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role_id' => 5,
            'name' => 'Munni',
            'emp_id' => 'til-Wel04',
            'email' => 'munni@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role_id' => 9,
            'name' => 'Fazlul Haq Khan',
            'emp_id' => 'til-admin02',
            'email' => 'fazlul@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role_id' => 17,
            'name' => 'Shawan Elahi',
            'emp_id' => 'til-time_section_01',
            'email' => 'shawan@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role_id' => 3,
            'name' => 'MZ Kabir',
            'emp_id' => 'til-admin03',
            'email' => 'kabir@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role_id' => 11,
            'name' => 'Md.Zakaria Hossain',
            'emp_id' => 'til-compliance01',
            'email' => 'zakaria@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);



        User::create([
            'role_id' => 3,
            'name' => 'Md. Hedayet ullah Shamol',
            'emp_id' => 'TIL-admin-supervisor01',
            'email' => 'shamol@ntg.com.bd',
            'email_verified_at' => now(),
            'picture' => 'avatar.png',
            'dob' => '1989-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '9',
            'designation_id' => '10',
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        //Abdullah al Mamun & Anwar Hossain (IE)
        User::create([
            'role_id' => 7,
            'name' => 'Abdullah al Mamun',
            'emp_id' => 'til-ie01',
            'email' => 'abdullah@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);
        User::create([
            'role_id' => 7,
            'name' => 'Anwar Hossain',
            'emp_id' => 'til-ie02',
            'email' => 'anwar@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        // Md. Ashiqur Rahman (Store)
        User::create([
            'role_id' => 15,
            'name' => 'Md. Ashiqur Rahman',
            'emp_id' => 'til-store01',
            'email' => 'ashiq@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);

        // Md.Shakhawat Hossain (HR, Admin & Compliance)
        User::create([
            'role_id' => 4,
            'name' => 'Md.Shakhawat Hossain',
            'emp_id' => 'til-hr01',
            'email' => 'shakhawat@ntg.com.bd',
            'picture' => 'avatar.png',
            'dob' => '1986-02-03',
            'joining_date' => '2019-02-03',
            'division_id' => '1',
            'company_id' => '1',
            'department_id' => '15',
            'designation_id' => '11',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'password_text' => '123', // This is for the user to login with password
            'remember_token' => Str::random(10),
        ]);
    }
}
