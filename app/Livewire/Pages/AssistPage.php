<?php

namespace App\Livewire\Pages;

use App\Models\Assist;
use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Http\Request;

use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use function Illuminate\Filesystem\join_paths;

#[Layout('components.layouts.authenticated')]
class AssistPage extends Component
{
  use WithPagination;

  public Assist $comment_to;

  #[On('upload-rka')]
  public function uploadRKA($dataset)
  {
    if (!$dataset) {
      return;
    }

    $file_route = "/files";
    $unit_code = '2.16.2.20.2.21.02.0000';
    $created_by = auth('web')->user()->id;
    $updated_by = $created_by;

    $upserts = [];

    foreach ($dataset as $data) {
      $meta = $data['meta'];
      $filename = $meta['name'];

      $path = join_paths($file_route, $data['disk'], $data['path']);
      $arr  = $this->handleFilename($filename);
      $sub_activity_code = $arr['code'];
      $title = $arr['title'];

      $upserts[] = [
        'filename' => $meta['name'],
        'filepath' => $path,
        'unit_code' => $unit_code,
        'sub_activity_code' => $sub_activity_code,
        'title' => $title,
        'created_by' => $created_by,
        'updated_by' => $updated_by,
      ];
    }

    Assist::upsert(
      $upserts,
      ['unit_code', 'sub_activity_code'],
      ['filepath', 'title', 'updated_by']
    );

    $this->resetPage();
  }

  #[On('save-comment')]
  public function saveComment($comment)
  {
    if (!$comment) {
      return;
    }

    $created_by = auth('web')->user()->id;
    $updated_by = $created_by;

    if (!$this->comment_to) {
      return;
    }

    $this->comment_to->comments()->create([
      'body' => trim($comment),
      'created_by' => $created_by,
      'updated_by' => $updated_by,
    ]);

    $this->comment_to->refresh();

    $this->dispatch('notifications', [
      'type' => 'success',
      'message' => 'Berhasil menyimpan komentar',
    ]);
    $this->dispatch('comment-updated', id: $this->comment_to->id);
  }

  private function handleFilename(string $filename)
  {
    $index = 10;
    $name_parts = str($filename)->explode(' ');

    $code = $name_parts[$index];
    $last_part = $name_parts->slice($index + 1)->implode(' ');
    $title = explode('.', $last_part)[0];

    return compact('code', 'title');
  }

  public function mount(Request $request)
  {
    if ($request->has('comment_to')) {
      // dd($request->get('comment_to'));
      $this->comment_to = Assist::find($request->get('comment_to'));
    }
  }

  public function render()
  {
    $query = Assist::query();

    $assists = $query->paginate(15);

    return view('livewire.pages.assist-page', compact('assists'));
  }
}
