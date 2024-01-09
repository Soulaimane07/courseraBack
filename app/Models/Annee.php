<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'annee_filiere');
    }
}
