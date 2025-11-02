<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    use HasFactory;

    protected $table = 'cycles';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'code_cycle',
        'cycle',
        'cycle_ar'
    ];

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'filier_cycles', 'id_cycle', 'id_filiere');
    }
}