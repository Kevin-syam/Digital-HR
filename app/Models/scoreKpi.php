<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreKpi extends Model
{
    use HasFactory;

    protected $table = 'score_kpis';

    protected $fillable = [
        'kpi_id',
        'dept_id',
        'realisation',
        'score',
        'period'
    ];

    const RECORDS_PER_PAGE = 10;

    const IS_MAX = 1;


    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

    public function kpi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kpi::class, 'kpi_id', 'id');
    }

    // public function kpi(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(Score::class,'post_id','id')
    //       ->where([
    //       ['status', '=', 'verified'],
    //       ['is_active', '=', self::IS_ACTIVE ],
    //     ]);
    // }

}