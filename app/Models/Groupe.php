<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Groupe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'filiere_id'
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_groupe');
    }
}
