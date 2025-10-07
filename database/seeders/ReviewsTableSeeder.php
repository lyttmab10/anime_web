<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    public function run()
    {
        // ดึงข้อมูลจาก table ปัจจุบัน
        $reviews = DB::connection('mysql')->table('reviews')->get();

        foreach ($reviews as $review) {
            // แปลง stdClass เป็น array
            $reviewData = (array) $review;
            
            // ลบรหัส id เดิมเพื่อให้ auto-increment ทำงาน
            unset($reviewData['id']);
            
            // เพิ่มข้อมูลใหม่
            DB::table('reviews')->insert($reviewData);
        }
    }
}