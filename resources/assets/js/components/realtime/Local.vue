<template>
  <div id="allDivision" >  
    <div style="text-align:left;font-family:'Dosis';" >
    <h2><strong>　　Real time Management </strong></h2>
    </div>
    <div id="tab_container">
        <input id="select1" name="sex" type="radio" checked>
        <button id="btn1"><label for="select1" class="men">지역별</label></button>
        <input id="select2" name="sex" type="radio">
        <button id="btn2"><label for="select2" class="women">보충기사별</label></button>

        <!-- ****************** Local list ****************** --> 
        <div class="page1" id="page_1">
          <br />
    
          <button id="localBtn" href="#" @click="SouthKorea(127.826836,36.684272)">전국</button>
          <br /><br />
          <div v-for="(item_1, key, index) in itemList" :key="index">
            <div v-if="item_1.text=='서울특별시'">
             <button id="localBtn" href="#" v-b-toggle.seoul  @click="NationWideEmit(item_1.latitude,item_1.longitude)" >{{item_1.text}}</button>
            </div>
            <div v-if="item_1.text=='대구광역시'">
             <button id="localBtn" href="#" v-b-toggle.daegu  @click="NationWideEmit(item_1.latitude,item_1.longitude)" >{{item_1.text}}</button>
            </div>
            <div v-if="item_1.text!='대구광역시'&&item_1.text!='서울특별시'">
             <button id="localBtn" href="#" v-b-toggle.index  @click="NationWideEmit(item_1.latitude,item_1.longitude)" >{{item_1.text}}</button>
            </div>
          <br />
          <div v-if="item_1.text=='서울특별시'">
              <b-collapse id="seoul">
                  <b-card style="max-width: 27em;">
                    <v-btn outline color="indigo"  class="subLocalBtnFrame"   @click="localEmit(item_2.longitude,item_2.latitude) " v-for="item_2 in item_1.children" :key="item_2.no">  <tr id="subLocalButton">{{item_2.name}}　 </tr>
                      <Br />
                      <tr >
                        <td id="subLocalButton_1" v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==1)">{{item_3.count}}</td><td id="subLocalButton_2" v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==0)">/{{item_3.count}}</td>
                      </tr></v-btn>
                  </b-card>
                </b-collapse>
           </div>
           <div v-if="item_1.text=='대구광역시'">
              <b-collapse id="daegu">
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
                  </v-avatar> {{item.supplementer}}　<button class="work_order_button" @click="orderList(item.supplementer)"><p id="work_order_font">작업지시서</p></button>
                </div>
           <v-dialog v-model="orderJobModal" fullscreen>
            <v-card flat>
              <div id="spOrderListCutDiv">
                <div id="spOrderListHeader">                        
                  <div>
                    <v-btn outline color="indigo" @click="dateChange(-1)" style="width: 20%; height: 20%;">◀</v-btn>
                    <v-btn dark @click.native.stop="dialog = true" style="width: 20%; height: 20%;">{{today}}</v-btn>
                    <v-btn outline color="indigo" @click="dateChange(1)" style="width: 20%; height: 20%;">▶</v-btn>
                    <!-- 달력 띄우는 모달창 -->
                     <v-dialog v-model="dialog" max-width="400">
                      <v-card>
                        <v-date-picker color="green lighten-1" v-model="pickerDate" :landscape="landscape" :reactive="reactive"></v-date-picker>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn color="green darken-1" flat="flat" @click.native="dialog = false" >취소</v-btn>
                          <v-btn color="green darken-1" flat="flat" @click.native="dialog = false" @click="chooseDateChange()">확인</v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog> 
                    <!-- 달력 띄우는 모달창 -->
                   </div>
                  <div>
                    <h3>작업지시서</h3>
                  </div>
                  <div>
                    <h3 align="right">보충기사: {{spName}}</h3>
                  </div>
                </div>
                <div>
                  <br>
                  <table class="blueTable">
                    <tbody v-for="count in allProductTableRepeationCount" :key="count.index">
                      <tr>
                        <th>음료명</th>
                        <th v-for="product in allProductCountArrCut[count - 1]" :key="product.index">
                          {{product.productName}}
                        </th>
                      </tr>
                      <tr>
                        <th>필요량</th>
                        <td v-for="product in allProductCountArrCut[count - 1]" :key="product.index">
                          {{product.productCount}}
                        </td>
                      </tr>
                    </tbody>
                    <tbody>
                      <tr>
                        <th>자판기수</th><td colspan="2">{{allVDCount}}</td>
                        <th>제품수</th><td colspan="2">{{allPDCount}}</td>
                        <th>총량</th><td colspan="2">{{allCount}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <br>
                  <table class="blueTable" v-if="allVDCount > 0">
                    <thead>
                      <th>자판기이름</th>
                      <th colspan="2">라인1</th><th colspan="2">라인2</th><th colspan="2">라인3</th><th colspan="2">라인4</th>
                      <th colspan="2">라인5</th><th colspan="2">라인6</th><th colspan="2">라인7</th><th colspan="2">라인8</th>
                      <th>비고</th>
                    </thead>
                    <tbody>
                      <tr v-for="vending in sameVDArr" :key="vending.index">
                        <td v-if="vending.orderNote == ''">{{vending.vd_name}}</td>
                        <td v-else style="background-color: aqua;">{{vending.vd_name}}</td>

                        <td v-if="vending.lineAndProduct[0].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[0].productName}}</td>
                        <td v-else>{{vending.lineAndProduct[0].productName}}</td>
                        <td v-if="vending.lineAndProduct[0].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[0].sp_val}}</td>
                        <td v-else>{{vending.lineAndProduct[0].sp_val}}</td>

                        <td v-if="vending.lineAndProduct[1].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[1].productName}}</td>
                        <td v-else>{{vending.lineAndProduct[1].productName}}</td>
                        <td v-if="vending.lineAndProduct[1].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[1].sp_val}}</td>
                        <td v-else>{{vending.lineAndProduct[1].sp_val}}</td>

                        <td v-if="vending.lineAndProduct[2].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[2].productName}}</td>
                        <td v-else>{{vending.lineAndProduct[2].productName}}</td>
                        <td v-if="vending.lineAndProduct[2].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[2].sp_val}}</td>
                        <td v-else>{{vending.lineAndProduct[2].sp_val}}</td>

                        <td v-if="vending.lineAndProduct[3].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[3].productName}}</td>
                        <td v-else>{{vending.lineAndProduct[3].productName}}</td>
                        <td v-if="vending.lineAndProduct[3].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[3].sp_val}}</td>
                        <td v-else>{{vending.lineAndProduct[3].sp_val}}</td>

                        <td v-if="vending.lineAndProduct[4].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[4].productName}}</td>
                        <td v-else>{{vending.lineAndProduct[4].productName}}</td>
                        <td v-if="vending.lineAndProduct[4].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[4].sp_val}}</td>
                        <td v-else>{{vending.lineAndProduct[4].sp_val}}</td>

                        <td v-if="vending.lineAndProduct[5].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[5].productName}}</td>
                        <td v-else>{{vending.lineAndProduct[5].productName}}</td>
                        <td v-if="vending.lineAndProduct[5].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[5].sp_val}}</td>
                        <td v-else>{{vending.lineAndProduct[5].sp_val}}</td>

                        <td v-if="vending.lineAndProduct[6].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[6].productName}}</td>
                        <td v-else>{{vending.lineAndProduct[6].productName}}</td>
                        <td v-if="vending.lineAndProduct[6].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[6].sp_val}}</td>
                        <td v-else>{{vending.lineAndProduct[6].sp_val}}</td>

                        <td v-if="vending.lineAndProduct[7].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[7].productName}}</td>
                        <td v-else>{{vending.lineAndProduct[7].productName}}</td>
                        <td v-if="vending.lineAndProduct[7].sp_val >= 27" style="background-color: yellow;">{{vending.lineAndProduct[7].sp_val}}</td>
                        <td v-else>{{vending.lineAndProduct[7].sp_val}}</td>
                        
                        <td v-if="saveToday == today" @click="createNote(vending.vd_name, vending.vd_id)">{{vending.orderNote}}</td>
                        <td v-else>{{vending.orderNote}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="green darken-1" flat="flat" @click.native="orderJobModal = false">확인</v-btn>
                    </v-card-actions>
                  <v-dialog v-model="orderJobDialog" max-width="520" max-height="300">
                    <v-card>
                      <v-card-title>
                        <h2>작업 지시창</h2>
                      </v-card-title>
                      <v-card-text>
                        <v-card-text>
                          ● 현재 자판기 : {{jobOrderVDName}}
                          <v-spacer></v-spacer>
                          ● 보충 기사 : {{spName}}
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
                   </div>
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
    // watch를 통해 보충기사 및 날짜에 변화가 있을 경우 해당 메서드로 오게 된다.
    joborderRenew(){
      // 보충기사가 클릭 됬는지 확인하는 if문
      if(this.spName){
        //보충기사의 그날 작업 지시서를 http통신을 통해 DB에서 가져온다.
        this.axios.get("/management/jobOrder/" + this.spName + "/" + this.today)
        .then((response) => {
          this.allProductCountArr = [];
          this.sameVDArr = [];
          this.allCount = 0;

          for(var i = 0; i < response.data.length; i++) {
            var productObj = {
              productName: "",
              productCount: 0
            }

            productObj.productName = response.data[i].drink_name;
            productObj.productCount = response.data[i].sp_val;
            this.allCount += productObj.productCount;

            var is_nameSame = false;

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
                this.sameVDArr[j].lineAndProduct.push({lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val});
                
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
                this.sameVDArr.push({vd_id: response.data[i].vd_id, vd_name: response.data[i].vd_name, orderNote: response.data[i].note, lineAndProduct: [{lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val}]});
              }
              else {
                this.sameVDArr.push({vd_id: response.data[i].vd_id, vd_name: response.data[i].vd_name, orderNote: "", lineAndProduct: [{lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val}]});
              }
            }
          }

          this.allProductTableRepeationCount = Math.ceil(this.allProductCountArr.length / 8);
          this.allProductCountArrCut = [];
          var cut = 0;

          for (var i = 0; i < this.allProductTableRepeationCount; i++) {
            this.allProductCountArrCut[i] = [];

            for (var j = cut; j < this.allProductCountArr.length; j++) {
              if (j % 8 != 0 || j == cut) {
                this.allProductCountArrCut[i].push(this.allProductCountArr[j]);
              }
              else {
                cut = j;
                break;
              }
            }
          }

          this.allVDCount = this.sameVDArr.length;
          this.allPDCount = this.allProductCountArr.length;
        });  
      }
    },
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
          this.allProductCountArr = [];
          this.sameVDArr = [];
          this.allCount = 0;

          for(var i = 0; i < response.data.length; i++) {
            var productObj = {
            productName: "",
            productCount: 0
          }

          productObj.productName = response.data[i].drink_name;
          productObj.productCount = response.data[i].sp_val;
          this.allCount += productObj.productCount;

          var is_nameSame = false;

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
              this.sameVDArr[j].lineAndProduct.push({lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val});
              
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
              this.sameVDArr.push({vd_id: response.data[i].vd_id, vd_name: response.data[i].vd_name, orderNote: response.data[i].note, lineAndProduct: [{lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val}]});
            }
            else {
              this.sameVDArr.push({vd_id: response.data[i].vd_id, vd_name: response.data[i].vd_name, orderNote: "", lineAndProduct: [{lineNum: response.data[i].drink_line, productName: pdName, sp_val: response.data[i].sp_val}]});
            }
          }
        }

        this.allProductTableRepeationCount = Math.ceil(this.allProductCountArr.length / 8);
        this.allProductCountArrCut = [];
        var cut = 0;

        for (var i = 0; i < this.allProductTableRepeationCount; i++) {
          this.allProductCountArrCut[i] = [];

          for (var j = cut; j < this.allProductCountArr.length; j++) {
            if (j % 8 != 0 || j == cut) {
              this.allProductCountArrCut[i].push(this.allProductCountArr[j]);
            }
            else {
              cut = j;
              break;
            }
          }
        }

        this.allVDCount = this.sameVDArr.length;
        this.allPDCount = this.allProductCountArr.length;
      });       
    },

    //<-----------------National axios --------------------> 
    NationEmit(response){
      var response = response;
      var count =0;
      EventBus.$emit('NationEvent',36.684272,127.826836,response,count); 
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
    SouthKorea:function(arg1,arg2){
      EventBus.$emit('SouthKorea',arg1,arg2);
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
      this.SouthKorea();
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
margin-top:10px;
margin-right:1px;
margin-bottom:10px; 
margin-left:280px;
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
    height: 700px;
    margin-top: 12px;
    background-image: url("/images/realtime/left_frame.png");
  /*   background-repeat: no-repeat;*/
    background-position: center; 
    background-size: 370px 700px;
    
}

/* 지역별 조회 배경 설정 */
#page_2{
    width:  400px;
    height: 700px;
    margin-top: 15px;
    background-image: url("/images/realtime/left_frame.png");
  /*  background-repeat: no-repeat;*/
    background-position: center; 
    background-size: 370px 700px;
    
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
/* ------------------------- 버튼1- 지역별 조회 설정 ----------------------*/
#btn1 {
    position: relative;
    border: 3px solid  #0064c8;
    border-radius:10px;
    font-size: 21px;
    color: #FFFFFF;
    padding-top:5px;
    padding-right:38%;
    width: 177px;
    height: 55px;
    text-align: center;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    text-decoration: none;
    overflow: hidden;
    cursor: pointer; 
    font-family:"Nanum Gothic";
    margin-top: 5%;
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
    width: 177px;
    height: 55px;
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
    height: 70px;
    width: 150px;
    
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
</style>