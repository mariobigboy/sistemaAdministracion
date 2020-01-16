<?php 
ob_start();
session_start();
include 'inc/config.php'; 
include("inc/functions.php");

 /*   $row['user']['id'];
    $row['user']['full_name'];
    $row['user']['email'];
    $row['user']['phone'];
    $row['user']['password'];
    $row['user']['photo'];
    $row['user']['role'];
    $row['user']['sucursal'];
    $row['user']['status'];*/
$fecha = time();
$ip = getRealIpAddr();
                $logStat = $pdo->prepare("INSERT INTO `tbl_logs`(
                										`id_usuario`, 
                										`id_sucursal`, 
                										`id_producto`, 
                										`detalle`, 
                										`fecha`,
                                                        `ip`
                									) VALUES (?,?,?,?,?,?);");
                $logStat->execute(array(
                						$_SESSION['user']['id'],
                						$_SESSION['user']['sucursal'],
                						'0',
                						'LOGOUT: '.$_SESSION['user']['full_name'],
                						$fecha,
                                        $ip
                					));


unset($_SESSION['user']);
header("location: login.php"); 
?>