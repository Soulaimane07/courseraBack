<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];

    public function groupes()
    {
        return $this->hasMany(Groupe::class);
    }

    public function annees()
    {
        return $this->belongsToMany(Annee::class, 'annee_filiere');
    }
}
