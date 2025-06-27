<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $factura->id }} - SkyLink One</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            margin: 0;
            padding: 0;
            font-size: 13px;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #1A1A5E;
            padding: 20px 0 10px 0;
        }
        .logo {
            width: 180px;
        }
        .factura-info {
            flex: 1;
            text-align: right;
        }
        .factura-info h2 {
            margin: 0;
            color: #1A1A5E;
            font-size: 1.3rem;
        }
        .factura-info table {
            margin-left: auto;
        }
        .section {
            margin: 18px 0 0 0;
        }
        .section-title {
            color: #1A1A5E;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 2px 6px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background: #1A1A5E;
            color: #fff;
            padding: 6px 4px;
            font-size: 13px;
        }
        .table td {
            border-bottom: 1px solid #e9ecef;
            padding: 5px 4px;
        }
        .table-striped tr:nth-child(even) td {
            background: #f4f4f4;
        }
        .totals {
            margin-top: 18px;
            width: 100%;
        }
        .totals td {
            padding: 4px 6px;
        }
        .totals .label {
            color: #1A1A5E;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo_skylinkone.png') }}" class="logo" alt="SkyLink One Logo">
        <div class="factura-info">
            <h2>Factura</h2>
            <table>
                <tr>
                    <td><strong>Fecha:</strong></td>
                    <td>{{ \Carbon\Carbon::parse($factura->fecha_factura)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td><strong>N° de factura:</strong></td>
                    <td>{{ $factura->id }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <table class="info-table">
            <tr>
                <td class="section-title">Para:</td>
                <td class="section-title">Envia:</td>
            </tr>
            <tr>
                <td>
                    {{ $factura->cliente->nombre_completo ?? '' }}<br>
                    {{ $factura->cliente->direccion ?? '' }}<br>
                    {{ $factura->cliente->telefono ?? '' }}
                </td>
                <td>
                    SkyLink One<br>
                    Managua<br>
                    85607503
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Warehouse</th>
                    <th>Descripción</th>
                    <th>Tracking</th>
                    <th>Servicio</th>
                    <th>Peso (lb)</th>
                    <th>Precio Unitario</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
            @php $total = 0; @endphp
            @foreach($factura->paquetes as $paquete)
                <tr>
                    <td>{{ $paquete->numero_guia ?? '-' }}</td>
                    <td>{{ $paquete->notas ?? '-' }}</td>
                    <td>{{ $paquete->tracking_codigo ?? '-' }}</td>
                    <td>
                        @if($paquete->servicio)
                            {{ Str::contains(strtolower($paquete->servicio->tipo_servicio), 'mar') ? 'Mar' : 'Air' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $paquete->peso_lb ?? '-' }}</td>
                    <td>${{ number_format($paquete->tarifa_manual ?? $paquete->monto_calculado, 2) }}</td>
                    <td>${{ number_format($paquete->monto_calculado, 2) }}</td>
                </tr>
                @php $total += $paquete->monto_calculado; @endphp
            @endforeach
            </tbody>
        </table>
    </div>

    <table class="totals">
        <tr>
            <td class="label text-right">Subtotal:</td>
            <td class="text-right">${{ number_format($total, 2) }}</td>
        </tr>
        <tr>
            <td class="label text-right">Delivery:</td>
            <td class="text-right">${{ number_format($factura->delivery ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="label text-right">Total:</td>
            <td class="text-right">${{ number_format($total + ($factura->delivery ?? 0), 2) }}</td>
        </tr>
    </table>

    <div class="section" style="margin-top: 30px;">
        <span class="section-title">Nota:</span>
        <span>{{ $factura->nota }}</span>
    </div>
</body>
</html> 