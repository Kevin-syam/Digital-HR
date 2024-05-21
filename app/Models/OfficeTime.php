<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class OfficeTime extends Model
{
    use HasFactory;

    protected $table = 'office_times';

    const SHIFT = ['morning', 'evening', 'night'];

    const CATEGORY = ['full_timer', 'part_timer'];

    const RECORD_PER_PAGE = 10;

    protected $fillable = [
        'company_id',
        'opening_time',
        'closing_time',
        'shift',
        'category',
        'holiday_count',
        'description',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::user()->id;
            $model->updated_by = Auth::user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::user()->id;
        });
    }

    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return Attribute
     */
    protected function closingTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => date("g:i A", strtotime($value)),
        );
    }

    /**
     * @return Attribute
     */
    protected function openingTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => date("g:i A", strtotime($value)),
        );
    }


}
