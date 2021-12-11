<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
    private function successRespone($data, $code) {
        return response()->json($data, $code);
    }

    protected function errorResponse ($message, $code) {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200) {
        $collection = $this->sortData($collection);
        return $this->successRespone(['data' => $collection], $code);
    }

    protected function showOne (Model $model, $code = 200) {
        return $this->successRespone(['data' => $model], $code);
    }

    protected function sortData(Collection $collection) {
        if (request()->has('sort_by_asc')) {
            $attribute = request()->sort_by_asc;
            $collection = $collection->sortBy->{$attribute};
        }
        if (request()->has('sort_by_desc')) {
            $attribute = request()->sort_by_desc;
            $collection = $collection->sortByDesc->{$attribute};
        }

        return $collection;
    }
}

