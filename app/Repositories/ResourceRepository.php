<?php

namespace App\Repositories;

use App\Models\Resource;

class ResourceRepository implements ResourceRepositoryInterface
{
    public function all()
    {
        return Resource::paginate(config('app.pagination_size'));
    }

    public function find($id)
    {
        return Resource::find($id);
    }

    public function create(array $data)
    {
        return Resource::create($data);
    }

    public function update($id, array $data)
    {
        $resource = Resource::find($id);
        if ($resource) {
            $resource->update($data);

            return $resource;
        }
        return null;
    }

    public function delete($id)
    {
        $resource = Resource::find($id);
        if ($resource) {
            $resource->delete();
            return true;
        }
        return false;
    }
}
