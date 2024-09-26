<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileAvailableListResource;
use App\Http\Resources\ProfileAvailableResource;
use App\Models\Views\ProfileAvailableView;
use App\Services\ProfileAvailableService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[Group("Perfis", "Endpoint relacionado a perfis em geral da aplicação")]
class ProfileAvailableController extends Controller
{
    public function __construct(
        private ProfileAvailableService $service
    ) {
    }

    /**
     * Novos usuários
     */
    #[ResponseFromApiResource(ProfileAvailableListResource::class, ProfileAvailableView::class, collection: true)]
    public function news(): ResourceCollection
    {
        return $this->service->news();
    }

    /**
     * Meus usuários favoritos
     */
    #[ResponseFromApiResource(ProfileAvailableListResource::class, ProfileAvailableView::class, collection: true)]
    public function favorites(): ResourceCollection
    {
        return $this->service->favorites();
    }

    /**
     * Usuários que me favoritaram
     */
    #[ResponseFromApiResource(ProfileAvailableListResource::class, ProfileAvailableView::class, collection: true)]
    public function favoritedme(): ResourceCollection
    {
        return $this->service->favoritedme();
    }

    /**
     * Usuários mais próximos
     */
    #[ResponseFromApiResource(ProfileAvailableListResource::class, ProfileAvailableView::class, collection: true)]
    public function closest(): ResourceCollection
    {
        return $this->service->closest();
    }

    /**
     * Usuários que me visitaram
     */
    #[ResponseFromApiResource(ProfileAvailableListResource::class, ProfileAvailableView::class, collection: true)]
    public function visitedme(): ResourceCollection
    {
        return $this->service->visitedme();
    }

    /**
     * Perfil de um usuário
     */
    #[ResponseFromApiResource(ProfileAvailableResource::class, ProfileAvailableView::class)]
    public function show(Request $request): ?ProfileAvailableResource
    {
        return $this->service->getProfile(auth()->user()->profile->id, $request);
    }

    /**
     * Busca de usuários
     */
    #[ResponseFromApiResource(ProfileAvailableListResource::class, ProfileAvailableView::class, collection: true)]
    #[QueryParam('s_academic', required: false)]
    #[QueryParam('s_beardcolor', required: false)]
    #[QueryParam('s_beardsize', required: false)]
    #[QueryParam('s_children', required: false)]
    #[QueryParam('s_drink', required: false)]
    #[QueryParam('s_eyecolor', required: false)]
    #[QueryParam('s_gender', required: false)]
    #[QueryParam('s_haircolor', required: false)]
    #[QueryParam('s_hairtype', required: false)]
    #[QueryParam('s_maritalstatus', required: false)]
    #[QueryParam('s_income', required: false)]
    #[QueryParam('s_patrimony', required: false)]
    #[QueryParam('s_physical', required: false)]
    #[QueryParam('s_skintone', required: false)]
    #[QueryParam('s_smoke', required: false)]
    #[QueryParam('s_stylelife', required: false)]
    #[QueryParam('s_tattoo', required: false)]
    #[QueryParam('s_height', required: false)]
    #[QueryParam('s_age', required: false)]
    #[QueryParam('s_location', required: false)]
    public function search(): ResourceCollection
    {
        return $this->service->search();
    }
}
