<?php
require_once('conexion.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql6 = $pdo->prepare("DELETE FROM inventarios WHERE id = ?");
        $sql6->execute([$id]);
    }
}
?>
