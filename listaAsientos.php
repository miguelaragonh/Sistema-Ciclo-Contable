<?php
include('conexion.php');
$sql = $pdo->prepare("SELECT * FROM asientos where estado = 1");
$sql->execute();
$listaAsientos = $sql->fetchAll(PDO::FETCH_ASSOC);
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
          <a href="#">
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
          <h2>Lista de Asientos</h2>
        </div>
        <button class="cerrar" id="cerrar" onclick="mayorizar()">Mayorizar</button>
      </div>
      <div class="info-wrapper">
        <h3 class="info-title">Datos de Asientos</h3>
        <table class="info-table">
        <?php if (!empty($listaAsientos)) : ?>
            <thead>
              <th>Cuenta</th>
              <th>Monto</th>
              <th>Fecha</th>
              <th>Movimiento</th>
              <th>Numero Asiento</th>
            </thead>
            <tbody>
            <?php foreach ($listaAsientos as $asiento) : ?>
                <tr>
                    <td><?php echo $asiento['numCuenta']; ?></td>
                    <td><?php echo $asiento['monto']; ?></td>
                    <td><?php echo $asiento['fecha']; ?></td>
                    <td><?php echo $asiento['tipoMovimiento']; ?></td>
                    <td><?php echo $asiento['numAsiento']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <?php else : ?>
                <h5 style="color:red;"> No hay elementos en la lista de asientos. </h5>
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