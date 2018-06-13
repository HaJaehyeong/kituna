<template>

    <div class="topMenu">
        <div class="menu" style="margin-left:3%;">
            <h5><a class="menuHeaderA" href="/realtime"> RealTime </a></h5>
        </div>
        <div class="menu">
            <h5><a class="menuHeaderA" href="/analysis"> Analysis </a></h5>
        </div>
        <div class="menu">
            <h5><a class="menuHeaderA" href="/management"> VendingMachine Management </a></h5>
        </div>
        <div class="menu">
            <h5><a class="menuHeaderA" href="/product"> Product Management</a></h5>
        </div>
        <div>
            <h5><img src="/images/mainPageImage/menu.png" style="margin-left:3%;"></h5>
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
  list-style-type: none;
  overflow: hidden;
  background-color: #24A6BD;
  position: -webkit-sticky; /* Safari */
  position: sticky;
  z-index: 3;
}
div.menu {
  width: 20%;
  margin-left: 1.5%;
  float: left;
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
}


 .menuHeaderA:link { color: red; text-decoration: none;}
 .menuHeaderA:visited { color: rgb(255, 255, 255); text-decoration: none;}
 .menuHeaderA:hover { color: rgb(190, 230, 192); text-decoration: underline;}
 
 .tabs.mytabs .nav-tabs .nav-link.active {
  background-color: #aabbcc !important;
}
</style>