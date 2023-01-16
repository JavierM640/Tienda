<?php

$conexion = new PDO("mysql:host=localhost;dbname=tienda", "root", "");

$error = '';
$formato = '';

if (isset($_POST['registro'])) {
    $email = $_POST['correo'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellidos'];
    $tlf = $_POST['tlf'];
    $direccion = $_POST['direccion'];
    $contraseña = $_POST['pass'];
    $vcontraseña = $_POST['Vpass'];

    $passEncryp = password_hash($contraseña, PASSWORD_DEFAULT);

    $comprobacionEmail = "SELECT email FROM usuario WHERE email = '$email'";
    $comprobacionTlf = "SELECT telefono FROM usuario WHERE telefono = '$tlf'";

    $crear = "INSERT INTO usuario (email, nombre, apellidos, direccion, telefono, contraseña, activo) 
                VALUES ('$email', '$nombre', '$apellido', '$direccion', '$tlf', '$passEncryp', '1')";

    $exiE = $conexion->query($comprobacionEmail);
    $existeE = $exiE->fetch(PDO::FETCH_NUM);

    $exiT = $conexion->query($comprobacionTlf);
    $existeT = $exiT->fetch(PDO::FETCH_NUM);

    $patternTlf = "/^[0-9]{3}[0-9]{3}[0-9]{3}$/";
    $patternN = "/^[a-zA-Z-' ]*$/";
    $patternPass = "/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/";

    if (empty($email) or empty($nombre) or empty($apellido) or empty($direccion) or empty($tlf) or empty($contraseña) or empty($vcontraseña)) {
        $error = "Rellena todos los campos vacios";
    } else if ($existeE > 0) {
        $error = "El usuario ya existe";
    } else if (!preg_match($patternN, $nombre)) {
        $error = "El nombre solo puede contener letras y espacios";
    } else if (!preg_match($patternN, $apellido)) {
        $error = "Los apellidos solo pueden contener letras y espacios";
    } else if (!preg_match($patternTlf, $tlf)) {
        $error = "El número de teléfono no es válido";
    } else if ($existeT > 0) {
        $error = "Este número de teléfono ya se esta usando";
    } else if (!preg_match($patternPass, $contraseña)) {
        $error = "La contraseña no es válida";
        $formato = "La contraseña tiene que tener entre 8 y 16 carácteres, al menos 1 número y al menos 1 mayúscula";
    } else if ($contraseña !== $vcontraseña) {
        $error = "Las contraseñas no coincicen";
    } else {
        $nuevoUsuario = $conexion->query($crear);
        header("Location: login.php");
    }
}

if (isset($_POST['inicio'])) {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link href="css/css.css" rel="stylesheet" type="text/css">
</head>

<body>
    <form action="" method="POST" class="registro">
        <h5>Registro</h5>
        <div>
            <label for="">
                <input class="boton" type="email" name="correo" placeholder="Correo Electrónico">
            </label>

            <br>
            <br>

            <label for="">
                <input class="boton" type="text" name="nombre" placeholder="Nombre">
            </label>

            <br>
            <br>

            <label for="">
                <input class="boton" type="text" name="apellidos" placeholder="Apellidos">
            </label>

            <br>
            <br>

            <label for="">
                <input class="boton" type="number" name="tlf" placeholder="Número de Teléfono">
            </label>

            <br>
            <br>

            <label for="">
                <input class="boton" type="txt" name="direccion" placeholder="Dirección">
            </label>

            <br>
            <br>

            <label for="">
                <input class="boton" type="password" name="pass" placeholder="Contraseña">
            </label>

            <br>
            <br>

            <label for="">
                <input class="boton" type="password" name="Vpass" placeholder="Verificar Contraseña">
            </label>

            <br>
            <br>

            <button class="enviar" type="submit" name="registro">Registrarse</button>

            <p>¿Tienes cuenta?<a href="login.php"> Iniciar Sesión</a></p>

            <br><br>

            <?= $error ?>
            <br>
            <?= $formato ?>

        </div>
    </form>

</body>

</html>