<?php

namespace App\Jobs;

use App\Services\ProfileAvailableService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessVisits implements ShouldQueue
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
    public function handle(ProfileAvailableService $service): void
    {

        Log::info('[ProcessVisits] Buscando perfis visitados');
        $visits = $service->getVisitedYesterday();

        if (empty($visits)) {
            Log::info('[ProcessVisits] Nenhum perfil visitado encontrado');
            return;
        }

        foreach ($visits as $visit) {
            $data = [];
            $data['user_id'] = $visit->viewable_id;
            $data['total'] = $visit->total;

            Log::info(
                '[ProcessVisits] Enfileirando perfil visitado com id {id}',
                ['id' => $data['user_id']]
            );
            ProcessProfileVisited::dispatch($data);
        }
    }
}
