<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket POS</title>
    <style>
        body {
            font-family: 'Courier New', 'Liberation Mono', monospace;
            font-size: 10px;
            line-height: 1.1;
            margin: 0;
            padding: 0;
            color: #000;
            background-color: #fff;
            display: flex;
            justify-content: center;
            min-height: auto;
        }

        .container {
            width: 58mm; /* Ancho del contenido real */
            max-width: 58mm;
            padding: 6mm; /* Margen uniforme de 6mm en todos los lados */
            box-sizing: border-box;
            text-align: center;
        }

        .header {
            margin-bottom: 6px;
            text-align: center;
        }

        .logo {
            max-width: 50px;
            max-height: 50px;
            margin: 0 auto 3px auto;
            display: block;
        }

        .restaurant-name {
            font-size: 11px;
            font-weight: normal;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .sucursal-info {
            font-size: 8px;
            margin-bottom: 2px;
            line-height: 1.0;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
            width: 100%;
        }

        .ticket-info {
            margin-bottom: 6px;
            font-size: 8px;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            width: 100%;
        }

        .info-label {
            display: inline-block;
            width: 100%;
            text-align: left;
        }

        .items-section {
            width: 100%;
            margin-bottom: 6px;
            text-align: left;
        }

        .items-header {
            font-size: 8px;
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 1px;
            margin-bottom: 3px;
            display: flex;
            justify-content: space-between;
        }

        .items-header .cant {
            width: 15%;
            padding-bottom: 2px;
            text-align: center;
        }

        .items-header .desc {
            width: 50%;
            padding-bottom: 2px;
            text-align: left;
        }

        .items-header .precio {
            width: 20%;
            padding-bottom: 2px;
            text-align: right;
        }

        .items-header .total {
            width: 15%;
            text-align: right;
        }

        .item-row {
            font-size: 8px;
            margin-bottom: 1px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .item-cant {
            width: 15%;
            text-align: center;
            flex-shrink: 0;
        }

        .item-desc {
            width: 50%;
            text-align: left;
            word-wrap: break-word;
            padding-right: 2px;
        }

        .item-precio {
            width: 20%;
            text-align: right;
            flex-shrink: 0;
        }

        .item-total {
            width: 15%;
            text-align: right;
            flex-shrink: 0;
        }

        .totals {
            margin-top: 6px;
            border-top: 1px dashed #000;
            padding-top: 4px;
            font-size: 8px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
        }

        .total-label {
            text-align: left;
        }

        .total-value {
            font-size: 10px;
            text-align: right;
        }

        .grand-total {
            font-size: 10px;
            margin-top: 3px;
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: normal;
        }

        .payment-info {
            margin: 6px 0;
            font-size: 8px;
            text-align: left;
        }

        .footer {
            text-align: center;
            margin-top: 6px;
            font-size: 7px;
            border-top: 1px dashed #000;
            padding-top: 4px;
        }

        .footer p {
            margin: 2px 0;
        }

        /* Estilos específicos para impresión */
        @media print {
            body {
                margin: 0;
                padding: 0;
                display: block;
            }

            .container {
                width: 58mm;
                padding: 6mm;
                margin: 0;
            }
        }

        /* Ajustes para PDF */
        @page {
            size: 70mm 200mm;
            margin: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        @if(isset($restaurante) && $restaurante->logo)
            <img src="{{ $restaurante->logo->url }}" alt="Logo" class="logo">
        @endif
        <div class="restaurant-name">
            {{ $restaurante->nombre_legal ?? 'RESTAURANTE' }}
        </div>
        <div class="sucursal-info">
            {{ $sucursal->nombre ?? 'Sucursal Principal' }}<br>
            {{ $sucursal->direccion ?? 'Direccion no disponible' }}<br>
            Tel: {{ $sucursal->telefono ?? 'N/A' }}<br>
            RUC: {{ $restaurante->nro_ruc ?? 'N/A' }}
        </div>
    </div>

    <div class="divider"></div>

    <div class="ticket-info">
        <div class="info-row">
            <span class="info-label">Ticket: {{ $factura->nro_factura ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span
                class="info-label">Fecha: {{ $factura->created_at ? $factura->created_at->format('d/m/Y') : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span
                class="info-label">Hora: {{ $factura->created_at ? $factura->created_at->format('H:i') : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mesa: {{ $factura->orden->mesa->nombre ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Cliente: {{ $factura->orden->nombre_cliente ?? 'General' }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="items-section">
        <div class="items-header">
            <div class="items-header-row">
                <span class="cant">Cant</span>
                <span class="desc">Descripcion</span>
                <span class="precio">P.U.</span>
                <span class="total">Total</span>
            </div>
        </div>

        @foreach($items ?? [] as $item)
            <div class="item-row">
                <span class="item-cant">{{ $item->cantidad }}</span>
                <span class="item-desc">{{ $item->itemMenu->nombre ?? 'Producto' }}</span>
                <span class="item-precio">{{ number_format($item->monto / $item->cantidad, 2) }}</span>
                <span class="item-total">{{ number_format($item->monto, 2) }}</span>
            </div>
        @endforeach
    </div>

    <div class="totals">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span class="total-value">S/ {{ number_format($subtotal ?? 0, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">IGV ({{ $factura->igv->valor_porcentaje ?? 18 }}%):</span>
            <span class="total-value">S/ {{ number_format($igv ?? 0, 2) }}</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">TOTAL:</span>
            <span class="total-value">S/ {{ number_format($total ?? 0, 2) }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="payment-info">
        <div class="info-row">
            <span class="info-label">Metodo Pago:</span>
            <span class="info-value">{{ $factura->metodoPago->nombre ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado:</span>
            <span class="info-value">{{ ucfirst($factura->estado_pago ?? 'N/A') }}</span>
        </div>
        @if($factura->notas)
            <div class="info-row">
                <span class="info-label">Notas:</span>
                <span class="info-value">{{ $factura->notas }}</span>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>GRACIAS POR SU PREFERENCIA</p>
        <p>Ticket electronico</p>
        <p>{{ date('d/m/Y H:i') }}</p>
    </div>
</div>
</body>
</html>
