<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'post_name',
        'is_active',
        'dept_id'
    ];

    const RECORDS_PER_PAGE = 10;

    const IS_ACTIVE = 1;


    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class,'post_id','id')
          ->where([
          ['status', '=', 'verified'],
          ['is_active', '=', self::IS_ACTIVE ],
        ]);
    }

}


