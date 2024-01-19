<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
  $nombre = $_POST["nombre"];
  $descripcion = $_POST["descripcion"];
  $precio = $_POST["precio"];
  $cantidad = $_POST["cantidad"];
  $fecha = $_POST["fecha"];

  $sql4 = $pdo->prepare("SELECT numAsiento FROM asientos ORDER BY numAsiento DESC LIMIT 1");
  $sql4->execute();
  $numAsiento = $sql4->fetch(PDO::FETCH_ASSOC);
  $pos = $numAsiento['numAsiento'] + 1;

  $sql2 = $pdo->prepare("INSERT INTO `inventarios` (`nombre`, `descripcion`, `precio`, `cantidad`, `idEstado`, `fechaInventario`) 
    VALUES (:nombre, :descripcion, :precio, :cantidad, 1, :fechaInventario);");
  $sql2->bindParam(':nombre', $nombre);
  $sql2->bindParam(':descripcion', $descripcion);
  $sql2->bindParam(':precio', $precio);
  $sql2->bindParam(':cantidad', $cantidad);
  $sql2->bindParam(':fechaInventario', $fecha);
  $sql2->execute();

  $monto = $precio * $cantidad;

  $sql3 = $pdo->prepare("INSERT INTO asientos (numCuenta, monto, tipoMovimiento, fecha, numAsiento, estado) 
    VALUES (101030, :monto, 'D', :fecha, :numAsiento, 1)");
  $sql3->bindParam(':monto', $monto);
  $sql3->bindParam(':fecha', $fecha);
  $sql3->bindParam(':numAsiento', $pos);
  $sql3->execute();

  $sql5 = $pdo->prepare("INSERT INTO asientos (numCuenta, monto, tipoMovimiento, fecha, numAsiento, estado) 
    VALUES (101001, :monto, 'H', :fecha, :numAsiento, 1)");
  $sql5->bindParam(':monto', $monto);
  $sql5->bindParam(':fecha', $fecha);
  $sql5->bindParam(':numAsiento', $pos);
  $sql5->execute();

  header("Location:inventario.php");
}

$sql = $pdo->prepare("SELECT * FROM inventarios where idEstado = 1 and cantidad >0");
$sql->execute();
$listaProductos = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Ciclo Contable</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
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
        <a href="#">
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
        <h2>Registrar Productos</h2>
      </div>
    </div>
    <div class="card-container">
      <h3 class="card-title">Datos del Producto</h3>
      <div class="card-wrapper">
        <form action="" method="post">
          <label for="nombre" class="label">Nombre</label>
          <input class="input" type="text" name="nombre" required>
          <label for="descripcion">Descripcion</label>
          <input type="text" name="descripcion" id="descripcion" required>
          <label for="fecha" class="label">Fecha</label>
          <input class="input" type="date" onchange="formatDateForServer()" name="fecha" required>
          <label for="precio">Precio</label>
          <input class="input" type="number" id="precio" name="precio" placeholder="00" required>
          <label for="cantidad">Cantidad</label>
          <input class="input" type="number" id="cantidad" name="cantidad" placeholder="00" required>
          <button type="submit" name='submit'>Agregar</button>
        </form>
      </div>
    </div>
    <div class="card-container">
      <h3 class="card-title">Registro de Venta</h3>
      <div class="card-wrapper">
        <form action="venta.php" method="post">
          <label for="nombre">ID Producto</label>
          <input class="input" type="number" readonly id="idProducto" name="idProducto" placeholder="00" required  >
          <label for="precio">Precio de Compra</label>
          <input class="input" type="number"  readonly id="precio3" name="precio3" placeholder="00" required  >
          <label for="precio">Precio de Venta</label>
          <input class="input" type="number" id="precio2" name="precio2" placeholder="00" required disabled>
          <label for="precio">Unidades Vendidas</label>
          
          <input class="input" type="number" id="unidades" name="unidades" placeholder="00" required disabled>
          <input class="input" type="number" id="cant" name="cant"  style="display: none;">
          <label for="fecha" class="label">Fecha</label>
          <input class="input" type="date" onchange="formatDateForServer()" name="fecha" required>
         
          <button id="registrarV" disabled type="submit" name='submit' >Registrar</button>
        </form>
      </div>
    </div>
    <div class="info-wrapper">
      <h3 class="info-title">Productos</h3>
      <table class="info-table">
        <?php if (!empty($listaProductos) ) : ?>
          <thead>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descrpcion</th>
            <th>Precio Compra</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th> </th>
          </thead>
          <tbody>
            <?php foreach ($listaProductos as $producto) : ?>
              <tr>
                <td><?php echo $producto['id']; ?></td>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['descripcion']; ?></td>
                <td><?php echo $producto['precio']; ?></td>
                <td><?php echo $producto['cantidad']; ?></td>
                <td><?php echo $producto['fechaInventario']; ?></td>
                <td>
                  <?php
                  $id = $producto['id'];
                  ?>
                  <button id="eliminarProducto" data-producto-id="<?php echo $id; ?>"><i class="fa-solid fa-trash" ></i></button>

                  <button id="venderProducto" onclick="cargarVenta([<?php echo $producto['id']; ?>,<?php echo $producto['precio']; ?>,
                        <?php echo $producto['cantidad']; ?>])">
                        <i class="fa-solid fa-cash-register"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        <?php else : ?>
          <h5 style="color:red;"> No hay elementos. </h5>
        <?php endif; ?>
      </table>
    </div>
  </div>
  </div>
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
  $(document).ready(function() {
    $('#eliminarProducto').click(function() {
      var idProducto = $(this).data('producto-id');

 Swal.fire({
  title: "Quieres eliminar este producto?",
  showDenyButton: true,
  //showCancelButton: true,
  confirmButtonText: "Comfirmar",
  denyButtonText: `Cancelar`
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
    $.ajax({
        type: 'POST',
        url: 'eliminarProducto.php',
        data: {
          id: idProducto
        },
        success: function(response) {
          Swal.fire({
            type: "success",
            text: "Producto eliminado con exito",
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
        },
        error: function(error) {
          Swal.fire({
            type: "error",
            title: "Error al eliminar el producto",
          });
        }
      });
  } else if (result.isDenied) {
    Swal.fire("No se elimina el producto", "", "info");
  }
});
      
    });
  });

  function cargarVenta(data) {
    var idProductoInput = document.getElementById("idProducto");
    var precio3 = document.getElementById("precio3");
    var precio2 = document.getElementById("precio2");
    var unidadesInput = document.getElementById("unidades");
    var cant = document.getElementById("cant");

    idProductoInput.value = data[0];
    precio3.value = data[1];
    precio2.value = data[1];
    unidadesInput.value = data[2]; 
    cant.value = data[2]; 
  
    precio2.removeAttribute("disabled");
    unidadesInput.removeAttribute("disabled");
    document.getElementById("registrarV").removeAttribute("disabled");

    unidadesInput.addEventListener("change", function() {
        
        if (parseInt(unidadesInput.value) > parseInt(data[2])) {
            unidadesInput.value = data[2];
        }else if(parseInt(unidadesInput.value) < 1){
          unidadesInput.value = 1;
        }
    });
     
  }
</script>