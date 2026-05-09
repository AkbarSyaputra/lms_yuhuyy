<?php

namespace App\Actions\Materials;

use App\DTOs\MaterialData;
use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use Spatie\LaravelData\Optional;

class UpdateMaterialAction
{
    public function __construct(
        private readonly MaterialRepositoryInterface $materialRepository,
    ) {}

    public function execute(Material $material, MaterialData $data): Material
    {
        $payload = [
            'title' => $data->title,
            'type' => $data->type,
            'is_published' => $data->is_published,
        ];

        if (! ($data->content instanceof Optional)) {
            $payload['content'] = $data->content;
        }

        if (! ($data->file_path instanceof Optional)) {
            $payload['file_path'] = $data->file_path;
        }

        if (! ($data->external_url instanceof Optional)) {
            $payload['external_url'] = $data->external_url;
        }

        if (! ($data->order instanceof Optional)) {
            $payload['order'] = $data->order;
        }

        return $this->materialRepository->update($material, $payload);
    }
}
