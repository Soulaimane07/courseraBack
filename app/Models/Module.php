<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'filiere_id'
    ];
    public function cours()
    {
        return $this->hasMany(Cour::class);
    }
    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'professeur_module');
    }
    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'module_groupe');
    }
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

}
