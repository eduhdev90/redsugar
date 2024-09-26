<?php

namespace App\Repositories\User;

use App\Models\Views\ProfileAvailableView;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProfileAvailableRepository implements ProfileAvailableRepositoryInterface
{
    public function __construct(protected ProfileAvailableView $model)
    {
    }

    public function getById(int $id): null|ProfileAvailableView
    {
        return $this->model::find($id);
    }

    public function getByExternalId(string $externalId): ?ProfileAvailableView
    {
        return $this->model->where('external_id', $externalId)->first();
    }

    public function getFiltered($filters): LengthAwarePaginator
    {
        return $this->model->filter($filters)->paginate(9);
    }

    public function favorites($filters): LengthAwarePaginator
    {
        return $this->model->select($this->model->getTable() . '.*')->filter($filters)->paginate(9);
    }
}
