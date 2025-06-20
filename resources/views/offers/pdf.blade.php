<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offre #{{ $offer->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 200px;
        }
        .value {
            display: inline-block;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Offre d'Emploi #{{ $offer->id }}</h1>
        @if($offer->candidate)
            <p>Pour : {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Détails du Poste</div>
        <div class="info-row">
            <span class="label">Poste proposé :</span>
            <span class="value">{{ $offer->position_offered }}</span>
        </div>
        <div class="info-row">
            <span class="label">Type de contrat :</span>
            <span class="value">{{ $offer->contract_type ?? 'Non spécifié' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Date de début :</span>
            <span class="value">{{ $offer->start_date ? $offer->start_date->format('d/m/Y') : 'Non spécifiée' }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Rémunération</div>
        <div class="info-row">
            <span class="label">Salaire :</span>
            <span class="value">
                {{ $offer->salary ? number_format($offer->salary, 2, ',', ' ') . ' DH' : 'Non spécifié' }}
                {{ $offer->salary_period ? '(' . $offer->salary_period . ')' : '' }}
            </span>
        </div>
    </div>

    @if($offer->benefits)
    <div class="section">
        <div class="section-title">Avantages</div>
        <div class="value">{{ $offer->benefits }}</div>
    </div>
    @endif

    @if($offer->specific_conditions)
    <div class="section">
        <div class="section-title">Conditions Particulières</div>
        <div class="value">{{ $offer->specific_conditions }}</div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Informations Générales</div>
        <div class="info-row">
            <span class="label">Statut :</span>
            <span class="value">{{ ucfirst($offer->status) }}</span>
        </div>
        <div class="info-row">
            <span class="label">Créée par :</span>
            <span class="value">{{ $offer->createdBy->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Date de création :</span>
            <span class="value">{{ $offer->created_at->format('d/m/Y H:i') }}</span>
        </div>
        @if($offer->sent_at)
        <div class="info-row">
            <span class="label">Envoyée le :</span>
            <span class="value">{{ $offer->sent_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
        @if($offer->expires_at)
        <div class="info-row">
            <span class="label">Expire le :</span>
            <span class="value">{{ $offer->expires_at->format('d/m/Y') }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html> 