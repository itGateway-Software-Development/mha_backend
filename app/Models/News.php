<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'image', 'date', 'title', 'content'
    ];

    public function newsImages() {
        return $this->hasMany(NewsImage::class);
    }
}