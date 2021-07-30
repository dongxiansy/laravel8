<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'user_id', 'category_id', 'content',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reply()
    {
        return $this->hasMany(Reply::class,'topic_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



}
