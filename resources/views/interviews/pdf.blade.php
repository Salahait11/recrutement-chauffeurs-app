<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Entretiens</title>
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
        .status-planifié { background-color: #dbeafe; color: #1e40af; }
        .status-en_cours { background-color: #fef3c7; color: #92400e; }
        .status-terminé { background-color: #dcfce7; color: #166534; }
        .status-annulé { background-color: #fee2e2; color: #991b1b; }
        .type-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .type-initial { background-color: #dbeafe; color: #1e40af; }
        .type-technique { background-color: #fef3c7; color: #92400e; }
        .type-final { background-color: #dcfce7; color: #166534; }
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
        <h1>Liste des Entretiens</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if(count($filters) > 0)
    <div class="filters">
        <p><strong>Filtres appliqués :</strong></p>
        @if(isset($filters['candidate_id']))
            <p>Candidat : {{ $filters['candidate_id'] }}</p>
        @endif
        @if(isset($filters['type']))
            <p>Type : {{ $filters['type'] }}</p>
        @endif
        @if(isset($filters['status']))
            <p>Statut : {{ $filters['status'] }}</p>
        @endif
        @if(isset($filters['interviewer']))
            <p>Intervieweur : {{ $filters['interviewer'] }}</p>
        @endif
        @if(isset($filters['date_from']) || isset($filters['date_to']))
            <p>Période : 
                @if(isset($filters['date_from']))
                    du {{ \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') }}
                @endif
                @if(isset($filters['date_to']))
                    au {{ \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') }}
                @endif
            </p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Candidat</th>
                <th>Type</th>
                <th>Intervieweur</th>
                <th>Durée</th>
                <th>Statut</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($interviews as $interview)
                <tr>
                    <td>{{ $interview->interview_date->format('d/m/Y H:i') }}</td>
                    <td>{{ $interview->candidate->name }}</td>
                    <td>
                        <span class="type-badge type-{{ $interview->type }}">
                            {{ $interview->type }}
                        </span>
                    </td>
                    <td>{{ $interview->interviewer->name }}</td>
                    <td>{{ $interview->duration }} min</td>
                    <td>
                        <span class="status-badge status-{{ str_replace(' ', '_', $interview->status) }}">
                            {{ $interview->status }}
                        </span>
                    </td>
                    <td>{{ Str::limit($interview->notes, 50) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Aucun entretien trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total des entretiens : {{ $interviews->count() }}</p>
    </div>
</body>
</html> 