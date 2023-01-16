<?php
session_start();

$conexion = new PDO("mysql:host=localhost;dbname=tienda", "root", "");

$nostock = "";
$fijo = "";

$mostrar = "SELECT * FROM productos WHERE activo = 1";
$connect = $conexion->query($mostrar);

$num_filas = $connect->rowCount();

$email = $_SESSION['email'];

$cogerjefe = "SELECT jefe FROM usuario WHERE email = '$email'";
$jefe = $conexion->query($cogerjefe);
$mostrarJefe = $jefe->fetch(PDO::FETCH_ASSOC);

if (empty($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}


if (isset($_POST['carro'])) {

    $codigo = $_POST['carro'];

    $product = "SELECT * FROM productos WHERE codigo = '$codigo'";
    $connectP = $conexion->query($product);
    $carro = $connectP->fetch();

    $stock = "SELECT stock FROM productos WHERE codigo = '$codigo'";
    $comproS = $conexion->query($stock);
    $valor = $comproS->fetch();

    $cantidad = 1;
    if ($valor[0] <= 0) {
        $nostock = "No hay más stock de este producto";
    } else {

        if (!isset($_SESSION['carrito'])) {
            $producto = array(
                'Codigo' => $carro[0],
                'Nombre' => $carro[1],
                'Precio' => $carro[5],
                'Cantidad' => $cantidad,
                'Stock' => $carro[3],
                'Imagen' => $carro[6]
            );

            $_SESSION['carrito'][$codigo] = $producto;
        } else {

            $producto = array(
                'Codigo' => $carro[0],
                'Nombre' => $carro[1],
                'Precio' => $carro[5],
                'Cantidad' => $cantidad,
                'Stock' => $carro[3],
                'Imagen' => $carro[6]
            );

            if (array_key_exists($codigo, $_SESSION['carrito'])) {

                foreach ($_SESSION['carrito'] as $key => $val) {
                    if ($val['Codigo'] == $codigo) {
                        if ($_SESSION['carrito'][$key]['Cantidad'] >= $valor[0]) {
                            $nostock = "No puedes añadir más este producto a tu cesta porque su stock es de " . $valor[0];
                        } else {
                            $_SESSION['carrito'][$key]['Cantidad'] += 1;
                        }
                    }
                }
            } else {

                $_SESSION['carrito'][$codigo] = $producto;
            }
        }
    }
}

$pro = 0;
if (!empty($_REQUEST['cantidad'])) {
    $pro = $_REQUEST['cantidad'];
}

if (isset($_POST['mostrar'])) {

    $mostrar = $_POST['filtro'];

    header("Location: tienda.php?cantidad=$mostrar");
}

if ($pro == 0) {
    $tamaño_paginas = $num_filas;
} else {
    $tamaño_paginas = $pro;
}

if (isset($_GET["pagina"])) {
    $mostrar = $pro;
    if ($_GET['pagina'] == 1) {
        header("Location: tienda.php?cantidad=$mostrar");
    } else {
        $pagina = $_GET["pagina"];
    }
} else {
    $pagina = 1;
}

$empezar_desde = ($pagina - 1) * $tamaño_paginas;

$total_paginas = ceil($num_filas / $tamaño_paginas);

$sql_limite = "SELECT * FROM productos WHERE activo = 1 LIMIT $empezar_desde, $tamaño_paginas";

$resultado = $conexion->query($sql_limite);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tienda</title>
    <link href="css/css.css" rel="stylesheet" type="text/css">
</head>

<body>
    <form action="" method="POST">
        <div class="navbar">
            <a href="tienda.php"><img src="img/logo.png" class="logo"></a>
            <ul>
                <li><a href="carrito.php"><img src="img/cesta.png" class="cesta"></a></li>
                <li><a href="perfil.php"><img src="img/user.png" class="user"></a>
                    <ul>
                        <li><a href="logout.php">Cerrar sesión</a></li>
                        <?php
                        if ($mostrarJefe['jefe'] == "Sí") {
                            echo "<li><a href='panel.php'>Panel de control</a></li>";
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="filtro">
            <select name="filtro">
                <?php

                $consulta = "SELECT * FROM filtro";
                $connectC = $conexion->query($consulta);

                while ($row = $connectC->fetch()) {

                    if ($pro == $row[1]) {

                        echo "<option value='$row[1]' selected>$row[0]</option>";
                    } else {

                        echo "<option value='$row[1]'>$row[0]</option>";
                    }
                }

                ?>

            </select>
        </div>

        <div class="update">
            <button class="update" type="submit" name="mostrar">Actualizar </button>
            <br>
        </div>

        <div class="contenedor">
            <?php

            while ($registro = $resultado->fetch()) {
                echo "<div class='producto'>";
                echo "<img src='img/$registro[6]'>";
                echo "<h4>$registro[1]</h4>";
                echo "<input type='hidden' name='nombre' value='$registro[1]'>";
                echo "<p>Stock: $registro[3]</p>";
                echo "<p>Precio: $registro[5]€</p>";
                echo "<input type='hidden' name='precio' value='$registro[5]'>";
                echo "<input type='hidden' name='stock' value='$registro[3]'>";
                echo "<button class='comprar' type='submit' name='carro' value='$registro[0]'>Añadir al carrito</button>";
                echo "</div>";
            }

            ?>

        </div>

        <?= "<p class='nostock'>$nostock<p>" ?>
        
        <div class="paginas">
            <ul>
                <?php

                for ($i = 1; $i <= $total_paginas; $i++) {

                    echo "<li><a href='?pagina=$i&cantidad=$pro' class='paginacion'>  $i </a></li>";
                }

                ?>
            </ul>
        </div>



    </form>

</body>

</html>