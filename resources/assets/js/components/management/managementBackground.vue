<template>
    <div align="center"> 
        <div id="managementHeader">
            <router-view name="MainHeader"></router-view>
        </div>
        <div id="managementcontents">
            <div style="display: grid; grid-template-columns : 0.3fr 0.7fr;">
                <div>
                    <router-view name="ManagementSide"></router-view>
                </div>
                <div>
                    <router-view name="ManagementContents"></router-view>   
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                
            }
        },
    }
</script>

<style>
    #managementcontents{
        /* display     : grid;
        grid-template-columns : 0.15fr 0.2fr 0.5fr 0.15fr; */

        width: 80%;
    }
</style>