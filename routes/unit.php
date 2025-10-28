<?php

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;

Route::get('/units/json', function (Request $request) {
  $user = auth('web')->user();

  $search = str($request->search)->replace(' ', '');

  $query = Unit::query()
    ->select('code', 'name', 'description')
    ->when(
      @$user->role?->level === 3,
      fn(Builder $query) => $query->where('code', $user->role->unit?->code)
    )
    ->when(
      @$user->role?->level === 2,
      fn(Builder $query) => $query->where('group', $user->role->group)
    );

  $total = $query->count();

  $dataset = $query
    ->when(
      $request->search,
      fn(Builder $query) => $query
        ->whereRaw("REPLACE(name, ' ', '') LIKE '%{$search}%'")
        ->orWhereRaw("REPLACE(code, ' ', '') LIKE '%{$search}%'")
    )
    ->when(
      $request->exists('selected'),
      fn(Builder $query) => $query->whereIn('code', $request->input('selected', [])),
      fn(Builder $query) => $query->limit(10)
    )
    ->orderBy('code')
    ->get();

  return response()->json(compact('dataset', 'total'));
})->name('units.json');

Route::get('/units/data', function (Request $request) {
  $user = auth('web')->user();

  $search = str($request->search)->replace(' ', '');

  $query = Unit::query()
    ->select('code', 'name', 'description')
    ->when(
      @$user->role?->level === 3,
      fn(Builder $query) => $query->where('code', $user->role->unit?->code)
    )
    ->when(
      @$user->role?->level === 2,
      fn(Builder $query) => $query->where('group', $user->role->group)
    );

  $total = $query->count();

  $dataset = $query
    ->when(
      $request->search,
      fn(Builder $query) => $query
        ->whereRaw("REPLACE(name, ' ', '') LIKE '%{$search}%'")
        ->orWhereRaw("REPLACE(code, ' ', '') LIKE '%{$search}%'")
    )
    ->when(
      $request->exists('selected'),
      fn(Builder $query) => $query->whereIn('code', $request->input('selected', [])),
      fn(Builder $query) => $query->limit(10)
    )
    ->orderBy('code')
    ->get();
  return response()->json($dataset);
})->name('units.data');
