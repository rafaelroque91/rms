<?php

namespace App\Services;

use App\Repositories\ResourceRepositoryInterface;

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
        return $this->resourceRepository->find($id);
    }

    public function createResource(array $data)
    {
        return $this->resourceRepository->create($data);
    }

    public function updateResource($id, array $data)
    {
        return $this->resourceRepository->update($id, $data);
    }

    public function deleteResource($id)
    {
        return $this->resourceRepository->delete($id);
    }
}
