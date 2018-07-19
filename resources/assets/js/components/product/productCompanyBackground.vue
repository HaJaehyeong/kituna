<template>
    <div align="center" id="rowDiv1">
        <div id="productHeader">
            <router-view name="MainHeader"></router-view>
        </div>
        <!-- 메인 헤더 -->

        <div style="align: center; width: 80%;">
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
    </div>
</template>
<script>
    
    export default {
        mounted() {
            this.analysisOrder();
        },
        data(){
            return{
                changeDivName   : "start",     // 화면 이름
                sendProductId   : "",          // 자식에게 전달 할 제품명
                sendOrderArr    : []           // 자식에게 전달 할 정보
            }
        },
       
        methods: {
            onClickChild: function(value, sendData) {
                
                if (value == "productModify") {
                    this.sendProductId = sendData;
                }
                // 제품 수정 페이지 일 경우 

                if (value == "productRemove") {
                    this.sendProductId = sendData;
                }
                // 제품 삭제 페이지 일 경우

                if (value == "orderBtn") {
                    this.sendOrderArr = sendData;
                }
                // 주문 페이지 일 경우

                this.changeDivName = value;     // 페이지 변경
            },
            // 자식에게 전달 받은 값들을 저장

            analysisOrder: function() {
                if (typeof this.$route.query.value == "string") {
                    var string = this.$route.query.value;                                           // 전송된 제품 id값들 저장
                    var analysisOrderProductStockIdArr = string.replace(/(\s*)/g,""); 
                    analysisOrderProductStockIdArr = analysisOrderProductStockIdArr.split(',');
                    // 제품 id 값 추출

                    this.sendOrderArr = [];

                    this.axios.get('product/getProductData')
                    .then((response) => {
                        for (var i = 0; i < analysisOrderProductStockIdArr.length; i++) {
                            for (var j = 0; j < response.data.length; j++) {
                                if (response.data[j].stock_id == analysisOrderProductStockIdArr[i]) {
                                    // 해당 되는 id 값의 제품 찾기

                                    var analysisOrderObj = {
                                        orderProductName: response.data[j].drink_name,
                                        orderCompanyName: response.data[j].cp_name,
                                        orderCount: response.data[j].sp_val,
                                        orderProductPrice: response.data[j].drink_price,
                                        allMoney: Number(response.data[j].sp_val) * Number(response.data[j].drink_price)
                                    }
                                    // 주문 형식으로 객체 생성

                                    this.sendOrderArr.push(analysisOrderObj);
                                }
                            } 
                        }

                        this.changeDivName = "orderBtn";    // 주문 페이지로 변경
                    })
                    .catch((error) => {
                        console.log(error.response);
                        console.log("주문정보를 가져오는 것에 실패하였습니다.");
                    })
                }
            }
            // 분석에서 값 전달 받아서 주문하기
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
