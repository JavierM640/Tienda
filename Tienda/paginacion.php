<?php

$conexion = new PDO("mysql:host=localhost;dbname=tienda", "root", "");

$numElementos = 2;

if (isset($_GET['pag'])) {
    $pagina = $_GET['pag'];
} else {
    $pagina = 1;
}

$sql = "SELECT * FROM productos LIMIT " . (($pagina - 1) * $numElementos)  . "," . $numElementos;
$resultado = $conexion->query($sql);


$sql = "SELECT count(*) as num_productos FROM productos";
$resultadoMaximo = $conexion->query($sql);

$maximoElementos = $resultadoMaximo->fetch()['num_productos'];

?>


<table>
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Stock</th>
        <th>Precio</th>
    </tr>

    <select name="filtro" id="">
        <option value="0">todos</option>
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="15">15</option>

    </select>
    <?php


    while ($fila = $resultado->fetch()) {
        echo "<tr>";
        echo "<td><img src='img/$fila[6]'></td>";
        echo "<td>$fila[1]</td>";
        echo "<td>$fila[5]€</td>";
        echo "<td><button type ='submit' name = 'carro'>Añadir al carrito</button></td>";
        echo "</tr>";
    }



    ?>

</table>

<div>
    <?php
    if (isset($_GET['pag'])) {

        if ($_GET['pag'] > 1) {
    ?>
            <a href="paginacion.php?pag=<?php echo $_GET['pag'] - 1; ?>"><button>Anterior</button></a>
        <?php

        } else {
        ?>
            <a href="#"><button disabled>Anterior</button></a>
        <?php
        }
        ?>

    <?php
    } else {

    ?>
        <a href="#"><button disabled>Anterior</button></a>
        <?php
    }




    if (isset($_GET['pag'])) {

        if ((($pagina) * $numElementos) < $maximoElementos) {
        ?>
            <a href="paginacion.php?pag=<?php echo $_GET['pag'] + 1; ?>"><button>Siguiente</button></a>
        <?php

        } else {
        ?>
            <a href="#"><button disabled>Siguiente</button></a>
        <?php
        }

        ?>

    <?php

    } else {
    ?>
        <a href="paginacion.php?pag=2"><button>Siguiente</button></a>
    <?php
    }


    ?>