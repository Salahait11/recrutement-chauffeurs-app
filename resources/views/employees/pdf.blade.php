<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Employés</title>
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 10px;
            transform: rotate(0deg);
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #1a56db;
            padding-bottom: 8px;
        }
        .header h1 {
            color: #1a56db;
            font-size: 20px;
            margin-bottom: 3px;
        }
        .filters {
            margin-bottom: 15px;
            padding: 8px;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
        .filters p {
            margin: 3px 0;
            font-size: 9px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #1a56db;
            font-size: 9px;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }
        .status-active { background-color: #dcfce7; color: #166534; }
        .status-terminated { background-color: #fee2e2; color: #991b1b; }
        .status-on_leave { background-color: #fef3c7; color: #92400e; }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .increase-info {
            font-size: 8px;
            line-height: 1.2;
        }
        .increase-info div {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Liste des Employés</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if(count($filters) > 0)
    <div class="filters">
        <p><strong>Filtres appliqués :</strong></p>
        @if(isset($filters['department']))
            <p>Département : {{ $filters['department'] }}</p>
        @endif
        @if(isset($filters['status']))
            <p>Statut : {{ $filters['status'] }}</p>
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
                <th>Matricule</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Poste</th>
                <th>Département</th>
                <th>Date d'embauche</th>
                <th>Salaire</th>
                <th>Augmentations</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->employee_number ?? '-' }}</td>
                    <td>{{ $employee->user->name ?? 'N/A' }}</td>
                    <td>{{ $employee->user->email ?? 'N/A' }}</td>
                    <td>{{ $employee->job_title ?? '-' }}</td>
                    <td>{{ $employee->department ?? '-' }}</td>
                    <td>{{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $employee->salary ? number_format($employee->salary, 2, ',', ' ') . ' DH' : '-' }}</td>
                    <td>
                        <div class="increase-info">
                            <div><strong>3 mois:</strong> {{ $employee->getFirstIncreaseStatus() }}</div>
                            <div><strong>3 ans:</strong> {{ $employee->getSecondIncreaseStatus() }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $employee->status }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Aucun employé trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total des employés : {{ $employees->count() }}</p>
    </div>
</body>
</html> 