<?php

namespace App\Jobs;

use App\Services\FavoriteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessFavorites implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(FavoriteService $service): void
    {
        Log::info('[ProcessFavorites] Buscando perfis favoritados');
        $favorites = $service->getFavoritedYesterday();

        if (empty($favorites)) {
            Log::info('[ProcessFavorites] Nenhum perfil favoritado encontrado');
            return;
        }

        foreach ($favorites as $favorite) {
            $data = [];
            $data['user_id'] = $favorite->favorited_id;
            $data['total'] = $favorite->total;

            Log::info(
                '[ProcessFavorites] Enfileirando perfil favoritado com id {id}',
                ['id' => $data['user_id']]
            );
            ProcessUserFavorited::dispatch($data);
        }
    }
}
