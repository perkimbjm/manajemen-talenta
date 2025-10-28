<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
  /**
   * Show the form for creating the resource.
   */
  public function create(): never
  {
    abort(404);
  }

  /**
   * Store the newly created resource in storage.
   */
  public function store(Request $request): never
  {
    abort(404);
  }

  /**
   * Display the resource.
   */
  public function show(Request $request, string $disk, string $path)
  {
    // Get the file's content from MinIO
    $storage = Storage::disk($disk);

    if (!$storage->exists($path)) {
      abort(404, 'File not found.');
    }

    // Get the file's MIME type (for setting the correct Content-Type)
    /** @disregard [OPTIONAL_CODE] [OPTION_DESCRIPTION] */
    $mimeType = $storage->mimeType($path);

    if (!$request->exists('stream')) {
      return response($storage->get($path), 200)
        ->header('Content-Type', $mimeType)
        ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }

    // Stream the file from MinIO
    $stream = $storage->readStream($path);


    // Return the streamed response
    return response()->stream(function () use ($stream) {
      fpassthru($stream); // Send the file content to the response
    }, 200, [
      "Content-Type" => $mimeType,
      "Content-Disposition" => "inline; filename=\"" . basename($path) . "\""
    ]);
  }

  /**
   * Show the form for editing the resource.
   */
  public function download(Request $request, string $disk, string $path)
  {
    // Get the file's content from MinIO
    $storage = Storage::disk($disk);

    if (!$storage->exists($path)) {
      abort(404, 'File not found.');
    }

    // Get the file's MIME type (for setting the correct Content-Type)
    /** @disregard [OPTIONAL_CODE] [OPTION_DESCRIPTION] */
    $mimeType = $storage->mimeType($path);

    $stream = $storage->readStream($path);

    return response()->streamDownload(function () use ($stream) {
      fpassthru($stream);
    }, basename($path));
  }

  /**
   * Update the resource in storage.
   */
  public function update(Request $request)
  {
    //
  }

  /**
   * Remove the resource from storage.
   */
  public function destroy(): never
  {
    abort(404);
  }

  public function copy(Request $request)
  {
    $files = $request->get('files');

    $successful = [];
    $failed = [];

    foreach ($files as $file) {
      $result  = $this->copyFile($file['from'], $file['to']);

      if ($result) {
        $successful[] = $file;
      } else {
        $failed[] = $file;
      }
    }

    return response()->json(compact('successful', 'failed'));
  }

  public function move(Request $request)
  {
    $files = $request->get('files');

    $successful = [];
    $failed = [];

    foreach ($files as $file) {
      $result  = $this->copyFile($file['from'], $file['to']);

      if ($result) {
        $successful[] = $file;
        Storage::disk($file['from']['disk'])->delete($file['from']['path']);
      } else {
        $failed[] = $file;
      }
    }

    return response()->json(compact('successful', 'failed'));
  }

  private function copyFile($from, $to)
  {
    if (!@$from['disk']) {
      $from['disk'] = config('filesystems.default');
    }

    if (!@$to['disk'] || $from['disk'] === $to['disk']) {
      $result = Storage::disk($from['disk'])->copy($from['path'], $to['path']);
    } else

    if (@$to['disk']) {
      $contents = Storage::disk($from['disk'])->get($from['path']);
      $result = Storage::disk($to['disk'])->put($to['path'], $contents);
    }

    return $result;
  }

  public function showById(string $file_id)
  {
    $json_path = 'upload/' . $file_id . '.json';
    if (!Storage::exists($json_path)) {
      abort(404, 'File not found.');
    }

    $json = Storage::get($json_path);
    $data = json_decode($json);
    $extension = $data->extension;

    $file_path = "upload/{$file_id}.{$extension}";
    if (!Storage::exists($file_path)) {
      abort(404, 'File not found.');
    }

    $file = Storage::get($file_path);

    $filename = str($data->filename)->remove(".{$extension}")->toString();

    return response($file, 200)
      ->header('Content-Type', $data->filetype)
      ->header('Content-Disposition', "inline; filename=\"{$filename}.{$extension}\"");
  }
}
