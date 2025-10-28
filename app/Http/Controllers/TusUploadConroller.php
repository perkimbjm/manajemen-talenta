<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function Illuminate\Filesystem\join_paths;

class TusUploadConroller extends Controller
{
  public function complete(Request $request)
  {
    $result = $request->get('result');
    $successful = [];
    $failed = [];
    foreach ($result['successful'] as $res) {
      $id = last(explode('/', $res['id']));
      $temp_path = storage_path("app/temp/{$res['name']}");
      $rename = "{$id}.{$res['extension']}";
      $folder = 'asistensi-rka';
      $disk = 'minio';

      $storage = Storage::disk($disk);

      if (file_exists($temp_path)) {
        $result = $storage->putFileAs($folder, new File($temp_path), $rename);
      } else {
        $result = $storage->path(join_paths($folder, $rename));
        $temp_path = null;
      }

      if ($result) {
        $successful[] = [
          'id' => $res['id'],
          'meta' => $res['meta'],
          'temp_path' => $temp_path,
          'disk' => $disk,
          'path' => $result,
        ];

        if (file_exists($temp_path)) {
          unlink($temp_path);
        }
      } else {
        $failed[] = [
          'id' => $res['id'],
          'meta' => $res['meta'],
          'temp_path' => $temp_path,
        ];
      }
    }

    return response()->json(compact('successful', 'failed'));
  }
}
