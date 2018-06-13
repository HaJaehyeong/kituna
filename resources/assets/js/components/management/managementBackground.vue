<template>
    <div align="center" id="managementBackground"> 
        <div id="managementHeader">
            <router-view name="MainHeader"></router-view>
        </div>
        <div id="managementcontents">
            <div v-if="slideSide == true">
                <router-view name="ManagementSide"></router-view>
            </div>
            <div>
                <router-view @clicked="onClickChild" name="ManagementContents"></router-view>   
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                slideSide: true
            }
        },
       
        methods: {
            onClickChild: function(sendDataValue) {
                if (sendDataValue == false) {
                    var styleRemove = document.getElementById('managementcontents');
                    styleRemove.style.gridTemplateColumns = '1fr';
                }
                else {
                    var styleRemove = document.getElementById('managementcontents');
                    styleRemove.style.gridTemplateColumns = '0.3fr 0.7fr';
                }


                this.slideSide = sendDataValue;
            }
            // 자식에게 전달 받은 값들을 저장
        }
    }
</script>

<style>
    #managementBackground{
        display     : grid;
        grid-template-rows: 0.05fr 0.95fr
        /* background-image: url("/images/mainPageImage/backgroundVending.jpg"); */
    }
    #managementcontents{
        display     : grid;
        grid-template-columns : 0.3fr 0.7fr
    }
</style>