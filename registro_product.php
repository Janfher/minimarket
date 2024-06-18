<?php
// Iniciar la sesión para almacenar los productos
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Función para guardar los datos del formulario en un array asociativo
    function guardarProducto($nombre, $precio, $cantidad) {
        return [
            "Nombre" => $nombre,
            "Precio" => $precio,
            "Cantidad" => $cantidad,
            "Valor Total" => $precio * $cantidad,
            "Estado" => $cantidad > 0 ? "En stock" : "Agotado"
        ];
    }

    // Función para mostrar una tabla de productos
    function mostrarTablaProductos($productos) {
        echo "<table class='w-full bg-white border border-gray-200 mt-6'>";
        echo "<thead>";
        echo "<tr class='bg-gray-200 text-gray-700 text-sm'>";
        echo "<th class='py-2 px-2'>Nombre</th>";
        echo "<th class='py-2 px-2'>Precio por Unidad</th>";
        echo "<th class='py-2 px-2'>Cantidad en Inventario</th>";
        echo "<th class='py-2 px-2'>Valor Total</th>";
        echo "<th class='py-2 px-2'>Estado</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($productos as $producto) {
            echo "<tr>";
            echo "<td class='py-2 px-2 border-t text-sm'>{$producto['Nombre']}</td>";
            echo "<td class='py-2 px-2 border-t text-sm'>{$producto['Precio']}</td>";
            echo "<td class='py-2 px-2 border-t text-sm'>{$producto['Cantidad']}</td>";
            echo "<td class='py-2 px-2 border-t text-sm'>{$producto['Valor Total']}</td>";
            echo "<td class='py-2 px-2 border-t text-sm'>{$producto['Estado']}</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }

    // Validar y guardar los datos del formulario
    $errores = [];
    if (empty($_POST["nombre"])) {
        $errores["nombre"] = "El nombre es requerido.";
    }
    if (empty($_POST["precio"]) || !is_numeric($_POST["precio"]) || $_POST["precio"] <= 0) {
        $errores["precio"] = "El precio debe ser un número positivo.";
    }
    if (empty($_POST["cantidad"]) || !is_numeric($_POST["cantidad"]) || $_POST["cantidad"] < 0) {
        $errores["cantidad"] = "La cantidad debe ser un número no negativo.";
    }

    if (empty($errores)) {
        // Inicializar el array asociativo de productos si no existe
        if (!isset($_SESSION['productos'])) {
            $_SESSION['productos'] = [];
        }

        // Guardar el producto en el array asociativo
        $_SESSION['productos'][] = guardarProducto($_POST["nombre"], $_POST["precio"], $_POST["cantidad"]);
    } else {
        echo "<script>
            document.getElementById('errorNombre').innerText = '{$errores['nombre']}';
            document.getElementById('errorPrecio').innerText = '{$errores['precio']}';
            document.getElementById('errorCantidad').innerText = '{$errores['cantidad']}';
        </script>";
    }
}
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-6">
    <h2 class="text-2xl font-bold mb-4 text-center text-blue-600">Productos Registrados</h2>
    <?php
    if (isset($_SESSION['productos']) && count($_SESSION['productos']) > 0) {
        mostrarTablaProductos($_SESSION['productos']);
    } else {
        echo "<p class='text-center text-gray-500'>No hay productos registrados.</p>";
    }
    ?>
</div>

<script src="./js/validation.js"></script>
</body>
</html>