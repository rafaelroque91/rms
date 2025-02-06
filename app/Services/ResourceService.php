<?php

namespace App\Services;

use App\Repositories\ResourceRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ResourceService
{
    public function __construct(
        private readonly ResourceRepositoryInterface $resourceRepository)
    {
    }

    public function getAllResources()
    {
        return $this->resourceRepository->all();
    }

    public function getResourceById($id)
    {
        return Cache::remember("resource_{$id}", config('app.pagination_size'), function () use ($id) {
            return $this->resourceRepository->find($id);
        });
    }

    public function createResource(array $data)
    {
        $result = $this->resourceRepository->create($data);
        Cache::add("resource_{$result->id}", $result, config('app.pagination_size'));
        return $result;
    }

    public function updateResource($id, array $data)
    {
        $result = $this->resourceRepository->update($id, $data);
        Cache::add("resource_{$result->id}", $result, config('app.pagination_size'));
        return $result;
    }

    public function deleteResource($id): void
    {
        $this->resourceRepository->delete($id);
        Cache::forget("resource_{$id}");
    }
}
