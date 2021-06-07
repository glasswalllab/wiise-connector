<?php

namespace glasswalllab\wiiseconnector\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
  use HasFactory;

  // Disable Laravel's mass assignment protection
  protected $guarded = [];
  protected $table = 'api_tokens';
  protected $casts = [
      'tokenExpires' => 'datetime:Y-m-d',
  ];
}