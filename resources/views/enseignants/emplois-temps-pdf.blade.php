<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Emploi du temps - {{ $user->name }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      color: #333;
    }
    
    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #133b85;
      padding-bottom: 10px;
    }
    
    .header h1 {
      color: #133b85;
      margin: 0;
      font-size: 20px;
    }
    
    .header p {
      margin: 5px 0;
      color: #666;
    }
    
    .info {
      margin-bottom: 15px;
    }
    
    .info p {
      margin: 3px 0;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
    
    th {
      background-color: #f2f4f8;
      font-weight: bold;
    }
    
    .seance {
      margin-bottom: 5px;
      padding: 5px;
      background-color: #f9f9f9;
      border-radius: 3px;
      border-left: 3px solid #133b85;
    }
    
    .module {
      font-weight: bold;
      color: #133b85;
      margin-bottom: 5px;
    }
    
    .details {
      font-size: 10px;
      color: #666;
      line-height: 1.3;
    }
    
    .detail-line {
      margin-bottom: 2px;
    }
    
    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 10px;
      color: #999;
      border-top: 1px solid #ddd;
      padding-top: 10px;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>Faculté des Lettres et des Sciences Humaines</h1>
   
    <h2>Emploi du temps - {{ $user->name }}</h2>
  </div>
  
  <div class="info">
    <p><strong>Enseignant:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Date de génération:</strong> {{ now()->format('d/m/Y H:i') }}</p>
  </div>
  
  <table>
    <thead>
      <tr>
        <th>Jour/Horaire</th>
        <th>8h30 - 10h30</th>
        <th>10h30 - 12h30</th>
        <th>14h30 - 16h30</th>
        <th>16h30 - 18h30</th>
      </tr>
    </thead>
    <tbody>
      @php
        $horaires = [
          '08:30:00-10:30:00' => '8h30 - 10h30',
          '10:30:00-12:30:00' => '10h30 - 12h30', 
          '14:30:00-16:30:00' => '14h30 - 16h30',
          '16:30:00-18:30:00' => '16h30 - 18h30'
        ];
      @endphp

      @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
      <tr>
        <td style="font-weight: bold; background-color: #f2f4f8;">{{ $jour }}</td>
        @foreach($horaires as $horaireKey => $horaireAffichage)
        <td>
          @if(isset($emploisParJour[$jour]) && $emploisParJour[$jour]->count() > 0)
            @foreach($emploisParJour[$jour] as $seance)
              @php
                $seanceHoraire = $seance->debut . '-' . $seance->fin;
              @endphp
              @if($seanceHoraire === $horaireKey)
              <div class="seance">
                <div class="module">{{ $seance->module->nom_module ?? 'Module non défini' }}</div>
                <div class="details">
                  <div class="detail-line"><strong>Filière:</strong> {{ $seance->filiere->nom_filiere ?? 'Non définie' }}</div>
                  <div class="detail-line"><strong>Salle:</strong> {{ $seance->salle->nom_salle ?? 'Non définie' }}</div>
                  <div class="detail-line"><strong>Groupe:</strong> {{ $seance->groupe->nom_groupe ?? 'Non défini' }}</div>
                  <div class="detail-line"><strong>Type:</strong> {{ $seance->type_seance }}</div>
                </div>
              </div>
              @endif
            @endforeach
          @endif
        </td>
        @endforeach
      </tr>
      @endforeach
    </tbody>
  </table>
  
  <div class="footer">
    Généré le {{ now()->format('d/m/Y à H:i') }} | © FLDM - Tous droits réservés
  </div>
</body>
</html>