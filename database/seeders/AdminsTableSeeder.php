<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123456');
    
        $admin = new Admin;
        $admin->name = 'Amit Gupta';
        $admin->role = 'admin';
        $admin->mobile = '9800000000';
        $admin->email = 'admin@admin.com';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();

    
        $admin = new Admin;
        $admin->name = 'SOBAKIN Ekpe Christian';
        $admin->role = 'admin';
        $admin->mobile = '9800000001';
        $admin->email = 'christ@christ.com';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();

        $admin = new Admin;
        $admin->name = 'SOBAKIN Ekpe Christian';
        $admin->role = 'admin';
        $admin->mobile = '9800000001';
        $admin->email = 'christ2@christ2.com';
        $admin->password = $password;
        $admin->status = 0;
        $admin->save();

        $admin = new Admin;
        $admin->name = 'Steve';
        $admin->role = 'subadmin';
        $admin->mobile = '9700000000';
        $admin->email = 'steve@admin.com';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();  

        $admin = new Admin;
        $admin->name = 'John';
        $admin->role = 'subadmin';
        $admin->mobile = '9600000000';
        $admin->email = 'john@admin.com';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();


    }
}
