<?php

namespace App\Http\Controllers\Api\Plan;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Plan\ProductService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[Group("Planos", "Endpoint relacionado aos planos")]
class ProductController extends Controller
{

    public function __construct(private ProductService $service)
    {
    }

    /**
     * Lista Planos
     */
    #[ResponseFromApiResource(ProductResource::class, Product::class, collection: true, with: ['prices'])]
    public function index(): ResourceCollection
    {
        return $this->service->getActiveAll();
    }
}
