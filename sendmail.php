<?php
/** CREATED BY PhpStorm
 * USER:孟凡
 * DATE:2017/4/28 0028
 * TIME:下午 3:32
 */
require_once("./functions.php");
$email=$_REQUEST['email'];
$name=$_REQUEST['name'];
$con=$_REQUEST['message'];
sendMail($email,$name,$con);
file_put_contents('message.txt',"----message----\r\n",FILE_APPEND | LOCK_EX);
file_put_contents('message.txt','Email:'.$email. '   Name:'.$name. "\r\nMessage:".$con,FILE_APPEND | LOCK_EX);