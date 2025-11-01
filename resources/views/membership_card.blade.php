<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership Card</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 30px 20px;
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }

        .card {
            width: 650px;
            height: 400px;
            position: relative;
            background: url('{{ public_path("img/NOK-Card-BG.jpg") }}') no-repeat center center;
            background-size: cover;
            border-radius: 12px;
            overflow: hidden;
            color: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        .overlay {
            position: absolute;
            top: 0; 
            left: 0;
            width: 100%; 
            height: 100%;
            background: rgba(0,0,0,0.35);
        }

      
        .title {
            position: absolute;
            top: 28px;
            right: 22px;
            font-size: 19px;
            font-weight: 700;
            text-align: right;
            color: #ffffff;
            line-height: 1.2;
        }

        .logo {
            position: absolute;
            top: 28px;
            left: 22px;
            width: 85px; 
        }

        .member-info {
            position: absolute;
            left: 20px;
            top: 220px;
            line-height: 1.4;
        }

        .member-info p {
            margin: 3px 0;
        }

        .member-info p:nth-child(1) {
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .member-info p:nth-child(2),
        .member-info p:nth-child(3),
        .member-info p:nth-child(4),
        .member-info p:nth-child(5) {
            font-size: 15px;
            font-weight: 500;
        }

        .nok-id {
            position: absolute;
            top: 220px;
            right: 20px;
            font-size: 13px;
            font-weight: bold;
            background: #0039A9;
            padding: 6px 12px;
            border-radius: 10px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        
        .civil-info {
            position: absolute;
            bottom: 15px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 13px;
            color: #ffffff;
            opacity: 0.95;
            letter-spacing: 0.5px;
        }

        .civil-info p {
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="overlay"></div>

        <img src="{{ public_path('img/NOKlogo.png') }}" alt="Logo" class="logo">
        <div class="title">NIGHTINGALES OF KUWAIT (NOK)
        <p>(NOK Membership Card)</p>
        </div>

        <div class="member-info">
            <p>{{ $record->memberName }}</p>
            <p>Civil ID: {{ $record->civil_id }}</p>
            <p>Date of Membership: {{ $record->card_issued_at?->format('d-m-Y') ?? 'N/A' }}</p>
            <p>Date of Expiry: {{ $record->card_valid_until?->format('d-m-Y') ?? 'N/A' }}</p>
            <p>Contact No: {{ $record->mobile }}</p>
        </div>

        <div class="nok-id">
            {{ $record->nok_id }}
        </div>

        <div class="civil-info">
            <p>nightingalesofkuwait24@gmail.com | www.nokkw.org</p>
        </div>
    </div>

</body>
</html>
