<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    use HasFactory;
    protected $table = 'prescription_trackings';
    protected $primaryKey = 'id_prescription';

    protected $fillable = [
        'id_record',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
        'prescribed_at',
    ];

    protected $casts = [
        'prescribed_at' => 'datetime',
    ];

    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class, 'id_record', 'id_record');
    }
}