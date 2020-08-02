<?php
$time = NULL;
$timenow = date("Y");
$timebuild = 2020;
if ($timebuild == $timenow) {
  $time = $timebuild;
}
?>
<div class="d-flex justify-content-end mt-2 mb-3">
  <div class="p-2 auto text-right">
    <span class="font-weight-bolder text-white">&copy;<?=$time?> Dragon Fruit Control</span>
    <br>
    <span class="font-weight-bolder text-white">By: rjs-devs</span>
  </div>
</div>
