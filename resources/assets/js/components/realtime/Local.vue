<template>
  <div>  
    <h2><b-badge variant="light">Index</b-badge></h2>
    <div class="tab_container">
    <!-- ****************** Local list ****************** --> 
    <v-tabs v-model="active" icons-and-text centered dark  color="cyan darken-1">
      <v-tab  v-for="choose in spOrVdCard" :key="choose" ripple > {{ choose }} 조회</v-tab>
      <v-tab-item v-for="choose in spOrVdCard" :key="choose">
        <v-card flat v-if="choose=='지역별'">
          <b-tabs pills card vertical>
           <b-tab title="전국" data-toggle="tab" href="#" @click="SouthKorea(127.826836,36.684272)"></b-tab>
            <b-tab :title="item_1.text" data-toggle="tab" href="#"  @click="NationWideEmit(item_1.latitude,item_1.longitude)" v-for="(item_1, key, index) in itemList" :key="index">
            <div class ="box" v-for="item_2 in item_1.children" :key="item_2.no">
              <v-card tile>
               <br />
                <v-layout row wrap><v-flex xs1></v-flex>
                  <v-card hover>
                     <v-chip label @click="localEmit(item_2.longitude,item_2.latitude) " flat  outline color="cyan darken-4" >{{item_2.name}}</v-chip>  
                  </v-card>
                  <v-flex xs1></v-flex>
                  <v-card  tile hover>
                   <div v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==1)">
                      <v-layout row wrap><v-card tile color="red lighten-4">　 매진임박 :  {{item_3.count}}대 　</v-card>
                       </v-layout>
                   </div>
                   <div v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==3)">
                      <v-layout row wrap>
                         <v-layout row wrap><v-flex xs1></v-flex><v-card v-if="item_3.count!=0" tile color="grey lighten-5">　 작업지시 : {{item_3.count}}대　</v-card>
                         </v-layout>
                      </v-layout>
                   </div>
                   <div v-for="item_3 in itemsCount" :key="item_3.no" v-if="(item_2.englishN==item_3.address)&&(item_3.vd_soldout==0)">
                      <v-layout row wrap>
                         <v-layout row wrap><v-flex xs1></v-flex><v-card v-if="item_3.count!=0" tile color="grey lighten-5">　 총 자판기 : {{item_3.count}}대　</v-card>
                         </v-layout>
                      </v-layout>
                   </div>
                   
                 </v-card>
                </v-layout>
               <br />
             </v-card>
           </div>
         </b-tab>
        </b-tabs>
      </v-card>
          <!-- ******************  supplemener list ****************** -->
          <v-card flat v-else-if="choose=='보충기사별'">
            <div v-for="(item,index) in itemList2" :key="item.index" v-if="index>=0">
              <div class="text-xs-center">
                <div  @click="supplementerEmit(item.supplementer)">
                  <v-avatar>
                    <img v-bind:src="item.imgSrc">
                  </v-avatar>
                  {{item.supplementer}}
                </div>
                <v-btn @click="orderList(item.supplementer)">작업지시서</v-btn>
              </div>
            </div>     
          </v-card>
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
        </v-tab-item>
      </v-tabs>
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
/* left div */
#leftList{
          width:100%;
          height: 100%;
          float: left;
}

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
   background: #f6f9fa;
}

h1 {
   color: #ccc;
   text-align: center;
}

a {
  color: rgb(60, 52, 52);
  text-decoration: none;
  outline: none;
}

/*Fun begins*/
.tab_container {
   width: 90%;
   margin: 0 auto;
   padding-top: 0px;
   position: relative;
}

input, section {
  clear: both;
  padding-top: 10px;
  display: none;
}

label {
  font-weight: 700;
  font-size: 18px;
  display: block;
  float: left;
  width: 50%;
  height: 7.5ex;
  padding: 1.5em;
  color: #301b1b;
  cursor: pointer;
  text-decoration: none;
  text-align: center;
  background: #f0f0f0;
}

#tab1:checked ~ #content1,
#tab2:checked ~ #content2 {
  display: block;
  padding: 20px;
  background: #fff;
  color: rgb(27, 15, 15);
  border-bottom: 2px solid #f0f0f0;
}

.tab_container .tab-content p,
.tab_container .tab-content h3 {
  -webkit-animation: fadeInScale 0.7s ease-in-out;
  -moz-animation: fadeInScale 0.7s ease-in-out;
  animation: fadeInScale 0.7s ease-in-out;
}
.tab_container .tab-content h3  {
  text-align: center;
}

.tab_container [id^="tab"]:checked + label {
  background: #fff;
  box-shadow: inset 0 3px rgb(238, 0, 12);
}

.tab_container [id^="tab"]:checked + label .fa {
  color: rgb(184, 38, 38);
}

label .fa {
  font-size: 1.3em;
  margin: 0 0.4em 0 0;
}

/*Media query*/
@media only screen and (max-width: 930px) {
  label span {
    font-size: 14px;
  }
  label .fa {
    font-size: 14px;
  }
}

@media only screen and (max-width: 768px) {
  label span {
    display: none;
  }

  label .fa {
    font-size: 16px;
  }

  .tab_container {
    width: 98%;
  }
}

/*Content Animation*/
@keyframes fadeInScale {
  0% {
     transform: scale(0.9);
     opacity: 0;
  }
  
  100% {
     transform: scale(1);
     opacity: 1;
  }
}
table.blueTable {
  border: 1px solid #1C6EA4;
  background-color: #EEEEEE;
  width: 100%;
  text-align: center;
  border-collapse: collapse;
}
table.blueTable td {
  border: 1px solid #AAAAAA;
  padding: 3px 2px;
  width: 100px;
}
table.blueTable tbody td {
  font-size: 13px;
  width: 100px;
}
table.blueTable thead, table.blueTable th {
  background: #1C6EA4;
  background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
  background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
  background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
  border-bottom: 2px solid #444444;
  font-size: 15px;
  font-weight: bold;
  color: #FFFFFF;
  text-align: center;
  border-left: 2px solid #D0E4F5;
   width: 1000px;
}
table.blueTable thead th:first-child {
  border-left: none;
}
</style>