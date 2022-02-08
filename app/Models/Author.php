<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'name',
        'birth_date',
        'user_id'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
