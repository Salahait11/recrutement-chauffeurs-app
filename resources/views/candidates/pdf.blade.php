<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Candidats</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a56db;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #1a56db;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
        .filters p {
            margin: 5px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #1a56db;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .status-nouveau { background-color: #dbeafe; color: #1e40af; }
        .status-contacte { background-color: #fef3c7; color: #92400e; }
        .status-entretien { background-color: #f3e8ff; color: #5b21b6; }
        .status-test { background-color: #dcfce7; color: #166534; }
        .status-offre { background-color: #fce7f3; color: #9d174d; }
        .status-embauche { background-color: #e0e7ff; color: #3730a3; }
        .status-refuse { background-color: #fee2e2; color: #991b1b; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Liste des Candidats</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if($filters['search'] || $filters['status'] || $filters['date_from'] || $filters['date_to'])
    <div class="filters">
        <p><strong>Filtres appliqués :</strong></p>
        @if($filters['search'])
            <p>Recherche : {{ $filters['search'] }}</p>
        @endif
        @if($filters['status'])
            <p>Statut : {{ $statusLabels[$filters['status']] ?? $filters['status'] }}</p>
        @endif
        @if($filters['date_from'] || $filters['date_to'])
            <p>Période : 
                @if($filters['date_from'])
                    du {{ \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') }}
                @endif
                @if($filters['date_to'])
                    au {{ \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') }}
                @endif
            </p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th>Date d'inscription</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidates as $candidate)
                <tr>
                    <td>{{ $candidate->first_name }} {{ $candidate->last_name }}</td>
                    <td>{{ $candidate->email }}</td>
                    <td>{{ $candidate->phone }}</td>
                    <td>
                        <span class="status-badge status-{{ $candidate->status }}">
                            {{ $statusLabels[$candidate->status] }}
                        </span>
                    </td>
                    <td>{{ $candidate->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Aucun candidat trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total des candidats : {{ $candidates->count() }}</p>
    </div>
</body>
</html> 