<template>
    <div class="topMenu">
         <div class="menu" style="margin-left:1%; margin-bottom:1%;">
          <a  href="/mainPage"> <img width="45%" src="/images/logo_blue.png"> </a>
        </div>
        <div class="menu" id="a1">
            <h5><a class="menuHeaderA"  href="/realtime"><strong> RealTime </strong></a></h5>
        </div>
        <div class="menu" id="a2">
            <h5><a class="menuHeaderA" href="/analysis"><strong> Analysis </strong></a></h5>
        </div>
        <div class="menu" id="a3">
            <h5><a class="menuHeaderA" href="/management"><strong> VendingMachine </strong><br/><strong>Management </strong></a></h5>
        </div>
        <div class="menu" id="a4">
            <h5><a class="menuHeaderA" href="/product"><strong> Product</strong><br/><strong>Management</strong></a></h5>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
              
            }
        }

    }
</script>

<style>

div.topMenu {
  font-family: "Dosis";  
  font-size: 20px;
  list-style-type: none;
  overflow: hidden;
  background-color: #0064c8;
  position: -webkit-sticky; /* Safari */
  position: sticky;
  z-index: 3;
}
div.menu {
  width: 16%;
  margin-left: 2%;
  margin-top: 0.2%;
  margin-bottom: 0.2%;
  float: left;
  display: block;
  color: white;
  text-align: center;
 /* padding: 4px 4px;
 */}
 #a1 {
   margin-top:25px;
 }
 #a2 {
   margin-top:25px;
 }
  #a3 {
   margin-top:10px;
 }
 #a4 {
   margin-top:10px;
 }


 .menuHeaderA:link { color: rgb(255, 255, 255); text-decoration: none;}
 .menuHeaderA:visited { color: rgb(255, 255, 255); text-decoration: none;}
 .menuHeaderA:hover { color: rgb(189, 211, 240); text-decoration: none;}

</style>