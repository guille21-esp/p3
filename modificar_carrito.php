<?php
session_start();
require_once 'Database.php';

if(isset($_SESSION['id_cliente'])){
    header('Location: login.php');
    exit;
}