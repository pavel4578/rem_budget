<?php
session_start();
include 'temp/bd.php'; 
include 'temp/head.php';
if (empty($_SESSION['id_user']) || empty($_SESSION['role'])) {
    header("Location: avto.php");
    exit();
}
$user_id = intval($_SESSION['id_user']);
$user_role = $_SESSION['role'];
if ($user_role === 'client') {
    include 'temp/nav_client.php';
} elseif ($user_role === 'admin') {
    include 'temp/nav_manager.php';
}
?>

<main class="container mt-5" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Кабинет Администратора  (<?php echo ($_SESSION['fio']);?>)</h2>
                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Заявка успешно отправлена и добавлена в базу данных!
                </div>
            <?php endif; ?>
                <?php if ($user_role === 'admin'): ?>
                <h3 class="text-secondary mb-3">Все заявки клиентов</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>№</th>
                                <th>Клиент (ФИО / Тел)</th>
                                <th>Тип помещения</th>
                                <th>Вид ремонта</th>
                                <th>Площадь</th>
                                <th>Итоговая стоимость</th>
                                <th>Статус заявки</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $sql = "SELECT r.*, u.fio, u.phone, rt.name AS room_name, rep.name AS repair_name 
                                    FROM requests r
                                    JOIN users u ON r.user_id = u.id_user
                                    JOIN room_types rt ON r.room_type_id = rt.id
                                    JOIN repair_types rep ON r.repair_type_id = rep.id
                                    ORDER BY r.created_at DESC";
                            $result = $mysqli->query($sql);

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <strong><?php echo ($row['fio']); ?></strong><br>
                                        <small class="text-muted"><?php echo ($row['phone']); ?></small>
                                    </td>
                                    <td><?php echo ($row['room_name']); ?></td>
                                    <td><?php echo ($row['repair_name']); ?></td>
                                    <td><?php echo $row['area']; ?> кв. м</td>
                                    <td><strong><?php echo number_format($row['total_cost'], 0, '.', ' '); ?> руб.</strong></td>
                                    <td>
                                
                                        <?php 
                                        $badge_class = 'bg-warning text-dark';
                                        if ($row['status'] === 'dogovor_zaklyuchen' || $row['status'] === 'договор заключен') $badge_class = 'bg-primary';
                                        if ($row['status'] === 'work_completed' || $row['status'] === 'выполнение работ завершено') $badge_class = 'bg-success';
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?> fs-6">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                        
                                        <form method="POST" action="update_status.php" class="d-flex gap-1">
                                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                            <select name="status" class="form-select form-select-sm" required>
                                                <option value="в рассмотрении" <?php if($row['status']=='в рассмотрении') echo 'selected'; ?>>В рассмотрении</option>
                                                <option value="договор заключен" <?php if($row['status']=='договор заключен') echo 'selected'; ?>>Договор заключен</option>
                                                <option value="выполнение работ завершено" <?php if($row['status']=='выполнение работ завершено') echo 'selected'; ?>>Работы завершены</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-success">Обновить</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else: 
                            ?>
                                <tr><td colspan="8" class="text-center text-muted">Заявок в системе пока нет.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>


            <?php else: ?>
                <h3 class="text-secondary mb-3">Мои отправленные заявки</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>№ заявки</th>
                                <th>Тип помещения</th>
                                <th>Вид ремонта</th>
                                <th>Площадь</th>
                                <th>Ориентировочная цена</th>
                                <th>Статус</th>
                                <th>Отзыв</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $sql = "SELECT r.*, rt.name AS room_name, rep.name AS repair_name 
                                    FROM requests r
                                    JOIN room_types rt ON r.room_type_id = rt.id
                                    JOIN repair_types rep ON r.repair_type_id = rep.id
                                    WHERE r.user_id = $user_id
                                    ORDER BY r.created_at DESC";
                            $result = $mysqli->query($sql);

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo ($row['room_name']); ?></td>
                                    <td><?php echo ($row['repair_name']); ?></td>
                                    <td><?php echo $row['area']; ?> кв. м</td>
                                    <td><strong><?php echo number_format($row['total_cost'], 0, '.', ' '); ?> руб.</strong></td>
                                    <td>
                                        <span class="badge bg-info text-dark fs-6"><?php echo ($row['status']); ?></span>
                                    </td>
                                    <td>
                                        
                                        <?php if ($row['status'] === 'выполнение работ завершено'): ?>
                                            <a href="otziv.php?request_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success">Написать отзыв</a>
                                        <?php else: ?>
                                            <span class="text-muted"><small>Доступно после завершения</small></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else: 
                            ?>
                                <tr><td colspan="7" class="text-center text-muted">Вы еще не отправляли заявки. Воспользуйтесь калькулятором на главной.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php include 'temp/footer.php'; ?>
