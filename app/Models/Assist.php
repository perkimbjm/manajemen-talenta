<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Assist extends Model
{
  use HasFactory, HasUuids, SoftDeletes;

  protected $fillable = [
    'description',
    'unit_code',
    'sub_activity_code',
    'title',
    'filepath',
    'filename',
    'description',
    'created_by',
    'updated_by',
  ];

  public function comments()
  {
    return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'desc');
  }
}
