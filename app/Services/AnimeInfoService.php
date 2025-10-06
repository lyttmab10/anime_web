<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AnimeInfoService
{
    private $baseUrl = 'https://api.jikan.moe/v4';

    public function getAnimeInfo($title)
    {
        try {
            // Search for anime by title
            $response = Http::get("{$this->baseUrl}/anime", [
                'q' => $title,
                'limit' => 1
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['data']) && !empty($data['data'])) {
                    $anime = $data['data'][0];
                    
                    return [
                        'title' => $anime['title'] ?? $title,
                        'studio' => $this->extractStudio($anime),
                        'episodes' => $anime['episodes'] ?? null,
                        'status' => $this->mapStatus($anime['status'] ?? null),
                        'season' => $anime['season'] ?? null,
                        'release_date' => $anime['aired']['from'] ? substr($anime['aired']['from'], 0, 10) : null,
                    ];
                }
            }
            
            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching anime info: ' . $e->getMessage());
            return null;
        }
    }

    private function extractStudio($anime)
    {
        if (isset($anime['studios']) && !empty($anime['studios'])) {
            $studios = array_map(function($studio) {
                return $studio['name'];
            }, $anime['studios']);
            
            return implode(', ', $studios);
        }
        
        return null;
    }

    private function mapStatus($status)
    {
        if (!$status) return null;

        $statusMap = [
            'Not yet aired' => 'not_yet_aired',
            'Currently Airing' => 'currently_airing',
            'Finished Airing' => 'finished_airing',
        ];

        $key = array_search($status, [
            'Not yet aired' => 'Not yet aired',
            'Currently Airing' => 'Currently Airing',
            'Finished Airing' => 'Finished Airing',
        ]);

        return $statusMap[$key] ?? 'finished_airing';
    }
}