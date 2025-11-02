<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seance;
use App\Models\Groupe;
use App\Models\NonDisponibilite;
use App\Models\Rattrapage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Notifications\DeclarationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Notifications\RattrapageNotification;

class EnseignantController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $enseignants = User::where('id_role', 2)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->get();

        return view('enseignants.index', compact('enseignants'));
    }

    public function create()
    {
        return view('enseignants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => 2,
        ]);

        return redirect()->route('enseignants.index')
            ->with('success', 'Enseignant créé avec succès');
    }

    public function show(User $enseignant)
    {
        return view('enseignants.show', compact('enseignant'));
    }

    public function edit(User $enseignant)
    {
        return view('enseignants.edit', compact('enseignant'));
    }

    public function update(Request $request, User $enseignant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$enseignant->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $enseignant->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $enseignant->password,
        ]);

        return redirect()->route('enseignants.index')
            ->with('success', 'Enseignant mis à jour avec succès');
    }

    public function destroy(User $enseignant)
    {
        $enseignant->delete();
        return redirect()->route('enseignants.index')
            ->with('success', 'Enseignant supprimé avec succès');
    }

    public function dashboard()
    {
        $user = auth()->user();

        // Récupérer les séances liées à l'enseignant, avec les relations nécessaires
        $seances = Seance::where('user_id', $user->id)
                         ->with(['module', 'salle', 'groupe'])
                         ->get();

        // Récupérer tous les groupes pour le formulaire
        $groupes = Groupe::all();

        return view('enseignants.dashboard', compact('seances', 'groupes'));
    }

    // Méthode pour l'historique des non-disponibilités
    public function historique(Request $request)
    {
        $user = auth()->user();
        if ($user->id_role !== 2) {
            abort(403, 'Accès refusé.');
        }

        $search = $request->input('search');
        $filter = $request->input('filter', 'tous');
        
        $historiques = NonDisponibilite::where('enseignant_id', $user->id)
            ->when($search, function ($query, $search) {
                return $query->where('raison', 'like', "%{$search}%")
                             ->orWhere('periode', 'like', "%{$search}%")
                             ->orWhere('statut', 'like', "%{$search}%");
            })
            ->when($filter !== 'tous', function ($query) use ($filter) {
                return $query->where('statut', $filter);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('enseignants.historique', compact('historiques'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès!');
    }

    
    public function declaration()
    {
        $user = Auth::user();
        
        if (!$user || $user->id_role != 2) {
            return redirect('/login')->with('error', 'Accès non autorisé');
        }
        
        return view('enseignants.declaration', compact('user'));
    }

    public function storeDeclaration(Request $request)
    {
        $user = auth()->user();
        
        $rules = [
            'type_periode' => 'required|in:journee,periode',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'raison' => 'required|string|max:500',
        ];

        if ($request->type_periode === 'periode') {
            $rules['periode'] = 'required|in:8:30h-10:30h,10:30h-12:30h,14:30h-16:30h,16:30h-18:30h';
        } else {
            $rules['periode'] = 'nullable';
        }

        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors())
                ->withInput();
        }

        // Vérifier les conflits de dates
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = $request->date_fin ? Carbon::parse($request->date_fin) : $dateDebut;

        // Vérifier les déclarations existantes pour cette période
        $existingDeclaration = NonDisponibilite::where('enseignant_id', $user->id)
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->where(function($q) use ($dateDebut, $dateFin) {
                    // Vérifier si la nouvelle période chevauche une période existante
                    $q->whereBetween('date_debut', [$dateDebut, $dateFin])
                       ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
                       ->orWhere(function($q2) use ($dateDebut, $dateFin) {
                           $q2->where('date_debut', '<=', $dateDebut)
                              ->where('date_fin', '>=', $dateFin);
                       });
                });
            })
            ->when($request->type_periode === 'periode', function($query) use ($request) {
                // Pour les périodes spécifiques, vérifier aussi le créneau horaire
                $query->where('periode', $request->periode);
            })
            ->where('statut', '!=', 'rejete') // Ignorer les déclarations rejetées
            ->first();

        if ($existingDeclaration) {
            return redirect()->back()
                ->with('error', 'Une déclaration existe déjà pour cette période.')
                ->withInput();
        }

        // Créer la déclaration - CORRECTION: Toujours définir la période même pour les journées complètes
        $declaration = NonDisponibilite::create([
            'enseignant_id' => $user->id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin ?: $request->date_debut,
            'type_periode' => $request->type_periode,
            'periode' => $request->type_periode === 'periode' ? $request->periode : 'journee_complete',
            'raison' => $request->raison,
            'statut' => 'en_attente',
        ]);

        // Notifier les administrateurs
        $admins = User::where('id_role', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new DeclarationNotification(
                'Nouvelle déclaration de non-disponibilité de ' . $user->name,
                $declaration->id
            ));
        }

        // Redirection vers l'historique
        return redirect()->route('enseignant.historique')
            ->with('success', 'Déclaration de non-disponibilité soumise avec succès!');
    }

    public function editDeclaration($id)
    {
        $user = auth()->user();
        $declaration = NonDisponibilite::where('id', $id)
                                      ->where('enseignant_id', $user->id)
                                      ->firstOrFail();
        
        return view('enseignants.edit-declaration', compact('declaration'));
    }

    public function updateDeclaration(Request $request, $id)
    {
        $user = auth()->user();
        
        $rules = [
            'type_periode' => 'required|in:journee,periode',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'raison' => 'required|string|max:500',
        ];

        if ($request->type_periode === 'periode') {
            $rules['periode'] = 'required|in:8:30h-10:30h,10:30h-12:30h,14:30h-16:30h,16:30h-18:30h';
        } else {
            $rules['periode'] = 'nullable';
        }

        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors())
                ->withInput();
        }

        $declaration = NonDisponibilite::where('id', $id)
                                      ->where('enseignant_id', $user->id)
                                      ->firstOrFail();

        // Vérifier les conflits avec d'autres déclarations (sauf celle en cours)
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = $request->date_fin ? Carbon::parse($request->date_fin) : $dateDebut;

        $existingDeclaration = NonDisponibilite::where('enseignant_id', $user->id)
            ->where('id', '!=', $id)
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->where(function($q) use ($dateDebut, $dateFin) {
                    // Vérifier si la nouvelle période chevauche une période existante
                    $q->whereBetween('date_debut', [$dateDebut, $dateFin])
                       ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
                       ->orWhere(function($q2) use ($dateDebut, $dateFin) {
                           $q2->where('date_debut', '<=', $dateDebut)
                              ->where('date_fin', '>=', $dateFin);
                       });
                });
            })
            ->when($request->type_periode === 'periode', function($query) use ($request) {
                $query->where('periode', $request->periode);
            })
            ->where('statut', '!=', 'rejete') // Ignorer les déclarations rejetées
            ->first();

        if ($existingDeclaration) {
            return redirect()->back()
                ->with('error', 'Une autre déclaration existe déjà pour cette période.')
                ->withInput();
        }

        $declaration->update([
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin ?: $request->date_debut,
            'type_periode' => $request->type_periode,
            'periode' => $request->type_periode === 'periode' ? $request->periode : 'journee_complete',
            'raison' => $request->raison,
        ]);

        return redirect()->route('enseignant.historique')
            ->with('success', 'Déclaration modifiée avec succès!');
    }

    public function destroyDeclaration($id)
    {
        $user = auth()->user();
        $declaration = NonDisponibilite::where('id', $id)
                                      ->where('enseignant_id', $user->id)
                                      ->firstOrFail();

        $declaration->delete();

        return redirect()->route('enseignant.historique')
            ->with('success', 'Déclaration supprimée avec succès!');
    }

public function emploisTemps(Request $request)
{
    $user = auth()->user();
    
    // Récupérer tous les semestres disponibles
    $semestres = \App\Models\Semistre::all();
    
    // Récupérer le semestre sélectionné ou le premier par défaut
    $selectedSemestre = $request->input('semestre', $semestres->first()->id_semestre ?? null);
    
    // Récupérer les séances de l'enseignant avec toutes les relations nécessaires
    $seances = Seance::where('user_id', $user->id)
                     ->when($selectedSemestre, function($query) use ($selectedSemestre) {
                         return $query->where('id_semestre', $selectedSemestre);
                     })
                     ->with(['module', 'salle', 'groupe', 'filiere', 'semestre'])
                     ->get();

    // Organiser les séances par jour
    $emploisParJour = [
        'Lundi' => collect(),
        'Mardi' => collect(),
        'Mercredi' => collect(),
        'Jeudi' => collect(),
        'Vendredi' => collect(),
        'Samedi' => collect()
    ];

    foreach ($seances as $seance) {
        // Convertir le jour de la base de données (minuscule) en format affichage (avec majuscule)
        $jour = ucfirst($seance->jour);
        
        if (isset($emploisParJour[$jour])) {
            $emploisParJour[$jour]->push($seance);
        }
    }

    return view('enseignants.emplois-temps', compact('user', 'emploisParJour', 'semestres', 'selectedSemestre'));
}

public function downloadEmploisTemps(Request $request)
{
    $user = auth()->user();
    
    // Récupérer le semestre sélectionné
    $selectedSemestre = $request->input('semestre');
    
    // Récupérer les séances de l'enseignant avec toutes les relations nécessaires
    $seances = Seance::where('user_id', $user->id)
                     ->when($selectedSemestre, function($query) use ($selectedSemestre) {
                         return $query->where('id_semestre', $selectedSemestre);
                     })
                     ->with(['module', 'salle', 'groupe', 'filiere', 'semestre'])
                     ->get();

    // Organiser les séances par jour
    $emploisParJour = [
        'Lundi' => collect(),
        'Mardi' => collect(),
        'Mercredi' => collect(),
        'Jeudi' => collect(),
        'Vendredi' => collect(),
        'Samedi' => collect()
    ];

    foreach ($seances as $seance) {
        // Convertir le jour de la base de données (minuscule) en format affichage (avec majuscule)
        $jour = ucfirst($seance->jour);
        
        if (isset($emploisParJour[$jour])) {
            $emploisParJour[$jour]->push($seance);
        }
    }

    $pdf = PDF::loadView('enseignants.emplois-temps-pdf', compact('user', 'emploisParJour', 'selectedSemestre'));
    
    $semestreName = $selectedSemestre ? \App\Models\Semistre::find($selectedSemestre)->nom_semestre : 'tous';
    
    return $pdf->download('emploi-du-temps-' . $user->name . '-' . $semestreName . '.pdf');
}
    public function markNotificationAsRead($id)
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est bien un enseignant
        if ($user->id_role !== 2) {
            return redirect()->route('enseignant.historique')
                ->with('error', 'Accès non autorisé.');
        }

        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            
            // Vérifier le type de notification et rediriger en conséquence
            $data = $notification->data;
            
            // CORRECTION: Vérifier si c'est une notification de rattrapage
            if (isset($data['rattrapage_id'])) {
                // Rediriger vers l'historique des rattrapages
                return redirect()->route('enseignant.rattrapage.historique')
                    ->with('success', 'Notification marquée comme lue');
            } 
            else if (isset($data['declaration_id'])) {
                // Rediriger vers l'historique des déclarations
                return redirect()->route('enseignant.historique')
                    ->with('success', 'Notification marquée comme lue');
            } 
            else {
                // Redirection par défaut vers l'historique des déclarations
                return redirect()->route('enseignant.historique')
                    ->with('success', 'Notification marquée comme lue');
            }
        }

        return redirect()->route('enseignant.historique')
            ->with('error', 'Notification non trouvée');
    }

    public function historiqueRattrapage(Request $request)
    {
        $user = auth()->user();
        
        if ($user->id_role !== 2) {
            abort(403, 'Accès refusé.');
        }

        $search = $request->input('search');
        $filter = $request->input('filter', 'tous');
        
        $rattrapages = Rattrapage::where('user_id', $user->id)
            ->when($search, function ($query, $search) {
                return $query->where('module', 'like', "%{$search}%")
                             ->orWhere('groupe', 'like', "%{$search}%")
                             ->orWhere('statut', 'like', "%{$search}%");
            })
            ->when($filter !== 'tous', function ($query) use ($filter) {
                return $query->where('statut', $filter);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('enseignants.historique-rattrapage', compact('rattrapages'));
    }

    public function rattrapage()
    {
        $user = Auth::user();
        
        if (!$user || $user->id_role != 2) {
            return redirect('/login')->with('error', 'Accès non autorisé');
        }
        
        return view('enseignants.rattrapage', compact('user'));
    }

    public function storeRattrapage(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'periode' => 'required|in:8:30h-10:30h,10:30h-12:30h,14:30h-16:30h,16:30h-18:30h',
            'type_seance' => 'required|in:Cours,TD,TP',
            'module' => 'required|string|max:255',
            'groupe' => 'required|string|max:255',
        ]);

        // Vérifier les conflits de dates
        $date = Carbon::parse($request->date);

        // Vérifier les rattrapages existants pour cette période
        $existingRattrapage = Rattrapage::where('user_id', $user->id)
            ->where('date', $date)
            ->where('periode', $request->periode)
            ->where('statut', '!=', 'rejete')
            ->first();

        if ($existingRattrapage) {
            return redirect()->back()
                ->with('error', 'Un rattrapage existe déjà pour cette période.')
                ->withInput();
        }

        // Créer le rattrapage avec l'ID de l'utilisateur connecté
        $rattrapage = Rattrapage::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'periode' => $request->periode,
            'type_seance' => $request->type_seance,
            'module' => $request->module,
            'groupe' => $request->groupe,
            'statut' => 'en_attente',
            'id_role' => $user->id_role,
        ]);

        // Notifier les administrateurs
        $admins = User::where('id_role', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new RattrapageNotification(
                'Nouvelle demande de rattrapage de ' . $user->name . ' pour le ' . $date->format('d/m/Y'),
                $rattrapage->id,
                'admin'
            ));
        }

        return redirect()->route('enseignant.rattrapage.historique')
            ->with('success', 'Demande de rattrapage soumise avec succès!');
    }

    public function editRattrapage($id)
    {
        $user = auth()->user();
        $rattrapage = Rattrapage::where('id', $id)
                              ->where('user_id', $user->id)
                              ->firstOrFail();
        
        // Vérifier si le rattrapage peut être modifié (seulement s'il est en attente)
        if ($rattrapage->statut !== 'en_attente') {
            return redirect()->route('enseignant.rattrapage.historique')
                ->with('error', 'Seuls les rattrapages en attente peuvent être modifiés.');
        }
        
        return view('enseignants.edit-rattrapage', compact('rattrapage'));
    }

    public function updateRattrapage(Request $request, $id)
    {
        $user = auth()->user();
        
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'periode' => 'required|in:8:30h-10:30h,10:30h-12:30h,14:30h-16:30h,16:30h-18:30h',
            'type_seance' => 'required|in:Cours,TD,TP',
            'module' => 'required|string|max:255',
            'groupe' => 'required|string|max:255',
        ]);

        $rattrapage = Rattrapage::where('id', $id)
                              ->where('user_id', $user->id)
                              ->firstOrFail();

        // Vérifier si le rattrapage peut être modifié (seulement s'il est en attente)
        if ($rattrapage->statut !== 'en_attente') {
            return redirect()->route('enseignant.rattrapage.historique')
                ->with('error', 'Seuls les rattrapages en attente peuvent être modifiés.');
        }

        // Vérifier les conflits avec d'autres rattrapages (sauf celui en cours)
        $date = Carbon::parse($request->date);

        $existingRattrapage = Rattrapage::where('user_id', $user->id)
            ->where('id', '!=', $id)
            ->where('date', $date)
            ->where('periode', $request->periode)
            ->where('statut', '!=', 'rejete')
            ->first();

        if ($existingRattrapage) {
            return redirect()->back()
                ->with('error', 'Un autre rattrapage existe déjà pour cette période.')
                ->withInput();
        }

        $rattrapage->update([
            'date' => $request->date,
            'periode' => $request->periode,
            'type_seance' => $request->type_seance,
            'module' => $request->module,
            'groupe' => $request->groupe,
        ]);

        return redirect()->route('enseignant.rattrapage.historique')
            ->with('success', 'Rattrapage modifié avec succès!');
    }

    public function destroyRattrapage($id)
    {
        $user = auth()->user();
        $rattrapage = Rattrapage::where('id', $id)
                              ->where('user_id', $user->id)
                              ->firstOrFail();

        // Vérifier si le rattrapage peut être supprimé (seulement s'il est en attente)
        if ($rattrapage->statut !== 'en_attente') {
            return redirect()->route('enseignant.rattrapage.historique')
                ->with('error', 'Seuls les rattrapages en attente peuvent être supprimés.');
        }

        $rattrapage->delete();

        return redirect()->route('enseignant.rattrapage.historique')
            ->with('success', 'Rattrapage supprimé avec succès!');
    }
}