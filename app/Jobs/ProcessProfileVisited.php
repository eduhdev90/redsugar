<?php

namespace App\Jobs;

use App\Mail\VisitedProfileEmail;
use App\Services\ProfileAvailableService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProcessProfileVisited implements ShouldQueue
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
        Log::info('[ProcessProfileVisited] Buscando perfil visitado com id {id}', ['id' => $this->data['user_id']]);
        $profile = $service->getById($this->data['user_id']);

        if (empty($profile)) {
            Log::notice('[ProcessProfileVisited] Perfil visitado com id {id} não encontrado', ['id' => $this->data['user_id']]);
            throw new HttpException(404, "[WARNING][ProcessProfileVisited] - Usuário com ID " . $this->data['user_id'] . " não encontrado");
        }

        Log::info(
            '[ProcessProfileVisited] Enviando email para perfil visitado com id {id} com total de {total} novas visitas',
            ['id' => $this->data['user_id'], 'total' => $this->data['total']]
        );

        Mail::to($profile->email)->send(new VisitedProfileEmail([
            'name' => $profile->name,
            'qtd' => $this->data['total']
        ]));

        Log::info(
            '[ProcessProfileVisited] Email para perfil visitado com id {id} enviado com sucesso',
            ['id' => $this->data['user_id']]
        );
    }
}
