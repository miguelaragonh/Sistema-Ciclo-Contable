let enviarButton = document.getElementById("enviarButton");
let numCuenta = document.getElementById("numCuenta");
let form = document.getElementById("form");
let resultado = document.getElementById("resultado");
let matriz = [],
  tablaT = [];
let d,
  h,
  id,
  editando = false,
  fila = 0;
cuenta = false;

const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});


form.addEventListener("submit", function (e) {
  e.preventDefault();
  let datos = new FormData(form);
  cargarLocalStorage([
    fila,
    parseInt(datos.get("numC")),
    datos.get("monto"),
    datos.get("fecha"),
    datos.get("tipoMov"),
  ]);
  validarMov();
  cantCuentas();
  llenarTabla();
  form.reset();
  if (matriz.length > 0) {
    document.getElementById("dateInput").value = matriz[0][3];
    document.getElementById("dateInput").readOnly = true;
  }
});

async function llenarTabla() {
  let datos = "";
  let tabla = document.getElementById("tabla");
  if (matriz.length > 0) {
    for (let i = 0; i < matriz.length; i++) {
      datos += "<tr>";
      for (let j = 0; j < matriz[i].length; j++) {
        if (j == 1) {
          datos += `<td>${await consultarNombreAsiento(
            matriz[i][j]
          )}</td>`;
        } else {
          datos += `<td>${matriz[i][j]}</td>`;
        }
      }

      datos += `<td><button onclick="cargarForm(${matriz[i][0]})"><i class="fa-solid fa-pen-to-square"></button></td>
      </tr>`;
    }
    tabla.innerHTML = datos;
  }
}

function cargarForm(id) {
  editando = true;
  this.id = id;
  const numCInput = document.querySelector(`select[name="numC"]`);
   
  numCInput.value = matriz[id][1];
  document.getElementById("monto").value = matriz[id][2];
  document.getElementById("dateInput").value = matriz[id][3];
  document.getElementById("dateInput").readOnly = true;
  document.getElementsByName("tipoMov")[0].value = matriz[id][4];
}

function cargarLocalStorage(datos) {
  if (!editando) {
    if (JSON.parse(localStorage.getItem("asiento")) !== null) {
      matriz.push(datos);
      localStorage.removeItem("asiento");
      localStorage.setItem("asiento", JSON.stringify(matriz));
      fila++;
    } else {
      matriz.push(datos);
      localStorage.setItem("asiento", JSON.stringify(matriz));
      fila++;
    }
    Toast.fire({
      type: "success",
      title: "Datos agregados!",
    });
  } else {
    matriz[this.id][0] = this.id;
    for (let i = 1; i < datos.length; i++) {
      matriz[this.id][i] = datos[i];
    }
    localStorage.removeItem("asiento");
    localStorage.setItem("asiento", JSON.stringify(matriz));
    Toast.fire({
      type: "success",
      title: "Datos editados!",
    });
  }
 }

function cantCuentas() {
  if (matriz.length < 2) {
    Toast.fire({
      type: "error",
      title: "Asiento no Balanceado",
    });
  }
 }

function validarMov() {
  (d = 0), (h = 0);
  for (let i = 0; i < matriz.length; i++) {
    if (matriz[i][4] == "D") {
      d += parseFloat(matriz[i][2]);
    } else if (matriz[i][4] == "H") {
      h += parseFloat(matriz[i][2]);
    }
  }
  if (h != d) {
    document.getElementById("cerrar").disabled = true;
    Toast.fire({
      type: "error",
      title: "Asiento no Balanceado",
    })
  } else if (h == d) {
    document.getElementById("cerrar").disabled = false;
    Toast.fire({
      type: "success",
      title: "Asiento Balanceado",
    })
  }
}

function formatDateForServer() {
  const dateInput = document.getElementById("dateInput");
  const selectedDate = dateInput.value;
  const formattedDate = selectedDate.split("/").reverse().join("-");
  dateInput.value = formattedDate;
}

//SQL
async function consultarNombreAsiento(id) {
  try {
    const response = await $.ajax({
      url: "consulta.php",
      method: "POST",
      data: { id: id, opcion: 1 },
    });
    return response;
  } catch (error) {
    console.error(error);
    return "Nombre Desconocido";
  }
}
async function obtenerUltimoRegistroAsiento() {
  try {
    const response = await $.ajax({
      url: "consulta.php",
      method: "POST",
      data: { opcion: 2 },
    });
    return response;
  } catch (error) {
    console.error(error);
    return null;
  }
}

async function agregarCuentasEnSelect() {
  let cuentas = await obtenerCuentas();
  let opcionesHTML = "<option selected>Cuenta</option>";
  for (let i = 0; i < cuentas.length; i++) {
    opcionesHTML += `<option value="${cuentas[i]["numCuenta"]}">${cuentas[i]["numCuenta"]}-${cuentas[i]["nomCuenta"]}</option>`;
  }
  document.getElementById("numCuenta").innerHTML = opcionesHTML;
}

async function obtenerCuentas() {//este m
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

async function cerrarAsiento() {
  let numAsiento = parseInt(await obtenerUltimoRegistroAsiento()) + 1;
   for (let i = 0; i < matriz.length; i++) {
    insertarAsiento([
      matriz[i][1],
      parseFloat(matriz[i][2]),
      matriz[i][3],
      matriz[i][4],
      numAsiento,
    ]);
  }
}

 


async function insertarAsiento(dato) {
  $.ajax({
    url: "agregarAsiento.php",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ datos: dato }),
    success: function (response) {
      if (response) {
        Swal.fire({
          type: "success",
          title: "Asiento Agregado Correctamente",
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload();
          }
        });
      } else {
        Swal.fire({
          type: "error",
          title: "Error al agregar Asiento",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

async function guardarMayorizacion(dato) {
  $.ajax({
    type: "POST",
    url: "consulta.php",
    data: { opcion : 5 , datos  : JSON.stringify(dato) },
    success: function (response) {
       if (response) {
        location.reload();
        Swal.fire({
          type: "success",
          title: "Mayorizacion relizada Correctamente",
          text: "Puede revisar los datos en el apartado de Mayorización",
        });
       
      } else {
        Swal.fire({
          type: "error",
          title: "Error al realizar la mayorizacion",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

async function mayorizar() {
let cuentas = await obtenerCuentas(),
  cuentasAgrupadas;
for (let i = 0; i < cuentas.length; i++) {
  cuentasAgrupadas = await agruparCuentas(cuentas[i]["numCuenta"]);
   if (cuentasAgrupadas != 0) {
    crearTablasT(cuentasAgrupadas, cuentas[i]["numCuenta"]);
  }
} 
 for (let i = 0; i <tablaT.length; i++) {
  await guardarMayorizacion(tablaT[i]);
}
}

async function crearTablasT(cuentasAgrupadas, cuenta) {
  let D = 0,
    H = 0,
    monto,
    mov;
  for (let i = 0; i < (await cuentasAgrupadas.length); i++) {
    if (cuentasAgrupadas[i]["tipoMovimiento"] == "D") {
      D += parseFloat(cuentasAgrupadas[i]["monto"]);
    } else {
      H += parseFloat(cuentasAgrupadas[i]["monto"]);
    }
  }
  if (D < H) {
    monto = H - D;
    mov = "H";
  } else {
    monto = D - H;
    mov = "D";
  }
  tablaT.push([cuenta, monto, mov]); 
  
 }
