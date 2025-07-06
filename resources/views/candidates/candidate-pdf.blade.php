<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche Candidat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #222;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 30px 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 26px;
            margin-bottom: 5px;
        }
        .candidate-number {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            font-family: monospace;
            font-size: 15px;
            padding: 4px 12px;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 22px;
        }
        .section-title {
            color: #2563eb;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .info-table td {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .info-table th {
            background: #f3f4f6;
            color: #2563eb;
            font-weight: bold;
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
        }
        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: bold;
            background-color: #e5e7eb;
            color: #374151;
        }
        .notes {
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 12px 16px;
            border-radius: 4px;
            margin-top: 8px;
            font-size: 13px;
        }
        .documents-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .documents-table th, .documents-table td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            font-size: 12px;
        }
        .documents-table th {
            background: #f3f4f6;
            color: #2563eb;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Fiche Candidat</h1>
        <div class="candidate-number">N° {{ $candidate->candidate_number }}</div>
        <div style="font-size:18px; font-weight:bold; margin-top:8px;">{{ $candidate->first_name }} {{ $candidate->last_name }}</div>
    </div>

    <div class="section">
        <div class="section-title">Identité</div>
        <table class="info-table">
            <tr>
                <th>CIN</th>
                <td>{{ $candidate->cin }}</td>
                <th>Situation familiale</th>
                <td>{{ $candidate->getMaritalStatusLabel() }}</td>
            </tr>
            <tr>
                <th>Nombre d'enfants</th>
                <td>{{ $candidate->children_count ?? 0 }} enfant(s)</td>
                <th>Date de naissance</th>
                                        <td>{{ $candidate->birth_date ? $candidate->birth_date->format('d/m/Y') : 'Non renseigné' }}</td>
            </tr>
            <tr>
                <th>Statut</th>
                <td><span class="status">{{ $statusLabels[$candidate->status] }}</span></td>
                <th></th>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Contact</div>
        <table class="info-table">
            <tr>
                <th>Email</th>
                <td>{{ $candidate->email }}</td>
                <th>Téléphone</th>
                <td>{{ $candidate->phone }}</td>
            </tr>
            <tr>
                <th>Adresse</th>
                <td colspan="3">{{ $candidate->address }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Permis de conduire</div>
        <table class="info-table">
            <tr>
                <th>Numéro de permis</th>
                <td>{{ $candidate->driving_license_number }}</td>
                <th>Date d'obtention</th>
                <td>{{ $candidate->driving_license_obtained_date ? $candidate->driving_license_obtained_date->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
                <th>Date d'expiration</th>
                                        <td>{{ $candidate->driving_license_expiry ? $candidate->driving_license_expiry->format('d/m/Y') : 'Non renseigné' }}</td>
                <th>Années d'expérience</th>
                <td>{{ $candidate->years_of_experience }} ans</td>
            </tr>
        </table>
    </div>

    @if($candidate->notes)
    <div class="section">
        <div class="section-title">Notes & Commentaires</div>
        <div class="notes">
            {{ $candidate->notes }}
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Documents joints</div>
        @if($candidate->documents->count() > 0)
        <table class="documents-table">
            <tr>
                <th>Nom du fichier</th>
                <th>Taille</th>
                <th>Date d'ajout</th>
            </tr>
            @foreach($candidate->documents as $document)
            <tr>
                <td>{{ $document->original_name }}</td>
                <td>{{ round($document->size/1024, 2) }} KB</td>
                <td>{{ $document->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </table>
        @else
            <p style="color:#888; font-size:12px;">Aucun document joint</p>
        @endif
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y H:i') }}
    </div>
</div>
</body>
</html> 