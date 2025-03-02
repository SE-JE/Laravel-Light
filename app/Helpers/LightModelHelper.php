<?php

namespace App\Helpers;

use stdClass;

trait LightModelHelper
{
    // =========================>
    // ## Dumping field
    // =========================>
    protected function scopeDumpField(
        $query,    //? query
        $request,  //? request field
    ) {
        $fillableAttributes = array_intersect_key(
            $request->only($query->getModel()->getFillable()), 
            array_flip($query->getModel()->getFillable())
        );
        
        $query->getModel()->fill($fillableAttributes);
    }

    // =========================>
    // ## Dumping field
    // =========================>
    protected function scopeSelectableColumns(
        $query,                  //? query
        array $selectable = []   //? when includes custom selectable
    ) {
        $query->select(array_merge($query->getModel()->selectable, $selectable));
    }

    // =========================>
    // ## Search
    // =========================>
    protected function scopeSearch(
        $query,                  //? query
        string $keyword = "",    //? keyword of search
        array $searchable = []   //? when includes custom searchable
    ) {
        if (!$keyword) return;
        
        $model = $query->getModel();
        $searchables = array_merge($model->searchable, $searchable);
        
        $query->where(function ($query) use ($keyword, $searchables) {
            foreach ($searchables as $searchable) {
                $parts = explode('.', $searchable);
                $column = array_pop($parts);
                $relation = implode('.', $parts);
                
                if(!$relation) {
                    $query->orWhere($column, 'ILIKE', "%$keyword%");
                } else {
                    $query->orWhereRelation($relation, $column, 'ILIKE', "%$keyword%");
                }
            }
        });
    }
    

    // =========================>
    // ## Filter
    // =========================>
    protected function scopeFilter(
        $query,                 //? query
        array|stdClass|null $filters     //? rules of filter
    ) {
        if (!$filters) return;

        foreach ($filters as $filterable => $filter) {
            [$type, $value] = explode(':', $filter) + [null, null];
            $filterablePieces = explode('.', $filterable);
            $column = array_pop($filterablePieces);
            $relation = implode('.', $filterablePieces);
    
            switch ($type) {
                case 'eq':
                    if(!$relation) {
                        $query->where($column, $value);
                    } else {
                        $query->whereRelation($relation, $column, $value);
                    }
                    break;
                case 'ne':
                    if(!$relation) {
                        $query->where($column, '!=', $value);
                    } else {
                        $query->whereRelation($relation, $column, '!=', $value);
                    }
                    break;
                case 'in':
                    if(!$relation) {
                        $query->whereIn($column, explode(',', $value));
                    } else {
                        $query->whereRelation($relation, $column, explode(',', $value));
                    }
                    break;
                case 'ni':
                    if(!$relation) {
                        $query->whereNotIn($column, explode(',', $value));
                    } else {
                        $query->whereRelationNotIn($relation, $column, explode(',', $value));
                    }
                    break;
                case 'bw':
                    if(!$relation) {
                        $query->where($column, '>=', explode(',', $value)[0])
                            ->where($column, '<=', explode(',', $value)[1]);
                    } else {
                        $query->whereRelation($relation, $column, '>=', explode(',', $value)[0])
                            ->whereRelation($relation, $column, '<=', explode(',', $value)[1]);
                    }
                    break;
                default:
                    break;
            }
        }
    }    
}