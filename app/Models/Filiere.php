<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';
    protected $primaryKey = 'id_filiere';
    public $timestamps = false;

    protected $fillable = [
        'nom_filiere',
        'id_departement'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement');
    }

    public function groupes()
    {
        return $this->hasMany(Groupe::class, 'id_filiere');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'id_filiere');
    }

    public function seances()
    {
        return $this->hasMany(Seance::class, 'id_filiere');
    }

    public function cycles()
    {
        return $this->belongsToMany(Cycle::class, 'filier_cycles', 'id_filiere', 'id_cycle');
    }
}