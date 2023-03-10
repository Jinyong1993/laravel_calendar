<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'event_id';
    // protected $connection = 'mysql';

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'tag_id');
    }
}

