<template>
    <div>
      <!--*******************  자판기 아이콘 클릭 전 ************************-->
      <div id="background" v-if="itemList==0">
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
         <img src="images/logo.png"  width="300px">
         <h4>Click on the vending machine icon in Google Maps</h4>
      </div>  
      <!--*******************  자판기 아이콘 클릭 후 ************************-->
    <div id="background" v-if="itemList!=0" > 
      <br /> <br /><br /><br />
     <v-card>
      <br /><br />
      <div v-for="(item, index) in itemList_v" :key="index">   
      <h1><b-badge variant="dark">{{item.name}}</b-badge></h1>
      <br /><br />
      </div>  
        <!-- 음료재고 -->
      <h2><b-badge variant="light">음료 재고</b-badge>
      <v-btn outline fab color="grey darken-1" @click="refresh()">
      <v-icon dark>autorenew</v-icon>
      </v-btn></h2>
       <table width="330"  align="center">
        <tr style=" height:80px; width:100px; text-align:center;vertical-align:middle ">
        <td id="no1" v-for="(item, index) in itemList" :key="index" v-if="(index<4)">
        <img v-bind:src="item.drink_img_path" style=" height:70px; width:30px;  "></td>
        </tr>
        <tr style=" height:10px; text-align:center; vertical-align:middle">
        <td id="no2" v-for="(item, index) in itemList" :key="index" v-if="(index<4)">
        <h3><b-badge v-if="(item.stock<9)" variant="danger" class="blink" >{{item.stock}}/35</b-badge>
        <b-badge v-if="((item.stock>=9)&&(item.stock<=34))" variant="secondary" >{{item.stock}}/35</b-badge>
        <b-badge v-if="(item.stock==35)" variant="success" class="blink2" >{{item.stock}}/35</b-badge>
        </h3></td>
        </tr> 
        <tr style="height:80px; width:100px; text-align:center;vertical-align:middle">
        <td id="no3" v-for="(item, index) in itemList" :key="index" v-if="(index>=4)&&(index<8)">
        <img v-bind:src="item.drink_img_path" style=" height:70px; width:30px;  "></td>
        </tr>
        <tr style="height:10px; text-align:center; vertical-align:middle">
        <td id="no4" v-for="(item, index) in itemList" :key="index" v-if="((index>=4)&&(index<8))">
        <h3><b-badge v-if="(item.stock<9)" variant="danger" class="blink" >{{item.stock}}/35</b-badge>
        <b-badge v-if="((item.stock>=9)&&(item.stock<=34))" variant="secondary">{{item.stock}}/35</b-badge>
        <b-badge v-if="(item.stock==35)" variant="success" class="blink2" >{{item.stock}}/35</b-badge>
        </h3></td>
        </tr> 
      </table> 
      <br />
       <!-- 동전 잔고 -->
      <div>
        <h2><b-badge variant="light">동전 잔고</b-badge></h2>
        </div>  
        <br />
        <div v-for="(item, index) in itemList_c" :key="index">
        <v-layout>
        <v-flex xs1></v-flex>  
        <v-card><v-card-text> 1000원   {{item.won1000}}개 </v-card-text></v-card>
        <v-card><v-card-text> 500원   {{item.won500}}개 </v-card-text></v-card>
        <v-card><v-card-text> 100원  {{item.won100}}개 </v-card-text></v-card>
        <v-card><v-card-text> 총  합 {{item.sum}}원 </v-card-text></v-card>
        </v-layout>   
      </div>
     <br /><br />
     <!-- 더보기 버튼  -->
     <v-menu
      :close-on-content-click="false"
      :nudge-width="150"
      v-model="menu"
      offset-x
    >
    <v-btn slot="activator" color="cyan darken-1" dark>
      <v-icon dark>dehaze</v-icon>　더보기　
    </v-btn>
    <v-card>
      <v-list>
        <v-list-tile avatar>
            <div v-for="(item, index) in itemList_v" :key="index">
          <v-list-tile-content>
            <br /><br /><br />
            <v-card-title > ● 보충기사 : {{item.supplementer}} </v-card-title>
            <v-card-title > ● 지역 : {{item.name}}</v-card-title>
          </v-list-tile-content>
          </div>
          <v-spacer></v-spacer>
          <v-list-tile-action>
            <v-btn
              :class="fav ? 'red--text' : ''"
              icon
              @click="fav = !fav"
            >
              <v-icon>favorite</v-icon>
            </v-btn>
          </v-list-tile-action>
        </v-list-tile>
    </v-list>
  <br />
    <v-list>
         <br />
     <div id="mainImageButtonDiv">
         <div class="partInfo"  @click.stop="dialog = true" @click="sale_history()">
                <img src="/images/document2.png" class="icon">
                <h6>판매 내역</h6>
            </div>
            <div class="partInfo" @click.stop="dialog2 = true" style="margin-left:3%;">
                <img src="/images/document.png" class="icon">
                <h6>작업지시 작성</h6>
            </div>
         <router-link :to="route_analysis" id="analyst" >
            <div class="partInfo" >
                <img src="/images/mainPageImage/analysis.png" class="icon">
                <h6>분석 보기</h6>
            </div>
        </router-link>
        <router-link :to="route_management" id="management">
            <div class="partInfo">
                <img src="/images/mainPageImage/vending-machine.png"  class="icon">
                <h6>자판기 관리</h6>
            </div>
        </router-link>         
        <!-- ***************판매내역 모달창********************* -->
             <v-layout row justify-center>
            <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition" :overlay="false">
             <v-card  color="light-blue darken-1">
                <v-toolbar >
                  <v-btn icon @click.native="dialog = false" >
                    <v-icon>close</v-icon>
                  </v-btn>
                  <v-toolbar-title>판매내역</v-toolbar-title>
                  <v-spacer></v-spacer>
                  <v-toolbar-items>
                    <v-btn color="white" flat @click.native="dialog = false">Cancel</v-btn>
                  </v-toolbar-items>
                </v-toolbar>
                <v-card>
                   <div v-for="(item, index) in itemList_v" :key="index">
                    <v-card-title > ● 현 자판기 명 : {{item.name}} </v-card-title>
                    <v-card-title >  ● 보충기사 : {{item.supplementer}}</v-card-title>
                   </div>
                </v-card>
                <v-card>
                  <!-- data table pagenation -->
                   <v-data-table
                      :headers="headers"
                      :items="items"
                      :loading="true"
                      class="elevation-1"
                    >
                      <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                      <template slot="items" slot-scope="props">
                        <td>{{ props.item.sell_date }}</td>
                        <td class="text-xs-right">{{ props.item.name }}</td>
                        <td class="text-xs-right">{{ props.item.price }}</td>
                        <td class="text-xs-right">{{ props.item.dName }}</td>
                      </template>
                    </v-data-table>
                </v-card>
               <br />
             </v-card>
            </v-dialog>     
          </v-layout>
          <!-- *************** 작업 지시 모달창 *************** -->
          <v-layout>
              <v-dialog v-model="dialog2" max-width="520" max-height="300">
                  <v-card>
                  <v-card-title>
                  <h2>　　　　　　작업 지시창</h2>
                  </v-card-title>
                  <v-card-text>
                     <div v-for="(item, index) in itemList_v" :key="index">
                    <v-card-text>● 현재 자판기 : {{item.name}}  <v-spacer></v-spacer>
                  ● 보충 기사 : {{item.supplementer}} </v-card-text>
                     </div>
                  <v-select
                      :items="select"
                      label="Please Select List"
                      item-value="text"
                      v-model ="selectedItem"
                    >
                  </v-select>
                    <div v-if="selectedItem=='기타'">
                    <v-text-field  label="작업지시를 적어주세요" v-model="selectedItem_etc"></v-text-field>
                </div> </v-card-text>
              
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="green darken-1" flat="flat" @click.native="dialog2 = false">cancel</v-btn>
                  <v-btn color="green darken-1" flat="flat" @click.native="dialog2 = submit(selectedItem,selectedItem_etc)">submit</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
        </v-layout>
       </div>
      </v-list>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn flat @click="menu = false">Cancel</v-btn>
      </v-card-actions>
      </v-card>
    </v-menu>
     </v-card>
</div>
    </div>
</template>

<script>
let array = [];
let obj =[];
let obj_c =[];
let temp;
let obj_v=[];

//import 'vuetify/dist/vuetify.min.css'
import { EventBus } from '../../app.js';
export default {
  data() {
    return {
    dialog: false,
    dialog2: false,
    fav: true,
    menu: false,
    message: false,
    hints: true,
    hover:true,
    itemList:[],
    itemList_c:[],
    itemList_v:[],
    items:[], /* 판매내역 */
      /* 판매내역 테이블 헤더 */
    headers: [
      {
        text: '날짜',
        align: 'left',
        value: 'name'
      },
      { text: '자판기명',align: 'center', value: 'sell_date' },
      { text: '가격',align: 'center', value: 'price' },
      { text: '상품명', align: 'center',value: 'dName' }
     ],
      vending_name:'', /* 모달창 자판기 이름 */
      vending_manager:'', /* 모달창 자판기 매니저 이름 */
      vending_id : '',/* 모달창 자판기 아이디 */
      select: [  /* 모달창 선택지 */
      { text: '긴급! 음료 재고 부족' },
      { text: '긴급! 잔고 부족' },
      { text: '축제 기간 ( 재고잔고 확인 요망 )' },
      { text: '기계 이상 및 고장' },
      { text: '기타' }
      ],
      selectedItem:'',
      selectedItem_etc:'',
      goToId:'',
      route_analysis:'', /* 해당 분석 페이지 */
      route_management:'' 
     
    }
  },
  created: function() {

   //<-------------------- 자판기 아이디 EventBus ------------->
    EventBus.$on('VendingId',(arg1,arg2,arg3,arg4) => {

    this.vendingId=arg1;
    this.vendingName=arg2;
    this.vendingSupplementer=arg3;
    this.vendingAddress=arg4;
    this.vending_id=this.vendingId;
   if(obj_v==[]){
     obj_v.push({id: this.vendingId,name:this.vendingName,supplementer:this.vendingSupplementer,address: this.vendingAddress});
    this.itemList_v=obj_v;
    
    }else{
       obj_v.splice(0,2);
        obj_v.push({name:this.vendingName,supplementer:this.vendingSupplementer,address: this.vendingAddress});
        this.itemList_v=obj_v;
   
    }
  
   
    temp=this.vendingId;
    this.route_analysis = "/analysis?id="+temp;
    this.route_management ="/management?id="+temp;
    this.goToId = temp;
    //<----------------- 물건 재고 axios ----------------------->
    this.axios.get("/realtime/getVdStock/"+this.vendingId)
       .then((response) =>{
           let key;
            response = response.data; 
           if(obj==[]){
             for(key in response){
                array[key] = {
                 line : response[key].line,
                 stock  : response[key].stock,
                 drink_img_path : response[key].drink_img_path
                }
                obj.push({line:array[key].line,
                         stock:array[key].stock,
                         drink_img_path:array[key].drink_img_path});
              }
           }
           else{
             obj.splice(0,8);
              for(key in response){
                array[key] = {
                 line : response[key].line,
                 stock  : response[key].stock,
                 drink_img_path : response[key].drink_img_path
                }
                obj.push({line:array[key].line,
                         stock:array[key].stock,
                         drink_img_path:array[key].drink_img_path});
              } 
           }   
         })
       .catch(function (error) {
         console.log(error);
       });
       
       
    //<----------------- 동전 잔고 axios ----------------------->
    this.axios.get("/realtime/coinStock/"+this.vendingId)
       .then((response) =>{
           let key;
            response = response.data; 
           if(obj_c==[]){
             for(key in response){
                array[key] = {
                 won1000 : response[key].won1000,
                 won500  : response[key].won500,
                 won100 : response[key].won100,
                 sum : response[key].sum
                 
                }
                obj_c.push({won1000:array[key].won1000,
                         won500:array[key].won500,
                         won100:array[key].won100,
                         sum:array[key].sum});
              }
           }
           else{
             obj_c.splice(0,8);
              for(key in response){
                array[key] = {
                 won1000 : response[key].won1000,
                 won500  : response[key].won500,
                 won100 : response[key].won100,
                 sum : response[key].sum
                }
                 obj_c.push({won1000:array[key].won1000,
                         won500:array[key].won500,
                         won100:array[key].won100,
                         sum:array[key].sum});
              } 
           }   
         })
       .catch(function (error) {
         console.log(error);
       });

        this.itemList = obj;
        this.itemList_c=obj_c;
   });
  },
  methods: {
    //<----------------- refresh methods --------------------->
       refresh(){
         //<-----------------refresh 물건 재고 axios ----------------------->
        this.axios.get("/realtime/getVdStock/"+temp)
       .then((response) =>{
           let key;
            response = response.data; 
           if(obj==[]){
             for(key in response){
                array[key] = {
                 line : response[key].line,
                 stock  : response[key].stock,
                 drink_img_path : response[key].drink_img_path
                }
                obj.push({line:array[key].line,
                         stock:array[key].stock,
                         drink_img_path:array[key].drink_img_path});
              }
           }
           else{
             obj.splice(0,8);
              for(key in response){
                array[key] = {
                 line : response[key].line,
                 stock  : response[key].stock,
                 drink_img_path : response[key].drink_img_path
                }
                obj.push({line:array[key].line,
                         stock:array[key].stock,
                         drink_img_path:array[key].drink_img_path});
              } 
           }   
         })
       .catch(function (error) {
         console.log(error);
       });
          //<----------------- refresh 동전 잔고 axios ----------------------->
    this.axios.get("/realtime/coinStock/"+this.vendingId)
       .then((response) =>{
           let key;
            response = response.data; 
           if(obj_c==[]){
             for(key in response){
                array[key] = {
                 won1000 : response[key].won1000,
                 won500  : response[key].won500,
                 won100 : response[key].won100,
                 sum : response[key].sum
                 
                }
                obj_c.push({won1000:array[key].won1000,
                         won500:array[key].won500,
                         won100:array[key].won100,
                         sum:array[key].sum});
              }
           }
           else{
             obj_c.splice(0,8);
              for(key in response){
                array[key] = {
                 won1000 : response[key].won1000,
                 won500  : response[key].won500,
                 won100 : response[key].won100,
                 sum : response[key].sum
                }
                 obj_c.push({won1000:array[key].won1000,
                         won500:array[key].won500,
                         won100:array[key].won100,
                         sum:array[key].sum});
              } 
           }   
         })
       .catch(function (error) {
         console.log(error);
       });
        
        this.itemList = obj; 
        this.itemList_c=obj_c;
        
       EventBus.$emit('selectId',this.vendingId); 
       
       } ,
      
          //<----------------- sale history axios ----------------------->
       sale_history :function(){
      this.axios.get("realtime/getSellDataList").then((response) =>{
        response = response.data;
        var obj_sell =[];
        if(obj==[]){ 
           for(let key in response){
         array[key] = {
            name : response[key].vd_name,
            sell_date : response[key].sell_date,
            price : response[key].sell_price,
            dName : response[key].drink_name 
          }
          obj_sell.push({
            isActive: true,
            name:array[key].name,
            sell_date:array[key].sell_date,
            price:array[key].price,
            dName:array[key].dName
          }); 
        }}
        else{
           obj_sell.splice(0,);
           obj_sell=[];
        for(let key in response){
          array[key] = {
            name : response[key].vd_name,
            sell_date : response[key].sell_date,
            price : response[key].sell_price,
            dName : response[key].drink_name 
          }
          obj_sell.push({
            isActive: true,
            name:array[key].name,
            sell_date:array[key].sell_date,
            price:array[key].price,
            dName:array[key].dName
          });
        }
        }       
        this.items = obj_sell;                   
      })
    },
      /* <---------------------모달창 내용 db전송 --------------------> */
    submit(selectedItem,selectedItem_etc){
        // console.log(selectedItem_etc);
         console.log(this.vending_id);

      if(selectedItem_etc==''){
        const formData = new FormData();
        formData.append('ven_id',this.vending_id);
        formData.append('ven_note',selectedItem);
       
         this.axios.post("realtime/addJobOrderVerTwo", formData).then((Response)=>{
            console.log(Response.data);
        }).catch(ex=>{
          console.log(ex);
        }) 
       } 
        else if(selectedItem_etc!=''){
        const formData = new FormData();
        formData.append('ven_id',this.vending_id);
        formData.append('ven_note',selectedItem_etc);
       
         this.axios.post("realtime/addJobOrderVerTwo", formData).then((Response)=>{
            console.log(Response.data);
        }).catch(ex=>{
          console.log(ex);
        }) 
       } 
        this.dialog = false
    },
  }
}
</script>

<style>
  #background {
      background-repeat: no-repeat;
      margin: 23px 0px 0px 0px;     
      width: 100%;
      height: 350;
       }
  #no1{
    background-color: #F2F2F2;
  }
  #no2{
    background-color: #6E6E6E;
  }
  #no3{
    background-color: #F2F2F2;
  }
  #no4{
    background-color: #6E6E6E;
  }


.icon {
  width :  30%;
  height:30% ;
  margin-top: 10%;
  margin-bottom: 10%;
}

.partInfo:hover .icon{
    opacity: 0.3
}
.partInfo {
  width: 15%;
  height: 90px;
  margin-left: 4%;
  margin-top: 3%;
  margin-bottom: 3%;
  margin-right: 1%;
  background-color: rgba(20, 124, 136, 0.72);
  display: inline-block;
  overflow-x:hidden;
  overflow-y:hidden;
  color: rgb(255, 255, 255);
  border-radius: 20px;
  text-align: center;
}

/* 매진임박일 경우 숫자 깜빡거림 설정 */
.blink{
  color:rgb(255, 255, 255);
  font-size:20px;
  animation:blink_animation .6s 7;
}
@keyframes blink_animation {
    50%   {color:rgb(255, 255, 255);}      
    100% {color:rgb(255, 0, 0)}
}

/* 완충일 경우 숫자 깜빡거림 설정 */
.blink2{
  color:rgb(255, 255, 255);
  font-size:20px;
  animation:blink_animation2 .6s 2;
}
@keyframes blink_animation2 {
    50%   {color:rgb(255, 255, 255);}      
    100% {color:rgb(38, 202, 38)}
}

</style>