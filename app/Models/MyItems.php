<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyItems extends Model
{
    use HasFactory;
    public $fillable = ['name', 'ingredients' , 'directions'];
}
