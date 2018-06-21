<template>
  <div>
    <br>
    <h3>Management</h3>
    <div>
      <v-btn id="spBtn" class="spBtnStyle" outline color="indigo" @click="contentPageChange('보충기사')"><h6>보충기사 조회</h6></v-btn>
      <v-btn id="vdBtn" class="spBtnStyle" outline color="grey lighten-1" @click="contentPageChange('자판기')"><h6>자판기 조회</h6></v-btn>
    </div>
    <div v-if="pageName == '보충기사'">
      <div class="spListDivBackground">
        <div v-for="sp in spArray" :key="sp.No">
          <div v-if="sp.No == 1" class="spListDiv" @click="spInfor(sp.Id, sp.sp_id, sp.Name, false)" :id="sp.sp_id" style="background-color: rgb(0, 183, 238);">
            <div>
              <img :src="sp.Pic" id="spImage" class="imgRodius">
            </div>
            <div style="margin: 15px;">
              <h4 :name="sp.Name" style="color: white">{{sp.Name}}</h4>
              <h6 :name="sp.Name" style="color: white">Supplement</h6>
            </div>
          </div>
          <div v-else class="spListDiv" @click="spInfor(sp.Id, sp.sp_id, sp.Name, false)" :id="sp.sp_id">
            <div>
              <img :src="sp.Pic" id="spImage" class="imgRodius">
            </div>
            <div style="margin: 15px;">
              <h4 :name="sp.Name" style="color: skyblue">{{sp.Name}}</h4>
              <h6 :name="sp.Name" style="color: skyblue">Supplement</h6>
            </div>
          </div>
        </div>
        <div class="spInsertBtn" v-b-modal.spInsertModal>
          <h1 style="color: skyblue">+</h1>
          <h5 style="color: skyblue">새로운 보충기사 등록하기</h5>
        </div>
        <div class="spUpdateAndspRemoveBtn">
          <v-btn v-if="clickedSpTrId == ''" class="spBtnStyle" @click="spUploadFunc(true)"><h6 style="color: #1565C0;">보충기사 수정</h6></v-btn>
          <v-btn v-else class="spBtnStyle" v-b-modal.spUploadModal @click="spUploadFunc(true)"><h6 style="color: #1565C0;">보충기사 수정</h6></v-btn>
          <v-btn color="error" class="spBtnStyle" @click="spRemoveFunc"><h6>보충기사 삭제</h6></v-btn>
        </div>
      </div>
      <b-modal id="spInsertModal" hide-footer ref="spInsertRef" title="보충기사 등록">
        <div>
          <img id="insertBlah" src="http://placehold.it/180" alt="your image" style="width: 180px; height: 180px;">
          <input type="file" accept=".png" id="insertFile" @change="readURL('insertFile')">
          <b-form-input type="text" placeholder="Enter your name" id="spInputName"></b-form-input>
          <b-form-input type="text" placeholder="Enter your id" id="spInputId"></b-form-input>
          <b-form-input type="text" placeholder="Enter your password" id="spInputPasswd"></b-form-input>
          <b-form-input type="text" placeholder="Enter your mail adress" id="spInputMail"></b-form-input>
          <b-form-input type="text" placeholder="Enter your phoneNumber" id="spInputPhone"></b-form-input>
          <b-form-input type="text" placeholder="Enter your address" id="spInputAddress"></b-form-input>
        </div>
        <br>
        <div>
          <b-btn @click="spInsertFunc">등록</b-btn>
          <b-btn @click="$refs.spInsertRef.hide()">취소</b-btn>
        </div>
      </b-modal>

      <b-modal id="spUploadModal" hide-footer ref="spUploadRef" title="보충기사 수정">
        <div>
          <img id="uploadBlah" src="http://placehold.it/180" alt="your image" style="width: 180px; height: 180px;">
          <input type="file" accept=".png" id="uploadFile" @change="readURL('uploadFile')">
          <b-form-input type="text" :value="spInputNameChange" id="spInputNameChange"></b-form-input>
          <b-form-input type="text" :value="spInputIdChange" id="spInputIdChange"></b-form-input>
          <b-form-input type="text" :value="spInputPasswdChange" id="spInputPasswdChange"></b-form-input>
          <b-form-input type="text" :value="spInputMailChange" id="spInputMailChange"></b-form-input>
          <b-form-input type="text" :value="spInputPhoneChange" id="spInputPhoneChange"></b-form-input>
          <b-form-input type="text" :value="spInputAddressChange" id="spInputAddressChange"></b-form-input>
        </div>
        <br>
        <div>
          <b-btn @click="spUploadFunc(false)">수정</b-btn>
          <b-btn @click="$refs.spUploadRef.hide()">취소</b-btn>
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
import { EventBus } from '../../app.js';
import draggable from 'vuedraggable';

//  -----자판기 조회-----
let response;
let obj = []; 
let obj_c=[]; 
let array = [];
let array_c =[];
let array_a =[];
let obj_A=[];
let selectLine;
let selcetDrink;
let selectDrinkId;

let click_lat;
let click_lng;
//  -----자판기 조회-----

export default {
  data() {
    return {
      //보충기사와 자판기 조회 버튼을 위한 변수
      spOrVdCard : { 0 : '보충기사', 1 : '자판기'},
      active: null,

      //보충 기사 정보를 담기 위한 배열
      spArray: [],
      
      selected:[],
      itemList_All:[], /* 음료리스트 담는 배열 */
      search: '',
      dialog: false,
      //<------------ 자판기 리스트 테이블 헤더부분 담기위한 배열 -------------->
      headers: [
          {
            text: 'No',
            align: 'left',
            sortable: true,
            value: 'No'
          },
          { text: '자판기 이름',sortable: true, value: 'Ven_Name' },
          { text: '위치', value: 'Location' },
          { text: '관리자',sortable: true, value: 'Manager' }
        ],
       options: [  { value: null, text: '8개' }],
      vendingName:"",
      items:[],
      select: [  /* 모달창 선택지 */
        ],
      editedIndex: -1,
      editedItem: {
        No: '',
        Ven_Name: '',
        Location: '',
        Manager: ''
      },
      defaultItem: {
        No: '',
        Ven_Name: '',
        Location: '',
        Manager: ''
      },
    
      editedItem_drink: {  /* 수정시 기존 라인당 음료 리스트  */
        Line1 : null,
        Line2 : null,
        Line3 : null,
        Line4 : null,
        Line5 : null,
        Line6 : null,
        Line7 : null,
        Line8 : null
      },
      defaultItem_drink: {
        Line1 : null,
        Line2 : null,
        Line3 : null,
        Line4 : null,
        Line5 : null,
        Line6 : null,
        Line7 : null,
        Line8 : null
      },


      clickedTr:'',
      clickTr:'',
      tempMarker:{lat:'',lng:''},
      /* <--------------------- VendingMachine Google map --------------------> */
      center: {
        lat: 36.323836,
        lng: 127.398077 
      }, 
      markers: [],
      marker_a : null,
      places: [],
      currentPlace: null,
      zoom:parseFloat(6.8),
      targetItem:'',
      
      draggable:true,

      /* empty drink itme list */
      InputDrinkItem: {
                    Line1: [],
                    Line2: [],
                    Line3: [],
                    Line4: [],
                    Line5: [],
                    Line6: [],
                    Line7: [],
                    Line8: []
       },
      selctTempItem:[],
      /* send drink item list */
      drink_Arr:{
        Line1 : null,
        Line2 : null,
        Line3 : null,
        Line4 : null,
        Line5 : null,
        Line6 : null,
        Line7 : null,
        Line8 : null,
      },
      TrueOrFalse             : false,      // 실시간 페이지에서 넘어왔는지 유무 확인
      clickedSpTrId           : "",         // 클릭한 보충기사 태그 아이디          
      spInputNameChange       : "",         // 보충기사 수정 모달창 이름 값
      spInputIdChange         : "",         // 보충기사 수정 모달창 아이디 값
      spInputPasswdChange     : "",         // 보충기사 수정 모달창 비밀번호 값
      spInputMailChange       : "",         // 보충기사 수정 모달창 이메일 값
      spInputPhoneChange      : "",         // 보충기사 수정 모달창 휴대전화번호 값
      spInputAddressChange    : "",         // 보충기사 수정 모달창 주소 값
      originPhoneNum          : "",         // 수정 전 휴대전화번호 값
      pageName                : "보충기사",  // 현재 페이지 이름
      clickedTrName           : "",         // 클릭한 DIV안에 있는 태그의 이름
      original_sp_login_id    : "",         // 수정 전 보충기사 아이디
    }
  },
  components: {
    draggable
  },
  mounted(){
    var link = document.location.href;
    var queryID=this.$route.query.id;
    if(link=="http://localhost:8000/management?id="+queryID){
      this.specific();
    };
    // 보충 기사 정보를 화면에 바로 보이게 하기 위해 mouted에서 메서드 바로 호출
    this.spList();
    this.geolocate();
    this.DrinkList();

    this.spInfor("hajae", "Ha Jaehyeong1", "Ha Jaehyeong", true);
  }, 
  //<------------------ search filtering ----------------------->
  computed : {
      formTitle () {
        return this.editedIndex === -1 ? '새 자판기 등록' : '자판기 정보 수정'
      },
      formSubTitle(){ 
        return this.editedIndex === -1 ? '자판기 음료 등록' : '자판기 음료 수정'
      }
  },
  watch: {
      dialog (val) {
        val || this.close()
      }
    },
  methods: {
    readURL(inputAndUpload) {
      var fileTag = document.getElementById(inputAndUpload);

      if (fileTag.files && fileTag.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            if (inputAndUpload == 'insertFile') {
              document.getElementById('insertBlah').setAttribute('src', e.target.result);
            }
            else {
              document.getElementById('uploadBlah').setAttribute('src', e.target.result);
            }
        };

        reader.readAsDataURL(fileTag.files[0]);
      }
    },
    // 모달창에 보충기사 이미지 미리보기

    spInsertFunc() {
        var saveSpName = document.getElementById("spInputName").value;
        var saveSpId = document.getElementById("spInputId").value;
        var saveSpPasswd = document.getElementById("spInputPasswd").value;
        var saveSpMail = document.getElementById("spInputMail").value;
        var saveSpPhone = document.getElementById("spInputPhone").value;
        var saveSpAddress = document.getElementById("spInputAddress").value;
        var saveImage = document.getElementById("insertFile").value;
        // 현재 모달창에 입력된 값

        if (saveSpName == "") {
          alert("이름을 입력해주십시오.");
        }
        else if (saveSpId == "") {
          alert("아이디를 입력해주십시오.");
        }
        else if (saveSpPasswd == "") {
          alert("비밀번호를 입력해주십시오.");
        }
        else if (saveSpMail == "") {
          alert("메일을 입력해주십시오.");
        }
        else if (saveSpPhone == "") {
          alert("휴대전화번호를 입력해주십시오.");
        }
        else if (saveSpAddress == "") {
          alert("주소를 입력해주십시오.");
        }
        else if (saveImage == "") {
          alert("이미지를 넣어주세요");
        }
        else {
          // 값 유무 확인

          const fileInput = document.getElementById("insertFile");
          const formData = new FormData();
          formData.append('sp_login_id', saveSpId);
          formData.append('sp_password', saveSpPasswd);
          formData.append('sp_name', saveSpName);
          formData.append('sp_mail', saveSpMail);
          formData.append('sp_phone', saveSpPhone);
          formData.append('sp_address', saveSpAddress);
          formData.append('sp_address', saveSpAddress);
          formData.append('imageFile', fileInput.files[0]);
          // 전송할 데이터

          this.axios.post("management/addSP", formData)
          .then((response) => {
            if(response.data == "good") {
              alert("등록되었습니다.");
              this.$refs.spInsertRef.hide();
              this.spList();
              // 새로고침
            }
            else {
              alert("등록에 실패하였습니다.11");
            }
          })
          .then((error) => {
            console.log(error);
            alert("등록에 실패하였습니다.22");
          })
        }
    },
    // 보충기사 등록하기

    spUploadFunc(openAndUpload) {
      if (openAndUpload == true) {
        if (this.clickedSpTrId == "") {
          // 보충기사를 선택하지 않았을 경우

          alert("수정할 보충기사를 선택하십시오.");
        }
        else{
          let getSPUrl = 'management/getSP';    // 보충기사 정보 url

          this.axios.get(getSPUrl)
          .then((response) => {
            console.log(response.data);
            console.log(this.clickedSpTrId);
            console.log(this.clickedSpTrId[this.clickedSpTrId.length-1]);

            for (var i = 0; i < response.data.length; i++) {
              if (this.clickedSpTrId == response.data[i].sp_name + response.data[i].sp_id) {
                // 해당 보충기사 찾기
                
                var showImage = document.getElementById("uploadBlah");

                this.spInputNameChange = response.data[i].sp_name;
                this.spInputIdChange = response.data[i].sp_login_id;
                this.original_sp_login_id = response.data[i].sp_login_id;
                this.spInputPasswdChange = response.data[i].sp_password;
                this.spInputMailChange = response.data[i].sp_mail;
                this.spInputPhoneChange = response.data[i].sp_phone;
                this.originPhoneNum = response.data[i].sp_phone;
                this.spInputAddressChange = response.data[i].sp_address;
                showImage.src = response.data[i].sp_img_path;
                // 수정 모달창에 보충기사 정보 입력

                break;
              }
            }
          })
          .catch((error) => {
            console.log(error.response);
            alert("보충기사 정보찾기에 실패하였습니다.");
          })
        }
      }
      else {
        this.spInputNameChange = document.getElementById('spInputNameChange').value;
        this.spInputIdChange = document.getElementById('spInputIdChange').value;
        this.spInputPasswdChange = document.getElementById('spInputPasswdChange').value;
        this.spInputMailChange = document.getElementById('spInputMailChange').value;
        this.spInputPhoneChange = document.getElementById('spInputPhoneChange').value;
        this.spInputAddressChange = document.getElementById('spInputAddressChange').value;
        // 현재 모달창에 입력된 값

        if (this.spInputNameChange == "") {
          alert("이름을 입력해주십시오.");
        }
        else if (this.spInputIdChange == "") {
          alert("아이디를 입력해주십시오.");
        }
        else if (this.spInputPasswdChange == "") {
          alert("비밀번호를 입력해주십시오.");
        }
        else if (this.spInputMailChange == "") {
          alert("메일을 입력해주십시오.");
        }
        else if (this.spInputPhoneChange == "") {
          alert("휴대전화번호를 입력해주십시오.");
        }
        else if (this.spInputAddressChange == "") {
          alert("주소를 입력해주십시오.");
        }
        else {
          // 값 유무 확인

          const formData = new FormData();
          formData.append('sp_id', this.clickedSpTrId[this.clickedSpTrId.length-1]);
          formData.append('original_sp_login_id', this.original_sp_login_id);
          formData.append('sp_login_id', this.spInputIdChange);
          formData.append('sp_password', this.spInputPasswdChange);
          formData.append('sp_name', this.spInputNameChange);
          formData.append('sp_mail', this.spInputMailChange);
          formData.append('sp_phone', this.spInputPhoneChange);
          formData.append('sp_address', this.spInputAddressChange);
          // 전송할 데이터

          if (document.getElementById("uploadFile").value != "") {
            const fileInput = document.getElementById("uploadFile");
            formData.append('imageFile', fileInput.files[0]);
            formData.append('is_file', "ok");
          }
          else {
            formData.append('is_file', "no");
          }
          // 이미지 파일 변경 유무 확인

          this.axios.post("management/updateSP", formData)
          .then((response) => {
            console.log(response.data);

            if(response.data == "good") {
              alert("수정되었습니다.");
              this.$refs.spUploadRef.hide()
              this.spList();
            }
            else {
              alert("수정에 실패하였습니다.11");
            }
          })
          .catch((error) => {
            console.log(error);
            alert("수정에 실패하였습니다.22");
          })
        }
      }
    },
    // 보충기사 정보 수정하기

    spRemoveFunc() {
      if (this.clickedSpTrId == "") {
        // 보충기사를 선택하지 않았을 경우

        alert("삭제할 보충기사를 선택하십시오.");
      }
      else{
        var removeCheck = confirm("해당 보충기사를 정말로 삭제하시겠습니까?");
        // 삭제할 것인지 다시 확인

        if (removeCheck == true) {
          // 삭제 진행 시

          let spRemoveUrl = 'management/deleteSP/' + this.clickedSpTrId[this.clickedSpTrId.length-1];    // 보충기사 삭제 url

          this.axios.get(spRemoveUrl)
          .then((response) => {
            if (response.data == "good") {
              alert("삭제되었습니다.");
              this.clickedSpTrId = "";
              this.spList();
              // 새로고침
            }
            else {
              alert("담당하고 있는 자판기가 아직 있습니다.");
            }
          })
          .catch((error) => {
            console.log(error.response);
            alert("보충기사 삭제에 실패하였습니다.");
          })
        }
      }
    },
    // 보충기사 수정 모달창에 정보입력 및 보충기사 삭제

    //서버 DB에서 보충기사 정보를 가지고 온다.
    spList(){
      this.axios.get("realtime/list/all/all").then((response) =>{
        response = response.data;
        for(let key in response){
          array_c[key] = {
          name : response[key].vd_name}
          obj_c.push({
            name:array_c[key].name})
        }
      });

      let url = 'management/getSP';
      this.axios.get(url).then((response) =>{
        this.spArray = [];

        //보충 기사 3명의 정보를 배열에 담는다.
        for(let i = 0 ; i < response.data.length; i++){

          var spObj = {
            No: i + 1,
            Pic: response.data[i]['sp_img_path'],
            Name: response.data[i]['sp_name'],
            Id: response.data[i]['sp_login_id'],
            sp_id: response.data[i]['sp_name'] + response.data[i]['sp_id']
          };

          this.spArray.push(spObj);
          this.select.push({
            text:response.data[i]['sp_login_id']});
          
          //console.log(this.select);
        }
      });
      //보충기사 관리를 누를 시, 이벤트를 발생시켜 상위 컴포넌트인 백그라운드로 가서 메서드 실행.
      //this.$emit('vending-list');
    },
    
    vendingList :function(){
      this.axios.get("realtime/list/all/all").then((response) =>{
        response = response.data;
        if(obj==[]){ 
           for(let key in response){
         array[key] = {
            id : response[key].vd_id,
            name : response[key].vd_name,
            address : response[key].vd_address,
            supplementer : response[key].vd_supplementer, 
          }
          obj.push({
            No:array[key].id,
            isActive: true,
            Ven_Name:array[key].name,
            Location:array[key].address,
            Manager:array[key].supplementer
          }); 
           EventBus.$emit('vending_name', response); 
        }}
        else{
           obj.splice(0,);
           obj=[];
        for(let key in response){
         array[key] = {
            id : response[key].vd_id,
            name : response[key].vd_name,
            address : response[key].vd_address,
            supplementer : response[key].vd_supplementer, 
          }
          obj.push({
            No:array[key].id,
            isActive: true,
            Ven_Name:array[key].name,
            Location:array[key].address,
            Manager:array[key].supplementer
          }); 
        }
        }       
        this.items = obj;
         EventBus.$emit('vending_name', response); 
        this.$emit('vending-list');                     
      })
    },

    spInfor(spId, realSp_id, spName, firstInput){
      if (this.clickedSpTrId == "") {
        if (firstInput == true) {
          this.clickedSpTrId = realSp_id;
          this.clickedTrName = spName;
        }
        else {
          var clickSpTr = document.getElementById(realSp_id);
          clickSpTr.style.backgroundColor = "rgb(0, 183, 238)";
          this.clickedSpTrId = realSp_id;
          this.clickedTrName = spName;

          for (var i = 0; i < document.getElementsByName(spName).length; i++) {
            document.getElementsByName(spName)[i].style.color = "white";
          }
        }
      }
      else {
        for (var i = 0; i < document.getElementsByName(this.clickedTrName).length; i++) {
          document.getElementsByName(this.clickedTrName)[i].style.color = "skyblue";
        }

        document.getElementById(this.clickedSpTrId).style.backgroundColor = "white";
        var clickSpTr = document.getElementById(realSp_id);
        clickSpTr.style.backgroundColor = "rgb(0, 183, 238)";
        this.clickedSpTrId = realSp_id;
        this.clickedTrName = spName;

        for (var i = 0; i < document.getElementsByName(spName).length; i++) {
          document.getElementsByName(spName)[i].style.color = "white";
        }
      }
      
      EventBus.$emit('jobOrder', spId); 
    },


    contentPageChange(page){
      if (page == "보충기사") {
        document.getElementById('spBtn').setAttribute('class', "spBtnStyle btn btn--outline btn--depressed indigo--text");
        document.getElementById('vdBtn').setAttribute('class', "spBtnStyle btn btn--outline btn--depressed grey--text text--lighten-1");
      }
      else {
        document.getElementById('spBtn').setAttribute('class', "spBtnStyle btn btn--outline btn--depressed grey--text text--lighten-1");
        document.getElementById('vdBtn').setAttribute('class', "spBtnStyle btn btn--outline btn--depressed indigo--text");
      }

      EventBus.$emit('contentPageChange', page); 
      this.vendingList();
    },

       // <------------ Stock EventBus --------------> 
     routerLinkToDetails_s(items,arg1,arg2) {

       EventBus.$emit('vendingInfo',arg1,arg2,items); 
        this.axios.get("realtime/getVdStock/"+items)
        .then(function (response) {
          
             response=response.data;
             EventBus.$emit('StockEventBus',response); 
        })  
    },
         // <------------ Coin EventBus --------------> 
     routerLinkToDetails_c(items) {
        this.axios.get("realtime/coinStock/"+items)
        .then(function (response) {
             response=response.data;
             EventBus.$emit('CoinEventBus',response); 
             
        })  
    },

/* <---------------- editItem Methods --------------> */  
      editItem (item) {
        this.editedIndex = this.items.indexOf(item)
        this.editedItem = Object.assign({}, item)
        
        //기존 자판기의 음료 목록 불러오기 
        this.axios.get("management/getVdStock/"+this.editedItem.No).then((response) =>{
        response = response.data;
        //console.log(response);
        var obj_drink=[];
        for(let key in response){
          array[key] = {
          drinkPath : response[key].drink_img_path
          }
        } 
        obj_drink.push({
            Line1:array[0].drinkPath,
            Line2:array[1].drinkPath,
            Line3:array[2].drinkPath,
            Line4:array[3].drinkPath,
            Line5:array[4].drinkPath,
            Line6:array[5].drinkPath,
            Line7:array[6].drinkPath,
            Line8:array[7].drinkPath
            }) ,


             this.editedItem_drink= Object.assign({}, obj_drink)
        });
        
      

        this.dialog = true      
        
      },
     /* <---------------- deleteItem Methods --------------> */    
      deleteItem (item) {
        const index = this.items.indexOf(item)
        confirm('정말로 자판기 삭제하시겠습니까?') && this.items.splice(index, 1)
        /* <------------ delete db값 변경 -----------> */
        const formData_d = new FormData();
        formData_d.append('ven_id',item.No);
        this.axios.post("management/deleteVD",formData_d).then((Response)=>{
        })  
      },
     /* <---------------- close Methods --------------> */      
       close () {
        this.dialog = false
        this.markers.splice(0,),
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          //this.InputDrinkItem = Object.assign({}, this.defaultItem_drink)
          this.editedIndex = -1
        }, 300)       
        
      },
      /* <---------------- save Methods --------------> */     
      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.items[this.editedIndex], this.editedItem)
          /* <------------update db값 변경 -----------> */
         const formData_i = new FormData();
        formData_i.append('ven_name',this.editedItem.Ven_Name);
        formData_i.append('ven_no',this.editedItem.No);
        formData_i.append('ven_Location',this.editedItem.Location);
        formData_i.append('ven_Manager',this.editedItem.Manager);
        let drink_Arr = this.drink_Arr;
        formData_i.append('drink_line', JSON.stringify(drink_Arr));
       
         this.axios.post("management/updateVD", formData_i).then((Response)=>{
            //console.log(Response.data);
        }).catch(ex=>{
          console.log(ex);
        })  
        } else {
          this.items.push(this.editedItem)
            /* <------------insert db값 변경 -----------> */
         const formData_u = new FormData();
        formData_u.append('ven_name',this.editedItem.Ven_Name);
        formData_u.append('ven_Location',this.editedItem.Location);
        formData_u.append('ven_Manager',this.editedItem.Manager);
        let drink_Arr = this.drink_Arr;
        formData_u.append('drink_line', JSON.stringify(drink_Arr));
        formData_u.append('clickLat',click_lat);
        formData_u.append('clickLng',click_lng);
        
       
         this.axios.post("management/registrationVD", formData_u).then((Response)=>{
          //console.log(Response.data);
        }).catch(ex=>{
          console.log(ex);
        }) 
        } 
                                          
        this.close()
      },
      /* <---------------- Mouse Over Methods --------------> */
      mouseover: function(overTr) {
                if (document.getElementById(overTr)!= this.clickedTr) {
                    document.getElementById(overTr).style.backgroundColor = 'rgba(88, 88, 88, 0.4)';
                }
            },
       /* <---------------- Mouse Out Methods  --------------> */
      mouseout: function(outTr) {
                if (document.getElementById(outTr) != this.clickedTr) {
                    
                    document.getElementById(outTr).style.backgroundColor = '#f6f9fa';
                }
            },
      /* <----------------  trClick Methods  -----------------> */
      trClick: function(trName) {
                this.clickTr = document.getElementById(trName);
                if (this.clickTr.style.backgroundColor == "#5f95a7") {
                    this.clickTr.style.backgroundColor = "#f6f9fa";
                }
                else {
                    if (this.clickedTr != "") {
                        this.clickedTr.style.backgroundColor = "#f6f9fa";
                    }  
                    this.clickedTr = this.clickTr;
                    this.clickTr.style.backgroundColor = "#5f95a7";
                }
    },

    /* <------------------- google map Methods ----------------------> */  
    setPlace(place) {
      this.currentPlace = place;
    },
    addMarker() {
      if (this.currentPlace) {
        const marker = {
          lat: this.currentPlace.geometry.location.lat(),
          lng: this.currentPlace.geometry.location.lng()
        };
       this.markers.push({ position: marker });
        
        var obj_marker=[];

        obj_marker.push({position: marker});
        
         this.zoom = parseInt(15); 
        click_lat=obj_marker[0].position.lat; //click한 좌표의 위도
        click_lng=obj_marker[0].position.lng; //click한 좌표의 경도
        
        this.tempMarker= obj_marker
        this.places.push(this.currentPlace);
        this.center = marker;
        this.currentPlace = null;
      }
    },
    /* <----------------- Default 좌표값 메서드 --------------> */
    geolocate: function(position) {
      navigator.geolocation.getCurrentPosition(position => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
      });
    },

    DrinkList:function(){

        this.axios.get("management/getProductInfo").then((response) =>{
        response = response.data;
        if(obj_A==[]){
        for(let key in response){
          array[key] = {
          id : response[key].drink_id,  
          name : response[key].drink_name,
          path: response[key].drink_img_path}
          obj_A.push({
           id:array[key].id,
           name:array[key].name,
           path:array[key].path
           
           })
        }}else{
           obj_A.splice(0,);
           for(let key in response){
          array[key] = {
          id : response[key].drink_id,  
          name : response[key].drink_name,
          path: response[key].drink_img_path}
          obj_A.push({
           id:array[key].id,
           name:array[key].name,
           path:array[key].path
           
           })
        }
        }
         this.itemList_All = obj_A;
        });
    },


//<--------------- Give an id on select One Drink method ------------------>
    chooseItem: function (event) {                   
                   selcetDrink = event.from.id;
                   var id =  event.from.id;
                   var number = parseInt(id);
                   selectDrinkId= number;

                   this.selctTempItem=this.itemList_All[number].path;
    },
    capLetter: function (input) {
                return input.charAt(0).toUpperCase() + input.slice(1);
    },
    //<--------------- Give an id on the left empty line method ------------------>
    newLine: function (event) {
                selectLine= event.to.id;
                this.lineInDrink();
    },
     //<---------------------- Setting Line and Drink method ---------------------->
    lineInDrink:function(){
      if(this.InputDrinkItem[selectLine]==''){
      this.InputDrinkItem[selectLine].push(this.selctTempItem);                          
      }
      else if(this.InputDrinkItem[selectLine]!=''){
        this.InputDrinkItem[selectLine].splice(0,1);
        this.InputDrinkItem[selectLine].push(this.selctTempItem);  
      }     
      //this.drink_Arr.splice(0,);
      this.drink_Arr[selectLine] = selectDrinkId;
      this.itemList_All.splice(0,);
      this.DrinkList();
   },
   //<---------------------- specific page ---------------------->
    specific(){
      var id = this.$route.query.id;
      
      this.spOrVdCard = { 0 : '보충기사', 1 : '자판기'}, 
      this.TrueOrFalse = true;
      console.log(this.TrueOrFalse);
      this.contentPageChange("자판기");
       
 
   }
  }
}
</script>

<style>
.spListDivBackground {
  position: relative;
  background-color: #0064c8;
  width: 280px;
  height: 600px;
  border-radius: 15px;
  padding-top: 10px;
}

.spListDiv {
  display : grid;
  grid-template-columns : 0.3fr 0.7fr;
  background-color: white;
  width: 260px;
  height: 80px;
  border-radius: 15px;
  padding: 9px;
  margin: 10px;
}

.imgRodius {
  border-radius: 15px;
  width: 60px;
  height: 60px;
}

.spInsertBtn {
  background-color: white;
  width: 260px;
  height: 80px;
  border-radius: 15px;
  padding: 5px;
}

.spUpdateAndspRemoveBtn {
  position: absolute;
  left: 10px;
  bottom: 10px;
}

.spBtnStyle {
  width: 115px;
  border-radius: 8px;
}
</style>