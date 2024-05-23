<?php
$username = 'Jon';
$conn = mysqli_connect("localhost:3307",$username,"1234","techshopdatabase");
$err = mysqli_connect_errno();

if($err != null){
  echo "Gabim gjate qasjes";
  $conn=null;
  die("Gabim");
  
}else{
  //echo "Jeni qasur me sukses ne Databaze";
}


?>