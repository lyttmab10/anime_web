<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anime;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing users and animes to create reviews for
        $users = User::limit(5)->get();
        $animes = Anime::limit(10)->get();
        
        foreach ($users as $user) {
            foreach ($animes as $anime) {
                // Randomly decide whether this user will review this anime (about 60% chance)
                if (rand(1, 10) <= 6) {
                    Review::create([
                        'user_id' => $user->id,
                        'anime_id' => $anime->id,
                        'rating' => rand(1, 5),
                        'review' => $this->generateRandomReview(),
                        'likes' => rand(0, 20),
                        'dislikes' => rand(0, 5),
                    ]);
                }
            }
        }
    }
    
    private function generateRandomReview()
    {
        $reviews = [
            "อนิเมะเรื่องนี้ดีมากครับ ชอบเลย",
            "ดูแล้วรู้สึกประทับใจมาก ต้องกลับไปดูอีก",
            "ภาพสวย เรื่องน่าสนใจมาก",
            "เสียงพากย์ดีมาก ชอบเลย",
            "เนื้อเรื่องลุ้นระทึกตลอดทั้งเรื่อง",
            "ตัวละครน่ารัก ชอบมากเลย",
            "ไม่คิดว่าจะชอบขนาดนี้",
            "เสียงดนตรีประกอบเพราะมาก",
            "ดูแล้วรู้สึกดีขึ้นทั้งวัน",
            "มีความตื่นเต้นตลอดเรื่อง",
            "ตัวละครมีความลึกซึ้ง ดีมาก",
            "แอนิเมชั่นคุณภาพดี",
            "ดูแล้วรู้สึกมีแรงบันดาลใจ",
            "ไม่ผิดหวังเลยกับเรื่องนี้",
            "ดูทีเดียวก็ติดใจ อยากดูอีก",
        ];
        
        return $reviews[array_rand($reviews)];
    }
}
