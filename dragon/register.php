<?php
$username = $email = $product = $reproduct = $password = $repassword = NULL;
$usernameErr = $emailErr = $productErr = $reproductErr = $passwordErr = $repasswordErr = NULL;

if (isset($_POST[$base->register])) {
  $username = $_POST[$base->username];
  $email = $_POST[$base->email];
  $product = $_POST[$base->product];
  $reproduct = $_POST[$base->reproduct];
  $password = $_POST[$base->password];
  $repassword = $_POST[$base->repassword];

  if (!empty($username) && !empty($email) && !empty($product) && !empty($reproduct) && !empty($password) && !empty($repassword)) {
    $usernameErr = $validation->user($username);
    $usernameErr = $validation->user_available($username);
    $emailErr = $validation->email($email);
    $emailErr = $validation->email_available($email);
    $productErr = $validation->product($product);
    $productErr = $validation->product_available($product);
    $reproductErr = $validation->repeat($product, $reproduct);
    $passwordErr = $validation->password($password);
    $repasswordErr = $validation->repeat($password, $repassword);

    if (empty($usernameErr) && empty($emailErr) && empty($productErr) && empty($reproductErr) && empty($passwordErr) && empty($repasswordErr)) {
      $productHash = $validation->hash($product);
      $passwordHash = $validation->hash($password);

      $json = new stdClass();
      $json->hour_on = '00';
      $json->hour_off = '00';
      $json->minute_on = '00';
      $json->minute_off = '00';
      $json->date = date("n/j/Y, h:i:s A");
      $lamp_data = json_encode($json);

      $json = new stdClass();
      $data = $date = array();
      for ($i = 0; $i < 10; $i++) {
        array_push($data, 0);
        array_push($date, 0);
      }
      $json->data = $data;
      $json->date = $date;
      $statistics = json_encode($json);
      $statistics_times = date("n/j/Y, h:i:s A");

      $sqli = "INSERT INTO $base->table (
        $base->username, $base->email, $base->product, $base->reproduct, $base->password, $base->repassword, $base->lamp_a,
        $base->lamp_b, $base->lamp_a_data, $base->lamp_b_data, $base->volt, $base->ampere, $base->statistics, $base->statistics_times, $base->times
      ) VALUES (
        '$username', '$email', '$productHash', '$reproduct', '$passwordHash', '$repassword',
        '0', '0', '$lamp_data', '$lamp_data', '0', '0', '$statistics', '$statistics_times', '$statistics_times'
      )";
      if ($base->query($sqli)) {
        $alert->show("fa fa-info text-success", "Registration successfully, please login in <a href='?page=login'>here</a>", true);
        $username = $email = $product = $reproduct = $password = $repassword = NULL;
        $usernameErr = $emailErr = $productErr = $reproductErr = $passwordErr = $repasswordErr = NULL;
      } else {
        $alert->show("fa fa-warning text-danger", "Registration failed, please try again");
      }
    }
  } else {
    $alert->show("fa fa-warning text-danger", "Please fill in all fields correctly");
  }
}
?>
<div class="container-fluid mt-3">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
      <form class="p-3 bg-white rounded border shadow-sm" action="?page=register" method="post">
        <span class="h5 underline-prepend">Register</span>
        <hr class="underline">
        <?php
        $form_eror = array($usernameErr, $emailErr, $productErr, $reproductErr, $passwordErr, $repasswordErr);
        $form_name = array($base->username, $base->email, $base->product, $base->reproduct, $base->password, $base->repassword);
        $form_icon = array("user", "envelope", "gear", "gear", "lock", "lock");
        $form_valu = array($username, $email, $product, $reproduct, $password, $repassword);
        for ($i = 0; $i < count($form_name); $i ++) {
          ?><div class="input-group input-group-sm <?=!empty($form_eror[$i]) ? "pb-0" : "pb-2"?>">
            <div class="input-group-prepend"><span class="input-group-text text-center fa-width-form fa fa-<?=$form_icon[$i]?>"></span></div>
            <input class="form-control shadow-none" type="text" name="<?=$form_name[$i]?>" value="<?=$form_valu[$i]?>" placeholder="<?=ucfirst(str_replace("_", " ", $form_name[$i]))?>">
          </div><?php
          if (empty($form_eror[$i])) echo ""; else echo $form_eror[$i];
        }
        ?>
        <div class="d-flex flex-column">
          <div class="p flex-fill">
            <label class="small">
              <span>If you already have an account, please login in </span>
              <a href="?page=login">here</a>
            </label>
          </div>
          <div class="p auto align-self-end">
            <button class="btn btn-sm btn-outline-secondary shadow-none" type="submit" name="<?=$base->register?>">
              <i class="fa fa-sign-in"></i>
              <span>&nbsp;Submit</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
