<?php

namespace App\Actions\Materials;

use App\Repositories\Contracts\MaterialRepositoryInterface;

class ReorderMaterialAction
{
    public function __construct(
        private readonly MaterialRepositoryInterface $materialRepository,
    ) {}

    public function execute(int $courseId, array $orderedIds): void
    {
        $this->materialRepository->reorder($courseId, $orderedIds);
    }
}
