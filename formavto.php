<?php 
include 'temp/head.php';
include 'temp/nav.php';
include 'temp/bd.php';
?>
<div class="row mt-5">
    <div class="col-3"></div>
        <form method="post" action="avto.php" class="col-6 bg-white p-4 rounded-4 shadow-sm border">
        <h1 class="mb-4 pt-2 fw-bold text-center text-primary">Авторизация</h1>
            <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label fw-semibold text-muted">Логин</label>
            <input type="text" class="form-control" id="exampleInputPassword1" name="login" required>
        </div>
            <div class="mb-4">
            <label class="form-label fw-semibold text-muted">Пароль</label>
            <input type="password" class="form-control" name="password" required>
        </div>
            <?php
      // не верный логин и пароль
   if (isset($_SESSION['auth_error'])) {
    echo '<div style="color: red; margin-bottom: 10px;">' . $_SESSION['auth_error'] . '</div>';
    unset($_SESSION['auth_error']);
}
?>
        <div class="d-flex flex-col sm:flex-row justify-content-between align-items-center gap-3">
 <button type="submit" class="btn border border-5 rounded-pill " style=" background-color: transparent; border: none; padding: 0.375rem 0.75rem;  Bootstrap */ font-size: 1rem; /* Размер шрифта */ line-height: 1.5; /* Межстрочный интервал */ background-image: linear-gradient( 90deg, #215f1be6,              rgba(14, 148, 55, 0.9)) !important;" color: #eaeef5 !important;  "> Войти</button>
            <a href="formreg.php" class="text-decoration-none small fw-semibold text-secondary">Еще не зарегистрированы? Регистрация</a>
        </div>
    </form>
        <div class="col-3"></div>
</div>
