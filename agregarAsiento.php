<?php
require_once('conexion.php');
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
     print_r($data);
    $sql = $pdo->prepare("INSERT INTO asientos (numCuenta, monto, tipoMovimiento, fecha, numAsiento, estado) VALUES (:numCuenta, :monto, :tipoMovimiento, :fecha, :numAsiento,1)");
    $sql->bindParam(':numCuenta', $data['datos'][0]);
    $sql->bindParam(':monto', $data['datos'][1]);
    $sql->bindParam(':tipoMovimiento', $data['datos'][3]);
    $sql->bindParam(':fecha', $data['datos'][2]);
    $sql->bindParam(':numAsiento', $data['datos'][4]);
    $sql->execute();
    echo 1;
} else {
    echo 0;
}
