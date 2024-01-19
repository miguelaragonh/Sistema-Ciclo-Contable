<?php
include('conexion.php'); 
$sql = $pdo->prepare("SELECT nomCuenta,saldo  FROM catalogocuentas WHERE LEFT(numCuenta,1) = 1 AND saldo > 0;");
$sql->execute();
$activos= $sql->fetchAll(PDO::FETCH_ASSOC);

$sql2 = $pdo->prepare("SELECT nomCuenta,saldo  FROM catalogocuentas WHERE LEFT(numCuenta,1) = 2 AND saldo > 0;");
$sql2->execute();
$pasivos= $sql2->fetchAll(PDO::FETCH_ASSOC);

$sql5 = $pdo->prepare("SELECT nomCuenta,saldo  FROM catalogocuentas WHERE LEFT(numCuenta,1) = 3 AND saldo > 0;");
$sql5->execute();
$capitales= $sql5->fetchAll(PDO::FETCH_ASSOC);

$sql3 = $pdo->prepare("SELECT SUM(saldo) FROM catalogocuentas WHERE LEFT(numCuenta,1) = 1 AND saldo > 0;");
$sql3->execute();
$activo=$sql3->fetchAll(PDO::FETCH_ASSOC);

$sql4 = $pdo->prepare("SELECT SUM(saldo) FROM catalogocuentas WHERE LEFT(numCuenta,1) = 2 AND saldo > 0;");
$sql4->execute();
$pasivo=$sql4->fetchAll(PDO::FETCH_ASSOC);

$sql6 = $pdo->prepare("SELECT SUM(saldo) FROM catalogocuentas WHERE LEFT(numCuenta,1) = 3 AND saldo > 0;");
$sql6->execute();
$capital=$sql6->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Ciclo Contable</title>
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="sweetalert2.min.css">
  </head>
  <body>
    <div class="sidebar">
      <div class="logo"></div>
      <ul class="menu">
        <li>
          <a href="index.php">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo Asiento</span>
          </a>
        </li>
        <li>
          <a href="catalogoCuentas.php">
            <i class="fa-solid fa-folder-open"></i>
            <span>Catalogo Cuentas</span>
          </a>
        </li>
        <li>
          <a href="listaAsientos.php">
            <i class="fa-solid fa-list"></i>
            <span>Lista Asientos</span>
          </a>
        </li>
        <li>
          <a href="mayorizacion.php">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <span>Mayorizacion</span>
          </a>
        </li>
        <li>
          <a href="inventario.php">
            <i class="fa-solid fa-boxes-stacked"></i>
            <span>Inventario</span>
          </a>
        </li>
        <li>
          <a href="perdidasYganancias.php">
            <i class="fa-solid fa-chart-line"></i>
            <span>Perdidas y Ganancias</span>
          </a>
        </li>
        <li>
          <a href="BalanzaComp.php">
            <i class="fa-solid fa-scale-balanced"></i>
            <span>Balanza Comprobacion</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa-solid fa-book"></i>
            <span>Balanza General</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="content-wrapper">
      <div class="header">
        <div class="header-title">
          <span>Ciclo Contable</span>
          <h2>Balanza de Comprobacion</h2>
        </div>
      </div>
      <div class="info-wrapper">
        <h3 class="info-title">Datos de la Balanza</h3>
        <table class="info-table">
        <?php if (!empty($activos) or !empty($pasivos) or !empty($capitales)) : ?>
        <thead>
                <th>Nombre Cuenta</th>
                <th>Activos</th>
                <th>Pasivos</th>
          </thead>
          <tbody>
          <?php foreach ($activos as $act) : ?>
              <tr>
                <td><?php echo $act['nomCuenta']; ?></td>
                <td><?php echo $act['saldo']; 
                $tota
                ?></td>
                <td></td>
              </tr>
            <?php endforeach; ?> <?php foreach ($pasivos as $pas) : ?>
              <tr>
                <td><?php echo $pas['nomCuenta']; ?></td>
                <td></td>
                <td><?php echo $pas['saldo']; ?></td>
                
              </tr>
            <?php endforeach; ?>
 
            <?php foreach ($capitales as $cap) : ?>
              <tr>
                <td><?php echo $cap['nomCuenta']; ?></td>
                <td></td>
                <td><?php echo $cap['saldo']; ?></td>
                
              </tr>
            <?php endforeach; ?>
            <tr>
                <td>Resultado</td>
                <td><?php echo $activo[0]['SUM(saldo)']; ?></td>
                <td><?php echo ($pasivo[0]['SUM(saldo)']+$capital[0]['SUM(saldo)'])?$pasivo[0]['SUM(saldo)']+$capital[0]['SUM(saldo)']:"No hay en trasaccion en cuenta pasiva"; ?></td>
            </tr>
            <?php else : ?>
                <h5 style="color:red;"> No hay datos para generar el Balance General. </h5>
            <?php endif; ?>
           </tbody>
        </table>
      </div>
      </div>
    </div>
  </body>
</html>
<script src="https://kit.fontawesome.com/ebacb183db.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="app.js"></script>