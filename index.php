<?php
  include('conexion.php')
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
          <a href="#">
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
          <h2>Nuevo Asiento</h2>
        </div>
        <button class="cerrar" id="cerrar" onclick="cerrarAsiento()">Cerrar Asiento</button>
      </div>
      <div class="card-container">
        <h3 class="card-title">Datos del Asiento</h3>
        <div class="card-wrapper">
            <form action="" id="form" method="post">
                <label for="monto" class="label">Monto</label>
                <input  class="input" type="number" id="monto" name="monto" placeholder="00" required>
                <label for="fecha" class="label">Fecha</label>
                <input  class="input" type="date" onchange="formatDateForServer()" id="dateInput" name="fecha" required>
                <label for="tipoMov" class="label">Tipo Mov</label>
                <select name="tipoMov" id="tipoMov" required><option selected>Tipo Movimiento</option><option value="D">D</option><option value="H">H</option></select>
                <label for="1">Numero Cuenta</label>
                <select name="numC" id="numCuenta" class="form-select-sm" required></select>
                <button type="submit" value="btnAgregar" name="accion" id="enviarButton">Agregar</button>
            </form>
        </div>
      </div>
      <div class="info-wrapper">
        <h3 class="info-title">Asiento</h3>
        <table class="info-table">
          <thead>
            <th>Numero Asiento</th>
            <th>Cuenta</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Movimiento</th>
            <th></th>
          </thead>
          <tbody id="tabla">
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
<script>
    window.onload = function () {
      agregarCuentasEnSelect();
    };

    async function agregarCuentasEnSelect() {
      let cuentas = await obtenerCuentas();
      let opcionesHTML = "<option selected>Cuenta</option>";
      for (let i = 0; i < cuentas.length; i++) {
        opcionesHTML += `<option value="${cuentas[i]["numCuenta"]}">${cuentas[i]["numCuenta"]}-${cuentas[i]["nomCuenta"]}</option>`;
      }
      document.getElementById("numCuenta").innerHTML = opcionesHTML;
    }

    async function obtenerCuentas() {
      try {
        const response = await $.ajax({
          url: "consulta.php",
          method: "POST",
          data: { opcion: 3 },
        });
        return JSON.parse(response);
      } catch (error) {
        console.error("Error:", error);
        return null;
      }
    }
    async function agruparCuentas(num) {
      try {
        const response = await $.ajax({
          url: "consulta.php",
          method: "POST",
          data: { opcion: 4, id: num },
        });
        return JSON.parse(response);
      } catch (error) {
        console.error("Error:", error);
        return null;
      }
    }

  </script>
