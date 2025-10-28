<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
  use HasFactory, HasUuids;

  protected $fillable = ['body', 'created_by', 'updated_by'];

  // Define the polymorphic relationship
  public function commentable()
  {
    return $this->morphTo();
  }

  public function created_by_user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function updated_by_user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }
}
