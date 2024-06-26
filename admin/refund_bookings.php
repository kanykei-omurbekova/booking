<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  adminLogin();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Админ Панель - Возврат Бронирований</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">ВОЗВРАТ БРОНИРОВАНИЙ</h3>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">

            <div class="text-end mb-4">
              <input type="text" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Введите для поиска...">
            </div>

            <div class="table-responsive">
              <table class="table table-hover border" style="min-width: 1200px;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">Данные пользователя</th>
                    <th scope="col">Детали комнаты</th>
                    <th scope="col">Сумма возврата</th>
                    <th scope="col">Действие</th>
                  </tr>
                </thead>
                <tbody id="table-data">                 
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>

  <?php require('inc/scripts.php'); ?>

  <script src="scripts/refund_bookings.js"></script>

</body>
</html>
