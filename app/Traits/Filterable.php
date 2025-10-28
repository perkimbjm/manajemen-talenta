<?php

namespace App\Traits;

function parseFacet(string $facet)
{
  $segments = explode('.', $facet);

  if (count($segments) > 1) {
    return [
      'relation' => $segments[0],
      'field' => $segments[1],
    ];
  } else {
    return [
      'field' => $facet,
    ];
  }
}

trait Filterable
{
  public function scopeSearch($query, ?string $searchTerm, ?array $fields = [])
  {
    if (!$searchTerm) {
      return $query;
    }

    $model = $query->getModel();
    $searchables = $model->searchables ?? [];
    $fields = array_merge($fields ?? [], $searchables);

    return $query->where(function ($q) use ($fields, $searchTerm) {
      foreach ($fields as $field) {
        $q->orWhere($field, 'like', '%' . $searchTerm . '%');
      }
    });
  }

  public function scopeSearchRelation($query, ?string $searchTerm, ?array $fields = [])
  {
    if (!$searchTerm) {
      return $query;
    }

    $model = $query->getModel();
    $searchables = $model->searchables ?? [];
    $fields = array_merge($fields ?? [], $searchables);

    return $query->where(function ($qry) use ($fields, $searchTerm) {
      foreach ($fields as $key => $field) {
        if (!is_array($field)) {
          $qry->orWhere($field, 'like', '%' . $searchTerm . '%');
          continue;
        }

        $qry->orWhereHas($key, function ($q) use ($field, $searchTerm) {
          $first = array_shift($field);
          $q->where($first, 'like', "%{$searchTerm}%");
          foreach ($field as $f) {
            $q->orWhere($f, 'like', "%{$searchTerm}%");
          }
        });
      }
    });
  }

  public function scopeFacets($query, ?array $facets, bool $strict = false)
  {
    if (!$facets) {
      return $query;
    }

    foreach ($facets as $facet => $value) {
      if (!isset($value) && !$strict) continue;
      $condition = parseFacet($facet);
      if (@$condition['relation']) {
        $query->whereHas($condition['relation'], function ($q) use ($value, $condition) {
          if (is_array($value))
            $q->whereIn($condition['field'], $value);
          else
            $q->where($condition['field'], $value);
        });
      } else {
        if (is_array($value))
          $query->whereIn($condition['field'], $value);
        else
          $query->where($condition['field'], $value);
      }
    }

    return $query;
  }
}
