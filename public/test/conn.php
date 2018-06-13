<?php
class test{
    private $conn;
    private $result_all = array();
    function __construct(){
        include_once "databases_info.php";
        $this->conn = new mysqli(HOST, USER, PASSWORD, DB_NAME);
        if(mysqli_connect_error()){
            echo mysqli_connect_errno().":".mysqli_connect_error();
        }
    }

    //받은 인자값으로 다양한 쿼리문을 날린다.
    function controller(){
        if(isset($_GET['con'])){
            switch($_GET['con']){
                case "select_all":
                    $this->select_all();
                    break;
                case "exist_id_check":
                    $this->exist_id_check();
                    break;
                case "create_user_ok":
                    $this->create_user_ok();
            }
        }
    }

    //로그인 버튼 클릭시,
    function select_all(){
        $query = "";

        //인자 2개를(아이디,비번)을 둘다 받지 않았을 경우 no_full 반환
        if(isset($_GET['id']) && isset($_GET['password'])){
            //문자열이 비어있지 않을 경우
            if(strcmp($_GET['id'],"") && strcmp($_GET['password'], "")) {
                $query .= "SELECT * FROM " . TABLE_NAME;
                $query .= " WHERE id = '" . $_GET['id'] . "'";
                //$query .= " AND password = '" . $_GET['password'] . "'";
            }
            else{
                echo "no_full";
                return;
            }
        }
        else{
            echo "no_full";
            return;
        }

        if ($result =  $this->conn->query($query)) {
            //글 갯수 $rows
            $rows = mysqli_num_rows($result);

            //검색 결과가 없을 때
            if($rows < 1){
                echo "no_id";
                return;
            }
            for ($i = 0; $i < $rows; $i++) {
                //[0]=num [1]=date [2]=id [3]=password [4]=name
                $result_arr = mysqli_fetch_row($result);

                if ($result_arr[3] == $_GET['password']) {
                //key,value 맞춰서 보낸다.
                array_push($this->result_all, array('id' => $result_arr[2], 'password' => $result_arr[3], 'name' => $result_arr[4]));
                }
                else{
                    echo "no_pass";
                    return;
                }
            }
            //최종 정상 반환 결과값
            //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
            //result -> X : 내용물
            echo json_encode(array("select"=>"select_all", "result"=>$this->result_all));
        }else
            echo mysqli_error($this->conn);
    }

    //회원가입 중복 아이디 찾기
    function exist_id_check(){
        $query ="";

        //널/공백 체크
        if(isset($_GET["id"])){
            if(strcmp($_GET['id'],"")){
                $query .= "SELECT * from " . TABLE_NAME;
                $query .= " where id ='" . $_GET['id'] . "'";
            }
            else{
                echo "no_full";
                return;
            }
        }else{
            echo "no_full";
            return;
        }

        if ($result =  $this->conn->query($query)) {
            //글 갯수 $rows
            $rows = mysqli_num_rows($result);

            //검색 결과가 없을 때
            if($rows < 1){
                //최종 정상 반환 결과값
                //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                //result -> X : 내용물

                echo json_encode(array("select"=>"exist_id_check", "exist_result"=>"no_exist"));
                return;
            }
            else{
                //최종 정상 반환 결과값
                //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                //result -> X : 내용물
                echo json_encode(array("select"=>"exist_id_check", "exist_result"=>"exist"));
                return;
            }
        }else
            echo "mysql_err";
    }

    //회원가입
    function create_user_ok(){
        $query = "";
        $query .= "INSERT INTO ".TABLE_NAME." (id,password,name) VALUES(";
        $query .= "'".$_GET['id']."',";
        $query .= "'".$_GET['password']."',";
        $query .= "'".$_GET['name']."')";


        if ($result =  $this->conn->query($query)) {
            echo "insert_OK";
        }else
            echo "insert_FUCK";
    }
}
$test = new test();
$test->controller();
?>