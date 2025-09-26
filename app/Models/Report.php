<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'scan_id',
        'report_data',
    ];

    public function scan()
    {
        return $this->belongsTo(Scan::class);
    }
}
