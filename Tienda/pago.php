<?php
session_start();

$conexion = new PDO("mysql:host=localhost;dbname=tienda", "root", "");

$email = $_SESSION['email'];

$cogerjefe = "SELECT jefe FROM usuario WHERE email = '$email'";
$jefe = $conexion->query($cogerjefe);
$mostrarJefe = $jefe->fetch(PDO::FETCH_ASSOC);

$total = 0;

foreach ($_SESSION['carrito'] as $key => $valor) {

    $cantidadT = $valor['Precio'] * $valor['Cantidad'];
    $total += $cantidadT;

    $quitarS = $valor['Stock'] -  $valor['Cantidad'];

    $sql = "UPDATE productos SET stock = $quitarS WHERE codigo = " . $valor['Codigo'];
    $connectP = $conexion->query($sql);
    $carro = $connectP->fetch();
}

$totalP = "<p class='realizado'>El pago de $total € se ha realizado correctamente</p>";

if(isset($_POST['tienda'])){
    header("Location: tienda.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pago</title>
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
                    echo "<div class='producto'>";
                    echo "<input type='hidden' name='cod' value=" . $valor['Codigo'] . ">";
                    echo "<img src='img/" . $valor['Imagen'] . "'>";
                    echo "<h4>" . $valor['Nombre'] . "</h4>";
                    echo "<p>Cantidad: " . $valor['Cantidad'] . "</p>";
                    echo "<p>Total: " . $cantidadT . " €</p>";
                    echo "</div>";
                }
            }

            ?>

        </div>
        <br>
        <?php

        echo "<div class= 'realizado'><p>El pago de $total € se ha realizado correctamente</p></div>";
        unset($_SESSION['carrito']);

        ?>

        <div class="volver">
            <button name='tienda'>Volver a la Tienda</button>
        </div>
        
    </form>

</body>

</html>