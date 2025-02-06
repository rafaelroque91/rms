<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Http\Resources\ResourcesResource;
use App\Services\ResourceService;
use App\Traits\ApiResponses;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceController extends Controller
{
    use ApiResponses;
    public function __construct(
        private readonly ResourceService $resourceService)
    {
    }

    public function index()
    {
        return new ResourceCollection($this->resourceService->getAllResources());
    }

    public function store(StoreResourceRequest $request)
    {
        try {
            $result = $this->resourceService->createResource($request->validated());
            return new ResourcesResource($result);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $resource = $this->resourceService->getResourceById($id);
        if (is_null($resource)) {
            return $this->error('Resource not found', 404);
        }
        return new ResourcesResource($resource);
    }

    public function update(UpdateResourceRequest $request, $id)
    {
        try {
            $result = $this->resourceService->updateResource($id, $request->validated());
            return new ResourcesResource($result);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }

    }

    public function destroy($id)
    {
        $deleted = $this->resourceService->deleteResource($id);
        if (!$deleted) {
            return $this->error('Resource not found', 404);
        }
        return response()->json(null, 204);
    }
}
