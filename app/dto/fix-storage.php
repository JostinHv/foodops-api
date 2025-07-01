<?php
// fix-storage.php - Ejecutar una sola vez y luego eliminar este archivo
// Colocar este archivo en la RAÍZ de tu subdominio (junto a index.php)

$target = __DIR__ . '/laravel/storage/app/public';
$link = __DIR__ . '/storage';

// Eliminar enlace existente si existe
if (file_exists($link) || is_link($link)) {
    if (is_link($link)) {
        unlink($link);
    } else {
        // Si es una carpeta, eliminarla (cuidado con esto)
        echo "Existe una carpeta 'storage' en lugar de un enlace. Elimínala manualmente primero.\n";
        exit;
    }
}

// Crear el enlace simbólico
if (symlink($target, $link)) {
    echo "✅ Enlace simbólico creado correctamente\n";
    echo "Target: $target\n";
    echo "Link: $link\n";
} else {
    echo "❌ Error al crear el enlace simbólico\n";
    echo "Es posible que el servidor no permita symlinks\n";
}

// Verificar que funciona
$testImage = 'imagenes/tenants/logos/1g8g2O5qU5LfffM7094gWouenfYLwbXUncieerAq.png';
$fullPath = $target . '/' . $testImage;

if (file_exists($fullPath)) {
    echo "✅ Imagen de prueba encontrada: $fullPath\n";
    echo "URL debería ser: https://foodops.cetivirgendelapuerta.com/foodops/storage/$testImage\n";
} else {
    echo "⚠️  Imagen de prueba no encontrada en: $fullPath\n";
}
?>
