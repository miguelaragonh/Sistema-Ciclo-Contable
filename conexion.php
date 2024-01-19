<?php
try {
    $hostname = 'localhost'; 
    $dbname = 'ciclo_contable'; 
    $username = 'root'; 
    $password = '';

    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname",
     $username, $password);

    // Configurar el modo de errores de PDO para que lance excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Conexión exitosa a la base de datos";
} catch (PDOException $e) {
    die("Error al conectarse a la base de datos: " . $e->getMessage());
}
?>