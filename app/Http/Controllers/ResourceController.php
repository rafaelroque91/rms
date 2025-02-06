<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Http\Resources\ResourcesResource;
use App\Services\ResourceService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceController extends Controller
{
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
        $result = $this->resourceService->createResource($request->validated());
        return new ResourcesResource($result);
    }

    public function show($id)
    {
        $resource = $this->resourceService->getResourceById($id);
        if (is_null($resource)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        return new ResourcesResource($resource);
    }

    public function update(Request $request, $id)
    {
        $result = $this->resourceService->updateResource($id, $request->all());
        if (isset($result['errors'])) {
            return response()->json($result['errors'], 400);
        }
        if (is_null($result)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        return response()->json($result);
    }

    public function destroy($id)
    {
        $deleted = $this->resourceService->deleteResource($id);
        if (!$deleted) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        return response()->json(null, 204);
    }
}
