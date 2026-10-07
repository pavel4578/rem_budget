<?php 
session_start(); 
include 'temp/head.php';
include 'temp/bd.php'; 
if(!empty($_SESSION['role'])){
    $role = $_SESSION['role'];
    if($role == 'client'){
        include 'temp/nav_client.php';
    }
    elseif ($role == 'admin'){
        include 'temp/nav_manager.php';
    }
}
else{
    include 'temp/nav.php';
}
?>
<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">Калькулятор стоимости ремонта «РемБюджет»</h3>
                    <img src="img/100-cropped.png" alt="100%" style="height: 80px; width: auto;">
                </div>
                <div class="card-body">
                         <form id="repair-calc-form" method="POST" action="save_request.php" 
                          oninput="
                            let factor = parseFloat(room_type.options[room_type.selectedIndex]?.getAttribute('data-factor')) || 0;
                            let price = parseFloat(repair_type.options[repair_type.selectedIndex]?.getAttribute('data-price')) || 0;
                            let area_val = parseFloat(area.value) || 0;
                            let total = area_val * price * factor;
                            total_cost_hidden.value = total.toFixed(2);
                            total_cost_display.value = total.toLocaleString('ru-RU');
                          ">
                        
                        <div class="mb-3">
                            <label for="room_type" class="form-label">Тип помещения:</label>
                            <select id="room_type" name="room_type_id" class="form-select" required>
                                <option value="" data-factor="0" disabled selected> Выберите тип помещения </option>
                                <?php
                                $query = $mysqli->query("SELECT id, name, price_factor FROM room_types");
                                while ($row = $query->fetch_assoc()) {
                                    echo "<option value='{$row['id']}' data-factor='{$row['price_factor']}'>{$row['name']} (коэфф. {$row['price_factor']})</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="repair_type" class="form-label">Вид ремонта:</label>
                            <select id="repair_type" name="repair_type_id" class="form-select" required>
                                <option value="" data-price="0" disabled selected> Выберите вид ремонта</option>
                                <?php
                                $query = $mysqli->query("SELECT id, name, base_price_per_m2 FROM repair_types");
                                while ($row = $query->fetch_assoc()) {
                                    echo "<option value='{$row['id']}' data-price='{$row['base_price_per_m2']}'>{$row['name']} ({$row['base_price_per_m2']} руб./м²)</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="area" class="form-label">Площадь пола (кв. м):</label>
                            <input type="number" id="area" name="area" class="form-control" min="1" step="0.1" placeholder="Введите площадь" required>
                        </div>

                        <input type="hidden" id="total_cost_hidden" name="total_cost" value="0">

                        <!-- Блок итоговой стоимости -->
                        <div class="bg-success p-2 text-white bg-opacity-90 text-center rounded-4 fs-4 fw-bold my-4">
                            Примерная стоимость: <output id="total-cost-display" name="total_cost_display">0</output> руб.
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="submit-request-btn" class="btn btn-success rounded-4 btn-lg">Отправить заявку</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
