<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slig',
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
        'slig' => 'string',
        'description' => 'string',
        'image' => 'string',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_by' => 'datetime',
        'updated_by' => 'datetime'
    ];
}
