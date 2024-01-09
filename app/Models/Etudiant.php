<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'CIN',
        'nom',
        'prenom',
        'dateNaissance',
        'email',
        'password',
        'numTele',
        'groupe_id'
    ];

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }

    public function modules()
    {
        return $this->groupe->modules();
    }

    public function certificats()
    {
        return $this->hasMany(Certificat::class);
    }
}
