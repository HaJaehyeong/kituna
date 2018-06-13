<template>
    <div align="center" class="wrapper"> 
        
        <div class="realtimeHeader" >
            <router-view name="MainHeader"></router-view>
        </div>
        <div class="Local">
            <router-view name="Local"></router-view>
        </div>
        <div class="GoogleMap">
            <router-view name="GoogleMap" ></router-view>
        </div>
        <div class ="realtimeVendingMachine">
            <router-view name="RealtimeVendingMachine"></router-view>
        </div>
    </div>
</template>

<script>
    export default {
       
    }
</script>

<style>

   .realtimeHeader {
    grid-area: rh;
   }
   .Local {
    grid-area: lc;
   }
   .GoogleMap {
    grid-area: gm;
   }
   .realtimeVendingMachine {
    grid-area: vm;
   }
    .wrapper { 
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    grid-auto-rows: minmax(100px, auto);
    grid-template-areas: 
      "rh rh  rh rh rh rh rh rh "
      "lc lc  gm gm gm gm vm vm ";
   }
    
</style>
