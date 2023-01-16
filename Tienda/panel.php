<?php
session_start();

$conexion = new PDO("mysql:host=localhost;dbname=tienda", "root", "");

$categorias = "SELECT * FROM categorias";
$connectC = $conexion->query($categorias);

$error = "";

$email = $_SESSION['email'];

$cogerjefe = "SELECT jefe FROM usuario WHERE email = '$email'";
$jefe = $conexion->query($cogerjefe);
$mostrarJefe = $jefe->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['eliminar'])) {

    $codigo = $_POST['eliminar'];

    $delete = "UPDATE productos SET activo=0 WHERE codigo=$codigo";
    $connect2 = $conexion->query($delete);
}

if (isset($_POST['add'])) {

    $nombre = $_POST['addname'];
    $stock = $_POST['addstock'];
    $categoria = $_POST['selectC'];
    $precio = $_POST['addprecio'];
    $imagen = $_POST['addimagen'];
    $estado = $_POST['addestado'];

    $comprobacionP = "SELECT * FROM productos";
    $connectCP = $conexion->query($comprobacionP);
    $fila = $connectCP->fetch();

    if ($nombre == $fila[1]) {
        $error = "Ya existe un producto con ese nombre";
    } else {
        $añadir = "INSERT INTO productos (nombre, stock, categoria, precio, imagen, activo) VALUES ('$nombre', '$stock', '$categoria', '$precio', '$imagen', '$estado')";
        $connectA = $conexion->query($añadir);
    }
}

if (isset($_POST['user'])) {

    $email = $_POST['correo'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['tlf'];
    $contraseña = $_POST['contraseña'];
    $jefe = $_POST['jefe'];

    $passEncryp = password_hash($contraseña, PASSWORD_DEFAULT);

    $comprobacionEmail = "SELECT email FROM usuario WHERE email = '$email'";
    $comprobacionTlf = "SELECT telefono FROM usuario WHERE telefono = '$telefono'";

    $exiE = $conexion->query($comprobacionEmail);
    $existeE = $exiE->fetch(PDO::FETCH_NUM);

    $exiT = $conexion->query($comprobacionTlf);
    $existeT = $exiT->fetch(PDO::FETCH_NUM);

    $patternTlf = "/^[0-9]{3}[0-9]{3}[0-9]{3}$/";
    $patternN = "/^[a-zA-Z-' ]*$/";
    $patternPass = "/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/";

    if (empty($email) or empty($nombre) or empty($apellidos) or empty($telefono) or empty($contraseña)) {
        $error = "Rellena todos los campos vacios";
    } else if ($existeE > 0) {
        $error = "El usuario ya existe";
    } else if (!preg_match($patternN, $nombre)) {
        $error = "El nombre solo puede contener letras y espacios";
    } else if (!preg_match($patternN, $apellidos)) {
        $error = "Los apellidos solo pueden contener letras y espacios";
    } else if (!preg_match($patternTlf, $telefono)) {
        $error = "El número de teléfono no es válido";
    } else if ($existeT > 0) {
        $error = "Este número de teléfono ya se esta usando";
    } else if (!preg_match($patternPass, $contraseña)) {
        $error = "La contraseña no es válida";
        $formato = "La contraseña tiene que tener entre 8 y 16 carácteres, al menos 1 número y al menos 1 mayúscula";
    } else {
        $añadirU = "INSERT INTO usuario (email, nombre, apellidos, telefono, contraseña, jefe) VALUES ('$email', '$nombre', '$apellidos', '$telefono', '$passEncryp', '$jefe')";
        $connectU = $conexion->query($añadirU);
    }
}

if (isset($_POST['eliminarU'])) {

    $codigo = $_POST['eliminarU'];

    $eliminarU = "UPDATE usuario SET activo=0 WHERE codigo=$codigo";
    $deleteU = $conexion->query($eliminarU);
}

if (isset($_POST['activar'])) {
    $codigo = $_POST['activar'];

    $estadoA = "UPDATE productos SET activo=1 WHERE codigo=$codigo";
    $connectA = $conexion->query($estadoA);
}

if (isset($_POST['desactivar'])) {
    $codigo = $_POST['desactivar'];

    $estadoD = "UPDATE productos SET activo=0 WHERE codigo=$codigo";
    $connectD = $conexion->query($estadoD);
}

if(isset($_POST['update'])){
    $codigo = $_POST['update'];
    $updateS = $_POST['updateE'];

    $update = "UPDATE productos SET stock = $updateS WHERE codigo=$codigo";
    $connectS = $conexion->query($update);

    echo $updateS;
}

$mostrar = "SELECT * FROM productos";
$connect = $conexion->query($mostrar);

$user = "SELECT * FROM usuario WHERE activo = 1";
$connectU = $conexion->query($user);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de control</title>
    <link href="css/css.css" rel="stylesheet" type="text/css">
</head>

<body>
    <form action="" method="POST">
        <div class="navbar">
            <a href="tienda.php"><img src="img/logo.png" class="logo"></a>
            <link rel="stylesheet" href="css/fontawesome/css/all.css">
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
                <th>Código</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th><button type='submit' name='añadir' class="añadir">Añadir Producto</button></th>
            </tr>
            <?php

            if (isset($_POST['añadir'])) {
                echo "<tr>";
                echo "<td></td>";
                echo "<td><input type='text' class='nombre1' name='addname' placeholder='Nombre'></td>";
                echo "<td><input type='number' name='addstock' placeholder='Stock'></td>";
                echo "<td><select name='selectC'>";

                while ($mostrarC = $connectC->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option>" . $mostrarC['nombre'] . "</option>";
                }

                echo "</select></td>";
                echo "<td><input type='number' name='addprecio' placeholder='Precio'></td>";
                echo "<td><input type='text' name='addimagen' placeholder='Imagen'></td>";
                echo "<td><input type='text' name='addestado' placeholder='Estado'></td>";
                echo "<td><button type='submit' name='add' class='añadirP'><i class='fas fa-plus'></i></button></td>";
                echo "</tr>";
            }

            while ($fila = $connect->fetch()) {
                echo "<tr>";
                echo "<td>$fila[0]</td>";
                echo "<td>$fila[1]</td>";
                echo "<td>$fila[3]</td>";
                echo "<td>$fila[4]</td>";
                echo "<td>$fila[5]€</td>";
                echo "<td>$fila[6]</td>";
                if ($fila[7] != 1) {
                    echo "<td><button type='submit' name='activar' class='añadir' value='$fila[0]'>Activar</button></td>";
                    
                } else {
                    echo "<td>Activo</td>";
                    echo "<td><button type='submit' name='eliminar' class='botonP' value='$fila[0]'><img src='img/papelera.png' class='papelera'></button></td>";
                }

                echo "</tr>";
            }

            ?>
        </table>

        <table>
            
            <tr>
                <th>Código</th>
                <th>Correo</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Jefe</th>
                <th><button type='submit' name='añadirU' class="añadir">Añadir Usuario</button></th>
            </tr>
            <?php

            if (isset($_POST['añadirU'])) {
                echo "<tr>";
                echo "<td><input type='text' name='correo' placeholder='Correo'></td>";
                echo "<td><input type='text' name='nombre' placeholder='Nombre'></td>";
                echo "<td><input type='text' name='apellidos' placeholder='Apellidos'></td>";
                echo "<td><input type='number' name='tlf' placeholder='Teléfono'></td>";
                echo "<td><input type='password' name='contraseña' placeholder='Contraseña'></td>";
                echo "<td><input type='text' name='jefe' placeholder='Jefe'></td>";
                echo "<td><button type='submit' name='user' class='añadirP'><i class='fas fa-plus'></i></button></td>";
                echo "</tr>";
            }

            while ($fila = $connectU->fetch()) {
                echo "<tr>";
                echo "<td>$fila[0]</td>";
                echo "<td>$fila[1]</td>";
                echo "<td>$fila[2]</td>";
                echo "<td>$fila[3]</td>";
                echo "<td>$fila[5]</td>";
                echo "<td>$fila[7]</td>";
                echo "<td><button type='submit' name='eliminarU' class='botonP' value='$fila[0]'><img src='img/papelera.png' class='papelera'></button></td>";
                echo "</tr>";
            }

            ?>

        </table>
        <?= $error ?>
    </form>

</body>

</html>