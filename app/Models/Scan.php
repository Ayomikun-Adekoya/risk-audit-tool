<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_url',
        'status',
        'risk_score',
        'started_at',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vulnerabilities()
    {
        return $this->hasMany(Vulnerability::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }
}
