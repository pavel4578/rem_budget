<?php
session_start();
include 'temp/head.php';
include 'temp/nav_client.php';
include 'temp/bd.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     
    if (empty($_SESSION['id_user'])) {
          header("Location: avto.php?msg=auth_required");
        exit();
    }
    $user_id = intval($_SESSION['id_user']);
    $room_type_id = intval($_POST['room_type_id']);
    $repair_type_id = intval($_POST['repair_type_id']);
    $area = floatval($_POST['area']);
    $total_cost = floatval($_POST['total_cost']);
    $status = 'в рассмотрении'; 
    $stmt = $mysqli->prepare("INSERT INTO requests (user_id, room_type_id, repair_type_id, area, total_cost, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    
    if ($stmt) {
        // Привязываем параметры: i = integer (целое), d = double/float (дробное), s = string (строка)
        $stmt->bind_param("iiidds", $user_id, $room_type_id, $repair_type_id, $area, $total_cost, $status);
        
        if ($stmt->execute()) {
                  header("Location: lich_cabinet.php");
            exit();
        } else {
            echo "Ошибка при выполнении запроса: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Ошибка подготовки запроса: " . $mysqli->error;
    }

} else {
    header("Location: index.php");
    exit();
}
?>
