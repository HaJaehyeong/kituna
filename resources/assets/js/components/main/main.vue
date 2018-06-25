<template>

<div id="mainBackgroundDiv">
    <div id="mainBackgroundSecondDiv">
        <div id="topLogoBar" >
            <img src="/images/mainPageImage/logo.png"  width="140" height="30" style="margin-top: 1%;">
            <div id="signDiv">
                Sign-in→
                <!-- <v-btn round color="cyan" style="float: right; color: white; padding-bottom: 35px;">Sign-in-></v-btn> -->
            </div>
        </div>
        <div>
            <br>
            <div>
                <v-btn small round color="cyan" style="color: white; padding-bottom: 30px;">Introduce</v-btn>
            </div>
            
            <h3 style="color: white;">자판기에 IoT기술을 접목시켜</h3>
            <h3 style="color: white;">간편한 판매데이터 수집 및 분석과</h3>
            <h3 style="color: white;">보충기사의 편의성까지 고려한</h3>
            <h3 style="color: white;">실시간 자판기 통합관리 서비스입니다.</h3>
        </div>
        <div id="mainImageButtonDiv">
            <router-link to="/realtime" id="realTime">
                <div class="partInfo">
                    <img src="/images/mainPageImage/realtime.png" class="icon">
                </div>
            </router-link>
            <router-link to="/analysis" id="analyst">
                <div class="partInfo">
                    <img src="/images/mainPageImage/analysis.png" class="icon">
                </div>
            </router-link>
            <router-link to="/management"  id="management">
                <div class="partInfo">
                    <img src="/images/mainPageImage/vending-machine.png" class="icon">
                </div>
            </router-link>
            <router-link to="/product" id="order">
                <div class="partInfo">
                    <img src="/images/mainPageImage/can.png" class="icon">
                </div>
            </router-link>
        </div>
    </div>


</div>
</template>

<style>
#mainBackgroundDiv {
  /* The image used */
  background-image: url("/images/mainPageImage/backgroundVending.png");
  /* Set a specific height */
  height: 100%;
  width: 100%;  
}
#mainBackgroundSecondDiv{
  display: grid;
  grid-template-rows: 0.1fr 0.4fr 0.6fr;
  height: 100%;
  width: 80%;  
  margin: auto;
}
#topLogoBar{
  margin-top: 1%;
  padding-bottom: 1%;
  border-bottom: 1px solid #FFFFFF;
}
.partInfo {
  width: 23%;
  height: 80%;
  margin-top: 5%;
  display: inline-block;
  overflow-x:hidden;
  overflow-y:hidden;
  color: rgb(255, 255, 255);
  border-radius: 20px;
  text-align: center;
  
}
#analyst, #management, #order{
  margin-left: 2%;
}
#signDiv{
 float: right; 
 width: 100px; 
 height: 30px; 
 color: white;
 font-size: 15pt;
 font-style: "Dosis";
 background-color: #00BCD4;
 border-radius: 20px 20px 20px 20px; 
 padding-left: 5px;
 margin-top: 1%;
}
.icon {
  width:100%;
  height: 100%;
}

.partInfo:hover .icon{
    opacity: 0.7
}
</style>
<script>
//   window.onscroll = function() {myFunction()};

//   function myFunction() {
//     var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
//     var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
//     var scrolled = 58 - (winScroll / height) * 60;
//     var divMoveToLeft = winScroll*1.25;
//     var divMoveMenu = winScroll/50;

//     if (scrolled > 28) {
//       document.getElementById('logoText').style.fontSize = scrolled + "px";
//       document.getElementById('logoTextK').style.fontSize = scrolled + "px";
//     } else { }
    
//     if (winScroll > 400 && winScroll < 500) {
//       document.getElementById('menuOne').style.marginLeft  = divMoveMenu+14 + "%";
//       document.getElementById('menuTwo').style.marginLeft  = divMoveMenu -18+ "%";
//       document.getElementById('menuThree').style.marginLeft  = divMoveMenu-14+ "%";
//       document.getElementById('menuFour').style.marginLeft  = divMoveMenu-9 + "%";
//     } else if (winScroll < 400) {
//      document.getElementById('menuOne').style.marginLeft  = 3 + "%";
//       document.getElementById('menuTwo').style.marginLeft  = 1.5 + "%";
//       document.getElementById('menuThree').style.marginLeft  = 1.5+ "%";
//       document.getElementById('menuFour').style.marginLeft  = 1.5 + "%";
//     }

//     if (divMoveToLeft < 600) {
//       document.getElementById('logo').style.left = -divMoveToLeft+"px";
//     } else {
//       document.getElementById('logo').style.left = -560+"px";
//       document.getElementById('logo').style.top = winScroll-460+"px";
//       document.getElementById('logoText').style.fontSize = 28 + "px";
//       document.getElementById('logoTextK').style.fontSize = 28 + "px";
//     }
    
    
//   }
</script>