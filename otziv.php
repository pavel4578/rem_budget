<?php
session_start();
include 'temp/bd.php'; 
if (!isset($_SESSION['id_user'])) {
    header("Location: lich_cabinet.php");
    exit();
}
$user_id = intval($_SESSION['id_user']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $request_id = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
        if ($request_id > 0 && !empty($comment)) {
                //  проверяем дубликаты
        $check_stmt = $mysqli->prepare("SELECT id FROM reviews WHERE user_id = ? AND request_id = ?");
        if (!$check_stmt) {
            die("Ошибка подготовки проверочного запроса: " . $mysqli->error);
        }
        
        $check_stmt->bind_param("ii", $user_id, $request_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result && $check_result->num_rows == 0) {
            $check_stmt->close();
            //  безопасная запись отзыва
            $stmt = $mysqli->prepare("INSERT INTO reviews (user_id, request_id, rating, comment) VALUES (?, ?, ?, ?)");
            
            //  если база данных вернула false, выводим конкретную причину
            if (!$stmt) {
                die("Ошибка MySQL при подготовке INSERT: " . $mysqli->error . ". Проверьте, совпадает ли структура таблицы reviews и включен ли AUTO_INCREMENT для поля id.");
            }
            
            $stmt->bind_param("iiis", $user_id, $request_id, $rating, $comment);
            
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: lich_cabinet.php?review_success=1");
                exit();
            } else {
                die("Ошибка выполнения запроса: " . $stmt->error);
            }
        } else {
            $check_stmt->close();
            header("Location: lich_cabinet.php?error=already_reviewed");
            exit();
        }
    } else {
        die("Пожалуйста, заполните текстовое поле отзыва и убедитесь, что передается ID заявки.");
    }
} else {
    header("Location: lich_cabinet.php");
    exit();
}
?>
