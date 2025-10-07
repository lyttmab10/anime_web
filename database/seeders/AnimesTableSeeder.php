<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimesTableSeeder extends Seeder
{
    public function run()
    {
        // ดึงข้อมูลจาก table ปัจจุบัน
        $animes = DB::connection('mysql')->table('animes')->get();

        foreach ($animes as $anime) {
            // แปลง stdClass เป็น array
            $animeData = (array) $anime;
            
            // ลบรหัส id เดิมเพื่อให้ auto-increment ทำงาน
            unset($animeData['id']);
            
            // เพิ่มข้อมูลใหม่
            DB::table('animes')->insert($animeData);
        }
    }
}