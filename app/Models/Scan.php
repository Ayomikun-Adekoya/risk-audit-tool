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
        'scan_depth',
        'status',
        'risk_score',
        'results',
        'started_at',
        'completed_at',

        // Consent-related fields
        'consent_given',
        'consent_ip',

        // Scan metrics
        'uses_https',
        'sql_injections_detected',
        'open_ports_count',
        'access_control_issues',
        'weak_passwords_detected',
        'has_logging_enabled',
        'ssrf_detected',
    ];

    protected $casts = [
        'results'            => 'array',    // automatically encodes/decodes JSON
        'started_at'         => 'datetime',
        'completed_at'       => 'datetime',
        'consent_given'      => 'boolean',
        'uses_https'         => 'boolean',
        'has_logging_enabled'=> 'boolean',
        'ssrf_detected'      => 'boolean',
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
