<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    use HasFactory;

    protected $fillable = [
        'lien',
        'titre',
        'desc',
        'dateDebut',
        'dateFin',
        'deadline_control',
        'module_id',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'cours_professeur', 'cour_id', 'professeur_id');
    }

    public function certificats()
    {
        return $this->hasMany(Certificat::class);
    }
}
