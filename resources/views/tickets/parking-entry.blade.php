<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Parqueo #{{ str_pad($session->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm 150mm;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 80mm;
        }
        .center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .barcode {
            text-align: center;
            margin: 15px 0;
        }
        .barcode img {
            width: 60mm;
            height: 40px;
        }
        .info-row {
            margin: 5px 0;
        }
        .header {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header center bold">
        <div style="font-size: 16px;">CENTRO DE SERVICIOS VEHICULARES</div>
        <div style="font-size: 11px;">Calle Falsa 123 - Ciudad</div>
        <div style="font-size: 11px;">NIT: 123.456.789-0</div>
    </div>

    <div class="separator"></div>

    <div class="center bold" style="font-size: 14px; margin: 10px 0;">
        TICKET DE INGRESO #{{ str_pad($session->id, 6, '0', STR_PAD_LEFT) }}
    </div>

    <div class="separator"></div>

    <div class="info-row">
        <strong>PLACA:</strong> {{ strtoupper($session->vehicle->plate) }}
    </div>
    <div class="info-row">
        <strong>TIPO:</strong> {{ $session->vehicle->type == 'car' ? 'AUTOMÓVIL' : 'MOTOCICLETA' }}
    </div>
    @if($session->vehicle->brand)
    <div class="info-row">
        <strong>MARCA:</strong> {{ strtoupper($session->vehicle->brand->name) }}
    </div>
    @endif
    <div class="info-row">
        <strong>FECHA INGRESO:</strong> {{ $session->entry_time->format('d/m/Y') }}
    </div>
    <div class="info-row">
        <strong>HORA INGRESO:</strong> {{ $session->entry_time->format('H:i:s') }}
    </div>

    <div class="separator"></div>

    <div class="barcode">
        <img src="data:image/png;base64,{{ base64_encode(
            \DNS1D::getBarcodePNG((string)$session->id, 'C128', 3, 60)
        ) }}" alt="barcode" />
        <div style="margin-top: 5px; font-size: 10px;">{{ str_pad($session->id, 6, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="center" style="font-size: 11px; margin-top: 15px;">
        Presente este ticket al salir
    </div>
    <div class="center" style="font-size: 10px; margin-top: 5px;">
        Gracias por su visita
    </div>
</body>
</html>
