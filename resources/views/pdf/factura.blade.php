<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15px;
            color: #333;
            background-color: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }
        .logo-section {
            flex: 1;
        }
        .logo {
            max-width: 120px;
            height: auto;
        }
        .restaurant-info {
            flex: 2;
            text-align: right;
        }
        .restaurant-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        .sucursal-info {
            color: #666;
            margin-bottom: 5px;
            line-height: 1.3;
            font-size: 10px;
        }
        .factura-title {
            font-size: 24px;
            color: #2c3e50;
            margin: 15px 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .factura-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            font-size: 10px;
        }
        .info-section {
            flex: 1;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 3px;
            font-size: 9px;
            text-transform: uppercase;
        }
        .info-value {
            color: #333;
            margin-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            font-size: 10px;
        }
        .items-table th {
            background-color: #2c3e50;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 9px;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .totals {
            width: 250px;
            margin-left: auto;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            font-size: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding: 3px 0;
        }
        .total-label {
            font-weight: bold;
            color: #2c3e50;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #2c3e50;
            padding-top: 8px;
            margin-top: 8px;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 9px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .payment-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            border: 1px solid #eee;
            font-size: 10px;
        }
        .payment-info .info-label {
            color: #2c3e50;
            margin-bottom: 3px;
        }
        .payment-info .info-value {
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background-color: #28a745;
            color: #fff;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-section">
            @if(isset($restaurante) && $restaurante->logo)
                <img src="{{ $restaurante->logo->url }}" alt="Logo" class="logo">
            @endif
        </div>
        <div class="restaurant-info">
            <div class="restaurant-name">
                {{ $restaurante->nombre_legal ?? 'Restaurante' }}
            </div>
            <div class="sucursal-info">
                {{ $sucursal->nombre ?? 'Sucursal' }}<br>
                {{ $sucursal->direccion ?? 'Dirección no disponible' }}<br>
                Tel: {{ $sucursal->telefono ?? 'N/A' }}<br>
                RUC: {{ $restaurante->nro_ruc ?? 'N/A' }}
            </div>
        </div>
    </div>

    <div class="factura-title">FACTURA</div>

    <div class="factura-info">
        <div class="info-section">
            <div class="info-label">Número de Factura</div>
            <div class="info-value">{{ $factura->nro_factura ?? 'N/A' }}</div>
            <div class="info-label">Fecha</div>
            <div class="info-value">{{ $factura->created_at ? $factura->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
        </div>
        <div class="info-section">
            <div class="info-label">Cliente</div>
            <div class="info-value">{{ $factura->orden->nombre_cliente ?? 'Cliente General' }}</div>
            <div class="info-label">Mesa</div>
            <div class="info-value">{{ $factura->orden->mesa->nombre ?? 'N/A' }}</div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th>Precio Unit.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $itemCount = 0;
                $totalPages = ceil(count($items ?? []) / 5);
            @endphp
            @foreach($items ?? [] as $item)
                @php
                    $itemCount++;
                    if ($itemCount > 5 && $itemCount % 5 === 1) {
                        echo '</tbody></table>';
                        echo '<div class="page-break"></div>';
                        echo '<table class="items-table"><thead><tr><th>Cantidad</th><th>Descripción</th><th>Precio Unit.</th><th>Total</th></tr></thead><tbody>';
                    }
                @endphp
                <tr>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->itemMenu->nombre ?? 'Producto' }}</td>
                    <td>S/ {{ number_format($item->monto / $item->cantidad, 2) }}</td>
                    <td>S/ {{ number_format($item->monto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span>S/ {{ number_format($subtotal ?? 0, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">IGV ({{ $factura->igv->valor_porcentaje ?? 18 }}%):</span>
            <span>S/ {{ number_format($igv ?? 0, 2) }}</span>
        </div>
        <div class="total-row grand-total">
            <span>Total:</span>
            <span>S/ {{ number_format($total ?? 0, 2) }}</span>
        </div>
    </div>

    <div class="payment-info">
        <div class="info-label">Método de Pago</div>
        <div class="info-value">{{ $factura->metodoPago->nombre ?? 'N/A' }}</div>
        <div class="info-label">Estado</div>
        <div class="info-value">
            <span class="status-badge status-{{ strtolower($factura->estado_pago ?? 'pending') }}">
                {{ ucfirst($factura->estado_pago ?? 'N/A') }}
            </span>
        </div>
        @if($factura->notas)
            <div class="info-label">Notas</div>
            <div class="info-value">{{ $factura->notas }}</div>
        @endif
    </div>

    <div class="footer">
        <p>Este documento es una representación impresa de una factura electrónica.</p>
        <p>Fecha de impresión: {{ date('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html> 