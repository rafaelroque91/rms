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
use Illuminate\Support\Facades\Cache;

class ResourceController extends Controller
{
    use ApiResponses;
    public function __construct(
        private readonly ResourceService $resourceService)
    {
    }

    public function index() : ResourceCollection
    {
        $resources = $this->resourceService->getAllResources();

        return new ResourceCollection($resources);
    }

    public function store(StoreResourceRequest $request) : ResourcesResource|JsonResponse
    {
        try {
            $result = $this->resourceService->createResource($request->validated());
            Cache::add("resource_{$result->id}", $result, config('app.pagination_size'));
            return new ResourcesResource($result);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function show($id) : ResourcesResource|JsonResponse
    {
        try {
            $resource = Cache::remember("resource_{$id}", config('app.pagination_size'), function () use ($id) {
                return $this->resourceService->getResourceById($id);
            });
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
            Cache::forget('resources');
            Cache::add("resource_{$result->id}", $result, config('app.pagination_size'));
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
            Cache::forget('resources');
            Cache::forget("resource_{$id}");
            return $this->success(null,null, 204);
        } catch (ModelNotFoundException $e) {
            return $this->error($e->getMessage(), 404);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
