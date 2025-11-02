<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $table = 'groupes';
    protected $primaryKey = 'id_groupe';
    public $timestamps = false;

    protected $fillable = [
        'nom_groupe',
        'id_filiere',
        'id_semestre',
        'effectif'
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'id_filiere', 'id_filiere');
    }

    public function semestre()
    {
        return $this->belongsTo(Semistre::class, 'id_semestre', 'id_semestre');
    }

    // Relation avec le cycle via la filière
    public function cycle()
    {
        return $this->hasOneThrough(
            Cycle::class,
            Filiere::class,
            'id_filiere', // Clé étrangère sur la table filieres
            'id', // Clé étrangère sur la table cycles
            'id_filiere', // Clé locale sur la table groupes
            'id_cycle' // Clé intermédiaire sur la table filieres
        );
    }

    public function seances()
    {
        return $this->hasMany(Seance::class, 'id_groupe', 'id_groupe');
    }

    // Méthode scope pour filtrer par cycle, filière et semestre
    public function scopeFilter($query, $filiereId, $semestreId, $cycleId = null)
    {
        $query->where('id_filiere', $filiereId);
        
        if ($semestreId) {
            $query->where('id_semestre', $semestreId);
        }
        
        if ($cycleId) {
            // Si vous utilisez la relation via filière
            $query->whereHas('filiere', function($q) use ($cycleId) {
                $q->where('id_cycle', $cycleId);
            });
        }
        
        return $query;
    }

    // Accesseur pour le nom complet du groupe
    public function getNomCompletAttribute()
    {
        $nom = $this->nom_groupe;
        
        if ($this->filiere) {
            $nom .= ' - ' . $this->filiere->nom_filiere;
        }
        
        if ($this->semestre) {
            $nom .= ' - ' . $this->semestre->nom_semestre;
        }
        
        return $nom;
    }
}