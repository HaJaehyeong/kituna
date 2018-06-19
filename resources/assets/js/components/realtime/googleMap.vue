<template>
<div> 
  <div id="mapImage">
    <v-layout>    
    <v-flex xs8>   
    </v-flex>
     <v-flex xs6 sm6 class="py-2">    
        <!-- 상단바 버튼  -->
        <v-btn-toggle v-model="icon" id="buttonFont">
              <v-btn flat value="left" @click="googleButton(0)" >
                <v-icon>brightness_1</v-icon><span>　전 체</span>
              </v-btn>
              <v-btn flat value="center" @click="googleButton(2)">
                <v-icon>brightness_3</v-icon><span>　매진</span>
              </v-btn>
              <v-btn flat value="right" @click="googleButton(1)">
                <v-icon>brightness_2</v-icon><span>　매진임박</span>
              </v-btn>
              <v-btn flat value="justify" @click="googleButton(3)" >
                <v-icon>border_color</v-icon><span> 작업지시</span>
              </v-btn>
         </v-btn-toggle>
      </v-flex>
   </v-layout>
   <!-- 구글 맵  -->              
    <gmap-map
      :center="center"
      :zoom="zoom"
      style="width:100%;  height: 400px;"
    >  
    <!-- cluster 적용  -->
    <gmap-cluster :grid-size="gridSize" v-if="clustering" >
     <gmap-info-window :options="infoOptions" 
        :position="infoWindowPos" 
        :opened="infoWinOpen" 
        @closeclick="infoWinOpen=false">
        {{infoContent}}
     </gmap-info-window>
     <gmap-marker 
      v-if="selectTrue==false"
      style="height:300px;width:300px;display:block;"
      :key="index"   
      v-for="(m, index) in markers"
      :position="m.position"
      :clickable="m.clickable"
      :draggable="m.draggable"
       @click="vendingStock(m,index);"
      :icon="m.icon">
     </gmap-marker>
      <gmap-marker 
       v-if="selectTrue==true"
      style="height:300px;width:300px;display:block;"
      :key="index"   
      v-for="(m, index) in markers"
      :position="m.position"
      :clickable="m.clickable"
      :draggable="m.draggable"
       @click="vendingStock_soldOut(m,index);"
      :icon="m.icon">
     </gmap-marker>
  </gmap-cluster>

     <!-- cluster 미적용  -->
    <div v-if="!clustering">
     <gmap-info-window :options="infoOptions" 
       :position="infoWindowPos" 
       :opened="infoWinOpen" 
       @closeclick="infoWinOpen=false">
       {{infoContent}}
     </gmap-info-window>
     <gmap-marker 
      v-if="selectTrue==false"
      style="height:300px;width:300px;display:block;"
      :key="index"   
      v-for="(m, index) in markers"
      :position="m.position"
      :clickable="m.clickable"
      :draggable="m.draggable"
       @click="vendingStock(m,index);"
      :icon="m.icon" >
     </gmap-marker>
      <gmap-marker 
       v-if="selectTrue==true"
      style="height:300px;width:300px;display:block;"
      :key="index"   
      v-for="(m, index) in markers"
      :position="m.position"
      :clickable="m.clickable"
      :draggable="m.draggable"
       @click="vendingStock_soldOut(m,index);"
      :icon="m.icon" >
     </gmap-marker>
    </div>
   </gmap-map>
   </div> 
    <div>
  </div>
</div>

</template>

<script>
import LocalList from '../../LocalList';
import { EventBus } from '../../app.js';

let response ;
let lat;
let lng;
let count =0;

let vendingId ;
let vendingName;
let vendingSupplementer;
let venAddress;
let tempVenId;

export default {
  
  name: "GoogleMap",
  data() {
    return {
      /* <----------------- button setting ------------------> */
        text: 'center',
        icon: 'left',
        toggle_none: null,
        toggle_one: 0,
        toggle_exclusive: 2,
        toggle_multiple: [0, 1, 2],
      /* <----------------- default setting ------------------> */
      longitude :0,
      latitude :0,
      center: {
        lat: 36.323836,
        lng: 127.398077 
      },
       draggable:false,
       zoom:parseFloat(6.8),
       infoContent: '',
       infoWinOpen: false,
       infoOptions: {
            pixelOffset: {
              width: 0,
              height: -30
       }
      },
      infoWindowPos: {
            lat: 0,
            lng: 0
      },
      clustering: true,
      gridSize: 30,
      markers:[],
      marker : null,
      marker_v:null,
      dialog : false,
      selectTrue: false,
      }
  },
  watch : {
    vendingId : function () {
      this.changeVending();
    }
  },
  created: function() {
     //<--------------- 전국 EventBus -------------->
    EventBus.$on('SouthKorea',(arg1,arg2) => {

                arg1 = parseFloat(arg1);
                arg2 = parseFloat(arg2);
                
                this.longitude = arg1;
                this.latitude = arg2;
                
                this.center = {
                  lat : this.latitude,
                  lng : this.longitude
                };
                this.zoom =parseInt(7.5);
   
   });
    //<-------------- 지역 EventBus --------------->
    EventBus.$on('LocalEvent',(arg1,arg2) => {

                arg1 = parseFloat(arg1);
                arg2 = parseFloat(arg2);
                
                this.longitude = arg1;
                this.latitude = arg2;
                
                this.center = {
                  lat : this.latitude,
                  lng : this.longitude
                };
                this.zoom =parseInt(15.5);
   
   });

    //<------------- 전국 각지 EventBus ------------->
    EventBus.$on('NationWideEvent',(arg1, arg2) => {
               
                arg1 = parseFloat(arg1);;
                arg2 = parseFloat(arg2);
               
                this.latitude =arg1 ;
                this.longitude = arg2;

                this.center = {
                  lat : this.latitude,
                  lng : this.longitude
                };
  
                this.zoom = parseInt(11.5);     
    });

    //<--------------- 전국 EventBus ---------------->
    EventBus.$on('NationEvent',(arg1, arg2,arg3,arg4) => {
                var count = arg4;
                count++;

                arg1 = parseFloat(arg1);;
                arg2 = parseFloat(arg2); 
                this.latitude = arg1 ;
                this.longitude = arg2;
                
                response = arg3.data; //db data
                
                
                 this.center = {
                  lat : this.latitude,
                  lng : this.longitude
                };

                this.zoom = parseInt(7);
                this.marker = [];   
                this.data = [];
                this.stock = [];
                
                for(let key in response){
                 //<---------------- marker setting -----------------> 
                 this.marker[key] = {
                 lat : response[key].vd_latitude,
                 lng : response[key].vd_longitude
                 }  
                 
                 //<---------------- datasetting -------------------->
                 this.data[key]={
                  이름:response[key].vd_name  ,
                  보충기사:response[key].vd_supplementer                
                 }

                 //<--------------- vending_stock ------------------>
                 this.stock[key]={
                  id : response[key].vd_id  
                 }
                 //<--------------- input marker ------------------->
                 if(count==1){
                   if(response[key].vd_soldout==0){
                   this.markers.push({position:this.marker[key],
                            icon :"/images/realtime/vending_black.png",
                      });
                   }
                   else if(response[key].vd_soldout==1){
                   this.markers.push({position:this.marker[key],
                            icon :"/images/realtime/vending_red.png",
                   });
                 }
               }
             }      
          });

        //<----------- 보충자 EventBus ------------->
        EventBus.$on('supplementerEvent',(arg1) => {
        this.name = arg1;
        var this_1= this;
       
        this.axios.get("/realtime/listSP/"+this.name)
        .then((response) =>{
        this_1.Supplementer_list(response.data);
        })

        });
        
        //<---------- 선택한 자판기 아이디 ------------->
        EventBus.$on('selectId',(arg1) => {
        vendingId= arg1;
        this.changeVending(vendingId);
        });
       
    },
   
  mounted() {  
  },

  methods: {

   //<----------------- 새로고침 후 자판기 색 변화 --------------------> 
    changeVending(selectVendingId){
     this.axios.get("/realtime/list/all/all")
       .then((response) =>{
           let key;
           response = response.data; 
             for(key in response){
                if((response[key].vd_id==selectVendingId)){
                if(response[key].vd_soldout==0){
                //  console.log("z",this.markers[key].icon);
                  this.markers[key].icon ="/images/realtime/vending_black_yel.png"  
                }
                else if(response[key].vd_soldout==1){
                  
                //  console.log("f",this.markers[key].icon);
                 this.markers[key].icon ="/images/realtime/vending_red_yel.png"  
                }     
                 else if(response[key].vd_soldout==2){
                  
                //  console.log("f",this.markers[key].icon);
                 this.markers[key].icon ="/images/realtime/vending_sold_out.png"  
                }   
                 else if(response[key].vd_soldout==3){
                  
                //  console.log("f",this.markers[key].icon);
                 this.markers[key].icon ="/images/realtime/vending_instruction.png"  
                }   
              }
            }

         
        })
    },
    /*  현재 구글맵 위치  */
    setPlace(place) {
      this.currentPlace = place;
    },
    
   //<----------------- 전체  자판기 정보 전송 --------------------> 
    vendingStock: function (m,index) {
    
    this.selectTrue=false;

    var vendingLat= m.position.lat;
    var vendingLng= m.position.lng;

    this.axios.get("/realtime/list/all/all")
       .then((response) =>{
           let key;
             response = response.data; 
               for(key in response){
                if((response[key].vd_latitude==vendingLat)||(response[key].vd_longitude==vendingLng)){
                  vendingId = response[key].vd_id
                  vendingName=response[key].vd_name
                  vendingSupplementer=response[key].vd_supplementer
                  venAddress=response[key].vd_address
                   
                  this.vending_id =vendingId
               }
            }   
           EventBus.$emit('VendingId',vendingId,vendingName,vendingSupplementer,venAddress); 
          
          
         /*<------------------------ 클릭시 마커 색 변경 -------------------------->*/
             this.marker_color = []; 
             this.marker_color = { 
                lat : vendingLat,
                lng : vendingLng  
             } 
             for(key in response){

                if(response[key].vd_soldout==0){
                  this.markers[key].icon="/images/realtime/vending_black.png"; 
                  if((response[key].vd_latitude==vendingLat)||(response[key].vd_longitude==vendingLng)){
                  this.markers[key].icon ="/images/realtime/vending_black_yel.png"  
                } 

                }
                else if(response[key].vd_soldout==1){
                   this.markers[key].icon="/images/realtime/vending_red.png"; 
                    if((response[key].vd_latitude==vendingLat)||(response[key].vd_longitude==vendingLng)){
                   this.markers[key].icon ="/images/realtime/vending_red_yel.png"  
                 } 
                }
       
 
                if((response[key].vd_latitude==vendingLat)&&(response[key].vd_longitude==vendingLng)){
                
                    // 일반 자판기일 경우 하이라이팅 변화
                      if(response[key].vd_soldout==0){ 
                      
                      this.markers[key].icon="/images/realtime/vending_black.png"; 
                    if((this.markers[key].position.lat==this.marker_color.lat)&&(this.markers[key].position.lng==this.marker_color.lng)){   
                    
                        this.markers[key].icon ="/images/realtime/vending_black_yel.png"
                      }
                    } 
                    // 매진임박 자판기일 경우 하이라이팅 변화
                      else if(response[key].vd_soldout==1){
                     
                      this.markers[key].icon="/images/realtime/vending_red.png"; 
                      if((this.markers[vendingId-1].position.lat==this.marker_color.lat)&&(this.markers[vendingId-1].position.lng==this.marker_color.lng)){   
                          this.markers[vendingId-1].icon ="/images/realtime/vending_red_yel.png"
                       }
                    }  
                }   
            }
        })
       .catch(function (error) {
         console.log(error);
       });   
           
    },
     //<-------------------- 매진임박 자판기 정보 전송 --------------------> 
    vendingStock_soldOut: function (m,index){
    
    var vendingLat= m.position.lat;
    var vendingLng= m.position.lng;

    this.axios.get("realtime/clickAButton/1")
       .then((response) =>{
           let key;
             response = response.data; 
               for(key in response){
                if((response[key].vd_latitude==vendingLat)||(response[key].vd_longitude==vendingLng)){
                  vendingId = response[key].vd_id
                  vendingName=response[key].vd_name
                  vendingSupplementer=response[key].vd_supplementer
                  venAddress=response[key].vd_address
                   
                  this.vending_id =vendingId
                 }
              }  

        EventBus.$emit('VendingId',vendingId,vendingName,vendingSupplementer,venAddress); 
          
         /*<------------------------ 클릭시 마커 색 변경 -------------------------->*/
           
             for(key in response){
               
             this.markers[key].icon="/images/realtime/vending_red.png";
             if((response[key].vd_latitude==vendingLat)||(response[key].vd_longitude==vendingLng)){
               this.markers[key].icon ="/images/realtime/vending_red_yel.png"  
             } 
          }
        })
       .catch(function (error) {
         console.log(error);
       });   
    },

    //<------------- marker setting --------------------> 
    Supplementer_list:function(arg1){

        this.markers = [];
        //console.log(this.markers); 
        this.marker_v = []; 
        this.data2 = [];

        for(let key in arg1){
           //<------------- marker setting --------------------> 
                  this.marker_v[key] = { 
                      lat : arg1[key].vd_latitude,
                      lng : arg1[key].vd_longitude   
                   }

                if(response[key].vd_soldout==0){
                this.markers.push({position:this.marker_v[key],
                        icon :"/images/realtime/vending_black.png",
                  });
                }

                else if(response[key].vd_soldout==1){
                this.markers.push({position:this.marker_v[key],
                        icon :"/images/realtime/vending_red.png",
                  });
                }  
       } 
     },
     
     /* <---------------------모달창 값 설정 --------------------> */
     instruction(){
         this.vending_name=vendingName, 
         this.vending_manager=vendingSupplementer, 
         this.vending_id=vendingId

    },
     /* <---------------------모달창 내용 db전송 --------------------> */
      submit(selectedItem,selectedItem_etc){
         console.log(selectedItem_etc);
       //  console.log(selectedItem);

      if(selectedItem_etc==''){
        const formData = new FormData();
        formData.append('ven_id',this.vending_id);
        formData.append('ven_note',selectedItem);
       
         this.axios.post("management/addJobOrder", formData).then((Response)=>{
            console.log(Response.data);
        }).catch(ex=>{
          console.log(ex);
        }) 
       } 
        else if(selectedItem_etc!=''){
        const formData = new FormData();
        formData.append('ven_id',this.vending_id);
        formData.append('ven_note',selectedItem_etc);
       
         this.axios.post("management/addJobOrder", formData).then((Response)=>{
            console.log(Response.data);
        }).catch(ex=>{
          console.log(ex);
        }) 
       } 
        this.dialog = false
    },
    /* <--------------------- 구글맵 상단바 버튼 --------------------> */
    googleButton(num){
       this.axios.get('realtime/clickAButton/'+num)
        .then((response) =>{
          response = response.data;

         /********** 전체 *********/
        if(num==0){ 
            //일단 값 받아오면 그 값에 따라 아이콘 색 다르게 주기
                this.markers.splice(0,);
                this.selectTrue=false;
   
                //this.zoom = parseInt(7);
                this.marker = [];   
                this.data = [];
                this.stock = [];
                for(let key in response){
                 //<------------- marker setting --------------------> 
                 this.marker[key] = {
                 lat : response[key].vd_latitude,
                 lng : response[key].vd_longitude
                 }  
            
                 //<--------------- input marker -------------------->
             
               
                if(response[key].vd_soldout==0){
                  this.markers.push({position:this.marker[key],
                          icon :"/images/realtime/vending_black.png",
                  });
                }
                else if(response[key].vd_soldout==1){
                  this.markers.push({position:this.marker[key],
                          icon :"/images/realtime/vending_red.png",
                  });
                }
                else if(response[key].vd_soldout==2){
                  this.markers.push({position:this.marker[key],
                          icon :"/images/realtime/vending_sold_out.png",
                  });
                }
                else if(response[key].vd_soldout==3){
                    this.markers.push({position:this.marker[key],
                          icon :"/images/realtime/vending_instruction.png",
                  });
                }  
             }
         } /********** 매진임박 *********/
         else if(num==1){
                this.selectTrue=true;
                this.markers.splice(0,);
                //this.zoom = parseInt(7);
                this.marker = [];   
                this.data = [];
                this.stock = [];
                for(let key in response){
                 //<-------------- marker setting -------------------> 
                 this.marker[key] = {
                 lat : response[key].vd_latitude,
                 lng : response[key].vd_longitude
                 }  

                 //<--------------- input marker -------------------->
                this.markers.push({position:this.marker[key],
                            icon :"/images/realtime/vending_red.png",
                });          
            }
         } /********** 매진 *********/
         else if(num==2){ 
                this.markers.splice(0,);
                //this.zoom = parseInt(7);
                this.marker = [];   
                this.data = [];
                this.stock = [];
              for(let key in response){
                 //<------------- marker setting --------------------> 
                 this.marker[key] = {
                 lat : response[key].vd_latitude,
                 lng : response[key].vd_longitude
                 }  
              
                 //<------------- input marker -------------------->
             
                 this.markers.push({position:this.marker[key],
                            icon :"/images/realtime/vending_sold_out.png",
                 });
              }
          } /********** 작업지시 *********/
           else if(num==3){
                  this.selectTrue=false;
                  this.markers.splice(0,);
                  //this.zoom = parseInt(7);
                  this.marker = [];   
                  this.data = [];
                  this.stock = [];
               
                for(let key in response){
                  //<------------- marker setting --------------------> 
                  this.marker[key] = {
                  lat : response[key].vd_latitude,
                  lng : response[key].vd_longitude
                  }  
                  
                  //<--------------- datasetting --------------------->
                  this.data[key]={
                    이름:response[key].vd_name  ,
                    보충기사:response[key].vd_supplementer                
                  }

                  //<------------- vending_stock -------------------->
                  this.stock[key]={
                    id : response[key].vd_id  
                  }
                  //<------------- input marker -------------------->
              
                  this.markers.push({position:this.marker[key],
                              icon :"/images/realtime/vending_instruction.png",
                  });
               }
             }
         })
      },
   }
};
</script>
<style>
/* <----------------전체 레이아웃 ----------------> */
#mapImage{
  
margin-top:15px;
margin-right:280px;
margin-bottom:0px; 

}
/* <------------- 상단바 버튼 css ------------> */
#buttonFont{
  background:#f7fbff;
  color:#03101f;
  border:none;
  position:relative;
  height:33px;
  width: 100%;
  font-size:0.9em;
  padding:0 2em;
  cursor:pointer;
  transition:800ms ease all;
  outline:none;
  font-family:"Nanum Gothic";
  font-weight: bold;
}
#buttonFont:hover{
  background:rgb(255, 255, 255);
  color:#04213d;
  
  font-weight: bold;
}
#buttonFont:before,#buttonFont:after{
  content:'';
  position:absolute;
  top:0;
  right:0;
  height:2px;
  width:0;
  background: #0064c8;
  transition:400ms ease all;
  
  font-weight: bold;
}
#buttonFont:after{
  right:inherit;
  top:inherit;
  left:0;
  bottom:0;
}
#buttonFont:hover:before,#buttonFont:hover:after{
  width:100%;
  transition:800ms ease all;
}


</style>