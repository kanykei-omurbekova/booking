<?php 

  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');
  date_default_timezone_set("Asia/Kolkata");

  session_start();
  unset($_SESSION['room']);

  function regenrate_session($uid)
  {
    $user_q = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$uid],'i');
    $user_fetch = mysqli_fetch_assoc($user_q);

    $_SESSION['login'] = true;
    $_SESSION['uId'] = $user_fetch['id'];
    $_SESSION['uName'] = $user_fetch['name'];
    $_SESSION['uPic'] = $user_fetch['profile'];
    $_SESSION['uPhone'] = $user_fetch['phonenum'];
  }

  if(isset($_GET['ORDERID'])) 
  {
    $ORDERID = $_GET['ORDERID'];
    $STATUS = $_GET['STATUS'];
    $TXNID = $_GET['TXNID'];
    $TXNAMOUNT = $_GET['TXNAMOUNT'];
    $RESPMSG = $_GET['RESPMSG'];

    $slct_query = "SELECT `booking_id`, `user_id` FROM `booking_order` 
      WHERE `order_id`='$ORDERID'";

    $slct_res = mysqli_query($con, $slct_query);

    if(mysqli_num_rows($slct_res) == 0){
      redirect('index.php');
    }

    $slct_fetch = mysqli_fetch_assoc($slct_res);

    if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)){
      regenrate_session($slct_fetch['user_id']);
    }

    if ($STATUS == "TXN_SUCCESS") 
    {
      $upd_query = "UPDATE `booking_order` SET `booking_status`='booked',
        `trans_id`='$TXNID',`trans_amt`='$TXNAMOUNT',
        `trans_status`='$STATUS',`trans_resp_msg`='$RESPMSG' 
        WHERE `booking_id`='$slct_fetch[booking_id]'";

      mysqli_query($con, $upd_query);
    }
    else 
    {
      $upd_query = "UPDATE `booking_order` SET `booking_status`='payment failed',
        `trans_id`='$TXNID',`trans_amt`='$TXNAMOUNT',
        `trans_status`='$STATUS',`trans_resp_msg`='$RESPMSG' 
        WHERE `booking_id`='$slct_fetch[booking_id]'";

      mysqli_query($con, $upd_query);
    }
    redirect('pay_status.php?order='.$ORDERID);
  }
  else{
    redirect('index.php');
  }

?>
