{{-- resources/views/pdfs/employee_summary.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fiche Employé - {{ $employee->user->name ?? 'Employé' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            font-size: 10pt;
            background-color: #fdfdfd;
        }

        h1 {
            text-align: center;
            color: #34495e;
            border-bottom: 2px solid #2980b9;
            padding-bottom: 10px;
            margin-bottom: 30px;
            font-size: 18pt;
        }

        h2 {
            color: #2980b9;
            margin-top: 30px;
            margin-bottom: 10px;
            font-size: 13pt;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }

        .header-info {
            text-align: right;
            font-size: 9pt;
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ecf0f1;
            border-radius: 4px;
            overflow: hidden;
        }

        td {
            padding: 8px 10px;
            vertical-align: top;
            border-bottom: 1px solid #bdc3c7;
        }

        td:first-child {
            width: 180px;
            font-weight: bold;
            background-color: #d6eaf8;
            color: #2c3e50;
        }

        td:last-child {
            background-color: #f8f9f9;
        }

        .section {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class="header-info">
        Généré le : {{ now()->format('d/m/Y H:i') }}<br>
        {{ config('app.name', 'Votre Entreprise') }}
    </div>

    <h1>Fiche Employé</h1>

    <div class="section">
        <h2>Informations Principales</h2>
        <table>
            <tr><td>Nom Complet :</td><td>{{ $employee->user->name ?? 'N/A' }}</td></tr>
            <tr><td>Email :</td><td>{{ $employee->user->email ?? 'N/A' }}</td></tr>
            <tr><td>Matricule :</td><td>{{ $employee->employee_number ?? '-' }}</td></tr>
            <tr><td>Poste :</td><td>{{ $employee->job_title ?? '-' }}</td></tr>
            <tr><td>Département :</td><td>{{ $employee->department ?? '-' }}</td></tr>
            <tr><td>Manager :</td><td>{{ $employee->manager->name ?? '-' }}</td></tr>
            <tr><td>Date d'Embauche :</td><td>{{ optional($employee->hire_date)->format('d/m/Y') }}</td></tr>
            <tr><td>Statut Actuel :</td><td>{{ ucfirst($employee->status ?? '-') }}</td></tr>
            @if($employee->status === 'terminated')
                <tr><td>Date de Fin :</td><td>{{ optional($employee->termination_date)->format('d/m/Y') }}</td></tr>
            @endif
            @if($employee->candidate)
                <tr><td>Profil Candidat :</td><td>#{{ $employee->candidate_id }} (Origine recrutement)</td></tr>
            @endif
            <tr><td>Salaire :</td><td>{{ $employee->salary ? number_format($employee->salary, 2, ',', ' ') . ' DH' : '-' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>Informations Administratives</h2>
        <table>
            <tr><td>N° Sécurité Sociale :</td><td>{{ $employee->social_security_number ?? '-' }}</td></tr>
            <tr><td>Coordonnées Bancaires :</td><td>{{ $employee->bank_details ?? '-' }}</td></tr>
        </table>
    </div>
</body>
</html>
