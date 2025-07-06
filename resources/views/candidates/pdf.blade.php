<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Candidats</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            color: #222;
        }
        .container {
            margin: 10px 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #1a56db;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #1a56db;
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .header .subtitle {
            color: #6b7280;
            font-size: 12px;
        }
        .filters {
            margin-bottom: 20px;
            padding: 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .filters h3 {
            color: #1a56db;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 8px;
        }
        .filter-item {
            font-size: 10px;
        }
        .filter-label {
            font-weight: bold;
            color: #374151;
        }
        .filter-value {
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 4px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #1a56db;
            color: white;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tr:hover {
            background-color: #f3f4f6;
        }
        .status-badge {
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            min-width: 50px;
        }
        .status-nouveau { background-color: #dbeafe; color: #1e40af; }
        .status-contacte { background-color: #fef3c7; color: #92400e; }
        .status-entretien { background-color: #f3e8ff; color: #5b21b6; }
        .status-test { background-color: #e0e7ff; color: #3730a3; }
        .status-offre { background-color: #fce7f3; color: #9d174d; }
        .status-embauche { background-color: #dcfce7; color: #166534; }
        .status-refuse { background-color: #fee2e2; color: #991b1b; }
        .candidate-number {
            font-family: monospace;
            font-weight: bold;
            color: #1a56db;
            font-size: 9px;
        }
        .contact-info {
            font-size: 8px;
        }
        .contact-email {
            font-weight: bold;
        }
        .contact-phone {
            color: #6b7280;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #6b7280;
        }
        .stats {
            display: flex;
            gap: 15px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 14px;
            font-weight: bold;
            color: #1a56db;
        }
        .stat-label {
            font-size: 8px;
            color: #6b7280;
        }
        .empty-message {
            text-align: center;
            padding: 30px;
            color: #6b7280;
            font-style: italic;
        }
        /* Largeurs spécifiques pour les colonnes */
        .col-number { width: 12%; }
        .col-name { width: 15%; }
        .col-cin { width: 12%; }
        .col-marital { width: 12%; }
        .col-license { width: 12%; }
        .col-contact { width: 20%; }
        .col-status { width: 10%; }
        .col-date { width: 7%; }
        
        /* Styles spécifiques pour les statistiques horizontales */
        .stats-container {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            gap: 30px !important;
            align-items: center !important;
            justify-content: flex-start !important;
        }
        .stat-box {
            display: inline-block !important;
            text-align: center !important;
            min-width: 80px !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .stat-number {
            font-size: 18px !important;
            font-weight: bold !important;
            color: #1a56db !important;
            display: block !important;
        }
        .stat-label {
            font-size: 9px !important;
            color: #6b7280 !important;
            display: block !important;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Liste des Candidats</h1>
        <div class="subtitle">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    @if($filters['search'] || $filters['status'] || $filters['date_from'] || $filters['date_to'])
    <div class="filters">
        <h3>Filtres appliqués</h3>
        <div class="filters-grid">
            @if($filters['search'])
                <div class="filter-item">
                    <span class="filter-label">Recherche :</span>
                    <span class="filter-value">{{ $filters['search'] }}</span>
                </div>
            @endif
            @if($filters['status'])
                <div class="filter-item">
                    <span class="filter-label">Statut :</span>
                    <span class="filter-value">{{ $statusLabels[$filters['status']] ?? $filters['status'] }}</span>
                </div>
            @endif
            @if($filters['date_from'] || $filters['date_to'])
                <div class="filter-item">
                    <span class="filter-label">Période :</span>
                    <span class="filter-value">
                        @if($filters['date_from'])
                            du {{ \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') }}
                        @endif
                        @if($filters['date_to'])
                            au {{ \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') }}
                        @endif
                    </span>
                </div>
            @endif
        </div>
    </div>
    @endif

    <div style="margin-bottom: 15px; padding: 10px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
        <!-- En-tête avec titre et pagination -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 10px;">
            <div style="font-weight: bold; color: #1a56db;">Statistiques des candidats</div>
            <div style="font-size: 9px; color: #6b7280;">
                @if($candidates instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    Page {{ $candidates->currentPage() }} sur {{ $candidates->lastPage() }}
                    ({{ $candidates->total() }} candidats au total)
                @else
                    {{ $candidates->count() }} candidats affichés
                @endif
            </div>
        </div>
        
        <!-- Statistiques en ligne horizontale -->
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-number">{{ $candidates->count() }}</div>
                <div class="stat-label">Total candidats</div>
            </div>
            @php
                $statusCounts = $candidates->groupBy('status')->map->count();
            @endphp
            @foreach($statusCounts as $status => $count)
                <div class="stat-box">
                    <div class="stat-number">{{ $count }}</div>
                    <div class="stat-label">{{ $statusLabels[$status] ?? $status }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-number">N° Candidat</th>
                <th class="col-name">Nom & Prénom</th>
                <th class="col-cin">CIN</th>
                <th class="col-marital">Situation familiale</th>
                <th class="col-license">Date obt. permis</th>
                <th class="col-contact">Contact</th>
                <th class="col-status">Statut</th>
                <th class="col-date">Date d'Ajout</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidates as $candidate)
                <tr>
                    <td class="candidate-number">{{ $candidate->candidate_number }}</td>
                    <td>
                        <strong>{{ $candidate->first_name }} {{ $candidate->last_name }}</strong>
                    </td>
                    <td>{{ $candidate->cin ?: '-' }}</td>
                    <td>{{ $candidate->getMaritalStatusLabel() ?: '-' }}</td>
                    <td>{{ $candidate->driving_license_obtained_date ? $candidate->driving_license_obtained_date->format('d/m/Y') : '-' }}</td>
                    <td class="contact-info">
                        <div class="contact-email">{{ $candidate->email }}</div>
                        <div class="contact-phone">{{ $candidate->phone }}</div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $candidate->status }}">
                            {{ $statusLabels[$candidate->status] }}
                        </span>
                    </td>
                    <td>{{ $candidate->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty-message">Aucun candidat trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div style="font-size: 9px; color: #6b7280;">
            Document généré le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>
</div>
</body>
</html> 