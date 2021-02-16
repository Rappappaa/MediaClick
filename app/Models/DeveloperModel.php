<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeveloperModel extends Model
{
    use HasFactory;
    protected $table = 'developer';
    public $timestamps = false;
    protected $guarded = [];
}
