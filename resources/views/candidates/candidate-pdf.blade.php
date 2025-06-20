<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Détails du Candidat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            color: #2563eb;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #4b5563;
        }
        .value {
            color: #1f2937;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: bold;
            background-color: #e5e7eb;
            color: #374151;
        }
        .notes {
            background-color: #f9fafb;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Détails du Candidat</h1>
        <p>{{ $candidate->first_name }} {{ $candidate->last_name }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informations Personnelles</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Email :</span>
                <span class="value">{{ $candidate->email }}</span>
            </div>
            <div class="info-item">
                <span class="label">Téléphone :</span>
                <span class="value">{{ $candidate->phone }}</span>
            </div>
            <div class="info-item">
                <span class="label">Adresse :</span>
                <span class="value">{{ $candidate->address }}</span>
            </div>
            <div class="info-item">
                <span class="label">Date de naissance :</span>
                <span class="value">{{ $candidate->birth_date->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Informations Professionnelles</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Numéro de permis :</span>
                <span class="value">{{ $candidate->driving_license_number }}</span>
            </div>
            <div class="info-item">
                <span class="label">Date d'expiration du permis :</span>
                <span class="value">{{ $candidate->driving_license_expiry->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <span class="label">Années d'expérience :</span>
                <span class="value">{{ $candidate->years_of_experience }} ans</span>
            </div>
            <div class="info-item">
                <span class="label">Statut :</span>
                <span class="status">{{ $statusLabels[$candidate->status] }}</span>
            </div>
        </div>
    </div>

    @if($candidate->notes)
    <div class="section">
        <div class="section-title">Notes</div>
        <div class="notes">
            {{ $candidate->notes }}
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Documents</div>
        @if($candidate->documents->count() > 0)
            <ul>
                @foreach($candidate->documents as $document)
                    <li>{{ $document->original_name }} ({{ round($document->size/1024, 2) }} KB)</li>
                @endforeach
            </ul>
        @else
            <p>Aucun document</p>
        @endif
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #6b7280;">
        <p>Document généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html> 