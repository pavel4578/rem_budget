<?php
session_start();
include 'temp/bd.php'; 
include 'temp/head.php';
$user_id = intval($_SESSION['id_user']);
include 'temp/nav_client.php';

// добавляем строчку,  код понимает, для какой заявки открывается форма
$active_review_id = isset($_GET['write_review_for']) ? intval($_GET['write_review_for']) : 0;
?>

<main class="container mt-5" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Личный кабинет <?php echo ($_SESSION['fio']); ?>.</h2>
            
            <?php if (isset($_GET['review_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Спасибо! Ваш отзыв успешно сохранен.
                </div>
            <?php endif; ?>
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
                                $sql = "SELECT r.*, rt.name AS room_name, rep.name AS repair_name, 
                                rev.rating, rev.comment
                                FROM requests r
                                JOIN room_types rt ON r.room_type_id = rt.id
                                JOIN repair_types rep ON r.repair_type_id = rep.id
                                JOIN users u ON r.user_id = u.id_user
                                LEFT JOIN reviews rev ON r.id = rev.request_id
                                WHERE r.user_id = $user_id AND u.role = 'client'
                                ORDER BY r.created_at DESC";
                                        
                        $result = $mysqli->query($sql);

                        if ($result && $result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo ($row['room_name']); ?></td>
                                <td><?php echo ($row['repair_name']); ?></td>
                                <td><?php echo $row['area']; ?> кв. м</td>
                                <td><strong><?php echo number_format($row['total_cost'], 0, '.', ' '); ?> руб.</strong></td>
                                <td>
                                    <?php 
                                    $badge_class = 'bg-info text-dark';
                                    if ($row['status'] === 'договор заключен') $badge_class = 'bg-primary text-white';
                                    if ($row['status'] === 'выполнение работ завершено') $badge_class = 'bg-success text-white';
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?> fs-6">
                                        <?php echo ($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'выполнение работ завершено'): ?>
                                        
                                        <?php if (!empty($row['comment'])): ?>
                                            <!-- Если отзыв в базе уже есть — выводим оценку звёздами и сам комментарий -->
                                            <div class="p-1 text-start">
                                                <div class="text-warning mb-1">
                                                    <?php echo str_repeat('★', intval($row['rating'])); ?><?php echo str_repeat('☆', 5 - intval($row['rating'])); ?>
                                                    <span class="text-muted small">(<?php echo $row['rating']; ?>/5)</span>
                                                </div>
                                                <small class="text-dark d-block" style="max-width: 250px; white-space: normal;">
                                                    <?php echo htmlspecialchars($row['comment']); ?>
                                                </small>
                                            </div>
                                        <?php else: ?>
                                            <!-- Если отзыва ещё нет — показываем ссылку на написание -->
                                            <a href="?write_review_for=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                                Написать отзыв
                                            </a>

                                            <?php if ($active_review_id === intval($row['id'])): ?>
                                                <div class="mt-2 p-2 border rounded bg-light" style="min-width: 200px;">
                                                    <form action="otziv.php" method="POST">
                                                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                                        // чтоб поставить звезду юникод клава eng 2506ALT+X
                                                        <div class="mb-2 text-start">
                                                            <label class="form-label small mb-1">Оценка:</label>
                                                            <select name="rating" class="form-select form-select-sm" required>
                                                                <option value="5">5 ★★★★★ (Отлично)</option>
                                                                <option value="4">4 ★★★★ (Хорошо)</option>
                                                                <option value="3">3 ★★★ (Нормально)</option>
                                                                <option value="2">2 ★★ (Плохо)</option>
                                                                <option value="1">1 ★ (Ужасно)</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-2">
                                                            <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Ваш отзыв..." required></textarea>
                                                        </div>

                                                        <div class="d-flex gap-1">
                                                            <button type="submit" name="submit_review" class="btn btn-sm btn-success w-100">
                                                                Отправить
                                                            </button>
                                                            <a href="?" class="btn btn-sm btn-outline-danger">✕</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <small class="text-muted">Доступно после завершения</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else: 
                        ?>
                            <tr><td colspan="7" class="text-center text-muted">Вы еще не отправляли заявок.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include 'temp/footer.php'; ?>
