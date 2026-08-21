<?php

namespace App\Http\Filters\V1;

class CustomerFilter extends QueryFilter
{
    protected $sortable = [
        'name',
        'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    /**
     * Filter by creation date, or by a date range when two dates are given.
     */
    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    /**
     * Eager load the requested relationships, ignoring any the model does not define.
     */
    public function include($value)
    {
        if (!is_string($value)) {
            return $this->builder;
        }

        $model = $this->builder->getModel();

        $relations = array_filter(
            explode(',', $value),
            fn ($relation) => $model->isRelation($relation)
        );

        return $this->builder->with($relations);
    }

    /**
     * Filter by one or more identifiers.
     */
    public function id($value)
    {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    /**
     * Filter by email, where * acts as a wildcard.
     */
    public function email($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('email', 'like', $likeStr);
    }

    /**
     * Filter by name, where * acts as a wildcard.
     */
    public function name($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('name', 'like', $likeStr);
    }

    /**
     * Filter by update date, or by a date range when two dates are given.
     */
    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
