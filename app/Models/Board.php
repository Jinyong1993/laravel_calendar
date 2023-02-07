<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    protected $table = 'board';
    protected $primaryKey = 'board_id';
    
    use SoftDeletes;

    // 1:n
    public function comments()
    {
        return $this->hasMany(BoardComment::class, 'board_id', 'board_id');
    }
    
}
