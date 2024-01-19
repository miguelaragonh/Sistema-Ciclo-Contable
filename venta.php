<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $id = $_POST["idProducto"];
    $precioCompra = $_POST["precio3"];
    $precioVenta = $_POST["precio2"];
    $unidades = $_POST["unidades"];
    $fecha = $_POST["fecha"];
    $cant = $_POST["cant"];

    $ingreso = $precioVenta - $precioCompra;
    $cantAct = $cant - $unidades;

    $sql = $pdo->prepare("SELECT numAsiento FROM asientos ORDER BY numAsiento DESC LIMIT 1");
    $sql->execute();
    $numAsiento = $sql->fetch(PDO::FETCH_ASSOC);
    $pos = ($numAsiento['numAsiento'])?$numAsiento['numAsiento'] + 1 : 1;


    $sql1 = $pdo->prepare("INSERT INTO asientos 
    (numCuenta, monto, tipoMovimiento, fecha, numAsiento, estado) 
    VALUES (201001, :monto, 'D', :fecha, :numAsiento, 1)");
    $sql1->bindParam(':monto', $precioVenta);
    $sql1->bindParam(':fecha', $fecha);
    $sql1->bindParam(':numAsiento', $pos);
    $sql1->execute();

    $sql2 = $pdo->prepare("INSERT INTO asientos 
    (numCuenta, monto, tipoMovimiento, fecha, numAsiento, estado) 
    VALUES (101030, :monto, 'H', :fecha, :numAsiento, 1)");
    $sql2->bindParam(':monto', $precioCompra);
    $sql2->bindParam(':fecha', $fecha);
    $sql2->bindParam(':numAsiento', $pos);
    $sql2->execute();

    $sql3 = $pdo->prepare("INSERT INTO asientos 
    (numCuenta, monto, tipoMovimiento, fecha, numAsiento, estado) 
    VALUES (501001, :monto, 'H', :fecha, :numAsiento, 1)");
    $sql3->bindParam(':monto', $ingreso);
    $sql3->bindParam(':fecha', $fecha);
    $sql3->bindParam(':numAsiento', $pos);
    $sql3->execute();

    $sql4 = $pdo->prepare("UPDATE inventarios set cantidad= $cantAct where id=$id;");
    $sql4->execute();


    var_dump($id . " " . $precioCompra . " " . $precioVenta . " " . $unidades . " " . $cant . " " . $fecha);
}
