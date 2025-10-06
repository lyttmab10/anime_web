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
        // Get all users and animes to create reviews for
        $users = User::all();
        $animes = Anime::all();
        
        foreach ($users as $user) {
            foreach ($animes as $anime) {
                // Randomly decide whether this user will review this anime (about 60% chance)
                if (rand(1, 10) <= 6) {
                    // Generate a rating that's more realistic based on the anime's rating
                    $rating = $this->generateRealisticRating($anime->rating);
                    
                    Review::create([
                        'user_id' => $user->id,
                        'anime_id' => $anime->id,
                        'rating' => $rating,
                        'review' => $this->generateRandomReview($rating),
                        'likes' => rand(0, 20),
                        'dislikes' => rand(0, 5),
                    ]);
                }
            }
        }
    }
    
    private function generateRealisticRating($animeRating)
    {
        // Adjust the rating based on the anime's overall rating
        // Higher-rated animes will tend to get higher ratings from users
        if ($animeRating >= 9.0) {
            // For highly-rated animes, most ratings will be 4-5 stars
            return rand(1, 10) > 2 ? rand(4, 5) : rand(2, 4);
        } elseif ($animeRating >= 8.0) {
            // For well-rated animes, ratings will be 3-5 stars
            return rand(1, 10) > 3 ? rand(3, 5) : rand(1, 4);
        } elseif ($animeRating >= 7.0) {
            // For average-rated animes, ratings will be more evenly distributed
            return rand(2, 5);
        } else {
            // For lower-rated animes, ratings will tend to be lower
            return rand(1, 10) > 4 ? rand(1, 3) : rand(2, 4);
        }
    }
    
    private function generateRandomReview($rating)
    {
        $positiveReviews = [
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
        
        $neutralReviews = [
            "ดูได้เรื่อยๆ ไม่แย่ แต่ก็ไม่ดีเลิศ",
            "เรื่องนี้ก็โอเคนะ ดูเพลินๆ",
            "ดูได้ไม่เบื่อ แต่ไม่ประทับใจมาก",
            "กลางๆ ดีแต่ไม่เด่น",
            "เรื่องนี้ก็ใช้ได้เลย",
            "ดูสนุกดี แต่ยังมีจุดที่ต้องปรับปรุง",
            "ไม่แย่ แต่ก็ไม่ถึงกับดีมาก",
            "ดูได้เพลินๆ ไม่มีอะไรน่าติ",
            "เนื้อเรื่องกลางๆ ไปได้เรื่อยๆ",
            "ดูจบแล้ว ไม่มีอะไรพิเศษ",
        ];
        
        $negativeReviews = [
            "ไม่ค่อยชอบเท่าไหร่",
            "เรื่องนี้ไม่เข้ากับเรามากนัก",
            "ดูแล้วไม่ประทับใจเลย",
            "เสียดายเวลาที่ดูเรื่องนี้",
            "ไม่แนะนำให้ดู",
            "เนื้อเรื่องไม่น่าสนใจ",
            "ดูไม่รู้เรื่องเลย",
            "ตัวละครไม่น่าสนใจ",
            "ภาพไม่สวยเลย",
            "เสียงไม่เพราะเลย",
        ];
        
        // Select review based on rating
        if ($rating >= 4) {
            return $positiveReviews[array_rand($positiveReviews)];
        } elseif ($rating == 3) {
            return $neutralReviews[array_rand($neutralReviews)];
        } else {
            return $negativeReviews[array_rand($negativeReviews)];
        }
    }
}
