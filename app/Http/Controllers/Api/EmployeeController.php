<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $params = $request->all();

    $query = Employee::query();

    $total = $query->count();
    $query->facets($request->get('facets'));
    $query->search($request->get('search'));

    $query->orderBy('nip', 'asc');

    if ($request->get('selecteds')) {
      $query->whereIn('nip', $request->get('selecteds'));
    }

    $query->with('unit');

    $data = $query->take(10)->get();

    $data = compact('data', 'total');

    return response()->json($data);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Employee $employee)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Employee $employee)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Employee $employee)
  {
    //
  }
}
