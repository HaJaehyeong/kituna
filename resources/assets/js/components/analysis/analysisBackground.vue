<template>

   <div align="center" id="analysisBackground"> 
        <div id="div_AnalysisHeader">
            <router-view name="MainHeader"></router-view>
        </div>
        <div id="div_AnalysisLineChartView" v-bind:style="{ 'background-image': 'url(images/analysis/background.png)' }">
            <router-view name="AnalysisLineChart" :realtimeVdId="realtimeVdId"></router-view>      
        </div>
    </div>

</template>
 
<script>
    export default {
        data(){
            return{ 
                realtimeVdId : 0,
            }
        },
        mounted () {
            this.realtimeValue();
        },
        methods: {
            realtimeValue(){
                if(this.$route.query.id){
                    this.realtimeVdId = this.$route.query.id;
                    console.log(this.realtimeVdId);
                }else{
                }
            }
        }
    }
</script>

<style>
    #analysisBackground{
        display     : grid;
        grid-template-rows: 0.04fr 0.96fr;
        /* background-image: url("/images/mainPageImage/backgroundVending.jpg"); */
    }

</style>
