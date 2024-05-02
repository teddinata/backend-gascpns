<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'packages';

    protected $dates = [
        'sale_start_at',
        'sale_end_at',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'total_question',
        'total_duration',
        'status',
        'sale_start_at',
        'sale_end_at',
        'start_at',
        'end_at',
        'discount',
        'voucher_code',
        'cover_path',
        'thumbnail_path',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // fill created_by, updated_by, deleted_by
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->id();
            $model->save();
        });
    }

    // students
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students', 'package_tryout_id', 'user_id');
    }

    public function packageTryOuts()
    {
        return $this->hasMany(PackageTryOut::class, 'package_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }
}
