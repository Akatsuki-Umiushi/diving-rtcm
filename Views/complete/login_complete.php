<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
$users = new UsersController();
$login = $users->Login();

 ?>
