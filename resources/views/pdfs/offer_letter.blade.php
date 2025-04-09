<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Lettre d'Offre d'Emploi</title>
  <style>
    @page {
      margin: 2cm;
    }

    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 12px;
      color: #2C3E50;
      background-color: #fff;
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #3498DB;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .header strong {
      font-size: 16px;
      color: #2980B9;
    }

    h3 {
      text-align: center;
      color: #2C3E50;
      text-decoration: underline;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .infos-table td {
      padding: 6px 8px;
      vertical-align: top;
      border: 1px solid #D6DBDF;
    }

    .infos-table td:first-child {
      background-color: #ECF0F1;
      font-weight: bold;
      width: 35%;
      color: #2C3E50;
    }

    .section {
      margin-top: 20px;
      text-align: justify;
      color: #2C3E50;
    }

    .date-place {
      margin-top: 25px;
    }

    .signature-block {
      margin-top: 35px;
      display: flex;
      justify-content: space-between;
    }

    .signature {
      width: 45%;
      font-size: 11px;
    }

    .signature p {
      margin-top: 60px;
      border-top: 1px solid #2C3E50;
      text-align: center;
      padding-top: 4px;
      color: #2C3E50;
    }

    .footer {
      margin-top: 30px;
      font-size: 10px;
      color: #7F8C8D;
      border-top: 1px dashed #BDC3C7;
      padding-top: 5px;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="header">
    <strong>{{ config('app.name') }}</strong><br>
    {{ config('app.address') ?? 'Adresse de l\'entreprise' }}<br>
    RC : {{ config('app.rc') ?? 'XXXXX' }} | IF : {{ config('app.if') ?? 'XXXXX' }} | Patente : {{ config('app.patente') ?? 'XXXXX' }}
  </div>

  <h3>Lettre d'offre d'emploi</h3>

  <table class="infos-table">
    <tr>
      <td>Nom du candidat :</td>
      <td>{{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}</td>
    </tr>
    <tr>
      <td>Email :</td>
      <td>{{ $offer->candidate->email }}</td>
    </tr>
    <tr>
      <td>Poste proposé :</td>
      <td>{{ $offer->position_offered }}</td>
    </tr>
    <tr>
      <td>Type de contrat :</td>
      <td>{{ $offer->contract_type ?? 'À définir' }}</td>
    </tr>
    <tr>
      <td>Date de début :</td>
      <td>{{ \Carbon\Carbon::parse($offer->start_date)->format('d/m/Y') }}</td>
    </tr>
    <tr>
      <td>Salaire brut mensuel :</td>
      <td>{{ number_format($offer->salary, 2, ',', ' ') }} MAD</td>
    </tr>
    @if($offer->benefits)
    <tr>
      <td>Avantages :</td>
      <td>{{ $offer->benefits }}</td>
    </tr>
    @endif
    @if($offer->specific_conditions)
    <tr>
      <td>Conditions particulières :</td>
      <td>{{ $offer->specific_conditions }}</td>
    </tr>
    @endif
    <tr>
      <td>Validité de l'offre :</td>
      <td>Jusqu’au {{ \Carbon\Carbon::parse($offer->expires_at)->format('d/m/Y') }}</td>
    </tr>
  </table>

  <div class="section">
    <p>
      Nous avons le plaisir de vous faire part de notre volonté de vous intégrer au sein de notre équipe.
      Pour confirmer votre acceptation, nous vous prions de bien vouloir signer la présente lettre.
    </p>
    <p>
      Cette lettre constitue une offre préalable à la signature du contrat de travail officiel qui précisera les modalités complètes de votre collaboration.
    </p>
  </div>

  <div class="date-place">
    <p>Fait à {{ config('app.city') ?? 'Casablanca' }}, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
  </div>

  <div class="signature-block">
   
    <div class="signature">
      <p>Signature du candidat</p>
    </div>
  </div>

  <div class="footer">
    Cette lettre n’est pas un contrat de travail. Elle doit être suivie d’un contrat signé entre les deux parties selon la législation marocaine.
  </div>

</body>
</html>
