<template>

<div id="mainBackgroundDiv">
    <div id="mainImageButtonDiv">
        <router-link to="/realtime" id="realTime">
            <div class="partInfo" style="margin-left:3%;">
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
    <div id="mainCharaterDiv">
        <div class="menu" id="menuOne" style="margin-left:3%;">
            <h4>RealTime</h4>
        </div>
        <div class="menu" id="menuTwo">
            <h4>Analysis</h4>
        </div>
        <div class="menu" id="menuThree">
            <h4>VendingMachine Management</h4>
        </div>
        <div class="menu" id="menuFour">
            <h4>Product Management</h4>
        </div>
        <div>
            <img src="/images/mainPageImage/menu.png" style="margin-left:3%;">
        </div>
    </div>

    <div id="logo">
  
        <h1 id="logoText">KI-TUNA</h1>
    </div>
    
    <!-- <div class="one">
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    </div> -->
</div>
</template>

<style>
#mainBackgroundDiv {
  /* The image used */
  background-image: url("/images/mainPageImage/backgroundVending.jpg");

  /* Set a specific height */
  height: 100vh;
  width: 100%;

  /* Create the parallax scrolling effect */
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
#mainImageButtonDiv {
  height: 60%;
  background-color: rgba(88, 88, 88, 0.4);
  overflow-x:hidden;
  overflow-y:hidden;
}

#mainCharaterDiv {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: rgba(88, 88, 88, 0.4);
  position: -webkit-sticky; /* Safari */
  position: sticky;
  top: 0;
  z-index: 3;
}

/* div.one {
  width:100%;
  height:60%;
  margin-top:4%;
  overflow-x:hidden;
  overflow-y:hidden;
  z-index:2;
  background:linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1) 20%);
  padding-top:20%;
} */


div.menu {
  width: 20%;
  margin-left: 1.5%;
  float: left;
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
}

#logo {
  position: relative;
  top: 0;
  padding: 4%;
  z-index: 3;
  text-align: center;
}


.partInfo {
  width: 20%;
  height: 80%;
  margin-left: 1%;
  margin-top: 5%;
  background-color: rgba(0, 0, 0, 0.6);
  display: inline-block;
  overflow-x:hidden;
  overflow-y:hidden;
  color: rgb(255, 255, 255);
  border-radius: 20px;
  text-align: center;
}

.icon {
  width:50%;
  height: 40%;
  margin-top: 10%;
}

#logoTextK{
  font-family: 'Black Ops One', cursive;
  color:#FFFFFF;
  display:inline-block;
  font-size:58px;
  font-family: 'Black Ops One', cursive;
}
#logoText{
  font-family: 'Black Ops One', cursive;
  color:#FFFFFF;
  display:inline-block;
  font-size:58px;
}
.partInfo:hover .icon{
    opacity: 0.3
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