<template>
  <div id="allDivision" >  
    <div style="text-align:left;font-family:'Dosis';" >
    <h2><strong>　　Real time Management </strong></h2>
    </div>
    <div id="tab_container">
        <input id="select1" name="sex" type="radio" checked>
        <button id="btn1"><label for="select1" class="local" @click="tokyo(139.700442,35.706269)">地域別</label></button>
        <input id="select2" name="sex" type="radio">
        <button id="btn2"><label for="select2" class="supporter">オペレーター別</label></button>

        <!-- ****************** Local list ****************** --> 
        <div class="page1" id="page_1">
          <br />
             
          <button id="localBtn" href="#" @click="tokyo(139.700442,35.706269)">東京</button>
          <br /><br />
          <div v-for="(item_1, key, index) in itemList" :key="index">
            <div v-if="item_1.text=='千代田区(chiyoda)'">
             <button id="localBtn" href="#" v-b-toggle.chiyoda  @click="NationWideEmit(item_1.latitude,item_1.longitude)" >{{item_1.text}}</button>
            </div>
            <div v-if="item_1.text=='新宿区(shinjuku)'">
             <button id="localBtn" href="#" v-b-toggle.shinjuku  @click="NationWideEmit(item_1.latitude,item_1.longitude)" >{{item_1.text}}</button>
            </div>
            <div v-if="item_1.text!='新宿区(shinjuku)'&&item_1.text!='千代田区(chiyoda)'">
             <button id="localBtn" href="#" v-b-toggle.index  @click="NationWideEmit(item_1.latitude,item_1.longitude)" >{{item_1.text}}</button>
            </div>
          <br />
          <div v-if="item_1.text=='千代田区(chiyoda)'">
              <b-collapse id="chiyoda">
                  <b-card style="max-width: 27em;">
                    <v-btn outline color="indigo"  class="subLocalBtnFrame"   @click="localEmit(item_2.longitude,item_2.latitude) " v-for="item_2 in item_1.children" :key="item_2.no">  <tr id="subLocalButton">{{item_2.name}}　 </tr>
                      <Br />
                      <tr >
                        <td id="subLocalButton_1" v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==1)">{{item_3.count}}</td><td id="subLocalButton_2" v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==0)">/{{item_3.count}}</td>
                      </tr></v-btn>
                  </b-card>
                </b-collapse>
           </div>
           <div v-if="item_1.text=='新宿区(shinjuku)'">
              <b-collapse id="shinjuku">
                  <b-card style="max-width: 27em;">
                      <v-btn outline color="indigo"  class="subLocalBtnFrame"   @click="localEmit(item_2.longitude,item_2.latitude) " v-for="item_2 in item_1.children" :key="item_2.no">  <tr id="subLocalButton">{{item_2.name}}　 </tr>
                      <Br />
                      <tr >
                        <td id="subLocalButton_1" v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==1)">{{item_3.count}}</td><td id="subLocalButton_2" v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==0)">/{{item_3.count}}</td>
                      </tr></v-btn>
                  </b-card>
                </b-collapse>
           </div>
          </div>
       </div>
        <!-- ******************  supplemener list ****************** -->
        <div class="page2" id="page_2">
           <br /><br /><br />
           <div  v-for="(item,index) in itemList2" :key="item.index" v-if="index>=0">
            <br />
              <div id="supporter_frame" >
                <div id="supprter_frame_2" @click="supplementerEmit(item.supplementer)">
                  <v-avatar >
                    <img v-bind:src="item.imgSrc">
                  </v-avatar> {{item.supplementer}}　<button class="work_order_button" @click="orderList(item.supplementer)"><p id="work_order_font">作業指示書</p></button>
                </div>
          <v-dialog v-model="orderJobModal" width="1000px">
            <v-card>
              <div class="contentsRowStyle" id="spOrderListCutDiv" style="margin: 50px;">
                <div>
                  <h3>{{spName}}の作業指示書</h3>
                </div>
                <div class="productStyle">
                  <div>
                    <table class="jobOrderTableStyle">
                      <tr v-for="count in allProductTableRepeationCount" :key="count.index" style="width: 100%; height: 50%;">
                        <td v-for="product in allProductCountArrCut[count - 1]" :key="product.index">
                          <div v-if="product.productCount != 0 && product.productCount != -1" class="productDivStyle">
                            <img :src="product.productImgSrc" style="width: 65%; height: 60px; margin: 10px;">
                            <h3 class="blueFontStyle" style="text-align: center;">{{product.productCount}}</h3>
                          </div>
                          <div v-else class="emptyDivStyle" style="position: relative;">
                            <div style="position: absolute; bottom: 7px; left: 25px;">
                              <h5 v-if="product.productCount == -1" style="color: white; text-align: center;">総量</h5>
                              <h3 v-if="product.productCount == -1" style="color: white; text-align: center;">{{allCount}}</h3>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div>
                    <div style="margin-bottom: 90px;">
                      <div>
                        <v-btn outline small color="indigo" @click="dateChange(-1)" class="arrowImgSytle" style="border: 0px;">◀</v-btn>
                        <v-btn outline small color="indigo" @click.native.stop="dialog = true" style="min-width: 0px; font-size: 18px; padding: 2px;">{{today}}</v-btn>
                        <v-btn outline small color="indigo" @click="dateChange(1)" class="arrowImgSytle" style="border: 0px;">▶</v-btn>
                      </div>

                      <!-- 달력 띄우는 모달창 -->
                      <v-dialog v-model="dialog" max-width="300">
                        <v-card>
                          <v-date-picker color="green lighten-1" v-model="pickerDate" :landscape="landscape" :reactive="reactive"></v-date-picker>
                          <v-spacer></v-spacer>
                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" flat="flat" @click.native="dialog = false" @click="chooseDateChange()">確認</v-btn>
                            <v-btn color="green darken-1" flat="flat" @click.native="dialog = false" >취소</v-btn>
                          </v-card-actions>
                        </v-card>
                      </v-dialog>
                      <!-- 달력 띄우는 모달창 -->

                    </div>
                    <div class="divRowHalf">
                      <div style="margin-bottom: 10px;">
                        <h5 class="blueFontStyle">オペレーター名</h5>
                        <h4>{{spName}}</h4>
                      </div>
                      <div class="divColumnHalf">
                        <div>
                          <h5 class="blueFontStyle">自販機数</h5>
                          <p><font size="5">{{allVDCount}}</font>&nbsp;&nbsp;&nbsp;&nbsp;<font class="blueFontStyle" size="3">個</font></p>
                        </div>
                        <div>
                          <h5 class="blueFontStyle">製品数</h5>
                          <p><font size="5">{{allPDCount}}</font>&nbsp;&nbsp;&nbsp;&nbsp;<font class="blueFontStyle" size="3">種類</font></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div>
                
                  <!-- 작업지시서 본 내용 -->
                  <table class="orderTableStyle">
                    <thead style="text-align: center;">
                      <th style="width: 13%;"><font size="4">自販機名</font></th>
                      <th v-for="n in 8" :key="n.index"><font size="4">{{n}}</font></th>
                      <th style="width: 16%;"><font size="4">備考</font></th>
                    </thead>
                    <tbody>
                      <tr v-for="vending in sameVDArr" :key="vending.index">
                        <td><font size="4" color="#1565C0">{{vending.vd_name}}</font></td>

                        <td v-for="productValue in vending.lineAndProduct" :key="productValue.index">
                          <img :src="productValue.imgSrc" style="width: 25px; height: 35px; margin-left: 10px; margin-right: 10px;">
                          <font size="4">{{productValue.sp_val}}</font>
                        </td>                      
                        
                        <td style="width: 10%">{{vending.orderNote}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div style="position: relative;">
                <v-btn style="position: absolute; right: 50px; bottom: 10px;" color="primary" @click.native="orderJobModal = false"><h3>確認</h3></v-btn>
              </div>
            </v-card>
               </v-dialog>
              </div>
            </div> 
        </div>
    </div> 
    
  </div>
</template>

<script>
import LocalList from '../../LocalList'
import { EventBus } from '../../app.js';
import uniq from 'lodash/uniq';

let Local='';
export default {
  name: 'Local',
  data() {
    return {      
      //탭 조회 버튼을 위한 변수
      spOrVdCard : { 0 : '지역별', 1 : '보충기사별'},
      active: null,
      itemList : LocalList.itemList,
      settings: {
        maxScrollbarLength: 150
      },
      seoul : {lat : 36.877033, lng: 127.95517},
      itemList2:[],
      itemsCount:[],
      select: [  /* 모달창 선택지 */
        { text: '긴급! 음료 재고 부족' },
        { text: '긴급! 잔고 부족' },
        { text: '축제 기간 ( 재고잔고 확인 요망 )' },
        { text: '기계 이상 및 고장' },
        { text: '기타' }
      ],
      selectedItem:'',
      selectedItem_etc:'',
      orderJobModal: false,
      dialog     : false,   // 모달 창 띄울지 결정하는 불린 값
      pickerDate : null, // 모달 안 달력에서 클릭 되는 날짜
      landscape  : false,
      reactive   : false,
      allProductCountArr: [],
      allProductTableRepeationCount: 0,
      allProductCountArrCut: [],
      allVDCount: 0,
      allPDCount: 0,
      allCount: 0,

      sameVDArr: [],
      slideSide: true,
      orderJobDialog: false,
      jobOrderVDName: "",
      jobOrderVDId: "",
      saveToday: "",
      spName : '',    // 작업지시서 내 보충 기사 이름
      today  : '',     // 오늘 날짜,
    }
  },
  mounted(){
    var self = this;
    this.axios.get("/realtime/list/all/all")
    .then((response) =>{
      self.NationEmit(response); 
    })
    this.SupplimenterList();
    this.localCount();
    this.todayFunction();
  },
  watch: {
    //오늘 날짜가 바뀌는 것을 실시간으로 감시한다.
    today: function(){
      this.joborderRenew();
    }
  },

  methods: {
    joborderRenew() {
      this.axios.get("/management/jobOrder/" + this.spName + "/" + this.today)
        .then((response) => {
          this.allProductCountArr = [];       // 모든 제품 정보 초기화
          this.sameVDArr = [];                // 같은 자판기의 제품 정보 초기화
          this.allCount = 0;                  // 총 작업지시 제품 수 초기화

          for(var i = 0; i < response.data.length; i++) {
            var productObj = {
              productName: "",
              productNoUnderLineName: "",
              productCount: 0,
              productImgSrc: ""
            }

            productObj.productName = response.data[i].drink_name;
            productObj.productNoUnderLineName = response.data[i].drink_name.replace("_", "\n");
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

            var is_vdSame = false;

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

          this.allVDCount = this.sameVDArr.length;
          this.allPDCount = this.allProductCountArr.length;
        })
    },
    // watch를 통해 보충기사 및 날짜에 변화가 있을 경우 해당 메서드로 오게 된다.
    
    createNote(chooseVDName, vendingId) {
      this.orderJobDialog = true;
      this.jobOrderVDName = chooseVDName;
      this.jobOrderVDId = vendingId;
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

        this.orderJobDialog = false;
      },
      orderList(supplementer){
        this.orderJobModal = true;
        this.spName = supplementer;

        this.axios.get("/management/jobOrder/" + this.spName + "/" + this.today)
        .then((response) => {
          this.allProductCountArr = [];       // 모든 제품 정보 초기화
          this.sameVDArr = [];                // 같은 자판기의 제품 정보 초기화
          this.allCount = 0;                  // 총 작업지시 제품 수 초기화

          for(var i = 0; i < response.data.length; i++) {
            var productObj = {
              productName: "",
              productNoUnderLineName: "",
              productCount: 0,
              productImgSrc: ""
            }

            productObj.productName = response.data[i].drink_name;
            productObj.productNoUnderLineName = response.data[i].drink_name.replace("_", "\n");
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

            var is_vdSame = false;

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

          this.allVDCount = this.sameVDArr.length;
          this.allPDCount = this.allProductCountArr.length;
        })
             
    },

    //<-----------------National axios --------------------> 
    NationEmit(response){
      var response = response;
      var count =0;
      EventBus.$emit('NationEvent',37.983772,140.000876,response,count); 
    },
 
    
    //<-----------------National local --------------------> 
    NationWideEmit(a1,a2){
      
      EventBus.$emit('NationWideEvent',a1,a2); 
      
    },
    //<--------------------- local  ------------------------>
    localEmit :function(arg1,arg2){
      
      EventBus.$emit('LocalEvent',arg1,arg2);

   
    },
    //<--------------------- South --------------------------->  
    tokyo:function(arg1,arg2){
      EventBus.$emit('tokyo',arg1,arg2);
    }, 
  
    //<----------------- SupplimenterList -------------------->
    
    SupplimenterList:function(){
      var obj =[];
      var array = [];
      this.axios.get("management/getSP")
       .then((response) =>{
           let key;
           if(obj==[]){
            for(let i = 0 ; i < response.data.length; i++){
             obj.push({
             supplementer:response.data[i]['sp_login_id'],
             imgSrc:response.data[i]['sp_img_path'],
            });
         } 
       }
           else{
             obj.splice(0,);
            for(let i = 0 ; i < response.data.length; i++){
             obj.push({
             supplementer:response.data[i]['sp_login_id'],
             imgSrc:response.data[i]['sp_img_path'],
            });
         } 
           }   
         })
       .catch(function (error) {
         console.log(error);
       });
         
      this.itemList2 = obj;
      this.tokyo();
    },

    //<--------------------- supplementer Emit  ------------------------>
    supplementerEmit :function(arg1){

      EventBus.$emit('supplementerEvent',arg1);
  
    },
    //<--------------------- 지역별 자판기 개수 및 매진임박 개수 ------------------------>
    localCount:function(){
     this.axios.get('realtime/getNumOfVd')
        .then((response) =>{
            var key;
            var obj =[];
            var array = [];
            response = response.data; 

           if(obj==[]){
             for(key in response){
                array[key] = {
                 address : response[key].address,
                 count  : response[key].count,
                 vd_soldout : response[key].vd_soldout
                }
                obj.push({address:array[key].address,
                         count:array[key].count,
                         vd_soldout:array[key].vd_soldout
                     });
              }
           }
           else{
             obj.splice(0,);
              for(key in response){
                array[key] = {
                 address : response[key].address,
                 count  : response[key].count,
                 vd_soldout : response[key].vd_soldout
                 
                }
                obj.push({address:array[key].address,
                         count:array[key].count,
                         vd_soldout:array[key].vd_soldout
                     });
               }
           }   
          this.itemsCount =obj;
      })
    }
  } 
}
</script>

<style>

/* 전체 구역 마진 설정 */
#allDivision{
margin-top:-20px;
margin-right:1px;
margin-bottom:10px; 
margin-left:80px;
font-family:"Gothic A1";
}
/* 하이퍼링크 설정 */
 #a:link { color: rgb(198, 198, 198); text-decoration: none;}
 #a:visited { color: rgb(65, 39, 39); text-decoration: none;}
 #a:hover { color: rgb(154, 169, 156); text-decoration: underline;}

 *,
*:after,
*:before {
   -webkit-box-sizing: border-box;
   -moz-box-sizing: border-box;
   box-sizing: border-box;
}

.clearfix:before,
.clearfix:after {
   content: " ";
   display: table;
}

.clearfix:after {
   clear: both;
}

body {
   font-family: sans-serif;
   background: #ffffff;
}

h1 {
   color: rgb(255, 255, 255);
   text-align: center;
}
/* 지역별 조회 배경 설정 */
#page_1{
    width:  400px;
    height: 550px;
    background-image: url("/images/realtime/left_frame.png");
  /*   background-repeat: no-repeat;*/
    background-position: center; 
    background-size: 370px 500px;
    font-size: 14px;
    margin-top:-10px;
    padding-top:6px;
    
}

/* 지역별 조회 배경 설정 */
#page_2{
    width:  400px;
    height: 550px;
    background-image: url("/images/realtime/left_frame.png");
  /*  background-repeat: no-repeat;*/
    background-position: center; 
    background-size: 370px 500px;
    margin-top:-10px;
    
}

/* 지역별 조회 버튼 css 설정 */
input#select1,input#select2{
  display:none;
}

.page1,.page2{
  display:none;
}
input#select1:checked ~ .page1{
  display:block;
}
input#select2:checked ~ .page2{
  display:block;
}

/* 이름 설정 */
label{
    display:inline-block;
    width: 200px;
    height: 70px;
    background:rgb(255, 255, 255);
    font-size: 20px;
    text-align:center;
    line-height:30px;
    font-family:"Nanum Gothic";
    color:#0064c8;
}
/* 탭 부분 폰트 설정 - 일본어  */
#tab_container{
   font-family:"Rounded Mplus 1c";
}
/* ------------------------- 버튼1- 지역별 조회 설정 ----------------------*/
#btn1 {
    position: relative;
    border: 3px solid  #0064c8;
    border-radius:10px;
    font-size: 21px;
    color: #FFFFFF;
    padding-top:5px;
    padding-right:38%;
    width: 130px;
    height: 50px;
    text-align: center;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    text-decoration: none;
    overflow: hidden;
    cursor: pointer; 
    font-family:"Nanum Gothic";
    margin-top: 2%;
    margin-right: 1%;
}
#btn1:hover {
  border: 3px solid #0064c8;
  color:#0064c8; 
  font-weight: bold;
}
#btn1:after {
    content: "";
    background: #0064c8;
    display: block;
    position: absolute;
    padding-top: 300%;
    padding-left: 350%;
    margin-left: -20px!important;
    margin-top: -120%;
    opacity: 0;
    transition: all 0.8s
   
}

#btn1:active:after {
    padding: 0;
    margin: 0;
    opacity: 1;
    transition: 0s
}
#btn1:focus {
  outline: none;
  border: 3px solid #0064c8;
  color:#0064c8;
  font-weight: bold;
}

/* ------------------ 버튼2- 보충기사별 조회 설정 -----------------*/
#btn2{
    position: relative;
    border: 3px solid  #0064c8;
    border-radius:10px;
    font-size: 21px;
    color: #FFFFFF;
    padding-top:5px;
    padding-right:38%;
    width: 160px;
    height: 50px;
    text-align: center;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    text-decoration: none;
    overflow: hidden;
    cursor: pointer; 
    font-family:"Nanum Gothic";
    
}
#btn2:hover {
  border: 3px solid #0064c8;
  color:#0064c8;
  font-weight: bold;
}
#btn2:after {
    content: "";
    background: #0064c8;
    display: block;
    position: absolute;
    padding-top: 300%;
    padding-left: 350%;
    margin-left: -20px!important;
    margin-top: -120%;
    opacity: 0;
    transition: all 0.8s;
}

#btn2:active:after {
    padding: 0;
    margin: 0;
    opacity: 1;
    transition: 0s
}
#btn2:focus {
  outline: none;
  border: 3px solid #0064c8;
  color: #0064c8;
  font-weight: bold;
}

/* 각 지역 버튼 설정 */
#localBtn{
  color: #ffffff;
  font-size: 20px;
  text-align: center;
  font-family:"Nanum Gothic";
  font-weight: bold;
  
}
/* 각 지역별 버튼*/
.subLocalBtnFrame{  

    padding-left : -19px;
    padding-right : 10px;
    padding-bottom : 15px;
    position: relative;
    width: 150px;
    height: 50px;
   
    
    border: 3px solid #04376a;
    border-radius:10px;
    margin: 10px;
    border: none;
    text-align: center;
    text-decoration: none;
    font-size: 22px;
    cursor: pointer;
    overflow: hidden;
    -webkit-transition-duration: 0.4s;
    transition-duration: 0.4s;

}

.subLocalBtnFrame:focus {
  outline: none;
  border: 3px solid #0064c8;
  color: #0064c8;
  font-weight: bold;
}

.subLocalBtnFrame:after {
			content: "";
			background-color: #0064c8;
			display: block;
			position: absolute;
			top: 70px;
			left: 0;
			padding-top: 200%;
			padding-left: 300%;
			margin-left: 0;
			margin-top: -120%;
			opacity: 0;
			transition: all 0.8s;
 }

 .subLocalBtnFrame:active::after {
			padding: 0px;
			margin: 0px;
			opacity: 1;
			transition: all 0s
      
 }

 .subLocalBtnFrame:hover { box-shadow: 0 0px 10px 0 rgba(102, 102, 102, 0.2), 0 6px 10px 0 rgba(0,0,0,0.19); }

 .subLocalBtnFrame:active  {
	transition: all 1s;
  border: 3px solid #0064c8;
  color: #0064c8;
  font-weight: bold;
}
/* 각 지역별 내부 폰트 */
#subLocalButton{
  color: #0064c8;
  font-size: 21px;
  text-align: center;
  font-family:"Nanum Gothic";
  font-weight: bold; 
}

/* 각지역별 내부 폰트 - 첫번째 숫자  */
#subLocalButton_1{
  color: #306ca8;
  font-size: 26px;
  text-align: center;
  font-family:"Fugaz One";
}
/* 각지역별 내부 폰트 - 두번째 숫자  */
#subLocalButton_2{
  color: #79a9da;
  font-size: 17px;
  text-align: center;
  font-family:"Fugaz One";
}

/* 보충기사별  테두리 */
 #supporter_frame{
  
    background-color: #ffffff;
    background-repeat:no-repeat;
    box-shadow: 0 10px 10px 10px rgba(102, 102, 102, 0.2), 0 16px 10px 0 rgba(0,0,0,0.19); 

    border-radius:10px;
    height:90px;
    width: 320px;
    margin-left: 2%;
    margin-bottom: 2%;
    padding-top :14%;
    padding-right :11%;

    font-size: 20px;
    font-family:"Nanum Gothic";
    color:#0064c8;
    font-weight: bold;
 
}
 #supprter_frame_2{
  margin-top: -10%;

 }
  /* 작업지시서 버튼 */
  .work_order_button{
    position: relative;
    width: 130px;
    height: 40px;
    padding-top: 3%;
    border-radius:10px;
    background-color: #ffffff;
    
    border: none;
    text-align: center;
    cursor: pointer;
    overflow: hidden;
    -webkit-transition-duration: 0.4s;
    transition-duration: 0.4s;
    box-shadow: 0 10px 10px 10px rgba(102, 102, 102, 0.2), 0 16px 10px 0 rgba(0,0,0,0.19); 

  }


  .work_order_button:focus {
  outline: none;
  border: 3px solid #0064c8;
  
	background-color: #f6faff;
  color: #0064c8;
  font-weight: bold;
}

.work_order_button:after {
			content: "";
			background-color: #f6faff;
			display: block;
			position: absolute;
			top: 70px;
			left: 0;
			padding-top: 200%;
			padding-left: 300%;
			margin-left: 0;
			margin-top: -120%;
			opacity: 0;
			transition: all 0.8s;
 }

 .work_order_button:active::after {
			padding: 0px;
			margin: 0px;
			opacity: 1;
			transition: all 0s
      
 }
 .work_order_button:hover { 
   text-shadow: 0 10px 10px 10px rgba(102, 102, 102, 0.2), 0 16px 10px 0 rgba(0,0,0,0.19); 
   box-shadow: 0 10px 10px 10px rgba(102, 102, 102, 0.2), 0 16px 10px 0 rgba(0,0,0,0.19); }

 #work_order_font{
    text-decoration: bold;
    font-size: 17px;
    font-family:"Nanum Gothic";
    color:#0064c8;
  }


.contentsRowStyle {
  display: grid;
  grid-template-rows: 0.4fr 0.6fr;
  height: 460px;
  background-color: #FAFAFA;
}

.productStyle {
  display: grid;
  grid-template-columns: 0.7fr 0.3fr;
}

table.jobOrderTableStyle td {
  width: 100px;
  height: 130px;
  border-bottom: none;
  border-top: none;
  border-left: none;
  border-right: none;
}

.emptyDivStyle {
  background-image: url("/images/management/blue_background.png");
  background-size: 100% 100%;
  background-repeat: no-repeat;
  border-radius: 15px;
  width:100%;
  height: 100%;
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

.blueFontStyle{
  color: #1565C0;
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
  height: 100%;
  border-radius: 15px;
}

.divRowHalf {
  display: grid;
  grid-template-rows: 0.5fr 0.5fr;
}

.divColumnHalf {
  display: grid;
  grid-template-columns: 0.5fr 0.5fr;
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
</style>