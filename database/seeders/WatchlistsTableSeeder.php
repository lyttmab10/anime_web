<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WatchlistsTableSeeder extends Seeder
{
    public function run()
    {
        // ดึงข้อมูลจาก table ปัจจุบัน
        $watchlists = DB::connection('mysql')->table('watchlists')->get();

        foreach ($watchlists as $watchlist) {
            // แปลง stdClass เป็น array
            $watchlistData = (array) $watchlist;
            
            // ลบรหัส id เดิมเพื่อให้ auto-increment ทำงาน
            unset($watchlistData['id']);
            
            // เพิ่มข้อมูลใหม่
            DB::table('watchlists')->insert($watchlistData);
        }
    }
}