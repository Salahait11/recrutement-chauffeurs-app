<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Employé - {{ $employee->user->name ?? 'N/A' }}</title>
    <style>
        @page {
            margin: 0.8cm;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        
        .header h1 {
            color: #2563eb;
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 3px 0;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 11px;
            margin: 0;
        }
        
        .main-container {
            display: flex;
            gap: 12px;
        }
        
        .left-column {
            flex: 1;
        }
        
        .right-column {
            flex: 1;
        }
        
        .section {
            margin-bottom: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .section-title {
            background: #f3f4f6;
            color: #374151;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 6px;
            border-bottom: 1px solid #e5e7eb;
            text-transform: uppercase;
        }
        
        .info-grid {
            padding: 4px 6px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 2px;
            align-items: center;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #374151;
            width: 40%;
            font-size: 9px;
        }
        
        .info-value {
            width: 60%;
            font-size: 9px;
            color: #111827;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        
        .table th {
            background: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 3px 4px;
            border: 1px solid #e5e7eb;
            text-align: left;
            font-size: 9px;
        }
        
        .table td {
            padding: 2px 4px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 9px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-terminated {
            background: #fef2f2;
            color: #dc2626;
        }
        
        .status-on_leave {
            background: #fef3c7;
            color: #d97706;
        }
        
        .increase-status {
            display: inline-block;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .increase-applied {
            background: #dcfce7;
            color: #166534;
        }
        
        .increase-pending {
            background: #fef3c7;
            color: #d97706;
        }
        
        .increase-not-applicable {
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .salary-highlight {
            font-weight: bold;
            color: #059669;
        }
        
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
        
        .no-candidate {
            font-style: italic;
            color: #6b7280;
        }
        
        .compact-table {
            margin-top: 4px;
        }
        
        .compact-table th,
        .compact-table td {
            padding: 1px 3px;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>FICHE EMPLOYÉ</h1>
        <p class="subtitle">{{ $employee->user->name ?? 'N/A' }} - Matricule: {{ $employee->employee_number ?? 'N/A' }}</p>
    </div>

    <div class="main-container">
        <!-- Colonne gauche -->
        <div class="left-column">
            <!-- Informations personnelles et professionnelles -->
            <div class="section">
                <div class="section-title">Informations Générales</div>
                <table class="table">
                    <tr>
                        <th width="35%">Nom complet</th>
                        <td>{{ $employee->user->name ?? 'N/A' }}</td>
                        <th width="35%">Poste</th>
                        <td>{{ $employee->job_title ?? 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $employee->user->email ?? 'N/A' }}</td>
                        <th>Département</th>
                        <td>{{ $employee->department ?? 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <th>Téléphone</th>
                        <td>{{ $employee->candidate->phone ?? 'N/A' }}</td>
                        <th>Date d'embauche</th>
                        <td>{{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <th>Adresse</th>
                        <td colspan="3">{{ $employee->candidate->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date de naissance</th>
                        <td>{{ $employee->candidate && $employee->candidate->birth_date ? $employee->candidate->birth_date->format('d/m/Y') : 'Non renseigné' }}</td>
                        <th>Statut</th>
                        <td>
                            <span class="status-badge status-{{ $employee->status }}">
                                {{ $employee->status == 'active' ? 'Actif' : ($employee->status == 'terminated' ? 'Terminé' : ($employee->status == 'on_leave' ? 'En Congé' : ucfirst($employee->status))) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>CIN</th>
                        <td>{{ $employee->candidate->cin ?? 'Non renseigné' }}</td>
                        <th>N° Sécurité Sociale</th>
                        <td>{{ $employee->social_security_number ?? 'Non renseigné' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Permis de conduire -->
            <div class="section">
                <div class="section-title">Permis de Conduire</div>
                <table class="table">
                    <tr>
                        <th width="30%">Numéro</th>
                        <td>{{ $employee->candidate->driving_license_number ?? 'Non renseigné' }}</td>
                        <th width="30%">Expérience</th>
                        <td>{{ $employee->candidate->years_of_experience ?? 0 }} ans</td>
                    </tr>
                    <tr>
                        <th>Date d'obtention</th>
                        <td>
                            {{ $employee->candidate && $employee->candidate->driving_license_obtained_date ? $employee->candidate->driving_license_obtained_date->format('d/m/Y') : 'Non renseigné' }}
                            @if($employee->candidate && $employee->candidate->driving_license_obtained_date)
                                ({{ $employee->candidate->getLicenseSeniority() }} ans)
                            @endif
                        </td>
                        <th>Date d'expiration</th>
                        <td>{{ $employee->candidate && $employee->candidate->driving_license_expiry ? $employee->candidate->driving_license_expiry->format('d/m/Y') : 'Non renseigné' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Informations de candidature -->
            <div class="section">
                <div class="section-title">Informations de Candidature</div>
                @if($employee->candidate)
                <table class="table">
                    <tr>
                        <th width="40%">N° Candidat</th>
                        <td>{{ $employee->candidate->candidate_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date candidature</th>
                        <td>{{ $employee->candidate->created_at ? $employee->candidate->created_at->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Statut candidature</th>
                        <td>{{ $employee->candidate->status ?? 'N/A' }}</td>
                    </tr>
                </table>
                @else
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Statut</div>
                        <div class="info-value no-candidate">Aucun profil candidat associé (création manuelle)</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="right-column">
            <!-- Informations salariales -->
            <div class="section">
                <div class="section-title">Informations Salariales</div>
                <table class="table">
                    <tr>
                        <th width="40%">Salaire initial</th>
                        <td class="salary-highlight">{{ $employee->initial_salary ? number_format($employee->initial_salary, 2, ',', ' ') . ' DH' : 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <th>Salaire actuel</th>
                        <td class="salary-highlight">{{ $employee->salary ? number_format($employee->salary, 2, ',', ' ') . ' DH' : 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <th>Augmentation 3 mois</th>
                        <td>
                            <span class="increase-status {{ $employee->getFirstIncreaseStatusClass() }}">
                                {{ $employee->getFirstIncreaseStatus() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Augmentation 3 ans</th>
                        <td>
                            <span class="increase-status {{ $employee->getSecondIncreaseStatusClass() }}">
                                {{ $employee->getSecondIncreaseStatus() }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Informations administratives -->
            <div class="section">
                <div class="section-title">Informations Administratives</div>
                <table class="table">
                    <tr>
                        <th width="40%">Coordonnées bancaires</th>
                        <td>{{ $employee->bank_details ?? 'Non renseigné' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Historique des augmentations -->
            @if($employee->first_increase_date || $employee->second_increase_date)
            <div class="section">
                <div class="section-title">Historique des Augmentations</div>
                <table class="table compact-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($employee->first_increase_date)
                        <tr>
                            <td>3 mois</td>
                            <td>+1 000 DH</td>
                            <td>{{ $employee->first_increase_date->format('d/m/Y') }}</td>
                            <td><span class="increase-status increase-applied">Appliquée</span></td>
                        </tr>
                        @endif
                        @if($employee->second_increase_date)
                        <tr>
                            <td>3 ans</td>
                            <td>+500 DH</td>
                            <td>{{ $employee->second_increase_date->format('d/m/Y') }}</td>
                            <td><span class="increase-status increase-applied">Appliquée</span></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Documents -->
            @if($employee->candidate && $employee->candidate->documents && $employee->candidate->documents->count() > 0)
            <div class="section">
                <div class="section-title">Documents</div>
                <table class="table compact-table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Type</th>
                            <th>Nom du fichier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee->candidate->documents as $document)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $document->document_type }}</td>
                            <td>{{ $document->file_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p>Fiche générée le {{ now()->format('d/m/Y à H:i') }} | Système de Gestion des Employés</p>
    </div>
</body>
</html> 