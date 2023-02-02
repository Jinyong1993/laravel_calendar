<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardComment extends Model
{
    protected $table = 'board_comment';
    protected $primaryKey = 'comment_id';
    use SoftDeletes;
}
