<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'image', 'approve', 'user_id', 'category'
    ];
    // App\Models\News.php

public function kategori()
{
    return $this->belongsTo(\App\Models\Kategori::class, 'category');
}

}
