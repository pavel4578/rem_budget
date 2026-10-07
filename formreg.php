<?php
include 'temp/head.php';
include 'temp/nav.php';
include 'temp/bd.php';
?>
<div class="container mt-4" style="max-width: 600px;">
  <form method="post" action="reg.php">
    <h1 class="mb-3 mt-3">Регистрация</h1>
    
    <div class="mb-3">
      <label for="fio" class="form-label">ФИО</label>
      <input type="text" class="form-control" id="fio" name="fio" pattern="[А-ЯЁа-яё\s\-]+" required>
    </div>
    
    <div class="mb-3">
      <label for="login" class="form-label">Логин</label>
      <input type="text" class="form-control" id="login" name="login" minlength="6" pattern="[a-zA-Z0-9]+" required>
         <div class="invalid-feedback">Пожалуйста, введите корректный логин, не менее 6 символов.</div> 
    </div>
    
    <div class="mb-3">
      <label for="pass" class="form-label">Пароль</label>
      <input type="password" class="form-control" id="pass" name="password" minlength="8" required>
         <div class="invalid-feedback">Пожалуйста, введите корректный пароль, не менее 8 символов.</div> 
    </div>
    
    <div class="mb-3"> 
      <label for="email" class="form-label">E-mail</label> 
      <input type="email" class="form-control" id="email" name="email" required> 
      <div class="invalid-feedback">Пожалуйста, введите корректный адрес электронной почты.</div> 
        <div class="form-text">Введите  адрес электронной почты  пример: Ivanov.Ivan@gmail.com </div>
    </div>
    
    <div class="mb-3">
      <label for="tel" class="form-label">Телефон</label>
      <input type="tel" class="form-control" id="tel" name="phone" placeholder="8(999)111-22-33" pattern="^8[\s\(-]?\d{3}[\s\)-]?\d{3}-?\d{2}-?\d{2}$" required>
      <div class="form-text">Введите номер в формате 8(XXX)XXX-XX-XX или 8XXXXXXXXXX</div>
    </div>

    <button type="submit" class="btn rounded-pill w-100" style="background-image: linear-gradient(90deg, #215f1be6,              rgba(14, 148, 55, 0.9)) !important;" "> 
      Зарегистрироваться 
    </button>
  </form>
</div>
