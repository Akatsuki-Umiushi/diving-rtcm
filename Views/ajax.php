<?php
session_start();
require_once(ROOT_PATH .'/Controllers/GoodsController.php');

if(isset($_POST['action']) && $_POST['action']){
  $goods = new GoodsController();
  $good_toggle = $goods->use_good_toggle();
  print_r($good_toggle);

}

 ?>
