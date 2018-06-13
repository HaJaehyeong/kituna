<template>

   <div align="center" id="analysisBackground"> 
        <div id="div_AnalysisHeader">
            <router-view name="MainHeader"></router-view>
        </div>
        <div id="div_AnalysisLineChartView">
            <router-view name="AnalysisLineChart"></router-view>      
        </div>
    </div>

</template>
 
<script>
    export default {

    }
</script>

<style>
    #analysisBackground{
        display     : grid;
        grid-template-rows: 0.05fr 0.95fr;
        /* background-image: url("/images/mainPageImage/backgroundVending.jpg"); */
    }
  
</style>
