<?php 

  require('admin/inc/essentials.php');
  require('admin/inc/db_config.php');
  require('admin/inc/mpdf/vendor/autoload.php');

  session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)){
    redirect('index.php');
  }

  if(isset($_GET['gen_pdf']) && isset($_GET['id']))
  {
    $frm_data = filteration($_GET);

    $query = "SELECT bo.*, bd.*, uc.email FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      INNER JOIN `user_cred` uc ON bo.user_id = uc.id
      WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
      OR (bo.booking_status='cancelled' AND bo.refund=1)
      OR (bo.booking_status='payment failed')) 
      AND bo.booking_id = '$frm_data[id]'";

    $res = mysqli_query($con, $query);
    $total_rows = mysqli_num_rows($res);

    if($total_rows == 0){
      header('location: index.php');
      exit;
    }

    $data = mysqli_fetch_assoc($res);

    $date = date("h:ia | d-m-Y", strtotime($data['datentime']));
    $checkin = date("d-m-Y", strtotime($data['check_in']));
    $checkout = date("d-m-Y", strtotime($data['check_out']));

    $table_data = "
    <h2>КВИТАНЦИЯ О БРОНИРОВАНИИ</h2>
    <table border='1'>
      <tr>
        <td>Номер заказа: $data[order_id]</td>
        <td>Дата бронирования: $date</td>
      </tr>
      <tr>
        <td colspan='2'>Статус: $data[booking_status]</td>
      </tr>
      <tr>
        <td>Имя: $data[user_name]</td>
        <td>Email: $data[email]</td>
      </tr>
      <tr>
        <td>Номер телефона: $data[phonenum]</td>
        <td>Адрес: $data[address]</td>
      </tr>
      <tr>
        <td>Название номера: $data[room_name]</td>
        <td>Стоимость: $data[price]c за ночь</td>
      </tr>
      <tr>
        <td>Дата заезда: $checkin</td>
        <td>Дата выезда: $checkout</td>
      </tr>
    ";

    if($data['booking_status'] == 'cancelled')
    {
      $refund = ($data['refund']) ? "Сумма возвращена" : "Еще не возвращено";

      $table_data .= "<tr>
        <td>Оплаченная сумма: $data[trans_amt]c</td>
        <td>Возврат: $refund</td>
      </tr>";
    }
    else if($data['booking_status'] == 'payment failed')
    {
      $table_data .= "<tr>
        <td>Сумма транзакции: $data[trans_amt] c</td>
        <td>Причина сбоя: $data[trans_resp_msg]</td>
      </tr>";
    }
    else
    {
      $table_data .= "<tr>
        <td>Номер комнаты: $data[room_no]</td>
        <td>Оплаченная сумма: $data[trans_amt] c</td>
      </tr>";
    }

    $table_data .= "</table>";

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($table_data);
    $mpdf->Output($data['order_id'] . '.pdf', 'D');

  }
  else {
    header('location: index.php');
  }
  
?>
