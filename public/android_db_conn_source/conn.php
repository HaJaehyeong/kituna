<?php
class test{

    private $conn;
    private $coin_info      = array();
    private $result_all     = array();
    private $product_all    = array();
    private $jo_colmn_arr   = array();
    private $car_stock_arr  = array();
    
    //생성자
    function __construct(){
        
        //db 정보를 가져온다
        include_once "databases_info.php";
        
        //db 접속(공통)
        $this->conn = new mysqli(HOST, USER, PASSWORD, DB_NAME);
        
        //접속 실패시 에러 반환
        if(mysqli_connect_error()){
            echo mysqli_connect_errno().":".mysqli_connect_error();
        }
        
    }
    
    //받은 인자값으로 다양한 쿼리문을 날린다.
    function controller(){

        //첫번째 인자로 con을 받는다 -> 최초 구분자
        if(isset($_GET['con'])){

            //라우팅역할을 해서 그에 맞는 함수를 호출한다
            switch($_GET['con']){

                //로그인 버튼 클릭시,
                case "select_all":
                    $this->select_all();
                    break;

                //회원가입 중복 아이디 찾기
                case "exist_id_check":
                    $this->exist_id_check();
                    break;

                //회원가입 insert
                case "create_user_ok":
                    $this->create_user_ok();
                    break;

                //아이디 찾기
                case "serch_id":
                    $this->serch_id();
                    break;

                //비밀번호 찾기
                case "serch_pass":
                    $this->serch_pass();
                    break;

                //마커들 정보 가져오기
                case "get_markers":
                    $this->get_markers();
                    break;

                //특정자판기 마커 클릭시 db에서 자판기 자료 가져오기
                case "get_vending_info":
                    $this->get_vending_info();
                    break;

                //작업지시서 가져오기
                case "get_order_sheet":
                    $this->get_order_sheet();
                    break;

                //자판기 강제갱신 vendingmachine, vd_stock, jo_column, car_stock update set 후
                //select jo_column 으로 자판기들 반환
                case "insert_vending":
                    $this->insert_vending();
                    break;

                //토큰 테이블(user_info_id, name)을 업데이트 하는 함수
                case "token":
                    $this->token();
                    break;

                case "calendar_get_Day":
                    $this->calendar_get_Day();
                    break;

            }

        }

    }

    //로그인 버튼 클릭시,
    function select_all(){
        
        $query = "";

        //인자 2개를(아이디,비번)을 둘다 받지 않았을 경우 no_full 반환
        if(isset($_GET['id']) && isset($_GET['password'])){

            //문자열이 비어있지 않을 경우 쿼리문 제작
            if(strcmp($_GET['id'],"") && strcmp($_GET['password'], "")) {
                $query = "SELECT * FROM " . SUPPLE_TABLE;
                $query .= " WHERE sp_login_id = '" . $_GET['id'] . "'";
            }

            //문자열이 비어있는 경우 에러 반환
            else{
                echo "no_full";
                return;
            }

        }

        //인자를 둘다 받지 않았다면 에러 반환
        else{
            echo "no_full";
            return;
        }

        //쿼리 실행
        if ($result =  $this->conn->query($query)) {

            //글 갯수 $rows
            $rows = mysqli_num_rows($result);

            //검색 결과가 없을 때 에러 반환
            if($rows < 1){
                echo "no_id";
                return;
            }

            //가져온 행반큼 반복문 실행
            for ($i = 0; $i < $rows; $i++) {

                //[0]=sp_id [1]=sp_login_id [2]=sp_password [3]=sp_name [4]=sp_phone [5]=sp_mail [6]=sp_address [7]=sp_img_path
                $result_arr = mysqli_fetch_row($result);

                //패스워드가 일치 할 시, 값들을 배열에 저장한다
                if ($result_arr[2] == $_GET['password']) {

                    //key,value 맞춰서 저장
                    array_push($this->result_all,
                        array(
                            'login_id'  => $result_arr[1],
                            'name'      => $result_arr[3],
                            'email'     => $result_arr[5],
                            'imgsrc'    => $result_arr[7]
                        ));

                }

                //패스워드가 일치하지 않을시 없다는걸 알려주는 메세지 반환
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
            echo "mysql_err";
    }

    //회원가입 중복 아이디 찾기
    function exist_id_check(){

        $query ="";

        //id를 받아왔는지 확인 / 성공시 다음 조건문으로
        if(isset($_GET["id"])){

            //받아온 id가 빈 값이 아니라면 쿼리 생성
            if(strcmp($_GET['id'],"")){
                $query = "SELECT * FROM " . SUPPLE_TABLE;
                $query .= " where sp_login_id ='" . $_GET['id'] . "' ";
            }

            //받아온 값이 빈 값이라면 에러 반환
            else{
                echo "no_full";
                return;
            }

        }

        //받아온 GET값이 없다면 에러 반환
        else{
            echo "no_full";
            return;
        }

        //쿼리 실행
        if ($result =  $this->conn->query($query)) {

            //글 갯수 $rows
            $rows = mysqli_num_rows($result);

            //검색 결과가 없을 때 빈 값이라는 것을 알려줌
            if($rows < 1){

                //최종 정상 반환 결과값
                echo "no_exist";
                return;
            }

            //검색 결과가 있는 경우 존재한다는 것을 알려줌
            else{
                //최종 정상 반환 결과값
                echo "exist";
                return;
            }

        }else
            echo $this->conn->error;
    }

    //회원가입 insert
    function create_user_ok(){

        $query = "";
        $query .= "INSERT INTO ".SUPPLE_TABLE." (sp_login_id,sp_password,sp_name,sp_phone,sp_mail,sp_address,sp_img_path) VALUES(";
        $query .= "'".$_GET['id']."',";
        $query .= "'".$_GET['password']."',";
        $query .= "'".$_GET['name']."',";
        $query .= "'".$_GET['phone']."',";
        $query .= "'".$_GET['email']."',";
        $query .= "'".$_GET['address']."',";
        $query .= "'/images/supplementer/".$_GET['id']."')";

        //쿼리 실행 -> 실패시 에러 반환
        if ($result =  $this->conn->query($query)) {
            echo "insert_OK";
        }else
            echo "mysql_err";

    }

    //아이디 찾기
    function serch_id(){

        //쿼리가 들어갈 문자열
        $query ="";

        //GET값을 받아 왔다면 다음 조건문으로
        if(isset($_GET["serch_name"])){

            //공백이 아니라면 쿼리문 생성
            if(strcmp($_GET['serch_name'],"")){
                $query = "SELECT * FROM " . SUPPLE_TABLE;
                $query .= " WHERE sp_name ='" . $_GET['serch_name'] . "'";
            }

            //공백이라면 에러 반환
            else{
                echo "no_full";
                return;
            }

        }

        //GET값을 받아오지 않았다면 에러 반환
        else{
            echo "no_full";
            return;
        }

        //쿼리 실행
        if ($result =  $this->conn->query($query)) {

            //글 갯수 $rows
            $rows = mysqli_num_rows($result);

            //검색 결과가 없을 때 결과가 없다는 문자열 반환
            if($rows < 1){
                //최종 정상 반환 결과값
                echo "no_exist";
                return;
            }

            //검색결과가 하나라도 있을 시, 값을 배열에 보낸다
            else{

                //받아온 값을 반복문에 돌린다
                for ($i = 0; $i < $rows; $i++) {

                    //[0]=sp_id [1]=sp_login_id [2]=sp_password [3]=sp_name [4]=sp_phone [5]=sp_mail [6]=address [7]=sp_img_path
                    $result_arr = mysqli_fetch_row($result);

                    //배열에 sp_login_id만 넣어서 보낸다
                    array_push($this->result_all, $result_arr[1]);
                }

                //최종 정상 반환 결과값
                //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                //result -> X : 내용물
                echo json_encode(array("select"=>"serch_id", "result"=>$this->result_all));
                return;
            }

        }else
            echo "mysql_err";
    }

    //비밀번호 찾기
    function serch_pass(){

        //쿼리가 들어갈 문자열
        $query ="";

        //GET값을 둘다 받아 왔다면 다음 조건문으로
        if(isset($_GET["serch_id"]) && isset($_GET['serch_name'])){

            //받아온 값이공백이 아닌경우 쿼리 생성
            if(strcmp($_GET['serch_id'],"") && strcmp($_GET['serch_name'],"")){
                $query = "SELECT * FROM " . SUPPLE_TABLE;
                $query .= " WHERE sp_login_id ='" . $_GET['serch_id'] . "'";
                $query .= " AND sp_name ='" . $_GET['serch_name'] . "'";
            }

            //받아온 값이 공백인 경우 에러 반환
            else{
                echo "no_full";
                return;
            }

        }

        //GET값을 제대로 받아오지 않았을 경우 에러 반환
        else{
            echo "no_full";
            return;
        }

        //쿼리 실행
        if ($result =  $this->conn->query($query)) {

            //글 갯수 $rows
            $rows = mysqli_num_rows($result);

            //검색 결과가 없을 때 결과값이 없다는 문자열 반환
            if($rows < 1){
                //최종 정상 반환 결과값
                echo "no_exist";
                return;
            }

            //검색된 결과값이 하나라도 있을 시, 값을 배열에 저장하여 반환
            else{

                for ($i = 0; $i < $rows; $i++) {

                    //[0]=sp_id [1]=sp_login_id [2]=sp_password [3]=sp_name [4]=sp_phone [5]=sp_mail [6]=address [7]=sp_img_path
                    $result_arr = mysqli_fetch_row($result);

                    //id,password,name을 배열에 저장
                    array_push($this->result_all, array("id"=>$result_arr[1], "password"=>$result_arr[2],"name"=>$result_arr[3]));

                    //id가 고유값이기때문에 1개밖에 나오지 않지만 만에하나를 위해 break
                    break;
                }

                //최종 정상 반환 결과값
                //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                //result -> X : 내용물
                echo json_encode(array("select"=>"serch_pass", "result"=>$this->result_all));
                return;
            }

        }else
            echo "mysql_err";
    }

    //마커들 정보 가져오기 -> 지도에 뿌려주는 역할
    function get_markers(){

        //쿼리가 들어갈 문자열
        $query ="";

        //GET값을 제대로 받아왔다면 다음 조건문으로
        if(isset($_GET['user_login_id']) && isset($_GET['order_date'])){

            //받아온 값이 빈값이 아니라면 쿼리문 생성
            if(strcmp($_GET['user_login_id'],"")){

                $query = "SELECT * FROM ".VENDING_TABLE." 
                WHERE vd_id in (SELECT DISTINCT vd_id
                                FROM ".JOB_COLUMN." jc
                                JOIN ".JOB_ORDER."  jo ON jo.jo_id = jc.jo_id
                                WHERE sp_login_id = '".$_GET['user_login_id']."' 
                                AND jc.sp_check = 0
                                AND date_format(jo.order_date, '%Y-%m-%d') ='".$_GET['order_date']."')";

                //쿼리문 실행
                if ($result =  $this->conn->query($query)) {

                    //글 갯수 $rows
                    $rows = mysqli_num_rows($result);

                    //검색 결과가 없을 때 마커가 없다는 문자열을 반환
                    if($rows < 1){
                        //최종 정상 반환 결과값
                        echo "no_marker";
                        return;
                    }

                    //검색결과가 하나라도 있을 시, 배열에 값을 넣어 반환
                    else{

                        for ($i = 0; $i < $rows; $i++) {

                            //[0]=vd_id [1]=vd_name [2]=vd_latitude [3]=vd_longitude [4]=vd_address [5]=vd_place [6]=vd_supplement [7]=vd_soldout
                            $result_arr = mysqli_fetch_row($result);

                            //배열에 차곡차곡 저장
                            array_push($this->result_all,
                                array(
                                    "vd_id"=>$result_arr[0],
                                    "vd_name"=>$result_arr[1],
                                    "vd_latitude"=>$result_arr[2],
                                    "vd_longitude"=>$result_arr[3],
                                    "vd_address"=>$result_arr[4],
                                    "vd_place"=>$result_arr[5],
                                    "vd_supplement"=>$result_arr[6],
                                    "vd_soldout"=>$result_arr[7]
                                ));

                        }

                        //최종 정상 반환 결과값
                        //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                        //result -> X : 내용물
                        echo json_encode(array("select"=>"get_markers", "result"=>$this->result_all));
                        return;
                    }

                    //이외 예외처리에 통과못한 경우 에러 반환
                }else
                    echo "mysql_err";
            }else{
                echo "no_marker";
            }
        }else{
            echo "no_marker";
        }
    }

    //특정자판기 마커 클릭시 db에서 자판기 자료 가져오기
    function get_vending_info(){

        //쿼리가 들어갈 문자열
        $query = "";

        //GET값을 제대로 받아왔다면 다음 조건문으로
        if(isset($_GET['vending_id'])){

            //받아온 값이 빈 값이 아닌경우 쿼리문 생성
            if(strcmp($_GET['vending_id'],"")){

                //해당 자판기의 동전 테이블 가져오기
                $query = "SELECT * FROM ".COINT_STOCK. " WHERE vd_id = ".$_GET['vending_id'];

                //쿼리 실행
                if ($result =  $this->conn->query($query)) {

                    //글 갯수 $rows
                    $rows = mysqli_num_rows($result);

                    //검색 결과가 없을 때 아무것도 하지 않는다
                    if($rows < 1){}

                    //검색 결과가 있을 때 배열에 값을 저장한다
                    else{

                        for ($i = 0; $i < $rows; $i++) {

                            //[0]=coin_var [1]=vd_id [2]=1000 [3]=500 [4]=100 [5]=50 [6]=10 [7]=5 [8]=1
                            $result_arr = mysqli_fetch_row($result);

                            //배열에 저장
                            array_push($this->coin_info,
                                array(
                                    "vd_id"=>$result_arr[0],
                                    "1000"=>$result_arr[1],
                                    "500"=>$result_arr[2],
                                    "100"=>$result_arr[3],
                                    "50"=>$result_arr[4],
                                    "10"=>$result_arr[5],
                                    "5"=>$result_arr[6],
                                    "1"=>$result_arr[7]
                                ));

                        }

                    }

                }

                //임시 note 저장 String
                $vending_note = "";

                //자판기 자체의 note가 있는지 확인한다(쿼리문 제작)
                $query = "SELECT note
                          FROM ".VENDING_TABLE."
                          WHERE vd_id = ".$_GET['vending_id'];

                //쿼리 실행
                if ($result =  $this->conn->query($query)) {

                    //글 갯수 $rows
                    $rows = mysqli_num_rows($result);

                    //검색 결과가 있을때
                    if ($rows > 0) {

                        //결과값이 원래 1개만 넘어오지만 예외처리로 반복문 만듦
                        for ($i = 0; $i < $rows; $i++) {

                            $result_arr = mysqli_fetch_row($result);

                            //결과를 note에 저장해 둔다
                            $vending_note = $result_arr[0];

                        }

                    }

                }

                //자판기 정보들 가져오기(쿼리문 제작)
                $query ="SELECT  vd_id, vd_name,drink_name, drink_path, sp_val, drink_line, note 
                         FROM ".JOB_COLUMN."  
                         WHERE vd_id = ".$_GET['vending_id']."
                         AND   sp_check = 0";

                //쿼리 실행
                if ($result =  $this->conn->query($query)) {

                    //글 갯수 $rows
                    $rows = mysqli_num_rows($result);

                    //검색 결과가 없을 때 에러 반환
                    if($rows < 1){
                        //최종 정상 반환 결과값
                        echo "1no_vending";
                        return;
                    }

                    //검색결과가 하나라도 있을 때 배열에 값을 저장하여 반환한다.
                    else{

                        for ($i = 0; $i < $rows; $i++) {

                            //[0]=vd_id [1]=vd_name [2]=drink_name [3]=drink_path [4]=s-_val [5]=drink_line [6]=note
                            $result_arr = mysqli_fetch_row($result);

                            //자판기에 노트가 있다면
                            if($vending_note != null && strlen($vending_note) > 1){

                                //jo_column에 노트가 있다면 노트에 둘다 저장하여 반환
                                if(strcasecmp($result_arr[6],"null") !=0 && $result_arr[6] != null){

                                    //배열에 저장
                                    array_push($this->result_all,
                                        array(
                                            "vd_id" => $result_arr[0],
                                            "vd_name" => $result_arr[1],
                                            "drink_name" => $result_arr[2],
                                            "drink_path" => $result_arr[3],
                                            "drink_stook" => $result_arr[4],
                                            "drink_line" => $result_arr[5],
                                            "note" => $vending_note ."\n".$result_arr[6]
                                        ));

                                }

                                //없다면 자판기 노트만 저장하여 반환
                                else{

                                    //배열에 저장
                                    array_push($this->result_all,
                                        array(
                                            "vd_id" => $result_arr[0],
                                            "vd_name" => $result_arr[1],
                                            "drink_name" => $result_arr[2],
                                            "drink_path" => $result_arr[3],
                                            "drink_stook" => $result_arr[4],
                                            "drink_line" => $result_arr[5],
                                            "note" => $vending_note
                                        ));

                                }

                                //중복을 막기위해 자판기 노트는 널로 바꾼다
                                $vending_note = null;
                            }

                            //자판기에 노트가 없다면 컬럼 노트만 추가한다
                            else {

                                //배열에 저장
                                array_push($this->result_all,
                                    array(
                                        "vd_id" => $result_arr[0],
                                        "vd_name" => $result_arr[1],
                                        "drink_name" => $result_arr[2],
                                        "drink_path" => $result_arr[3],
                                        "drink_stook" => $result_arr[4],
                                        "drink_line" => $result_arr[5],
                                        "note" => $result_arr[6]
                                    ));

                            }

                        }

                        //최종 정상 반환 결과값
                        //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                        //result -> X : 내용물
                        echo json_encode(array("select"=>"get_vending_info", "result"=>$this->result_all, "coin"=>$this->coin_info));
                        return;
                    }

                }

                //쿼리가 실행되지 않았다면 에러 반환
                else{
                    echo "2no_vending";
                }

            }

            //GET값이 올바르게 들어오지 않았을경우 에러 반환
            else{
                echo "3no_vending";
            }

        }

        //GET값이 없는 경우 에러 반환
        else{
            echo "4no_vending";
        }

    }

    //작업지시서 가져오기
    function get_order_sheet(){

        //자판기들의 노트 배열 생성
        $note_all = array();

        //먼저 자판기의 노트를 가져온다(쿼리 생성)
        $query = "SELECT vd_id, note FROM ".VENDING_TABLE." 
                  WHERE vd_id in (SELECT DISTINCT vd_id
                                FROM ".JOB_COLUMN." jc
                                JOIN ".JOB_ORDER."  jo ON jo.jo_id = jc.jo_id
                                WHERE sp_login_id = '".$_GET['user_login_id']."' 
                                AND date_format(jo.order_date, '%Y-%m-%d') ='".$_GET['serch_date']."'
                                )";

        //쿼리 실행
        if ($result =  $this->conn->query($query)) {

            //글 갯수 $rows
            $rows = mysqli_num_rows($result);

            //검색 결과가 있을 때 배열에 값을 저장하여 반환한다
            if($rows > 0){

                for ($i = 0; $i < $rows; $i++) {

                    //[0]=vd_id [1]=note
                    $result_arr = mysqli_fetch_row($result);

                    //널값인경우 아무것도 실행하지 않는다
                    if(strcasecmp($result_arr[1],"null") == 0 || $result_arr[1] == null){}

                    //널값이 아닌경우에만 받아온다 -> 배열에 저장
                    else {

                        array_push($note_all,
                            array(
                                "vd_id" => $result_arr[0],
                                "note" => $result_arr[1]
                            ));

                    }

                }

            }
        }

        //쿼리 초기화
        $query ="";

        //음료 종류 + 자동차 재고 부터 먼저 검색 쿼리 생성
        $query = "SELECT DISTINCT pi.drink_name, cs.stock 
                  FROM ".PRODUCT_INFO." pi 
                  JOIN ".STOCK_MANAGE." sm  ON pi.drink_id = sm.drink_id
                  JOIN ".CAR_STOCK."    cs  ON sm.stock_id = cs.stock_id
                  JOIN ".SUPPLE_TABLE." sup ON sup.sp_login_id = cs.sp_login_id
                  WHERE sup.sp_login_id='".$_GET['user_login_id']."'";

        //쿼리 실행
        if($result = $this->conn->query($query)){

            //글 갯수 $rows
            $rows = mysqli_num_rows($result);

            //갯수가 0개라면 에러 반환
            if($rows < 1){
                echo "no_marker";
                return;
            }

            //갯수가 하나라도 있을 경우 값을 배열에 저장하여 반환한다
            else{

                for ($i = 0; $i < $rows; $i++) {

                    //[0]=drink_name, [1]=stock
                    $result_arr = mysqli_fetch_row($result);

                    //배열에 저장
                    array_push($this->product_all,
                        array(
                            "drk_name"=>$result_arr[0],
                            "stock"=>$result_arr[1]
                        ));

                }

            }

        }

        //쿼리 초기화
        $query = "";

        //보충완료한 자판기랑 보충미완료 자판기를 구분하기 위한 boolean변수
        $before_check = true;

        //GET값을 제대로 받아왔다면 다음 조건문 실행
        if(isset($_GET['user_login_id']) && isset($_GET['serch_date'])){

            //받아온 값이 빈값이 아닐경우 쿼리 생성
            if(strcmp($_GET['user_login_id'],"")){

                //이미 보충완료를 한 상태인 지시서를 가져온다
                $query = "SELECT sp.sp_name, jc.vd_name, jc.drink_name, jc.drink_path, jc.sp_val, jc.drink_line, note, jc.sp_check, jc.vd_id
                           FROM ".JOB_COLUMN."   jc
                           JOIN ".JOB_ORDER."    jo ON jc.jo_id = jo.jo_id
                           JOIN ".SUPPLE_TABLE." sp ON jo.sp_id = sp.sp_id
                           WHERE sp.sp_login_id = '".$_GET['user_login_id']."'
                           AND date_format(jo.order_date, '%Y-%m-%d')='".$_GET['serch_date']."'
                           AND jc.sp_check = 1";

                //쿼리 실행
                if ($result =  $this->conn->query($query)) {

                    //글 갯수 $rows
                    $rows = mysqli_num_rows($result);

                    //검색 결과가 없을 때  이전 체크를 false로 바꾼다
                    if($rows < 1){
                        //최종 정상 반환 결과값
                        //echo "no_marker";
                        // return;
                        $before_check = false;
                    }

                    //검색 결과가 있을 때 값을 배열에 저장
                    else{

                        //이전 키 저장
                        $before_key = -1;

                        for ($i = 0; $i < $rows; $i++) {

                            //[0]=sp_name [1]=vd_name [2]=drink_name [3]=drink_path [4]=sp_val [5]=drink_line, [6]=note [7]=sp_check [8]=vd_id
                            $result_arr = mysqli_fetch_row($result);

                            //배열에 1개이상 들어가 있는 경우에만 실행
                            if(count($note_all) > 0) {

                                //배열 탐색(vd_id가 일치할 경우) -> 검색된 애의 배열위치 반환
                                $key = array_search($result_arr[8], array_column($note_all, "vd_id"));
                                
                                //검색한 결과값이 배열에 저장되어 있는 경우(배열위치가 INT로 저장되어있음) 다음 조건문 실행
                                if($key !==false && $before_key != $key){
                                    
                                    //노트가 들어있지 않은경우 자판기 노트만 출력
                                    if(strcasecmp("null",$result_arr[6]) == 0 || $result_arr[6] == null){
                                        
                                        //배열에 값을 추가한다
                                        array_push($this->result_all,
                                            array(
                                                "sp_name"    => $result_arr[0],
                                                "vd_name"    => $result_arr[1],
                                                "drink_name" => $result_arr[2],
                                                "drink_path" => $result_arr[3],
                                                "sp_val"     => $result_arr[4],
                                                "drink_line" => $result_arr[5],
                                                "note"       => $note_all[$key]["note"],
                                                "sp_check"   => $result_arr[7]
                                            ));
                                        
                                    }
                                    
                                    //노트가 들어있는 경우 둘다 배열에 추가
                                    else{

                                        //배열에 값을 추가한다
                                        array_push($this->result_all,
                                            array(
                                                "sp_name"    => $result_arr[0],
                                                "vd_name"    => $result_arr[1],
                                                "drink_name" => $result_arr[2],
                                                "drink_path" => $result_arr[3],
                                                "sp_val"     => $result_arr[4],
                                                "drink_line" => $result_arr[5],
                                                "note"       => $note_all[$key]["note"]."\n".$result_arr[6],
                                                "sp_check"   => $result_arr[7]
                                            ));

                                    }
                                    
                                    //이전키 초기화
                                    $before_key = $key;
                                    
                                }

                                //자판기 노트에 값이 들어있지 않는경우 자판기 컬럼의 노트만 배열에 추가
                                else{

                                    //배열에 값을 추가한다
                                    array_push($this->result_all,
                                        array(
                                            "sp_name"    => $result_arr[0],
                                            "vd_name"    => $result_arr[1],
                                            "drink_name" => $result_arr[2],
                                            "drink_path" => $result_arr[3],
                                            "sp_val"     => $result_arr[4],
                                            "drink_line" => $result_arr[5],
                                            "note"       => $result_arr[6],
                                            "sp_check"   => $result_arr[7]
                                        ));

                                }
                                
                            }
                            
                            //배열에 아무값도 없을경우 평범하게 출력
                            else{

                                //배열에 값을 추가한다
                                array_push($this->result_all,
                                    array(
                                        "sp_name"    => $result_arr[0],
                                        "vd_name"    => $result_arr[1],
                                        "drink_name" => $result_arr[2],
                                        "drink_path" => $result_arr[3],
                                        "sp_val"     => $result_arr[4],
                                        "drink_line" => $result_arr[5],
                                        "note"       => $result_arr[6],
                                        "sp_check"   => $result_arr[7]
                                    ));

                            }

                        }
                    }
                }else
                    echo "mysql_err";

                //아직 보충하지 않은 자판기를 가져오는 쿼리 생성
                $query = "SELECT sp.sp_name, jc.vd_name, jc.drink_name, jc.drink_path, jc.sp_val, jc.drink_line, note, jc.sp_check, jc.vd_id
                           FROM ".JOB_COLUMN."   jc
                           JOIN ".JOB_ORDER."    jo ON jc.jo_id = jo.jo_id
                           JOIN ".SUPPLE_TABLE." sp ON jo.sp_id = sp.sp_id
                           WHERE sp.sp_login_id = '".$_GET['user_login_id']."'
                           AND date_format(jo.order_date, '%Y-%m-%d')='".$_GET['serch_date']."'
                           AND jc.sp_check = 0";

                //쿼리 실행
                if ($result =  $this->conn->query($query)) {

                    //글 갯수 $rows
                    $rows = mysqli_num_rows($result);

                    //검색 결과가 없고, 이전 검색 또한 결과가 없을 시(에러 출력)
                    if($rows < 1 && $before_check == false){
                        //최종 정상 반환 결과값
                        echo "no_marker";
                        return;
                    }

                    //검색 결과가 없고, 이전 검색이 있을 경우(이전값만 출력)
                    else if($rows < 1 && $before_check == true){
                        //최종 정상 반환 결과값
                        //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                        //result -> X : 내용물
                        echo json_encode(array("select"=>"get_order_sheet", "result"=>$this->result_all, "product_all"=>$this->product_all));
                        return;
                    }

                    //검색 결과가 하나라도 있을 시, 배열에 값을 저장하여 반환
                    else if ($rows > 0){

                        //이전 키 저장
                        $before_key = -1;

                        for ($i = 0; $i < $rows; $i++) {

                            //[0]=sp_name [1]=vd_name [2]=drink_name [3]=drink_path [4]=sp_val [5]=drink_line, [6]=note [7]=sp_check [8] = vd_id
                            $result_arr = mysqli_fetch_row($result);

                            //배열에 1개이상 들어가 있는 경우에만 실행
                            if(count($note_all) > 0) {

                                //배열 탐색(vd_id가 일치할 경우) -> 검색된 애의 배열위치 반환
                                $key = array_search($result_arr[8], array_column($note_all, "vd_id"));

                                //검색한 결과값이 배열에 저장되어 있는 경우(배열위치가 INT로 저장되어있음) 다음 조건문 실행
                                if($key !==false && $before_key != $key){

                                    //노트가 들어있지 않은경우 자판기 노트만 출력
                                    if(strcasecmp("null",$result_arr[6]) == 0 || $result_arr[6] == null){

                                        //배열에 값을 추가
                                        array_push($this->result_all,
                                            array(
                                                "sp_name"    => $result_arr[0],
                                                "vd_name"    => $result_arr[1],
                                                "drink_name" => $result_arr[2],
                                                "drink_path" => $result_arr[3],
                                                "sp_val"     => $result_arr[4],
                                                "drink_line" => $result_arr[5],
                                                "note"       => $note_all[$key]["note"],
                                                "sp_check"   => $result_arr[7]
                                            ));

                                    }

                                    //노트가 들어있는 경우 둘다 추가
                                    else{

                                        //배열에 값을 추가
                                        array_push($this->result_all,
                                            array(
                                                "sp_name"    => $result_arr[0],
                                                "vd_name"    => $result_arr[1],
                                                "drink_name" => $result_arr[2],
                                                "drink_path" => $result_arr[3],
                                                "sp_val"     => $result_arr[4],
                                                "drink_line" => $result_arr[5],
                                                "note"       => $note_all[$key]["note"]."\n".$result_arr[6],
                                                "sp_check"   => $result_arr[7]
                                            ));

                                    }

                                    //이전키 초기화
                                    $before_key = $key;

                                }

                                //자판기 노트에 값이 들어있지 않는경우 자판기 컬럼의 노트만 배열에 추가
                                else{

                                    //배열에 값을 추가
                                    array_push($this->result_all,
                                        array(
                                            "sp_name"    => $result_arr[0],
                                            "vd_name"    => $result_arr[1],
                                            "drink_name" => $result_arr[2],
                                            "drink_path" => $result_arr[3],
                                            "sp_val"     => $result_arr[4],
                                            "drink_line" => $result_arr[5],
                                            "note"       => $result_arr[6],
                                            "sp_check"   => $result_arr[7]
                                        ));

                                }

                            }

                            //배열에 아무값도 없을경우 평범하게 출력
                            else{

                                //배열에 값을 추가
                                array_push($this->result_all,
                                    array(
                                        "sp_name"    => $result_arr[0],
                                        "vd_name"    => $result_arr[1],
                                        "drink_name" => $result_arr[2],
                                        "drink_path" => $result_arr[3],
                                        "sp_val"     => $result_arr[4],
                                        "drink_line" => $result_arr[5],
                                        "note"       => $result_arr[6],
                                        "sp_check"   => $result_arr[7]
                                    ));

                            }

                        }

                        //최종 정상 반환 결과값
                        //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                        //result -> X : 내용물
                        echo json_encode(array("select"=>"get_order_sheet", "result"=>$this->result_all, "product_all"=>$this->product_all));
                        return;

                    }

                }

                //쿼리 비정상 실행시 에러 반환
                else {
                    echo "mysql_err";
                }

            }

            //GET값이 비어있다면 에럽러반환
            else{
                echo "no_marker";
            }

        }

        //GET값을 받아오지 못했다면 에러 반환
        else{
            echo "no_marker";
        }

    }

    //자판기 강제갱신 vendingmachine, vd_stock, jo_column, car_stock update set 후
    //select jo_column 으로 자판기들 반환
    function insert_vending(){

        //GET값을 올바르게 받아왔다면 다음 조건문 실행
        if (isset($_GET['vending_id']) && isset($_GET['user_login_id'])) {

            //받아온 값이 빈 값이 아니라면 쿼리문 생성
            if (strcmp($_GET['vending_id'], "") && strcmp($_GET['user_login_id'], "")) {

                //vendingmachine update 쿼리 생성 및 실행
                $query = "UPDATE ".VENDING_TABLE." SET  vd_soldout = 0 WHERE vd_id = ".$_GET['vending_id'];
                if ($result = $this->conn->query($query)) {}
                //vd_stock update

                //vd stock update 쿼리 생성 및 실행
                $query = "UPDATE ".VD_STOCK." SET stock = 35 WHERE vd_id = ".$_GET['vending_id'];
                if ($result = $this->conn->query($query)) {}

                //jo_column update
                //$query = "UPDATE ".JOB_COLUMN." SET  sp_val = 0 WHERE vd_id = ".$_GET['vending_id'];
                //if ($result = $this->conn->query($query)) {}
                //jo_column note update
                //$query = "UPDATE ".JOB_COLUMN." SET  note = null WHERE vd_id = ".$_GET['vending_id'];
                //if ($result = $this->conn->query($query)) {}
                //jo_column sp_check update

                //Jo_column Update 쿼리 생성 및 실행
                $query = "UPDATE ".JOB_COLUMN." SET  sp_check = 1 WHERE vd_id = ".$_GET['vending_id'];
                if ($result = $this->conn->query($query)) {}

                //jo_colmn 재고량 구하는 쿼리 생성
                $query = "SELECT drink_name, sp_val 
                           FROM ".JOB_COLUMN." 
                           WHERE sp_login_id ='".$_GET['user_login_id']."'  
                           AND vd_id         = ".$_GET['vending_id'];

                //쿼리 실행
                if ($result =  $this->conn->query($query)) {

                    //글 갯수 $rows
                    $rows = mysqli_num_rows($result);

                    //검색 결과가 없을 때 에러 반환
                    if($rows < 1){
                        echo "no_marker";
                        return;
                    }

                    //검색 결과가 하나라도 있을 시, 배열에 값을 저장하여 반환
                    else{

                        for ($i = 0; $i < $rows; $i++) {

                            //[0]=drink_name [1]=sp_val
                            $result_arr = mysqli_fetch_row($result);

                            //배열에 값을 추가
                            array_push($this->jo_colmn_arr,
                                array(
                                    "drink_name" => $result_arr[0],
                                    "sp_val"     => $result_arr[1]
                                ));

                        }

                    }

                }else
                    echo "mysql_err";

                //car_stock 재고량 구하는 쿼리 생성
                $query = "SELECT pi.drink_name, cs.stock 
                           FROM ".CAR_STOCK."    cs 
                           JOIN ".STOCK_MANAGE." sm ON sm.stock_id = cs.stock_id
                           JOIN ".PRODUCT_INFO." pi ON pi.drink_id = sm.drink_id
                           WHERE cs.sp_login_id='".$_GET['user_login_id']."'";

                //쿼리 실행
                if ($result =  $this->conn->query($query)) {

                    //글 갯수 $rows
                    $rows = mysqli_num_rows($result);

                    //검색 결과가 없을 때 에러 반환
                    if($rows < 1){
                        echo "no_marker";
                        return;
                    }

                    //검색 결과가 하나라도 있을 때 배열에 값을 저장하여 반환
                    else{

                        for ($i = 0; $i < $rows; $i++) {

                            //[0]=drink_name [1]=sp_val
                            $result_arr = mysqli_fetch_row($result);

                            //배열에 값을 저장
                            array_push($this->car_stock_arr,
                                array(
                                    "drink_name" => $result_arr[0],
                                    "sp_val"     => $result_arr[1]
                                ));

                        }

                    }

                }else
                    echo "mysql_err";

                //연산부분 -> 수량만큼 빼서 최신화한다
                for($i=0; $i<count($this->car_stock_arr); $i++){

                    for($j=0; $j<count($this->jo_colmn_arr); $j++){

                        if( $this->car_stock_arr[$i]["drink_name"] == $this->jo_colmn_arr[$j]["drink_name"]) {
                            $this->car_stock_arr[$i]["sp_val"] -= $this->jo_colmn_arr[$j]["sp_val"];
                            break;
                        }

                    }

                }

                //car_stock update 0부터 차례대로 업데이트
                for($i=0; $i<count($this->car_stock_arr); $i++){

                    //쿼리 생성
                    $query = "UPDATE   ".CAR_STOCK."  
                    SET  stock      =  ".$this->car_stock_arr[$i]["sp_val"]." 
                    WHERE stock_id  =  ".($i+1)."
                    AND sp_login_id = '".$_GET['user_login_id']."'";

                    //쿼리 실행
                    if ($result = $this->conn->query($query)) {}

                }

                return;
            }

        }

    }

    //토큰 테이블(user_info_id, name)을 업데이트 하는 함수
    function token(){

        //GET값을 제대로 받아왔다면 쿼리 생성
        if(isset($_GET['user_info_id']) && isset($_GET['name']) && isset($_GET['token'])) {

            //user_info_id 업데이트 쿼리 생성 및 실행
            $query = "UPDATE " . TOKEN_INFO . " 
                      SET  user_info_id = '" . $_GET['user_info_id']."' 
                      WHERE token       = '" . $_GET['token']."'";
            if ($result = $this->conn->query($query)) {}

            //name 업데이트 쿼리 생성 및 실행
            $query = "UPDATE " . TOKEN_INFO . " 
                      SET  name   = '" . $_GET['name']."' 
                      WHERE token = '" . $_GET['token']."'";
            if ($result = $this->conn->query($query)) {}

        }

    }

    //달력에서 현재 로그인된 보충기사를 기준으로 작업지시서(날짜)들을 가져온다
    function calendar_get_Day(){

        //GET값을 제대로 받아왔다면 쿼리 생성
        if(isset($_GET['user_info_id'])){

            //쿼리 생성
            $query = "SELECT jo.order_date
                      FROM job_order jo
                      JOIN supplementer sm ON jo.sp_id = sm.sp_id
                      WHERE sm.sp_login_id = '".$_GET['user_info_id']."'";

            //쿼리 실행
            if($result = $this->conn->query($query)){

                //글 갯수 $rows
                $rows = mysqli_num_rows($result);

                //검색 결과가 없을 때 에러 반환
                if($rows < 1){
                    echo "no_date";
                    return;
                }

                //갯수만큼 반복
                for ($i = 0; $i < $rows; $i++) {

                    //[0]=order_date
                    $result_arr = mysqli_fetch_row($result);

                    //공백을 기준으로 배열생성하여 저장
                    $date_change = explode(" ", $result_arr[0]);

                    //배열에 값을 추가
                    array_push($this->result_all,
                        array(
                            "order_date" => $date_change[0],
                        ));

                }

                //최종 정상 반환 결과값
                //select -> X : 첫번째 get인자값에 맞춰 구분자를 넣어준다
                //result -> X : 내용물
                echo json_encode(array("select"=>"calendar_get_Day", "result"=>$this->result_all));
            }
        }
    }
}
$test = new test();
$test->controller();
?>