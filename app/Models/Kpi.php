<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $table = 'kpis';

    protected $fillable = [
        'kpi_desc',
        'dept_id',
        'weight',
        'kpi_target',
        'unit',
        'is_max'
    ];

    const RECORDS_PER_PAGE = 10;

    const IS_MAX = 1;


    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

    public function kpi(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ScoreKpi::class,'kpi_id','id');
    }

}