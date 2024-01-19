<?php
include('conexion.php');
$sql = $pdo->prepare("SELECT SUM(saldo) FROM catalogocuentas WHERE saldoNormal='D';");
$sql->execute();
$listaDebe = $sql->fetchAll(PDO::FETCH_ASSOC);

$sql2 = $pdo->prepare("SELECT  nomCuenta, saldo  FROM catalogocuentas WHERE saldoNormal='D' and saldo !=0;");
$sql2->execute();
$listaDebe2 = $sql2->fetchAll(PDO::FETCH_ASSOC);

$sqlA = $pdo->prepare("SELECT SUM(saldo)  FROM catalogocuentas WHERE saldoNormal='H';");
$sqlA -> execute();
$listaHaber = $sqlA->fetchAll(PDO::FETCH_ASSOC);

$sqlA2 = $pdo->prepare("SELECT nomCuenta, saldo  FROM catalogocuentas WHERE saldoNormal='H' and saldo !=0;");
$sqlA2 -> execute();
$listaHaber2 = $sqlA2->fetchAll(PDO::FETCH_ASSOC);
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
          <a href="#">
            <i class="fa-solid fa-scale-balanced"></i>
            <span>Balanza Comprobacion</span>
          </a>
        </li>
        <li>
          <a href="BalanzaGen.php">
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
        <thead><?php if (!empty($listaDebe) && !empty($listaDebe2) && !empty($listaHaber)) : ?>
              <th>Cuenta</th>
              <th>Debe</th>
              <th>Haber</th>
            </thead>
            <tbody>
              <?php foreach ($listaDebe2 as $debe2) : ?>
             <tr>
                <td><?php echo $debe2['nomCuenta']?></td>
                <td><?php echo $debe2['saldo']?></td>
                <td></td>
              </tr>
             <?php endforeach; ?>
             <?php foreach ($listaHaber2 as $haber) : ?>
             <tr>
                <td><?php echo $haber['nomCuenta']?></td>
                <td></td>
                <td><?php echo $haber['saldo']?></td>
              </tr> 
             <?php endforeach; ?>
            <?php foreach ($listaDebe as $debe) : ?>
                <tr>
                    <td>Totales </td>
                    <td><?php echo round($debe['SUM(saldo)']); ?></td>
            <?php endforeach; ?>
            <?php foreach ($listaHaber as $haber) : ?>
                    <td><?php echo round($haber['SUM(saldo)']); ?></td>
            <?php endforeach; ?>
                </tr>
            </tbody>
            <?php else : (empty($listaDebe) || !empty($listaHaber))?>
                <h5 style="color:red;"> No hay datos para generar la Balanza de Comprobacion. </h5>
            <?php endif; ?>
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
<script>