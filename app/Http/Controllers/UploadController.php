<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class UploadController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $files = $request->file('files');
    $dataset = collect([]);
    foreach ($files as $file) {
      $dataset->push((object) $this->getMetaFromFile($file));
    }

    // dd($dataset);

    return view('dashboard', compact('dataset'));
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }


  private function getMetaFromFile(UploadedFile $file)
  {
    $file_path = $file->store('rka');

    $filename = $file->getClientOriginalName();

    $index = 10;
    $name_parts = str($filename)->explode(' ');

    $code = $name_parts[$index];
    $last_part = $name_parts->slice($index + 1)->implode(' ');
    $description = explode('.', $last_part)[0];

    return compact('filename', 'code', 'description', 'file_path');
  }
}
