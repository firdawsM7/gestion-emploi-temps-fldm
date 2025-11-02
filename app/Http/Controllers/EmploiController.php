<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Salle;
use App\Models\Seance;
use App\Models\Module;
use App\Models\Semistre;
use App\Models\User;
use App\Models\Cycle;
use App\Exports\SeancesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class EmploiController extends Controller
{
    public function index()
    {
        $filiere_id = request('filiere_id');
        $groupe_id = request('groupe_id');
        $semestre_id = request('semestre_id');
        $cycle_id = request('cycle_id');
        
        $query = Seance::with(['module', 'salle', 'enseignant', 'groupe', 'cycle']);

        if ($filiere_id) {
            $query->where('id_filiere', $filiere_id);
        }

        if ($groupe_id) {
            $query->where('id_groupe', $groupe_id);
        }

        if ($semestre_id) {
            $query->where('id_semestre', $semestre_id);
        }

        if ($cycle_id) {
            $query->where('id_cycle', $cycle_id);
        }

        $emplois = $query->orderBy('jour')->orderBy('debut')->get();

        $filieres = Filiere::all();
        $semestres = Semistre::all();
        $cycles = Cycle::all();
        $groupes = $filiere_id ? Groupe::where('id_filiere', $filiere_id)->get() : collect();
        
        $selectedGroupe = null;
        if ($groupe_id) {
            $selectedGroupe = Groupe::find($groupe_id);
        }

        return view('emplois.index', [
            'emplois' => $emplois,
            'seances' => $emplois,
            'filieres' => $filieres,
            'semestres' => $semestres,
            'cycles' => $cycles,
            'groupes' => $groupes,
            'filiere_id' => $filiere_id,
            'groupe_id' => $groupe_id,
            'semestre_id' => $semestre_id,
            'cycle_id' => $cycle_id,
            'selectedGroupe' => $selectedGroupe
        ]);
    }

    public function ajouter(Request $request)
    {
        $filiere_id = $request->input('filiere_id') ?? session('last_filiere_id');
        $groupe_id = $request->input('groupe_id') ?? session('last_groupe_id');
        $semestre_id = $request->input('semestre_id') ?? session('last_semestre_id');
        $cycle_id = $request->input('cycle_id') ?? session('last_cycle_id');

        $filieres = Filiere::all();
        $semestres = Semistre::all();
        $cycles = Cycle::all();
        $groupes = $filiere_id ? Groupe::where('id_filiere', $filiere_id)->get() : collect();
        $groupe = $groupe_id ? Groupe::find($groupe_id) : null;

        if ($filiere_id && $groupe_id && $semestre_id && $cycle_id) {
            $modules = Module::where('id_filiere', $filiere_id)
                ->where('id_semestre', $semestre_id)
                ->get();
            
            $emploiExistants = Seance::where('id_groupe', $groupe_id)
                ->where('id_filiere', $filiere_id)
                ->where('id_semestre', $semestre_id)
                ->where('id_cycle', $cycle_id)
                ->get()
                ->groupBy(['jour', function ($item) {
                    return $item->debut . ' - ' . $item->fin;
                }]);
        } else {
            $modules = collect();
            $emploiExistants = collect();
        }

        $salles = Salle::all();
        $enseignants = User::where('id_role', 2)->get();

        if ($filiere_id) {
            session(['last_filiere_id' => $filiere_id]);
        }
        if ($groupe_id) {
            session(['last_groupe_id' => $groupe_id]);
        }
        if ($semestre_id) {
            session(['last_semestre_id' => $semestre_id]);
        }
        if ($cycle_id) {
            session(['last_cycle_id' => $cycle_id]);
        }

        return view('emplois.ajouter', compact(
            'filieres',
            'groupes',
            'modules',
            'salles',
            'enseignants',
            'semestres',
            'cycles',
            'filiere_id',
            'groupe_id',
            'semestre_id',
            'cycle_id',
            'emploiExistants',
            'groupe'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'filiere_id' => 'required|exists:filieres,id_filiere',
            'groupe_id' => 'required|exists:groupes,id_groupe',
            'semestre_id' => 'required|exists:semistres,id_semestre',
            'cycle_id' => 'required|exists:cycles,id',
        ]);

        $filiere_id = $validated['filiere_id'];
        $groupe_id = $validated['groupe_id'];
        $semestre_id = $validated['semestre_id'];
        $cycle_id = $validated['cycle_id'];

        session([
            'last_filiere_id' => $filiere_id,
            'last_groupe_id' => $groupe_id,
            'last_semestre_id' => $semestre_id,
            'last_cycle_id' => $cycle_id
        ]);

        $seances = $request->input('seances', []);

        // Supprimer les séances existantes pour ce groupe/filière/semestre/cycle
        Seance::where('id_filiere', $filiere_id)
            ->where('id_groupe', $groupe_id)
            ->where('id_semestre', $semestre_id)
            ->where('id_cycle', $cycle_id)
            ->delete();

        foreach ($seances as $jour => $plages) {
            foreach ($plages as $plage => $seanceData) {
                if (!empty($seanceData['module_id'])) {
                    [$plage_debut, $plage_fin] = explode(' - ', $plage);

                    Seance::create([
                        'id_filiere' => $filiere_id,
                        'id_semestre' => $semestre_id,
                        'id_cycle' => $cycle_id,
                        'id_groupe' => $groupe_id,
                        'jour' => $jour,
                        'debut' => $plage_debut,
                        'fin' => $plage_fin,
                        'id_module' => $seanceData['module_id'],
                        'id_salle' => $seanceData['salle_id'] ?? null,
                        'type_seance' => $seanceData['type_seance'] ?? 'Cours',
                        'user_id' => $seanceData['user_id'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('emplois.index', [
            'filiere_id' => $filiere_id,
            'groupe_id' => $groupe_id,
            'semestre_id' => $semestre_id,
            'cycle_id' => $cycle_id
        ])->with('success', 'Emploi du temps enregistré avec succès.');
    }

    public function consulter()
    {
        $filieres = Filiere::all();
        $semestres = Semistre::all();
        $cycles = Cycle::all();
        $filiere_id = request('filiere');
        $semestre_id = request('semestre');
        $cycle_id = request('cycle');
        $groupe_id = request('groupe');
        
        $groupes = $filiere_id ? Groupe::where('id_filiere', $filiere_id)->get() : collect();

        // Si tous les critères sont sélectionnés, chercher les séances
        $seances = collect();
        if ($filiere_id && $groupe_id && $semestre_id && $cycle_id) {
            $seances = Seance::where('id_groupe', $groupe_id)
                ->where('id_filiere', $filiere_id)
                ->where('id_semestre', $semestre_id)
                ->where('id_cycle', $cycle_id)
                ->with(['module', 'salle', 'enseignant', 'groupe', 'cycle'])
                ->orderBy('jour')
                ->orderBy('debut')
                ->get();
        }

        return view('emplois.consulter', compact(
            'filieres', 
            'semestres', 
            'cycles', 
            'groupes', 
            'filiere_id', 
            'semestre_id',
            'cycle_id',
            'groupe_id',
            'seances'
        ));
    }

    public function consulter1()
    {
        $filieres = Filiere::all();
        $semestres = Semistre::all();
        $cycles = Cycle::all();
        $filiere_id = request('filiere');
        $semestre_id = request('semestre');
        $cycle_id = request('cycle');
        $groupe_id = request('groupe');
        
        $groupes = $filiere_id ? Groupe::where('id_filiere', $filiere_id)->get() : collect();

        // Si tous les critères sont sélectionnés, chercher les séances
        $seances = collect();
        if ($filiere_id && $groupe_id && $semestre_id && $cycle_id) {
            $seances = Seance::where('id_groupe', $groupe_id)
                ->where('id_filiere', $filiere_id)
                ->where('id_semestre', $semestre_id)
                ->where('id_cycle', $cycle_id)
                ->with(['module', 'salle', 'enseignant', 'groupe', 'cycle'])
                ->orderBy('jour')
                ->orderBy('debut')
                ->get();
        }

        return view('emplois.consulter1', compact(
            'filieres', 
            'semestres', 
            'cycles', 
            'groupes', 
            'filiere_id', 
            'semestre_id',
            'cycle_id',
            'groupe_id',
            'seances'
        ));
    }

    public function rechercher(Request $request)
    {
        $validated = $request->validate([
            'filiere' => 'required|exists:filieres,id_filiere',
            'groupe' => 'required|exists:groupes,id_groupe',
            'semestre' => 'required|exists:semistres,id_semestre',
            'cycle' => 'required|exists:cycles,id',
        ]);

        $filiere_id = $validated['filiere'];
        $groupe_id = $validated['groupe'];
        $semestre_id = $validated['semestre'];
        $cycle_id = $validated['cycle'];

        $seances = Seance::where('id_groupe', $groupe_id)
            ->where('id_filiere', $filiere_id)
            ->where('id_semestre', $semestre_id)
            ->where('id_cycle', $cycle_id)
            ->with(['module', 'salle', 'enseignant', 'groupe', 'cycle'])
            ->orderBy('jour')
            ->orderBy('debut')
            ->get();

        $filieres = Filiere::all();
        $semestres = Semistre::all();
        $cycles = Cycle::all();
        $groupes = Groupe::where('id_filiere', $filiere_id)->get();

        return view('emplois.consulter', compact(
            'seances',
            'filieres',
            'semestres',
            'cycles',
            'groupes',
            'filiere_id',
            'groupe_id',
            'semestre_id',
            'cycle_id'
        ));
    }

    public function rechercher1(Request $request)
    {
        $validated = $request->validate([
            'filiere' => 'required|exists:filieres,id_filiere',
            'groupe' => 'required|exists:groupes,id_groupe',
            'semestre' => 'required|exists:semistres,id_semestre',
            'cycle' => 'required|exists:cycles,id',
        ]);

        $filiere_id = $validated['filiere'];
        $groupe_id = $validated['groupe'];
        $semestre_id = $validated['semestre'];
        $cycle_id = $validated['cycle'];

        $seances = Seance::where('id_groupe', $groupe_id)
            ->where('id_filiere', $filiere_id)
            ->where('id_semestre', $semestre_id)
            ->where('id_cycle', $cycle_id)
            ->with(['module', 'salle', 'enseignant', 'groupe', 'cycle'])
            ->orderBy('jour')
            ->orderBy('debut')
            ->get();

        $filieres = Filiere::all();
        $semestres = Semistre::all();
        $cycles = Cycle::all();
        $groupes = Groupe::where('id_filiere', $filiere_id)->get();

        return view('emplois.consulter1', compact(
            'seances',
            'filieres',
            'semestres',
            'cycles',
            'groupes',
            'filiere_id',
            'groupe_id',
            'semestre_id',
            'cycle_id'
        ));
    }

    public function destroy($id)
    {
        $seance = Seance::findOrFail($id);
        $filiere_id = $seance->id_filiere;
        $groupe_id = $seance->id_groupe;
        $semestre_id = $seance->id_semestre;
        $cycle_id = $seance->id_cycle;

        $seance->delete();

        return redirect()->route('emplois.index', [
            'filiere_id' => $filiere_id,
            'groupe_id' => $groupe_id,
            'semestre_id' => $semestre_id,
            'cycle_id' => $cycle_id
        ])->with('success', 'Séance supprimée avec succès.');
    }

    public function edit($id)
    {
        $seance = Seance::with(['module', 'salle', 'enseignant', 'groupe', 'cycle'])->findOrFail($id);
        $salles = Salle::all();
        $enseignants = User::where('id_role', 2)->get();
        $modules = Module::where('id_filiere', $seance->id_filiere)
                        ->where('id_semestre', $seance->id_semestre)
                        ->get();
        $cycles = Cycle::all();

        return view('emplois.edit', compact('seance', 'salles', 'enseignants', 'modules', 'cycles'));
    }

    public function update(Request $request, $id)
    {
        $seance = Seance::findOrFail($id);

        $validated = $request->validate([
            'type_seance' => 'required|in:Cours,TD,TP',
            'id_salle' => 'required|exists:salles,id_salle',
            'user_id' => 'required|exists:users,id',
            'id_module' => 'required|exists:modules,id_module',
            'id_cycle' => 'required|exists:cycles,id',
        ]);

        $seance->update([
            'type_seance' => $validated['type_seance'],
            'id_salle' => $validated['id_salle'],
            'user_id' => $validated['user_id'],
            'id_module' => $validated['id_module'],
            'id_cycle' => $validated['id_cycle'],
        ]);

        return redirect()->route('emplois.index', [
            'filiere_id' => $seance->id_filiere,
            'groupe_id' => $seance->id_groupe,
            'semestre_id' => $seance->id_semestre,
            'cycle_id' => $seance->id_cycle
        ])->with('success', 'Séance modifiée avec succès.');
    }

    public function exporter(Request $request)
    {
        $validated = $request->validate([
            'filiere_id' => 'required|exists:filieres,id_filiere',
            'groupe_id' => 'required|exists:groupes,id_groupe',
            'semestre_id' => 'required|exists:semistres,id_semestre',
            'cycle_id' => 'required|exists:cycles,id',
        ]);

        $filiere = Filiere::find($validated['filiere_id']);
        $groupe = Groupe::find($validated['groupe_id']);
        $semestre = Semistre::find($validated['semestre_id']);
        $cycle = Cycle::find($validated['cycle_id']);

        $fileName = sprintf(
            '%s_%s_Groupe_%s_S%d_%s.xlsx',
            $cycle->code_cycle,
            $filiere->nom_filiere,
            $groupe->nom_groupe,
            $semestre->numero_semestre ?? $semestre->id_semestre,
            now()->format('Y-m-d')
        );

        return Excel::download(
            new SeancesExport(
                $validated['filiere_id'],
                $validated['groupe_id'],
                $validated['semestre_id'],
                $validated['cycle_id']
            ),
            $fileName
        );
    }

    public function apiDisponibilite()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $salles = Salle::where('disponibilite', true)
            ->select('id_salle', 'nom_salle', 'capacite', 'disponibilite')
            ->get();

        return response()->json([
            'data' => $salles
        ]);
    }

    // Nouvelle méthode pour charger les groupes par filière
    public function getGroupesByFiliere($filiereId)
    {
        try {
            $groupes = Groupe::where('id_filiere', $filiereId)->get();
            return response()->json($groupes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur de chargement des groupes'], 500);
        }
    }

    // Nouvelle méthode pour charger les groupes avec filtre par semestre
    public function getGroupesByFiliereAndSemestre(Request $request)
    {
        try {
            $filiereId = $request->input('filiere_id');
            $semestreId = $request->input('semestre_id');
            
            $query = Groupe::where('id_filiere', $filiereId);
            
            if ($semestreId) {
                $query->where('id_semestre', $semestreId);
            }
            
            $groupes = $query->get();
            
            return response()->json($groupes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur de chargement des groupes'], 500);
        }
    }

    // Méthode pour récupérer les séances avec tous les critères
    public function getSeancesByCriteria($filiere_id, $groupe_id, $semestre_id, $cycle_id)
    {
        return Seance::where('id_groupe', $groupe_id)
            ->where('id_filiere', $filiere_id)
            ->where('id_semestre', $semestre_id)
            ->where('id_cycle', $cycle_id)
            ->with(['module', 'salle', 'enseignant', 'groupe', 'cycle'])
            ->orderBy('jour')
            ->orderBy('debut')
            ->get();
    }
}
