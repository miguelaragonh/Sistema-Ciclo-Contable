<?php
require_once('conexion.php');
try {
    if (isset($_POST['opcion'])) {
        $opcion = $_POST['opcion'];

        switch ($opcion) {
            case 1:
                $id = $_POST['id'];
                $sql = $pdo->prepare("SELECT nomCuenta FROM catalogocuentas WHERE numCuenta = :id");
                $sql->bindParam(':id', $id);
                $sql->execute();
                $resultado = $sql->fetch(PDO::FETCH_ASSOC);
                echo ($resultado) ? $resultado['nomCuenta'] : 'No se encontraron resultados para el ID proporcionado.';
                break;
            case 2:
                $sql = $pdo->prepare("SELECT numAsiento FROM asientos ORDER BY numAsiento DESC LIMIT 1");
                $sql->execute();
                $resultado = $sql->fetch(PDO::FETCH_ASSOC);
                echo ($resultado) ? $resultado['numAsiento'] : 0;
                break;
            case 3:
                $sql = $pdo->prepare("SELECT numCuenta, nomCuenta FROM catalogoCuentas;");
                $sql->execute();
                $listaCuentas = $sql->fetchAll(PDO::FETCH_ASSOC);
                echo ($listaCuentas) ? json_encode($listaCuentas) : 'No existen cuentas';
                break;
                case 4:
                    $id = $_POST['id'];
                    $sql = $pdo->prepare("SELECT monto, tipoMovimiento FROM asientos WHERE numCuenta=$id;");
                    $sql2 = $pdo->prepare("UPDATE asientos set estado=2 where numCuenta =$id");
                    $sql->execute();
                    $sql2->execute();
                    $listaCuentas = $sql->fetchAll(PDO::FETCH_ASSOC);
                    //echo $listaCuentas;
                    echo ($listaCuentas) ? json_encode($listaCuentas) : 0;
                    break;
                case 5:
                    $datosJSON = $_POST['datos'];
                    $datos = json_decode($datosJSON, true);
                    //echo (json_encode($datos));
                     $sql = $pdo->prepare("SELECT saldoNormal , saldo FROM catalogoCuentas WHERE numCuenta=$datos[0];");
                    $sql->execute();
                    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
                    echo $datos[0]." ".$datos[2]." ";
                    echo $resultado['saldoNormal']." ";
                    echo ($resultado['saldoNormal'] == $datos[2])?"suma":"resta";
                    if ($resultado) { 
                        echo $resultado['saldoNormal'];
                    
                        if ($resultado['saldoNormal'] == $datos[2]) {
                            $saldoActual = $resultado['saldo'] + $datos[1];
                            $sql2 = $pdo->prepare("UPDATE catalogoCuentas SET saldo = $saldoActual WHERE numCuenta = $datos[0];");
                           // $sqlA = $pdo->prepare("UPDATE asientos set estado=2 where numCuenta =$datos[0];");
                            $sql2->execute();
                            //$sqlA->execute();
                            echo "sumo";
                        } else {
                            $saldoActual = $resultado['saldo'] - $datos[1];
                            $sql2 = $pdo->prepare("UPDATE catalogoCuentas SET saldo = $saldoActual WHERE numCuenta = $datos[0];");
                            //$sqlA = $pdo->prepare("UPDATE asientos set estado=2 where numCuenta =$datos[0];");
                            $sql2->execute();
                            //$sqlA->execute();
                            echo "resto";
                        }
                    } else {
                        echo "No se encontró un resultado para numCuenta = " . $datos[0];
                    } 
                break;
            case 6:
                $sql = $pdo->prepare("SELECT SUM(saldo) AS saldo FROM catalogocuentas WHERE LEFT(numCuenta,1) = 4;");
                $sql->execute();
                $resultado = $sql->fetch(PDO::FETCH_ASSOC);
                echo ($resultado) ? $resultado['saldo'] : 0;
                break;
            case 7:
                $sql = $pdo->prepare("SELECT SUM(saldo) AS saldo FROM catalogocuentas WHERE LEFT(numCuenta,1) = 5;");
                $sql->execute();
                $resultado = $sql->fetch(PDO::FETCH_ASSOC);
                echo ($resultado) ? $resultado['saldo'] : 0;
                break;
            default:
            echo 'Opción no válida';
            break;
    }
    } else {
        echo 'Opción no especificada';
    }
} catch (PDOException $e) {
    die("Error al conectarse a la base de datos: " . $e->getMessage());
}
