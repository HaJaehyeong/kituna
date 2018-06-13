<?php
//토큰 저장 하는곳 -> POST로 정상값을 받아왔다면 정상 실행
if(isset($_POST['token']) && isset($_POST['name']) && isset($_POST['user_info_id'])){

    $token          = $_POST["token"];
    $name           = $_POST["name"];
    $user_info_id   = $_POST['user_info_id'];

    //데이터베이스에 접속해서 토큰을 저장
    include_once 'databases_info.php';
    
    $conn = mysqli_connect(HOST, USER, PASSWORD, DB_NAME);
    $query = "INSERT INTO ".TOKEN_INFO."(token, user_info_id, name) 
              VALUES ('".$token."','".$user_info_id."','".$name."') 
              ON DUPLICATE KEY UPDATE Token = '".$token."' ";

    //쿼리 실행
    mysqli_query($conn, $query);

    mysqli_close($conn);
}

?>