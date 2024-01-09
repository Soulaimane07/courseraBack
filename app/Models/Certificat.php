<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificat extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'etudiant_id',
        'cour_id',
        'pdf',
        'date_obtention',
        'note'
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }
    public function cour()
    {
        return $this->belongsTo(Cour::class);
    }
}
