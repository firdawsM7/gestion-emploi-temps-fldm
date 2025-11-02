<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    protected $table = 'seances';
    protected $primaryKey = 'id_seance';
    
    protected $fillable = [
        'id_filiere',
        'id_groupe', 
        'id_semestre',
        'id_cycle',
        'id_module',
        'id_salle',
        'user_id',
        'jour',
        'debut',
        'fin',
        'type_seance'
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'id_filiere', 'id_filiere');
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'id_groupe', 'id_groupe');
    }

    public function semestre()
    {
        return $this->belongsTo(Semistre::class, 'id_semestre', 'id_semestre');
    }

    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'id_cycle', 'id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module', 'id_module');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'id_salle', 'id_salle');
    }

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}