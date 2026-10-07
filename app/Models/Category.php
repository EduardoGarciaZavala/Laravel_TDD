<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'image' => 'string',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
