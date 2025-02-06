<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Http\Resources\ResourcesResource;
use App\Services\ResourceService;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceController extends Controller
{
    use ApiResponses;
    public function __construct(
        private readonly ResourceService $resourceService)
    {
    }

    public function index() : ResourceCollection
    {
        return new ResourceCollection($this->resourceService->getAllResources());
    }

    public function store(StoreResourceRequest $request) : ResourcesResource|JsonResponse
    {
        try {
            $result = $this->resourceService->createResource($request->validated());
            return new ResourcesResource($result);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function show($id) : ResourcesResource|JsonResponse
    {
        try {
            $resource = $this->resourceService->getResourceById($id);
            return new ResourcesResource($resource);
        } catch (ModelNotFoundException $e) {
            return $this->error($e->getMessage(), 404);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function update(UpdateResourceRequest $request, $id) : ResourcesResource|JsonResponse
    {
        try {
            $result = $this->resourceService->updateResource($id, $request->validated());
            return new ResourcesResource($result);
        } catch (ModelNotFoundException $e) {
            return $this->error($e->getMessage(), 404);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->resourceService->deleteResource($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return $this->error($e->getMessage(), 404);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
