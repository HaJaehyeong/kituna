<template>
  <div class="rowStyle">
    <div>
        <br><br><br>
    </div>
    <div v-if="contentPage == '보충기사'">
      <div class="contentsUpperStyle">
        <div style="margin-right: 10px;">
          <img :src="identifyImg" id="showImage" style="width: 100%; ">
        </div>
        <div style="margin-left: 10px;">
          <table class="spInfoTableStyle">
            <tr class="even">
              <th class="blueFontStyle"><font size="3">사원번호</font></th>
              <td><font size="3">{{identifyNo}}</font></td>
              <th class="blueFontStyle"><font size="3">전화번호</font></th>
              <td><font size="3">{{identifyPhoneNum}}</font></td>
            </tr>
            <tr>
              <th class="blueFontStyle"><font size="3">이름</font></th>
              <td><font size="3">{{identifyName}}</font></td>
              <th class="blueFontStyle"><font size="3">이메일</font></th>
              <td><font size="3">{{identifyEmail}}</font></td>
            </tr>
            <tr class="even">
              <th class="blueFontStyle"><font size="3">주소</font></th>
              <td colspan="3"><font size="3">{{identifyAddress}}</font></td>
            </tr>
          </table>
        </div>
      </div>
      <div>
        <v-tabs v-model="active" color="grey lighten-5" slider-color="indigo">
          <v-tab v-for="choose in spInforPageList" :key="choose">{{ choose }}</v-tab>
          <v-tab-item v-for="choose in spInforPageList" :key="choose">
            <div v-if="choose=='작업지시서'">
              <div class="contentsRowStyle" id="spOrderListCutDiv">
                <div class="productStyle">
                  <div>
                    <table class="jobOrderTableStyle">
                      <tbody>
                        <tr v-for="count in allProductTableRepeationCount" :key="count.index" style="width: 100%; height: 50%;">
                          <td v-for="product in allProductCountArrCut[count - 1]" :key="product.index">
                            <div v-if="product.productCount != 0 && product.productCount != -1" class="productDivStyle">
                              <img :src="product.productImgSrc" style="width: 65%; height: 60px; margin: 10px;">
                              <h3 class="blueFontStyle" style="text-align: center;">{{product.productCount}}</h3>
                            </div>
                            <div v-else class="emptyDivStyle" style="position: relative;">
                              <div style="position: absolute; bottom: 7px; left: 25px;">
                                <h5 v-if="product.productCount == -1" style="color: white; text-align: center;">총 량</h5>
                                <h3 v-if="product.productCount == -1" style="color: white; text-align: center;">{{allCount}}</h3>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div>
                    <div style="margin-bottom: 10px;">
                      <div>
                        <v-btn outline small color="indigo" @click="dateChange(-1)" class="arrowImgSytle" style="border: 0px;">◀</v-btn>
                        <v-btn outline small color="indigo" @click.native.stop="dialog = true" style="min-width: 0px; font-size: 18px; padding: 2px;">{{today}}</v-btn>
                        <v-btn outline small color="indigo" @click="dateChange(1)" class="arrowImgSytle" style="border: 0px;">▶</v-btn>
                      </div>
                      <div style="display: grid; grid-template-columns: 0.5fr 0.5fr;">
                        <v-btn v-if="saveToday == today && jobOrderCheck == false" @click="createJobOrderBtn" class="printAndOrderCreateBtnStyle" color="primary">작업지시서<br>생성</v-btn>
                        <v-btn v-else disabled  class="printAndOrderCreateBtnStyle" color="primary">작업지시서<br>생성</v-btn>
                        <v-btn @click="printWindow" class="printAndOrderCreateBtnStyle" color="teal accent-4"><img src="/images/management/print_btn.png" style="width: 150%; height: 100%"></v-btn>
                      </div>

                      <!-- 달력 띄우는 모달창 -->
                      <v-dialog v-model="dialog" max-width="300">
                        <v-card>
                          <v-date-picker color="green lighten-1" v-model="pickerDate" :landscape="landscape" :reactive="reactive"></v-date-picker>
                          <v-spacer></v-spacer>
                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" flat="flat" @click.native="dialog = false" @click="chooseDateChange()">확인</v-btn>
                            <v-btn color="green darken-1" flat="flat" @click.native="dialog = false" >취소</v-btn>
                          </v-card-actions>
                        </v-card>
                      </v-dialog>
                      <!-- 달력 띄우는 모달창 -->

                    </div>
                    <div class="divRowHalf">
                      <div style="margin-bottom: 10px;">
                        <h5 class="blueFontStyle">보충기사 이름</h5>
                        <h4>{{spName}}</h4>
                      </div>
                      <div class="divColumnHalf">
                        <div>
                          <h5 class="blueFontStyle">자판기 수</h5>
                          <p><font size="5">{{allVDCount}}</font>&nbsp;&nbsp;&nbsp;&nbsp;<font class="blueFontStyle" size="3">개</font></p>
                        </div>
                        <div>
                          <h5 class="blueFontStyle">제품 수</h5>
                          <p><font size="5">{{allPDCount}}</font>&nbsp;&nbsp;&nbsp;&nbsp;<font class="blueFontStyle" size="3">종</font></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div>
                
                  <!-- 작업지시서 본 내용 -->
                  <table class="orderTableStyle">
                    <thead style="text-align: center;">
                      <th style="width: 13%;"><font size="4">자판기 명</font></th>
                      <th v-for="n in 8" :key="n.index"><font size="4">{{n}}</font></th>
                      <th style="width: 16%;"><font size="4">비고</font></th>
                    </thead>
                    <tbody>
                      <tr v-for="vending in sameVDArr" :key="vending.index">
                        <td><font size="4" color="#1565C0">{{vending.vd_name}}</font></td>

                        <td v-for="productValue in vending.lineAndProduct" :key="productValue.index">
                          <img :src="productValue.imgSrc" style="width: 25px; height: 35px; margin-left: 10px; margin-right: 10px;">
                          <font size="4">{{productValue.sp_val}}</font>
                        </td>                      
                        
                        <td v-if="jobOrderCheck == false" @click="createNote(vending.vd_name, vending.vd_id)" style="width: 10%">{{vending.orderNote}}</td>
                        <td v-else style="width: 10%">{{vending.orderNote}}</td>
                      </tr>
                    </tbody>
                  </table>

                  <!-- 작업지시 모달창 -->
                <v-dialog v-model="orderJobDialog" max-width="520" max-height="300">
                  <v-card>
                    <v-card-title>
                      <h2>작업 지시창</h2>
                    </v-card-title>
                    <v-card-text>
                      <v-card-text>
                        <h3>● 현재 자판기 : {{jobOrderVDName}}</h3>
                        <v-spacer></v-spacer>
                        <h3>● 보충 기사 : {{spName}}</h3>
                      </v-card-text>
                      <v-select
                        :items="select"
                        label="Please Select List"
                        item-value="text"
                        v-model ="selectedItem">
                      </v-select>
                      <div v-if="selectedItem=='기타'">
                        <v-text-field  label="작업지시를 적어주세요" v-model="selectedItem_etc"></v-text-field>
                      </div>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="green darken-1" flat="flat" @click.native="orderJobDialog = false">cancel</v-btn>
                      <v-btn color="green darken-1" flat="flat" @click.native="orderJobDialog = sendNote(selectedItem,selectedItem_etc)">submit</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
                <!-- 작업지시 모달창 -->
                </div>
              </div>
            </div>
            <div v-else>
              <v-container fluid fill-height>
                <v-card>
                  <v-card-title>
                    자판기명 검색
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="vdNameSearch"
                      append-icon="search"
                      label="Search"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table :search="vdNameSearch" :headers="vdLineTableHeaderArr" :items="spAllVDNameStockArr" class="elevation-1">
                    <template slot="items" slot-scope="props">
                      <td>{{ props.item.vd_name }}</td>
                      <td>{{ props.item.line1 }}</td>
                      <td>{{ props.item.line2 }}</td>
                      <td>{{ props.item.line3 }}</td>
                      <td>{{ props.item.line4 }}</td>
                      <td>{{ props.item.line5 }}</td>
                      <td>{{ props.item.line6 }}</td>
                      <td>{{ props.item.line7 }}</td>
                      <td>{{ props.item.line8 }}</td>
                    </template>
                    <v-alert slot="no-results" :value="true" color="error" icon="warning">
                      Your search for "{{ vdNameSearch }}" found no results.
                    </v-alert>
                  </v-data-table>
                </v-card>
              </v-container>
            </div>
          </v-tab-item>
        </v-tabs>
      </div>
    </div>
    <div v-else>
    </div>
  </div>
</template>
<script type="text/javascript" src="jquery.min.js"></script>
<script>
  let obj =[];
  let obj_c =[];

  import { EventBus } from '../../app.js';
  import html2canvas from 'html2canvas'          // 캔버스 사용
  export default{  
    data(){
      return{
        vdNameSearch: '',
        contentPage: '보충기사',                 // default로 보충기사 페이지가 보이도록 한다.
        spAllVDNameStockArr: [],
        vdLineTableHeaderArr: [{
          text: '자판기명',
          align: 'left',
          value: 'vd_name'
        }, {
          text: '라인1',
          sortable: false,
          value: 'line1'
        }, {
          text: '라인2',
          sortable: false,
          value: 'line2'
        }, {
          text: '라인3',
          sortable: false,
          value: 'line3'
        }, {
          text: '라인4',
          sortable: false,
          value: 'line4'
        }, {
          text: '라인5',
          sortable: false,
          value: 'line5'
        }, {
          text: '라인6',
          sortable: false,
          value: 'line6'
        }, {
          text: '라인7',
          sortable: false,
          value: 'line7'
        }, {
          text: '라인8',
          sortable: false,
          value: 'line8'
        }],
        
        
        spInforPageList : ['작업지시서', '담당자판기'],    // content 클릭 되는 페이지 리스트
        active : null,

        spName : '',    // 작업지시서 내 보충 기사 이름
        today  : '',    // 오늘 날짜
          
        dialog     : false,   // 모달 창 띄울지 결정하는 불린 값
        pickerDate : null,    // 모달 안 달력에서 클릭 되는 날짜
        landscape  : false,
        reactive   : false,

        identifyNo        : '',
        identifyName      : '',
        identifyPhoneNum  : '',
        identifyEmail     : '',
        identifyAddress   : '',
        identifyImg       : '/images/default.png',
        
        fields: [{
          key: 'Line',
          sortable: true,
          variant: 'danger'
        },
        {
          key: 'Name',
          sortable: false
        },
        {
          key: 'Stock',
          sortable: true
        },
        {
          key: 'expiration_date',
          sortable: true
        },
        {
          key: 'Sell_price',
          sortable: true
        }],
        fields_c: [{
          key: 'won1000',
          sortable: false
        },
        {
          key: 'won500',
          sortable: false
        },
        {
          key: 'won100',
          sortable: false
        },
        {
          key: 'sum',
          sortable: false
        }],
        itemList:[],
        itemList_c:[],
        vending_name:'',          /* 모달창 자판기 이름 */
        vending_manager:'',       /* 모달창 자판기 매니저 이름 */
        vending_id : '',          /* 모달창 자판기 아이디 */
        select: [                 /* 모달창 선택지 */
          { text: '긴급! 음료 재고 부족' },
          { text: '긴급! 잔고 부족' },
          { text: '축제 기간 ( 재고잔고 확인 요망 )' },
          { text: '기계 이상 및 고장' },
          { text: '기타' }
        ],
        selectedItem:'',
        selectedItem_etc:'',

        allProductCountArr              : [],       // 모든 제품 정보
        allProductTableRepeationCount   : 0,        // 전체 보충 제품 테이블 줄 수
        allProductCountArrCut           : [],       // 전체 보충 제품
        allVDCount                      : 0,        // 전체 작업지시 자판기 수
        allPDCount                      : 0,        // 전체 작업지시 제품 종류
        allCount                        : 0,        // 전제 작업지시 제품 개수

        sameVDArr                       : [],       // 같은 자판기의 제품
        orderJobDialog                  : false,    // 작업지시 모달창 상태
        jobOrderVDName                  : "",       // 작업지시 내리는 자판기 이름
        jobOrderVDId                    : "",       // 작어지시 내리는 자판기 아이디
        saveToday                       : "",       // 오늘 날짜
        jobOrderCheck                   : false     // 작업지시서 생성 유무 확인
      }
    },
    watch: {
      // 오늘 날짜가 바뀌는 것을 실시간으로 감시한다.
      today: function(){
        this.joborderRenew();
      },

      // 보충기사 변경 시 작동
      spName: function(){
        this.joborderRenew();
        this.chargeVdRenew();
        this.identifyRenew();
      },
    },
    mounted(){
      // 오늘 날짜를 실행하자마자 구한다.
      this.todayFunction();
    },
    created: function() {
      // 보충 기사를 클릭 했을 시, 실행되는 EventBus, 매개변수로는 눌린 보충기사의 ID가 넘어온다.
      EventBus.$on('jobOrder', (spId) => {
        this.spName = spId; // 클릭 된 사람의 ID가 spName에 저장되며, spName은 watch에서 감시하고 있다.
      }); 

      EventBus.$on('contentPageChange',(page) => {
        this.contentPage = page; // content  페이지 변경을 위해 
      });

      /* <--------- 모달창 자판기 정보 EventBus--------------> */ 
      EventBus.$on('vendingInfo',(arg1,arg2,arg3) => {
        this.vending_name = arg1; 
        this.vending_manager = arg2; 
        this.vending_id = arg3;
      });
      //<------------- 재고 EventBus ------------->
      EventBus.$on('StockEventBus',(response) => { 
        this.data = [];
          if(obj==[]){
            for(let key in response){
              this.data[key]={
                Line:response[key].line  ,
                Name:response[key].drink_name,
                Stock:response[key].stock ,
              }
              obj.push({Line:this.data[key].Line, Name:this.data[key].Name, Stock:this.data[key].Stock});
            }
          }else{
            obj.splice(0);
            obj=[];
            for(let key in response){
              this.data[key]={
                Line:response[key].line  ,
                Name:response[key].drink_name,
                Stock:response[key].stock ,
                expiration_date:response[key].expiration_date,
                Sell_price:response[key].sell_price
              }
              obj.push({Line:this.data[key].Line, Name:this.data[key].Name,
                        Stock:this.data[key].Stock, expiration_date:this.data[key].expiration_date,
                        Sell_price:this.data[key].Sell_price});
            }
          }
          this.itemList = obj;
      });
     //<------------- 잔고 EventBus ------------->
      EventBus.$on('CoinEventBus',(response) => {
        this.data = [];

        if(obj_c==[]){
          for(let key in response){
             this.data[key]={won1000:response[key].won1000, won500:response[key].won500,
                            won100:response[key].won100, sum:response[key].sum};
             obj_c.push({won1000:this.data[key].won1000, won500:this.data[key].won500,
                       won100:this.data[key].won100, sum:this.data[key].sum});
          }
        }else{
          obj_c.splice(0);
          obj_c=[];
          for(let key in response){
             this.data[key]={won1000:response[key].won1000, won500:response[key].won500,
                            won100:response[key].won100, sum:response[key].sum};
             obj_c.push({won1000:this.data[key].won1000, won500:this.data[key].won500,
                       won100:this.data[key].won100, sum:this.data[key].sum});
          }
        }

        this.itemList_c = obj_c;
      });
    },
    methods: {
      createJobOrderBtn() {
        let getSPUrl = 'management/getSP';    // 보충기사 정보 url

        this.axios.get(getSPUrl)
        .then((response) => {
          for (var i = 0; i < response.data.length; i++) {
            if (response.data[i].sp_login_id == this.spName) {
              // 해당되는 보충기사 찾기

              let createJobOrderUrl = "management/createJobOrder/" + response.data[i].sp_id;
              // 작업지시서 생성 url
              
              this.axios.get(createJobOrderUrl)
              .then((response) => {
                if (response.data == true) {
                  alert("오늘의 작업지시서가 생성되었습니다.");
                  this.jobOrderCheck = true;  // 작업지시서 생성 확인 

                  let sendPushAlertUrl = "android_db_conn_source/push_notification.php";  // 푸시 알람 url

                  this.axios.post(sendPushAlertUrl)
                  .then((response) => {
                    alert("앱으로 푸시알람이 갔습니다.");
                  })
                  .catch((error) => {
                    console.log(error.response);
                    alert("푸시알람 보내기가 실패하였습니다.");
                  })
                }
                else {
                  alert("오늘의 작업지시서 이미 생성되어있습니다.");
                }
              })
              .catch((error) => {
                console.log(error.response);
                alert("작업지시서 생성에 실패하였습니다.");
              })

              break;
            }
          }
        })
        .catch((error) => {
          console.log(error.response);
          alert("보충기사 정보찾기에 실패하였습니다.");
        })
      },
      // 작업지시서 생성


	    printWindow(){
        html2canvas(document.getElementById("spOrderListCutDiv")).then(function (canvas) {
          // 해당 div 이미지화

          const html = document.querySelector('html');  // 새 element 생성

          html.appendChild(canvas);                     // 이미지 넣기
          document.body.style.display = 'none';
          window.print();                               // 인쇄
          document.body.style.display = 'block';

          html.removeChild(html.lastChild);             // 이미지 제거
        });
	    },
      // 인쇄


      sendNote(selectedItem,selectedItem_etc) {
        if(selectedItem_etc == ''){
          const formData = new FormData();
          formData.append('ven_id',this.jobOrderVDId);
          formData.append('ven_note',selectedItem);
        
          this.axios.post("management/addJobOrderVerTwo", formData)
          .then((Response) => {
            // console.log(Response.data);
            this.joborderRenew();
            alert("작업지시가 완료되었습니다.");
          })
          .catch(ex=>{
            console.log(ex.response);
          }) 
        } 
        else if(selectedItem_etc != ''){
          const formData = new FormData();
          formData.append('ven_id',this.vending_id);
          formData.append('ven_note',selectedItem_etc);
        
          this.axios.post("management/addJobOrderVerTwo", formData)
          .then((Response) => {
            // console.log(Response.data);
            this.joborderRenew();
            alert("작업지시가 완료되었습니다.");
          }).catch(ex => {
            console.log(ex.response);
          }) 
        }

        this.orderJobDialog = false;
      },
      // 지시사항 작성


      createNote(chooseVDName, vendingId) {
        this.orderJobDialog = true;
        this.jobOrderVDName = chooseVDName;
        this.jobOrderVDId = vendingId;
      },
      // 작업지시 모달창 오픈


      identifyRenew(){
        this.axios.get("/management/getSP").then((response) =>{
          for(let i = 0 ; i < response.data.length ; i ++){
            if(response.data[i]['sp_login_id'] == this.spName){
              this.identifyNo        = response.data[i]['sp_id'];
              this.identifyName      = response.data[i]['sp_name'];
              this.identifyPhoneNum  = response.data[i]['sp_phone'];
              this.identifyEmail     = response.data[i]['sp_mail'];
              this.identifyAddress   = response.data[i]['sp_address'];
              this.identifyImg       = response.data[i]['sp_img_path'];
            }
          }
        });
      },
      // watch를 통해 보충기사 및 날짜에 변화가 있을 경우 해당 메서드로 오게 된다.


      joborderRenew(){
        // 보충기사가 클릭 됬는지 확인하는 if문
        if(this.spName){
          //보충기사의 그날 작업 지시서를 http통신을 통해 DB에서 가져온다.
          this.axios.get("/management/jobOrder/" + this.spName + "/" + this.today)
          .then((response) => {

            this.allProductCountArr = [];       // 모든 제품 정보 초기화
            this.sameVDArr = [];                // 같은 자판기의 제품 정보 초기화
            this.allCount = 0;                  // 총 작업지시 제품 수 초기화

            for(var i = 0; i < response.data.length; i++) {
              var productObj = {
                productName: "",
                productCount: 0,
                productImgSrc: ""
              }

              productObj.productName = response.data[i].drink_name;
              productObj.productCount = response.data[i].sp_val;
              productObj.productImgSrc = "/images/drink/" + response.data[i].drink_name + "_back.png";
              this.allCount += productObj.productCount;
              // 작업지시 제품 수 정리

              var is_nameSame = false;    // 같은 이름 제품 유무

              for (var j = 0; j < this.allProductCountArr.length; j++) {
                if (this.allProductCountArr[j].productName == productObj.productName) {
                  this.allProductCountArr[j].productCount += productObj.productCount;
                  is_nameSame = true;
                  break;
                }
              }

              if (is_nameSame == false) {
                this.allProductCountArr.push(productObj);
              }
              // 같은 이름 제품 정보 정리

              var is_vdSame = false;      // 같은 자판기 유무

              for (var j = 0; j < this.sameVDArr.length; j++) {
                if (this.sameVDArr[j].vd_id == response.data[i].vd_id) {
                  var pdName = response.data[i].drink_name.replace("_", "\n");
                  this.sameVDArr[j].lineAndProduct.push({lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val, imgSrc: "/images/drink/" + response.data[i].drink_name + ".png"});
                  
                  if (response.data[i].note != null) {
                    this.sameVDArr[j].orderNote += "," + response.data[i].note;
                  }
          
                  is_vdSame = true;
                  break;
                }
              }
              
              if (is_vdSame == false) {
                var pdName = response.data[i].drink_name.replace("_", "\n");

                if (response.data[i].note != null) {
                  this.sameVDArr.push({vd_id: response.data[i].vd_id, vd_name: response.data[i].vd_name, orderNote: response.data[i].note, lineAndProduct: [{lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val, imgSrc: "/images/drink/" + response.data[i].drink_name + ".png"}]});
                }
                else {
                  this.sameVDArr.push({vd_id: response.data[i].vd_id, vd_name: response.data[i].vd_name, orderNote: "", lineAndProduct: [{lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val, imgSrc: "/images/drink/" + response.data[i].drink_name + ".png"}]});
                }
              }
              // 같은 이름 자판기 정보 정리
            }
            // 해당 보충기사의 모든 작업지시 사항 정리
            
            for (var i = 0; i < this.allProductCountArr.length; i++) {
              if (this.allProductCountArr[i].productCount == 0) {
                this.allProductCountArr.splice(i, 1);
              }
            }
            // 보충량 0인 제품 삭제

            this.allPDCount = this.allProductCountArr.length;   // 모든 제품 수

            var inputEmptyObj = {
              productName: "",
              productCount: 0,
              productImgSrc: ""
            }
            // 빈 값을 가진 객체

            while (this.allProductCountArr.length < 12) {
              if (this.allProductCountArr.length == 11) {
                 var inputLastEmptyObj = {
                  productName: "",
                  productCount: -1,
                  productImgSrc: ""
                }

                this.allProductCountArr.push(inputLastEmptyObj);
              }
              else {
                this.allProductCountArr.push(inputEmptyObj);
              }
            }
            // 공간 맞추기

            this.allProductTableRepeationCount = Math.ceil(this.allProductCountArr.length / 6);   // 모든 작업지시 테이블 줄 수 
            this.allProductCountArrCut = [];                                                      // 모든 작업지시 제품 정보
            var cut = 0;

            for (var i = 0; i < this.allProductTableRepeationCount; i++) {
              this.allProductCountArrCut[i] = [];

              for (var j = cut; j < this.allProductCountArr.length; j++) {
                if (j % 6 != 0 || j == cut) {
                  this.allProductCountArrCut[i].push(this.allProductCountArr[j]);
                }
                else {
                  cut = j;
                  break;
                }
              }
            }
            // 줄 수에 맞게 제품 정보 입력

            this.allVDCount = this.sameVDArr.length;            // 모든 작업지시 자판기 수

            let getSPUrl = 'management/getSP';                  // 보충기사 정보 url

            this.axios.get(getSPUrl)
            .then((response) => {
              for (var i = 0; i < response.data.length; i++) {
                if (response.data[i].sp_login_id == this.spName) {
                  // 해당되는 보충기사 찾기

                  let jobOrderCheckUrl = 'management/checkJobOrder/' + response.data[i].sp_id + "/" + this.today;    // 작업지시서 생성 유무확인 url
                  // 작업지시서 생성 유무 확인 url

                  this.axios.get(jobOrderCheckUrl)
                  .then((response) => {
                    if (response.data == true) {
                      // 작업지시서가 생성 되어 있을 경우

                      this.jobOrderCheck = true;
                    }
                    else {
                      // 작업지시서가 생성 되어 있지 않을 경우

                      this.jobOrderCheck = false;
                    }
                  })
                  .catch((error) => {
                    console.log(error.response);
                    alert("작업지시서 생성유무 확인에 실패하였습니다.");
                  })

                  break;
                }
              }
            })
            .catch((error) => {
              console.log(error.response);
              alert("보충기사 정보찾기에 실패하였습니다.");
            })
          })
          .catch((error) => {
            console.log(error.response);
            alert("작업지시 정보를 가져오는 것을 실패하였습니다.");
          })  
        }
      },


      chargeVdRenew(){
        // 보충기사의 ID로 해당 보충기사의 담당자판기의 현황을 볼 수 있다.
        this.axios.get("management/getVdInfo/" + this.spName)
        .then((response) => {
          this.spAllVDNameStockArr = [];

          var inputObj = {
            value: false, vd_name: "",
            line1: "", line2: "", line3: "", line4: "",
            line5: "", line6: "", line7: "", line8: ""
          }
          // 자판기 라인 객체

          for (var i = 0; i < response.data.length; i++) {
            inputObj.vd_name = response.data[i].vd_name;

            if (response.data[i].line == 1) { inputObj.line1 = response.data[i].drink_name + "  /  " + response.data[i].stock; }
            else if (response.data[i].line == 2) { inputObj.line2 = response.data[i].drink_name + "  /  " + response.data[i].stock; }
            else if (response.data[i].line == 3) { inputObj.line3 = response.data[i].drink_name + "  /  " + response.data[i].stock; }
            else if (response.data[i].line == 4) { inputObj.line4 = response.data[i].drink_name + "  /  " + response.data[i].stock; }
            else if (response.data[i].line == 5) { inputObj.line5 = response.data[i].drink_name + "  /  " + response.data[i].stock; }
            else if (response.data[i].line == 6) { inputObj.line6 = response.data[i].drink_name + "  /  " + response.data[i].stock; }
            else if (response.data[i].line == 7) { inputObj.line7 = response.data[i].drink_name + "  /  " + response.data[i].stock; }
            else { inputObj.line8 = response.data[i].drink_name + "  /  " + response.data[i].stock; }
            // 라인에 따라 제품 정보 입력

            if (inputObj.line1 != "" && inputObj.line2 != "" && inputObj.line3 != "" && inputObj.line4 != "" && inputObj.line5 != "" && inputObj.line6 != "" && inputObj.line7 != "" && inputObj.line8 != "") {
              this.spAllVDNameStockArr.push(inputObj);
              
              var inputObj = {
                value: false, vd_name: "",
                line1: "", line2: "", line3: "", line4: "",
                line5: "", line6: "", line7: "", line8: ""
              }
            }
          }
          // 자판기 라인에 따라 제품 정리
        })
        .catch((error) => {
          console.log(error.response);
          alert("담당자판기 정보를 가져오는 것에 실패하였습니다.");
        })
      },


      // 오늘 날짜를 구하는 함수
      todayFunction(){
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();
        
        // 월이 한자리일 경우 앞에 0을 붙여준다.
        if((month+'').length < 2){
          month = "0" + month;
        }

        if((day+'').length < 2){
          day = "0" + day;
        }

        this.today = year + '-' + month + '-' + day;
        this.saveToday = this.today;
      },

      //달력을 통해 클릭된 날로 바꾸는 함수
      chooseDateChange(){
        this.today = this.pickerDate;
      },

      // 날짜 버튼 좌,우 화살표 버튼 클릭 시, 하루씩 이동하는 함수
      dateChange(arg){
        // 기존 날짜를 '-'를 기준으로 split한다. 년/월/일로 나뉘는 배열 생성
        let changeToday = this.today.split('-');
        // 현재 년/월의 마지막 날을 구해둔다.
        let lastDay = ( new Date(changeToday[0],changeToday[1], 0) ).getDate();
        //만약 해당 달의 마지막 날일 경우
        if(parseInt(changeToday[2]) + arg == lastDay + 1){
          changeToday[1] = parseInt(changeToday[1]) + 1;
          //만약 해당 달이 한 자리일 경우 앞에 0을 붙여준다.
          if((changeToday[1]+'').length < 2){
            changeToday[1] = "0" + changeToday[1];
          };
          changeToday[2] = "0" + arg;
          this.today = changeToday[0] + "-" + changeToday[1] + "-" + changeToday[2];
        //만약 해당 달의 마지막 날일 경우이면서 버튼을 뒤로 누른 경우
        }else if(parseInt(changeToday[2]) == 1 && arg == '-1'){
          changeToday[1] = parseInt(changeToday[1]) - 1;
          //만약 해당 달이 한 자리일 경우 앞에 0을 붙여준다.
          if((changeToday[1]+'').length < 2){
            changeToday[1] = "0" + changeToday[1];
          };
          changeToday[2] = ( new Date(changeToday[0],changeToday[1], 0) ).getDate();
          this.today = changeToday[0] + "-" + changeToday[1] + "-" + changeToday[2];
        }else{
          changeToday[2] = parseInt(changeToday[2]) + arg;
          //만약 해당 일이 한 자리일 경우 앞에 0을 붙여준다.
          if((changeToday[2]+'').length < 2){         
            changeToday[2] = "0" + changeToday[2];
          }
          this.today = changeToday[0] + "-" + changeToday[1] + "-" + changeToday[2];
        }
      },
      /* <---------------------모달창 내용 db전송 --------------------> */
      submit(selectedItem,selectedItem_etc){
        // console.log(selectedItem_etc);
        // console.log(selectedItem);

        if(selectedItem_etc == ''){
          const formData = new FormData();
          formData.append('ven_id',this.vending_id);
          formData.append('ven_note',selectedItem);
        
          this.axios.post("management/addJobOrder", formData)
          .then((Response) => {
            // console.log(Response.data);
            alert("작업지시가 완료되었습니다.");
          })
          .catch(ex=>{
            console.log(ex.response);
          }) 
        } 
        else if(selectedItem_etc != ''){
          const formData = new FormData();
          formData.append('ven_id',this.vending_id);
          formData.append('ven_note',selectedItem_etc);
        
          this.axios.post("management/addJobOrder", formData)
          .then((Response) => {
            // console.log(Response.data);
            alert("작업지시가 완료되었습니다.");
          }).catch(ex => {
            console.log(ex.response);
          }) 
        }

        this.dialog = false;
      }
    }
  }

</script>

<style>
table.spInfoTableStyle {
  width: 100%;
  height: 120px;
}
table.spInfoTableStyle .even {
  background: #E3F2FD;
}
table.spInfoTableStyle th {
  width: 15%;
  padding-left: 20px;
}
table.spInfoTableStyle td {
  border-bottom: none;
  border-top: none;
  border-left: none;
  border-right: 10px solid #FAFAFA;
}

.rowStyle {
  height: 100%;
  display: grid;
  grid-template-rows: 0.115fr 0.885fr;
}

.contentsUpperStyle {
  display: grid;
  grid-template-columns: 0.15fr 0.85fr;
}

.productDivStyle {
  background-image: url("/images/management/white_background.png");
  background-size: 100% 100%;
  background-repeat: no-repeat;
  border-radius: 15px;
  width:100%;
  height: 100%;
  text-align: center;
}
.emptyDivStyle {
  background-image: url("/images/management/blue_background.png");
  background-size: 100% 100%;
  background-repeat: no-repeat;
  border-radius: 15px;
  width:100%;
  height: 100%;
}

.contentsRowStyle {
  display: grid;
  grid-template-rows: 0.4fr 0.6fr;
  height: 460px;
  background-color: #FAFAFA;
}

.arrowImgSytle {
  min-width: 0px;
  margin: 0px;
  padding: 1px;
}

.printAndOrderCreateBtnStyle {
  border: 0px;
  min-width: 0px;
  margin: 5px;
  padding: 1px;
  height: 90%;
  border-radius: 15px;
}

table.jobOrderTableStyle td {
  width: 100px;
  height: 130px;
  border-bottom: none;
  border-top: none;
  border-left: none;
  border-right: none;
}

.divRowHalf {
  display: grid;
  grid-template-rows: 0.5fr 0.5fr;
}

.divColumnHalf {
  display: grid;
  grid-template-columns: 0.5fr 0.5fr;
}

.productStyle {
  display: grid;
  grid-template-columns: 0.7fr 0.3fr;
}

.blueFontStyle{
  color: #1565C0;
}

table.orderTableStyle {
  width: 100%;
}
table.orderTableStyle th {
  border-bottom: 3px #1565C0 solid;
  color: #1565C0;
}
table.orderTableStyle td {
  border-bottom: none;
  border-top: none;
  border-left: none;
  border-right: none;
}
table.orderTableStyle tr:nth-child(even) {
  background: #E3F2FD;
}

#showImage {
  width: 150px;
  height: 120px;
  margin-right: 10px;
}

#spOrderListCutDiv{
  display : grid;
  grid-template-rows: 0.1fr 0.9fr;
}


</style>