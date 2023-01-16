<?php

session_start();

if (empty($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION["email"];

$conexion = new PDO("mysql:host=localhost;dbname=tienda", "root", "");

$cogerjefe = "SELECT jefe FROM usuario WHERE email = '$email'";
$jefe = $conexion->query($cogerjefe);
$mostrarJefe = $jefe->fetch(PDO::FETCH_ASSOC);

$datos = "SELECT * FROM usuario WHERE email = '$email'";
$conectD = $conexion->query($datos);

$mostrarD = $conectD->fetch();

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link href="css/css.css" rel="stylesheet" type="text/css">
</head>

<body>
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
    <table>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Número de Tlf</th>
            <th>Dirección</th>
        </tr>
        <tr>
            <?php
            echo "<td>$mostrarD[2] $mostrarD[3]</td>";
            echo "<td>$mostrarD[1]</td>";
            echo "<td>$mostrarD[5]</td>";
            echo "<td>$mostrarD[4]</td>";
            ?>
        </tr>
    </table>
</body>

</html>