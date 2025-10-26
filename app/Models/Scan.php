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
        'scan_depth',   // ✅ newly added
        'status',
        'risk_score',
        'started_at',
        'completed_at',
    ];

    /**
     * Relationship: A scan belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A scan can have many vulnerabilities.
     */
    public function vulnerabilities()
    {
        return $this->hasMany(Vulnerability::class);
    }

    /**
     * Relationship: A scan can have one report.
     */
    public function report()
    {
        return $this->hasOne(Report::class);
    }
}
