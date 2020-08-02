<?php
class database
{
  function __construct($host, $user, $pass, $base) {
    $this->host = $host;
    $this->user = $user;
    $this->pass = $pass;
    $this->base = $base;
  }

  public $login = "login";
  public $register = "register";
  public $table = "user_information";

  public $username = "username";
  public $email = "email";
  public $product = "product";
  public $reproduct = "re_product";
  public $password = "password";
  public $repassword = "re_password";

  public $lamp_a = "lamp_a";
  public $lamp_b = "lamp_b";
  public $lamp_a_data = "lamp_a_data";
  public $lamp_b_data = "lamp_b_data";

  public $id = "id";
  public $times = "times";
  public $volt = "volt";
  public $ampere = "ampere";
  public $statistics = "statistics";
  public $statistics_times = "statistics_data";

  function preQuery($sqli) {
    $base = mysqli_query($this->preConnect(), $sqli);
    return $base;
  }

  function createDatabase() {
    $base = mysqli_select_db($this->preConnect(), $this->base);
    if (!$base) {
      $base = $this->preQuery("CREATE DATABASE $this->base");
      if (!$base) {
        echo "Create database failed";
      }
    }
  }

  function preConnect() {
    $base = mysqli_connect($this->host, $this->user, $this->pass);
    return $base;
  }

  function createTable() {
    $this->createDatabase();
    $this->connect();
    $base = $this->query("DESCRIBE $this->table");
    if (!$base) {
      $sqli = "CREATE TABLE $this->table(
        $this->id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        $this->username VARCHAR(225) NOT NULL,
        $this->email VARCHAR(225) NOT NULL,
        $this->product VARCHAR(225) NOT NULL,
        $this->reproduct VARCHAR(225) NOT NULL,
        $this->password VARCHAR(225) NOT NULL,
        $this->repassword VARCHAR(225) NOT NULL,
        $this->lamp_a VARCHAR(225) NOT NULL,
        $this->lamp_b VARCHAR(225) NOT NULL,
        $this->lamp_a_data VARCHAR(225) NOT NULL,
        $this->lamp_b_data VARCHAR(225) NOT NULL,
        $this->volt VARCHAR(225) NOT NULL,
        $this->ampere VARCHAR(225) NOT NULL,
        $this->statistics VARCHAR(225) NOT NULL,
        $this->statistics_times VARCHAR(225) NOT NULL,
        $this->times VARCHAR(225) NOT NULL
      )";
      if (!$this->query($sqli)) {
        echo "Create table failed";
      }
    }
  }

  function connect() {
    $base = mysqli_connect($this->host, $this->user, $this->pass, $this->base);
    return $base;
  }

  function query($sqli) {
    $base = mysqli_query($this->connect(), $sqli);
    return $base;
  }

  function select($sqli) {
    $base = mysqli_num_rows($this->query($sqli));
    return $base;
  }

  function read_user($user) {
    $base = $this->query("SELECT * FROM $this->table WHERE $this->username='$user'");
    if (mysqli_num_rows($base)) {
      $base = mysqli_fetch_assoc($base);
      return $base[$this->username];
    } else {
      return false;
    }
  }

  function read_password($user, $password) {
    $base = $this->query("SELECT $this->password FROM $this->table WHERE $this->username='$user'");
    if (mysqli_num_rows($base)) {
      $base = mysqli_fetch_assoc($base);
      $pass = $base[$this->password];
      if (password_verify($password, $pass)) {
        return true;
      } else {
        return false;
      }
    }
  }

  function read_key($user) {
    $base = $this->query("SELECT $this->product FROM $this->table WHERE $this->username='$user'");
    $base = mysqli_fetch_assoc($base);
    return $base[$this->product];
  }

  function read_product($user, $product) {
    $base = $this->query("SELECT $this->reproduct FROM $this->table WHERE $this->username='$user'");
    if (mysqli_num_rows($base)) {
      $base = mysqli_fetch_assoc($base);
      $data = $base[$this->reproduct];
      if (password_verify($data, $product)) {
        return true;
      } else {
        return false;
      }
    }
  }
}

class validation extends database
{
  function error($error) {
    return '<span class="small text-danger" style="text-indent: 1px;">
      <i class="fa fa-warning small"></i>
      <span>&nbsp;'.$error.'</span>
    </span>';
  }

  function user_available($user) {
    if ($this->select("SELECT * FROM $this->table WHERE $this->username='$user'")) {
      return $this->error("Username is already taken");
    }
  }

  function email_available($email) {
    if ($this->select("SELECT * FROM $this->table WHERE $this->email='$email'")) {
      return $this->error("Email is already taken");
    }
  }

  function product_available($product) {
    if ($this->select("SELECT * FROM $this->table WHERE $this->reproduct='$product'")) {
      return $this->error("Product is already taken");
    }
  }

  function user($user) {
    if (!preg_match('/^[a-zA-Z0-9]*$/', $user)) {
      return $this->error("Username is invalid, only use letters and numbers");
    }
  }

  function email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return $this->error('Email is invalid, please enter it correctly');
    }
  }

  function product($product) {
    if (!is_numeric($product)) {
      return $this->error("Product id is invalid, please enter it correctly");
    }
  }

  function password($password) {
    if (strlen($password) < 8) {
      return $this->error("Password is invalid, use at least eight characters");
    }
  }

  function repeat($repeat, $repeater) {
    if ($repeat != $repeater) {
      return $this->error("The repetition must be the same");
    }
  }

  function hash($hash) {
    $options = ['cost' => 12];
    return password_hash($hash, PASSWORD_DEFAULT, $options);
  }

  function verify($password, $passwordHash) {
    return password_verify($password, $passwordHash);
  }
}

class notification
{
  function show($icon, $alert, $cancelable = false) {
    ?>
    <div class="modal fade" id="modal-warning">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <span class="small">
              <i class="<?=$icon?>"></i>
              <span>&nbsp;<?=$alert?></span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $("#modal-warning").modal("show");
      <?php
      if (!$cancelable) {
        ?>
        setTimeout(function() {
          $("#modal-warning").modal("hide");
        }, 2000);
        <?php
      }
      ?>
    </script>
    <?php
  }
}

class timeSelected
{
  function getHour() {
    $hour = NULL;
    for ($i = 0; $i < 24; $i ++) {
      $hour .= '<option value="'.$i.'">'.sprintf("%02d", $i).'</option>';
    }
    return $hour;
  }

  function getMinute() {
    $minute = NULL;
    for ($i = 0; $i < 60; $i ++) {
      $minute .= '<option value="'.$i.'">'.sprintf("%02d", $i).'</option>';
    }
    return $minute;
  }
}

?>
