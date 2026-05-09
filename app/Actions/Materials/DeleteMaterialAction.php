<?php

namespace App\Actions\Materials;

use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;

class DeleteMaterialAction
{
    public function __construct(
        private readonly MaterialRepositoryInterface $materialRepository,
    ) {}

    public function execute(Material $material): bool
    {
        return $this->materialRepository->delete($material);
    }
}
