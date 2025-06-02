<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $factura->nro_factura }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals table {
            width: 300px;
            margin-left: auto;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA</h1>
        <h2>N° {{ $factura->nro_factura }}</h2>
        <p>Fecha: {{ $factura->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <h3>Información del Cliente</h3>
        <p><strong>Cliente:</strong> {{ $factura->orden->nombre_cliente }}</p>
        <p><strong>Mesa:</strong> {{ $factura->orden->mesa->nombre }}</p>
    </div>

    <div class="info-section">
        <h3>Detalle de la Orden</h3>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->itemMenu->nombre }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>S/ {{ number_format($item->itemMenu->precio, 2) }}</td>
                    <td>S/ {{ number_format($item->monto, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>S/ {{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>IGV ({{ $factura->igv->valor_porcentaje }}%):</strong></td>
                <td>S/ {{ number_format($igv, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td>S/ {{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <h3>Información de Pago</h3>
        <p><strong>Método de Pago:</strong> {{ $factura->metodoPago->nombre }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($factura->estado_pago) }}</p>
        @if($factura->estado_pago === 'pagado')
        <p><strong>Fecha de Pago:</strong> {{ $factura->fecha_pago->format('d/m/Y H:i') }}</p>
        @endif
    </div>

    <div class="footer">
        <p>Este documento es una factura electrónica generada automáticamente.</p>
        <p>Gracias por su preferencia.</p>
    </div>
</body>
</html> 