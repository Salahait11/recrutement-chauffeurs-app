<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Absences</title>
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
        .justified-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .justified-yes { background-color: #dcfce7; color: #166534; }
        .justified-no { background-color: #fee2e2; color: #991b1b; }
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
        <h1>Liste des Absences</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if(count($filters) > 0)
    <div class="filters">
        <p><strong>Filtres appliqués :</strong></p>
        @if(isset($filters['employee_id']))
            <p>Employé : {{ $filters['employee_id'] }}</p>
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
        @if(isset($filters['is_justified']))
            <p>Justifié : {{ $filters['is_justified'] ? 'Oui' : 'Non' }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Employé</th>
                <th>Date</th>
                <th>Heures</th>
                <th>Motif</th>
                <th>Justifié</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absences as $absence)
                <tr>
                    <td>{{ $absence->employee->user->name ?? 'N/A' }}</td>
                    <td>{{ $absence->absence_date->format('d/m/Y') }}</td>
                    <td>
                        {{ $absence->start_time ? \Carbon\Carbon::parse($absence->start_time)->format('H:i') : '-' }}
                        -
                        {{ $absence->end_time ? \Carbon\Carbon::parse($absence->end_time)->format('H:i') : '-' }}
                    </td>
                    <td>{{ $absence->reason_type ?? '-' }}</td>
                    <td>
                        <span class="justified-badge justified-{{ $absence->is_justified ? 'yes' : 'no' }}">
                            {{ $absence->is_justified ? 'Oui' : 'Non' }}
                        </span>
                    </td>
                    <td>{{ Str::limit($absence->notes, 50) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Aucune absence trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total des absences : {{ $absences->count() }}</p>
    </div>
</body>
</html> 