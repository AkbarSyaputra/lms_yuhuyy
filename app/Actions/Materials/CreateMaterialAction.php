<?php

namespace App\Actions\Materials;

use App\DTOs\MaterialData;
use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use Spatie\LaravelData\Optional;

class CreateMaterialAction
{
    public function __construct(
        private readonly MaterialRepositoryInterface $materialRepository,
    ) {}

    public function execute(int $courseId, MaterialData $data): Material
    {
        $payload = array_filter([
            'course_id' => $courseId,
            'title' => $data->title,
            'type' => $data->type,
            'content' => $data->content instanceof Optional ? null : $data->content,
            'file_path' => $data->file_path instanceof Optional ? null : $data->file_path,
            'external_url' => $data->external_url instanceof Optional ? null : $data->external_url,
            'order' => $data->order instanceof Optional ? null : $data->order,
            'is_published' => $data->is_published,
        ], fn ($v) => $v !== null);

        $payload['course_id'] = $courseId;
        $payload['is_published'] = $data->is_published;

        return $this->materialRepository->create($payload);
    }
}
