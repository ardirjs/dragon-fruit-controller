<?php
$page = $user = $keys = NULL;
if (!isset($_GET["page"])) {
  ?>
  <script type="text/javascript">
    location.href = "http://<?=$_SERVER['HTTP_HOST']?>/?page=home";
  </script>
  <?php
}
if (isset($_GET["page"]) && isset($_GET["user"]) && isset($_GET["keys"])) {
  $page = $_GET['page'];
  $user = $_GET["user"];
  $keys = $_GET["keys"];
}
?>
<div class="modal fade" id="modal-loading">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <div class="d-flex align-items-center">
          <div class="p auto"><span class="spinner-border"></span></div>
          <div class="p auto"><span class="small">&emsp;Please wait...!!!</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include_once 'meter.php';
include_once 'lines.php';
?>
<div class="container-fluid">
  <?php include_once 'footer.php'; ?>
</div>
