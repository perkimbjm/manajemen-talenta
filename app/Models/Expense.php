<?php

namespace App\Models;

use App\Models\Scopes\OrderedCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy(OrderedCodeScope::class)]
class Expense extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = ['code', 'name'];
}
