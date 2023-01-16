<?php

session_start();

$conexion = new PDO("mysql:host=localhost;dbname=tienda", "root", "");

$error = '';

if (isset($_POST['iniciar'])) {
    $_SESSION['email'] = $_POST['email'];
    $email = $_SESSION['email'];
    $contraseña = $_POST['pass'];

    $verificar = "SELECT email,contraseña FROM usuario WHERE email = '$email' AND activo = '1'";

    $connect = $conexion->query($verificar);
    $row = $connect->fetch(PDO::FETCH_ASSOC);


    $cogerjefe = "SELECT jefe FROM usuario WHERE email = '$email'";
    $jefe = $conexion->query($cogerjefe);
    $mostrarJefe = $jefe->fetch(PDO::FETCH_ASSOC);

    if ($email == '' or $contraseña == '') {
        $error = "Rellena todos los campos";
    } else if ((!empty($row['contraseña']) && (password_verify($contraseña, $row['contraseña'])) && (!empty($row['email'])))) {

        session_start();

        $_SESSION["email"] = $email;
        header("Location: tienda.php");
    } else {
        $error = "Usuario o contraseña incorrecto";
    }
}

if (isset($_POST['registro'])) {
    header("Location: Registro.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inicio Sesión</title>
    <link href="css/css.css" rel="stylesheet">
</head>

<body>
    <form action="" method="POST" class="login">
        <h5>Iniciar Sesión</h5>

        <label for="">
            <input class="boton" type="text" name="email" placeholder="Correo Electronico">
        </label>

        <br>
        <br>

        <label for="">
            <input class="boton" type="password" name="pass" placeholder="Contraseña">
        </label>

        <br>
        <br>

        <button class="enviar" type="submit" name="iniciar">Iniciar Sesión</button>

        <p class="error"><?= $error ?></p>

        <br>

        <p>¿No tienes cuenta?<a href="registro.php"> Registrarte</a></p>

        <br>
        

    </form>

</body>

</html>