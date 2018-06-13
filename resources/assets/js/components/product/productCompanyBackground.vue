<template>
    <div align="center" id="rowDiv1">
        <div id="productHeader">
            <router-view name="MainHeader"></router-view>
        </div>


        <div id="columnsDiv1" v-if="changeDivName == 'start'">
            <div id="companyInformation">
                <router-view name="CompanyInformation" @clicked="onClickChild"></router-view>
            </div>
            <div id ="productInformation">
                <router-view name="ProductInformation" @clicked="onClickChild"></router-view>
            </div>
        </div>
        <!-- 메인화면 -->
        



        <div align="center" id="rowDiv3" v-else-if="changeDivName == 'productModify'"> 
            <div id="modifyInfo">
                <router-view name="ModifyInfo" :sendProductId="sendProductId" @clicked="onClickChild"></router-view>
            </div>
        </div>
        <!-- 제품 정보 수정 -->



        <div align="center" id="rowDiv4" v-else-if="changeDivName == 'productUpload'"> 
            <div id="uploadInfo">
                <router-view name="UploadInfo" @clicked="onClickChild"></router-view>
            </div>
        </div>
        <!-- 제품 등록 -->



        <div align="center" id="rowDiv5" v-else-if="changeDivName == 'productRemove'">
            <div id="productRemoveDiv">
                <router-view name="ProductRemove" :sendProductId="sendProductId" @clicked="onClickChild"></router-view>
            </div>
        </div>
        <!-- 제품 삭제 -->



        <div align="center" id="rowDiv6" v-else-if="changeDivName == 'orderHistory'">
            <div id="orderHistoryDiv">
                <router-view name="OrderHistory" @clicked="onClickChild"></router-view>
            </div>
        </div>
        <!-- 주문서 내역 -->



        <div align="center" id="rowDiv7" v-else-if="changeDivName == 'orderBtn'">
            <div id="orderDiv">
                <router-view name="Order" @clicked="onClickChild" :sendOrderArr="sendOrderArr"></router-view>
            </div>
        </div>
        <!-- 주문 -->
    </div>
</template>
<script>
    
    export default {
        mounted() {
            this.analysisOrder();
        },
        data(){
            return{
                changeDivName: "start",     // 화면 이름
                sendProductId: "",          // 자식에게 전달 할 제품명
                sendOrderArr: []            // 자식에게 전달 할 정보
            }
        },
       
        methods: {
            onClickChild: function(value, sendData) {
                
                if (value == "productModify") {
                    this.sendProductId = sendData;
                }

                if (value == "productRemove") {
                    this.sendProductId = sendData;
                }

                if (value == "orderBtn") {
                    this.sendOrderArr = sendData;
                }

                this.changeDivName = value;
            },
            // 자식에게 전달 받은 값들을 저장

            analysisOrder: function() {
                if (typeof this.$route.query.value == "string") {
                    var string = this.$route.query.value;
                    var analysisOrderProductStockIdArr = string.replace(/(\s*)/g,"");
                    analysisOrderProductStockIdArr = analysisOrderProductStockIdArr.split(',');

                    this.sendOrderArr = [];

                    this.axios.get('product/getProductData')
                    .then((response) => {
                        for (var i = 0; i < analysisOrderProductStockIdArr.length; i++) {
                            for (var j = 0; j < response.data.length; j++) {
                                if (response.data[j].stock_id == analysisOrderProductStockIdArr[i]) {
                                    var analysisOrderObj = {
                                        orderProductName: response.data[j].drink_name,
                                        orderCompanyName: response.data[j].cp_name,
                                        orderCount: response.data[j].sp_val,
                                        orderProductPrice: response.data[j].drink_price,
                                        allMoney: Number(response.data[j].sp_val) * Number(response.data[j].drink_price)
                                    }

                                    this.sendOrderArr.push(analysisOrderObj);
                                }
                            } 
                        }

                        this.changeDivName = "orderBtn";
                    })
                    .catch((error) => {

                    })
                }
            },
        }
    }
</script>
<style>
    #productHeader{
        font-family: sans-serif;
        background: #f6f9fa;
    }
    #companyInformation{
        font-family: sans-serif;
        background: #f6f9fa;
    }
    #productInformation{
        font-family: sans-serif;
        background: #f6f9fa;
    }




    #rowDiv1{
        display: grid;
        grid-template-rows: 0.5fr 4fr;
    }
    #columnsDiv1{
        display: grid;
        grid-template-columns: 3fr 7fr;
    }






    #modifyInfo {
        font-family: sans-serif;
        background: #f6f9fa;
    }
    #rowDiv3 {
        display: grid;
        grid-template-columns: 5fr;
    }





    #uploadInfo {
        font-family: sans-serif;
        background: #f6f9fa;
    }
    #rowDiv4 {
        display: grid;
        grid-template-columns: 5fr;
    }




    #productRemoveDiv {
        font-family: sans-serif;
        background: #f6f9fa;
    }
    #rowDiv5 {
        display: grid;
        grid-template-columns: 5fr;
    }




    #orderHistoryDiv {
        font-family: sans-serif;
        background: #f6f9fa;
    }
    #rowDiv6 {
        display: grid;
        grid-template-columns: 5fr;
    }




    #orderDiv {
        font-family: sans-serif;
        background: #f6f9fa;
    }
    #rowDiv7 {
        display: grid;
        grid-template-columns: 5fr;
    }
</style>