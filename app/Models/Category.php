<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    use HasFactory;

    //field yang boleh diisi
    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    //join dengan tabel product
    public function products() {
        return $this->hasMany(Product::class);
    }
}
