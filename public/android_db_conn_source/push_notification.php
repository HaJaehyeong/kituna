<?php   //이 php 파일을 호출하면 사용자에게 Push알람이 간다!!

//-------- 밑에서 호출시 실행되는 함수 ----------------
function send_notification ($tokens, $message){
    
    //구글fcm에 접속하여 알림을 보내는 과정
    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(

        //1000개 미만의 device에게 메시지를 전송 할 수 있다.
        'registration_ids' => $tokens,

        //payload 설정(notification or data)
        //'notification' => array('title' => '공지사항', 'body' => $message),
        'data' => $message

    );

    //google FCM API_KEY를 가져와서 설정에 추가한다.
    $headers = array(
        'Authorization:key =' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                        //url설정
    curl_setopt($ch, CURLOPT_POST, true);                 //POST방식으로 설정
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);             //HTTP 설정(XML 전송)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);       //curl_exec() 결과 직접 밖으로 호출
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);          //호스트에 대한 인증서 이름 확인
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);      //인증서 확인
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields)); //POST메소드 파라미터 값 설정

    //실행!
    $result = curl_exec($ch);
    
    //실행 실패시 죽어요 흑흑 ㅠㅠ
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    
    //자원은 항상 닫자! 메모리 누수 일어난다! 뿌에엥
    curl_close($ch);
    return $result;
}
//-------------------------------------------------------------------------

//데이터베이스에 접속해서 토큰들을 가져와서 FCM에 발신요청
include_once 'databases_info.php';
$conn = mysqli_connect(HOST, USER, PASSWORD, DB_NAME);

//특정 인원에게만 보내고 싶을 시,
if(isset($_POST['user_info_id'])){
    $sql = "SELECT * FROM ".TOKEN_INFO." WHERE user_info_id = '".$_POST['user_info_id']."'";
}

//전체 다 보내고 싶을 시, POST를 주지 않는다
else {
    $sql = "SELECT * FROM " . TOKEN_INFO;
}

//유저들의 정보들을 가져온다(id,token,user_info_id,name)
$result = mysqli_query($conn,$sql);
$tokens = array();

//가져온 값이 없다면 실행하지 않는다
if(mysqli_num_rows($result) > 0 ){

    //있다면 유저들의 정보를 배열에 담는다.
    while ($row = mysqli_fetch_assoc($result)) {
        $tokens[] = $row["token"];
    }
}

mysqli_close($conn);

$myMessage = "";

//메세지를 받았다면 그메세지를 출력한다
if(isset($_POST['message'])){
    $myMessage = $_POST['message']; //폼에서 입력한 메세지를 받음
}

//받지 않았다면 기본 메세지를 출력한다.
if ($myMessage == ""){
    $myMessage = "作業の指示が追加されました";
}

$message = array("message" => $myMessage);

//지정한 유저들에게 메세지를 보낸다
$message_status = send_notification($tokens, $message);
echo $message_status;

?>
