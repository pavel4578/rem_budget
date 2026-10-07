<?php
include 'temp/bd.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
    $new_status = isset($_POST['status']) ? trim($_POST['status']) : '';
    $allowed_statuses = ['в рассмотрении', 'договор заключен', 'выполнение работ завершено'];
    if ($request_id > 0 && in_array($new_status, $allowed_statuses)) {
    $query = "UPDATE requests SET status = ? WHERE id = ?";
            if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("si", $new_status, $request_id);
                if ($stmt->execute()) {
                header("Location: admin_cabinet.php?success=1");
                exit();
            } else {
                die("Ошибка при обновлении статуса в базе данных: " . $stmt->error);
            }
        } else {
            die("Ошибка подготовки SQL-запроса: " . $mysqli->error);
        }
    } else {
        die("Переданы некорректные или пустые данные (неверный ID или статус).");
    }
} else {
    die("Ошибка: Этот файл обрабатывает только POST-запросы от формы.");
}
?>
