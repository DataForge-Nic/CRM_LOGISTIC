<div style="font-family: Arial, sans-serif; color: #222;">
    <h2 style="color: #1A2E75;">Factura de SkylinkOne</h2>
    <p>Hola {{ $factura->cliente->nombre_completo ?? '' }},</p>
    <p>Adjunto encontrarás tu factura generada por SkylinkOne CRM.</p>
    <p><strong>Monto total:</strong> ${{ number_format($factura->monto_total, 2) }} USD</p>
    <p><strong>Fecha:</strong> {{ $factura->fecha_factura }}</p>
    <p>Si tienes alguna duda, contáctanos.</p>
    <br>
    <p style="color:#888; font-size:0.95em;">Este correo fue enviado automáticamente por SkylinkOne CRM.</p>
</div> 