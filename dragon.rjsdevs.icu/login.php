<?php
$username = $password = NULL;
$usernameErr = $passwordErr = NULL;

if (isset($_POST[$base->login])) {
  $username = $_POST[$base->username];
  $password = $_POST[$base->password];

  if (!empty($username) && !empty($password)) {
    if (!$base->read_user($username)) {
      $usernameErr = $validation->error("Username not registered");
    } else {
      if (!$base->read_password($username, $password)) {
        $passwordErr = $validation->error("Incorrect password");
      } else {
        $product = $base->read_key($username);
        ?>
        <script type="text/javascript">
          location.href = "http://<?=$_SERVER['HTTP_HOST']?>/?page=home&user=<?=$username?>&keys=<?=$product?>";
        </script>
        <?php
        $username = $password = NULL;
        $usernameErr = $passwordErr = NULL;
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
      <form class="p-3 bg-white rounded border shadow-sm" action="?page=login" method="post">
        <span class="h5 underline-prepend">Login</span>
        <hr class="underline">
        <?php
        $form_eror = array($usernameErr, $passwordErr);
        $form_name = array($base->username, $base->password);
        $form_icon = array("user", "lock");
        $form_valu = array($username, $password);
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
              <span>If you don't have an account, please register in </span>
              <a href="?page=register">here</a>
            </label>
          </div>
          <div class="p auto align-self-end">
            <button class="btn btn-sm btn-outline-secondary shadow-none" type="submit" name="<?=$base->login?>">
              <i class="fa fa-sign-in"></i>
              <span>&nbsp;Submit</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
