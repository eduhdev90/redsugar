<?php

namespace App\Jobs;

use App\Mail\FavoritedUsersEmail;
use App\Services\ProfileAvailableService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessUserFavorited implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ProfileAvailableService $service): void
    {
        Log::info('[ProcessUserFavorited] Buscando perfil favoritado com id {id}', ['id' => $this->data['user_id']]);
        $profile = $service->getById($this->data['user_id']);

        if (empty($profile)) {
            Log::notice('Perfil favoritado com id {id} não encontrado', ['id' => $this->data['user_id']]);
            throw new ModelNotFoundException("[WARNING][ProcessUserFavorited] - Usuário com ID " . $this->data['user_id'] . " não encontrado");
        }

        Log::info(
            '[ProcessUserFavorited] Enviando email para perfil favoritado com id {id} com total de {total} novos usuários',
            ['id' => $this->data['user_id'], 'total' => $this->data['total']]
        );

        Mail::to($profile->email)->send(new FavoritedUsersEmail([
            'name' => $profile->name,
            'qtd' => $this->data['total']
        ]));

        Log::info(
            '[ProcessUserFavorited] Email para perfil favoritado com id {id} enviado com sucesso',
            ['id' => $this->data['user_id']]
        );
    }
}
