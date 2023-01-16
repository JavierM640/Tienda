<?php

session_start();

$conexion = new PDO("mysql:host=localhost;dbname=tienda", "root", "");

$borrar = "";
$cantidad = 1;
$total = 0;

$email = $_SESSION['email'];

$cogerjefe = "SELECT jefe FROM usuario WHERE email = '$email'";
$jefe = $conexion->query($cogerjefe);
$mostrarJefe = $jefe->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['eliminar'])) {

    $id = $_POST['eliminar'];

    foreach ($_SESSION['carrito'] as $key => $valor) {
        if ($valor['Codigo'] == $id) {
            unset($_SESSION['carrito'][$key]);
        }
    }
}

if (isset($_POST['agregar'])) {

    $id = $_POST['agregar'];

    foreach ($_SESSION['carrito'] as $key => $val) {
        if ($val['Codigo'] === $id) {
            $_SESSION['carrito'][$key]['Cantidad'] += 1;
        }
    }
}

if (isset($_POST['quitar'])) {

    $id = $_POST['quitar'];

    foreach ($_SESSION['carrito'] as $key => $val) {
        if ($val['Codigo'] === $id && $val['Cantidad'] > 1) {
            $_SESSION['carrito'][$key]['Cantidad'] -= 1;
        }
    }
}

if (isset($_POST['pago'])) {
    header('Location: pago.php');
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
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

            <div class="contenedor">

                <?php

                if (!empty($_SESSION['carrito'])) {

                    foreach ($_SESSION['carrito'] as $key => $valor) {

                        $cantidadT = $valor['Precio'] * $valor['Cantidad'];
                        echo "<div class='carrito'>";
                        echo "<img src='img/".$valor['Imagen']."'>";
                        echo "<input type='hidden' name='cod' value=" . $valor['Codigo'] . ">";
                        echo "<h4>" . $valor['Nombre'] . "</h4>";
                        echo "<p>Cantidad: " . $valor['Cantidad'] . "</p>";
                        echo "<p>Precio: " .  $cantidadT . " €</p>";
                        echo "<div>";
                        echo "<td><button type='submit' class='sumar' name='agregar'  value=" . $valor['Codigo'] . ">+1</button></td>";
                        echo "<td><button type='submit' class='sumar' name='quitar'  value=" . $valor['Codigo'] . ">-1</button></td>";
                        echo "</div>";
                        echo "<td><button type='submit' class='delete' name='eliminar'  value=" . $valor['Codigo'] . ">Borrar Producto</button></td>";
                        echo "</div>";
                        $total += $cantidadT;
                    }
                    echo "</div>";
                    echo "<div class='pagar'>";
                    echo "<button type='submit' name='pago'>Pagar $total €</button>";
                    echo "</div>";
                }else{
                    echo "<img src='img/vacio.png' class='vacio'>";
                }

                ?>
            

    </form>
</body>

</html>