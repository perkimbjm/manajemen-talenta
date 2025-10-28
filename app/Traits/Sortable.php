<?php

namespace App\Traits;

trait Sortable
{
  public function scopeSortBy($query, string $sortBy, string $sortDirection = 'asc')
  {
    $model = $query->getModel();
    if (!property_exists($model, 'sortables')) {
      return $query;
    }

    if (in_array($sortBy, $model->sortables))
      return $query->orderBy($sortBy, $sortDirection);
  }
}
