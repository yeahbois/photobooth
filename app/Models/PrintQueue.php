<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintQueue extends Model
{
    use HasFactory;

    protected $table = 'print_queue';

    protected $fillable = ['photo_id'];
}