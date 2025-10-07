<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // ดึงข้อมูลจาก table ปัจจุบัน
        $users = DB::connection('mysql')->table('users')->get();

        foreach ($users as $user) {
            // แปลง stdClass เป็น array
            $userData = (array) $user;
            
            // hash password ใหม่เพื่อความปลอดภัย
            $userData['password'] = Hash::make($user->password);
            
            // ลบรหัส id เดิมเพื่อให้ auto-increment ทำงาน
            unset($userData['id']);
            
            // เพิ่มข้อมูลใหม่
            DB::table('users')->insert($userData);
        }
    }
}