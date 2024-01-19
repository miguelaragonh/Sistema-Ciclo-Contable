<?php
include('conexion.php');
$sql = $pdo->prepare("SELECT nomCuenta,saldo FROM catalogocuentas WHERE LEFT(numCuenta,1) = 4 AND saldo > 0;");
$sql->execute();
$listaGastos = $sql->fetchAll(PDO::FETCH_ASSOC);
$sqlA = $pdo->prepare("SELECT SUM(saldo) FROM catalogocuentas WHERE LEFT(numCuenta,1) = 4;");
$sqlA->execute();
$totalGastos = $sqlA->fetchAll(PDO::FETCH_ASSOC);
$sqlB = $pdo->prepare("SELECT nomCuenta,saldo FROM catalogocuentas WHERE numCuenta = 501001;");
$sqlB->execute();
$totalIngresos = $sqlB->fetchAll(PDO::FETCH_ASSOC);

$sql3 = $pdo->prepare("SELECT saldo from catalogocuentas WHERE numCuenta = 301010;");
$sql3->execute();
$saldo = $sql3->fetchAll(PDO::FETCH_ASSOC);

$resultado = ($totalGastos[0]['SUM(saldo)'] > $totalIngresos[0]['saldo']) ? 1 : 2;
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
          <a href="#">
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
          <h2>Perdidas y Ganancias</h2>
        </div>
      </div>
      <div class="info-wrapper">
        <h3 class="info-title">Datos de Pedidas y Ganancias</h3>
        <table>
          <thead>
            </tr> <?php if (!empty($listaGastos) && !empty($totalGastos)) : ?>
              <tr>
                <th>Tipo de Cuenta</th>
                <th>Nombre Cuenta</th>
                <th>Saldo Gastos</th>
                <th>Saldo Ingresos</th>
                <th>Total</th>
              </tr>
          </thead>
          <tbody>
            <tr>
              <th>Gastos</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
            <?php foreach ($listaGastos as $gastos) : ?>
              <tr>
                <td></td>
                <td><?php echo $gastos['nomCuenta']; ?></td>
                <td><?php echo $gastos['saldo']; ?></td>
                <td></td>
                <td></td>
              </tr>
            <?php endforeach; ?>
            <?php foreach ($totalGastos as $total) : ?>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo $total['SUM(saldo)']; ?></td>
              </tr>
            <?php endforeach; ?>
            <tr>
              <th>Ingresos</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
            <?php foreach ($totalIngresos as $ing) : ?>
              <tr>
                <td></td>
                <td><?php echo $ing['nomCuenta']; ?></td>
                <td></td>
                <td><?php echo $ing['saldo']; ?></td>
                <td></td>
              </tr>
              
            <?php endforeach; ?>

            <tr>
              <th>Resultado</th>
              <th></th>
              <th></th>
              <th></th>
              <td>
                <?php if ($resultado == 1) : ?>
                  PERDIDAS :
                  <?php
                      $totalGastos2 = $totalGastos[0]['SUM(saldo)']- $totalIngresos[0]['saldo'] ; 
                      $saldoN = $saldo[0]['saldo'] - $totalGastos2;
                      $sql = $pdo->prepare("UPDATE catalogocuentas set saldo=$saldoN  WHERE numCuenta = 301010;");
                      $sql->execute();
                      echo $totalGastos2;
                  ?>
                <?php else : ?>
                  GANANCIAS :
                  <?php
                      $totalIngresos2 = $totalIngresos[0]['saldo'] - $totalGastos[0]['SUM(saldo)'];
                      $saldoN = ($saldo[0]['saldo']) + $totalIngresos2;
                      $sql = $pdo->prepare("UPDATE catalogocuentas set saldo=$saldoN WHERE numCuenta = 301010;");
                      $sql->execute();
                      echo $totalIngresos2;

                  ?>
                <?php endif; ?>
              </td>
            </tr>
          </tbody>
        <?php else : ?>
          <h5 style="color:red;"> No hay datos disponibles. </h5>
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