<?php
include('conexion.php');
$sql = $pdo->prepare("SELECT numCuenta, nomCuenta FROM `catalogoCuentas` WHERE LENGTH(numCuenta) = 6;");
$sql->execute();
$listaCuentas = $sql->fetchAll(PDO::FETCH_ASSOC);
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
          <a href="#">
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
          <h2>Catalogo de Cuentas</h2>
        </div>
      </div>
      <div class="info-wrapper">
        <h3 class="info-title">Cuentas</h3>
        <table class="info-table">
          <thead> <?php if (!empty($listaCuentas)) : ?>
            <th>Numero Cuenta</th>
            <th>Cuenta</th>
          </thead>
          <tbody id="tabla">
            <?php foreach ($listaCuentas as $cuenta) : ?>
                <tr>
                    <td><?php echo $cuenta['numCuenta']; ?></td>
                    <td><?php echo $cuenta['nomCuenta']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <?php else : ?>
                <h5 style="color:red;"> No hay elementos en la lista de cuentas. </h5>
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