<template>
    <div style="display: grid; grid-template-rows: 0.2fr 0.75fr 0.05fr;">
        <div>
            <div class="remove_btn_style" id="productRemove" @click="onClickButton('productRemove')" style="height: 70px; width: 160px; float: right; text-align: right; padding-top: 15px; padding-right: 7px;">
                <font color="white" size="5" style="font-family: 'Nanum Gothic';">製品削除</font>
            </div>
            <div class="update_btn_style" id="productModify" @click="onClickButton('productModify')" style="height: 70px; width: 160px; float: right; margin-right: 5px; text-align: right; padding-top: 15px; padding-right: 7px;">
                <font color="white" size="5" style="font-family: 'Nanum Gothic';">製品修正</font>
            </div>
            <div class="insert_btn_style" id="productUpload" @click="onClickButton('productUpload')" style="height: 70px; width: 160px; float: right; margin-right: 5px; text-align: right; padding-top: 15px; padding-right: 7px;">
                <font color="white" size="5" style="font-family: 'Nanum Gothic';">製品追加</font>
            </div>
        </div>
        <div>
            <!-- 제품 정보 테이블 -->
            <table id="tableStyle">
                <thead>
                    <th>製品</th><th @mousedown="productNameSort" @mouseup="afterSort" :id="thProductName">製品名*</th><th>会社名</th><th>保存期間</th><th>会社在庫<br>(BOX)</th><th>最大在庫<br>(BOX)</th><th>単価(円)</th><th>必要量<br>(BOX)</th><th>注文</th><th>注文量</th><th></th>
                </thead>
                <tbody>
                    <tr v-for="contact in contacts" :key="contact.id" :name="contact.stock_id" @mouseover="mouseover(contact.stock_id)" @mouseout="mouseout(contact.stock_id)" @click="trClick(contact.stock_id)" style="border: 1px solid rgb(153, 200, 223);">
                        <td><img :src="'/images/drink/' + contact.drink_name + '.png'" style="width: 30px; height: 50px; margin: 3px;"></td>
                        <td><font size="3" style="font-family: 'Dosis';">{{contact.drink_name}}</font></td>
                        <td><font size="3" style="font-family: 'Dosis';">{{contact.cp_name}}</font></td>
                        <td><font style="font-family: 'Fugaz+One';">{{contact.expiration_date}}</font></td>
                        <td><font size="4" style="font-family: Fugaz+One';">{{contact.stock}}</font></td>
                        <td><font size="4" style="font-family: 'Fugaz+One';">{{contact.max_stock}}</font></td>
                        <td :name="contact.stock_id"><font size="4" style="font-family: 'Fugaz+One';">{{contact.drink_price}}</font></td>
                        <td><font size="4" style="font-family: 'Fugaz+One';">{{contact.sp_val}}</font></td>
                        <td><input type="checkbox" style="width: 20px; height: 20px;" :id="contact.stock_id" @click="checkedCheck(contact.cp_name, contact.stock_id)"></td>
                        <td><input type="text" value="0" class="inputOrderCount" :name="contact.stock_id" @keyup="inputNumberCheck(contact.stock_id)"></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <!-- 제품 정보 테이블 -->
        </div>
        <br>
        <div style="display: grid; grid-template-columns: 0.6fr 0.4fr;">
            <div style="width: 500px; height: 100px; background-color: #0064c8; border-radius: 15px;">
                <table style="width: 100%; height: 100%;">
                    <tr>
                        <td><font color="white" size="3" style="font-family: 'Nanum Gothic';">総金額(円)</font></td>
                        <td><font color="white" size="3" style="font-family: 'Nanum Gothic';">総注文量(BOX)</font></td>
                        <td><font color="white" size="3" style="font-family: 'Nanum Gothic';">総会社数</font></td>
                        <td><font color="white" size="3" style="font-family: 'Nanum Gothic';">注文書数</font></td>
                    </tr>
                    <tr>
                        <td><font id="allOrderMoneyTag" color="white" size="5" style="font-family: 'Nanum Gothic';">0</font></td>
                        <td><font id="allOrderCountTag" color="white" size="5" style="font-family: 'Nanum Gothic';">0</font></td>
                        <td><font id="orderCompanyCount" color="white" size="5" style="font-family: 'Nanum Gothic';">0</font></td>
                        <td><font id="orderSheetCount" color="white" size="5" style="font-family: 'Nanum Gothic';">0</font></td>
                    </tr>
                </table>
            </div>
            <div>
                <div class="order_btn_style" id="orderBtn" @click="onClickButton('orderBtn')" style="height: 100px; width: 160px; float: right; margin-right: 5px; text-align: right; padding-top: 30px; padding-right: 20px;">
                    <font color="white" size="5" style="font-family: 'Nanum Gothic';">注文</font>
                </div>
                <div class="history_btn_style" id="orderHistory" @click="onClickButton('orderHistory')" style="height: 100px; width: 160px; float: right; text-align: right; padding-top: 30px; padding-right: 7px; margin-right: 10px;">
                    <font color="white" size="5" style="font-family: 'Nanum Gothic';">注文履歴</font>
                </div>
            </div> 
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                saveStockId         : "",                   // 선택한 제품의 재고 아이디
                thProductName       : "thProductName",      // 제품명 th의 아이디
                sortCheck           : false,                // 정렬 상태
                contacts            : [],                   // 제품 정보
                orderCompanyArr     : [],                   // 주문 체크 박스를 선택한 회사들
                companyNameCheck    : false,                // 주문 체크 박스가 체크 되어있는지 확인
                count               : 0,                    // 주문한 회사 수
                allOrderCount       : 0,                    // 총 주문 수
                allOrderMoney       : 0,                    // 총 주문 금액
                clickedTr           : "",                   // 체크 되어 있는 tr태그 이름
                clickTr             : "",                   // 체크 한 tr 태그 이름
                sortSave            : [],                   // 정렬하기 전의 값
                originalColor       : ""                    // 원래 tr색
            }
        },
        mounted(){
            this.getPrdocutData();                                 // 제품 정보 호출
        },
        methods: {
            productNameSort: function() {
                this.sortSave = [];

                for(var i = 0; i < this.contacts.length; i++) {
                    if (document.getElementById(this.contacts[i].stock_id).checked == true) {
                        this.sortSave.push({"drink_name": this.contacts[i].drink_name, "checkBoxChecked": true, "orderCount": document.getElementsByName(this.contacts[i].stock_id)[2].value, "orderProduct_id": this.contacts[i].stock_id});
                    }
                    else {
                        this.sortSave.push({"drink_name": this.contacts[i].drink_name, "checkBoxChecked": false, "orderCount": document.getElementsByName(this.contacts[i].stock_id)[2].value, "orderProduct_id": this.contacts[i].stock_id});
                    }
                }
                // 주문 체크 확인

                if (this.sortCheck == false) {
                    document.getElementById(this.thProductName).innerText = "제품명⇩";
                    this.contacts.sort(function(a, b) {
                        return a.drink_name < b.drink_name ? -1 : a.drink_name > b.drink_name ? 1 : 0;
                    });

                    this.sortCheck = true;
                }
                else {
                    document.getElementById(this.thProductName).innerText = "제품명⇧";
                    this.contacts.sort(function(a, b) {
                        return a.drink_name > b.drink_name ? -1 : a.drink_name < b.drink_name ? 1 : 0;
                    });

                    this.sortCheck = false;
                }
                // 제품명으로 정렬
            },
            // 제품이름 정렬


            afterSort: function() {
                for(var i = 0; i < this.contacts.length; i++) {
                    document.getElementById(this.contacts[i].stock_id).checked = false;
                    document.getElementsByName(this.contacts[i].stock_id)[2].value = 0;
                }
                // 체크 상태 초기화
                
                for(var i = 0; i < this.sortSave.length; i++) {
                    if (this.sortSave[i].checkBoxChecked == true) {
                        document.getElementById(this.sortSave[i].stock_id).checked = true;
                    }

                    if (Number(this.sortSave[i].orderCount) != 0) {
                        document.getElementsByName(this.sortSave[i].stock_id)[2].value = this.sortSave[i].orderCount;
                    }
                }
                // 원래 체크 되어 있던 제품의 체크박스에 체크 설정
            },
            // 정렬 후 체크 상태 설정


            mouseover: function(overTr) {
                if (document.getElementsByName(overTr)[0] != this.clickedTr) {
                    this.originalColor = document.getElementsByName(overTr)[0].style.backgroundColor;
                    document.getElementsByName(overTr)[0].style.backgroundColor = 'rgba(88, 88, 88, 0.4)';
                }
            },
            // 마우스가 있는 tr태그 배경색 변경


            mouseout: function(outTr) {
                if (document.getElementsByName(outTr)[0] != this.clickedTr) {
                    document.getElementsByName(outTr)[0].style.backgroundColor = this.originalColor;
                }
            },
            // 마우스 나간 tr태그 배경색 변경


            onClickButton: function(clickedBtn) {
                if(clickedBtn == "productModify") {
                    // 제품 수정 버튼 클릭 시

                    if(this.clickTr != "") {
                        this.$emit('clicked', clickedBtn, this.saveStockId);
                    }
                    else {
                        alert("수정 할 제품을 선택하시오");
                    }
                    // 제품 클릭 유무 확인
                }
                else if(clickedBtn == "productRemove") {
                    // 제품 삭제 버튼 클릭 시

                    if(this.clickTr != "") {
                        this.$emit('clicked', clickedBtn, this.saveStockId);
                    }
                    else {
                        alert("삭제 할 제품을 선택하시오");
                    }
                    // 제품 클릭 유무 확인
                }
                else if(clickedBtn == "orderBtn") {
                    // 제품 주문 버튼 클릭 시

                    var orderArr = [];
                    
                    for (var i = 0; i < this.contacts.length; i++) {
                        if (document.getElementById(this.contacts[i].stock_id).checked == true && document.getElementsByName(this.contacts[i].stock_id)[2].value > 0) {
                            orderArr.push({orderProductName: this.contacts[i].drink_name, orderCount: Number(document.getElementsByName(this.contacts[i].stock_id)[2].value),
                                            orderCompanyName: this.contacts[i].cp_name, orderProductPrice: Number(this.contacts[i].drink_price), allMoney: Number(document.getElementsByName(this.contacts[i].stock_id)[2].value) * Number(this.contacts[i].drink_price)});
                        }
                    }
                    // 주문선택한 제품 정보 저장
                    

                    if(orderArr.length != 0) {
                        this.$emit('clicked', clickedBtn, orderArr);
                    }
                    else {
                        alert("주문 제품 선택 및 주문량을 입력하시오.");
                    }
                    // 주문 체크 박스 체크 유무 및 주문량 입력 유무 확인
                }
                else {
                    this.$emit('clicked', clickedBtn);
                }
            },
            // 화면 이동 버튼 클릭 시 이벤트를 발생시켜 부모에게 값을 전달



            trClick: function(trStockId) {
                this.clickTr = document.getElementsByName(trStockId)[0];       // 현재 클릭한 tr

                if (this.clickTr.style.backgroundColor == "#5f95a7") {
                    this.clickTr.style.backgroundColor = "#f6f9fa";
                }
                else {
                    if (this.clickedTr != "") {
                        this.clickedTr.style.backgroundColor = "#f6f9fa";
                    }

                    this.saveStockId = trStockId;                               // 클릭한 tr 아이디 저장
                    this.clickedTr = this.clickTr;                              // 클릭한 태그 저장
                    this.clickTr.style.backgroundColor = "#5f95a7";              
                }
            },
            // 클릭한 tr태그 배경색 변경 및 클린한 태그 name 저장


            getPrdocutData: function() {
                let getPrdocutDataUrl = "product/getProductData";               // 제품 정보 url

                this.axios.get(getPrdocutDataUrl)
                .then((response) => {
                    this.contacts = response.data;
                })
                .catch((error) => {
                    console.log(error.response);
                    console.log("제품정보를 가져오는 것에 실패하였습니다.");
                })
            },
            // 제품 정보 저장


            checkedCheck: function(orderCompanyName, orderProduct_id) {
                var checkboxTag = document.getElementById(orderProduct_id);                 // 체크 박스 태그
                var orderCompanyCount = document.getElementById("orderCompanyCount");       // 선택한 총 회사 수
                var orderSheetCount = document.getElementById("orderSheetCount");           // 선택한 주문서 수

                if(checkboxTag.checked) {
                    // 체크박스에 체크 시

                    for(var i = 0; i < this.orderCompanyArr.length; i++) {
                        if(this.orderCompanyArr[i] == orderCompanyName) {
                            this.companyNameCheck = true;
                            break;
                        }
                    }

                    if (this.companyNameCheck == false) {
                        this.count++;
                        orderCompanyCount.innerText = this.count;
                        orderSheetCount.innerText = this.count;
                    }
                    else {
                        this.companyNameCheck = false;
                    }
                    // 선택한 총 회사 수 및 주문서 수 계산

                    this.orderCompanyArr.push(orderCompanyName);        // 주문한 회사 명 저장
                    this.orderCountCalculate(orderProduct_id);          // 금액 계산
                }
                else {
                    // 체크박스 체크 해제 시

                    for(var i = 0; i < this.orderCompanyArr.length; i++) {
                        if (this.orderCompanyArr[i] == orderCompanyName) {
                            this.companyNameCheck = true;
                            this.orderCompanyArr.splice(i,1);           // 저장된 주문 회사명 제거
                            break;
                        }
                    }

                    this.companyNameCheck = false;

                    for(var i = 0; i < this.orderCompanyArr.length; i++) {
                        if (this.orderCompanyArr[i] == orderCompanyName) {
                            this.companyNameCheck = true;
                            break;
                        }
                    }

                    if (this.companyNameCheck == false) {
                        this.count--;
                        orderCompanyCount.innerText = this.count;
                        orderSheetCount.innerText = this.count;
                    }
                    else {
                        this.companyNameCheck = false;
                    }
                    // 선택한 총 회사 수 및 주문서 수 계산

                    this.orderCountCalculate(orderProduct_id);      // 금액 계산
                }
            },
            // 주문 체크박스 클릭시 주문서 수 및 주문한 회사 수 계산, 금액 계산


            inputNumberCheck: function(orderProduct) {
                var orderProductCount = document.getElementsByName(orderProduct)[2].value;  // 입력한 주문량
                var checkboxTag = document.getElementById(orderProduct);                    // 주문 체크박스 태그
                var text = "";                                                              // 주문량 저장 변수
                
                for(var i = 0; i < orderProductCount.length; i++) {

                    if(orderProductCount[i] == "0") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "1") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "2") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "3") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "4") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "5") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "6") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "7") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "8") {text += orderProductCount[i];}
                    else if (orderProductCount[i] == "9") {text += orderProductCount[i];}
                    else {
                        alert("숫자만 입력하시오.");
                        document.getElementsByName(orderProduct)[2].value = text;
                    }
                }
                // 주문량 숫자만 입력되게 설정

                this.allOrderCount = 0;     // 총 주문량 초기화
                this.allOrderMoney = 0;     // 총 주문 금액 초기화
                
                for(var i = 0; i < this.contacts.length; i++) {
                    if(document.getElementById(this.contacts[i].stock_id).checked) {
                        this.allOrderCount += Number(document.getElementsByName(this.contacts[i].stock_id)[2].value);
                        this.allOrderMoney += (Number(document.getElementsByName(this.contacts[i].stock_id)[2].value) * Number(this.contacts[i].drink_price));
                    }
                }
                // 총 주문량 및 금액 계산

                document.getElementById("allOrderCountTag").innerText = String(this.allOrderCount);
                document.getElementById("allOrderMoneyTag").innerText = String(this.allOrderMoney);
            },
            // 주문량 입력


            orderCountCalculate: function(orderProduct) {
                var checkboxTag = document.getElementById(orderProduct);        // 주문 체크박스 태그 

                if(checkboxTag.checked) {
                    // 체크박스 체크 시

                    this.allOrderCount += Number(document.getElementsByName(orderProduct)[2].value);
                    document.getElementById("allOrderCountTag").innerText = String(this.allOrderCount);

                    this.allOrderMoney += (Number(document.getElementsByName(orderProduct)[1].innerText) * Number(document.getElementsByName(orderProduct)[2].value));
                    document.getElementById("allOrderMoneyTag").innerText = String(this.allOrderMoney);
                }
                else {
                    // 체크 박스 해제 시
                    
                    this.allOrderCount -= Number(document.getElementsByName(orderProduct)[2].value);
                    document.getElementById("allOrderCountTag").innerText = String(this.allOrderCount);

                    this.allOrderMoney -= (Number(document.getElementsByName(orderProduct)[1].innerText) * Number(document.getElementsByName(orderProduct)[2].value));
                    document.getElementById("allOrderMoneyTag").innerText = String(this.allOrderMoney);
                }
            }
            // 금액 계산
        }
    }
</script>
<style>
    .inputOrderCount {
        width: 100px;
        height: 30px;
        background-color: white;
        border: 1px solid rgb(153, 200, 223);
    }


    #tableStyle {
        text-align: center;
        display: block;
        width: 100%;
        height: 100%;
        margin-left: 30px;
    }
    #tableStyle thead {
        border-bottom: 1px solid #0064c8;
    }
    #tableStyle tr:nth-child(even) {
        background: #E3F2FD;
    }
    #tableStyle tbody {
        display: block;
        height: 400px;
        overflow: auto;
    }
    #tableStyle th:nth-of-type(1), #tableStyle td:nth-of-type(1) { width: 100px; }
    
    #tableStyle th:nth-of-type(2) { width: 120px; }
    #tableStyle td:nth-of-type(2) { width: 100px; }

    #tableStyle th:nth-of-type(3) { width: 120px; }
    #tableStyle td:nth-of-type(3) { width: 100px; }

    #tableStyle th:nth-of-type(4) { width: 120px; }
    #tableStyle td:nth-of-type(4) { width: 100px; }

    #tableStyle th:nth-of-type(5) { width: 80px; }
    #tableStyle td:nth-of-type(5) { width: 100px; }

    #tableStyle th:nth-of-type(6), #tableStyle td:nth-of-type(6) { width: 100px; }
    #tableStyle th:nth-of-type(7), #tableStyle td:nth-of-type(7) { width: 100px; }

    #tableStyle th:nth-of-type(8) { width: 100px; }
    #tableStyle td:nth-of-type(8) { width: 100px; }

    #tableStyle th:nth-of-type(9), #tableStyle td:nth-of-type(9) { width: 100px; }

    #tableStyle th:nth-of-type(10) { width: 120px; }
    #tableStyle td:nth-of-type(10) { width: 100px; }

    #tableStyle th:last-child { width: 50px; }
    #tableStyle td:last-child { width: calc( 50px - 19px );  }








    .insert_btn_style {
        position: relative;
        background-image: url("/images/product/insert_btn.png");
        background-size: 100% 100%;
        background-repeat: no-repeat;
        border-radius: 15px;
        cursor: pointer;
    }

    .update_btn_style {
        position: relative;
        background-image: url("/images/product/update_btn.png");                                                          
        background-size: 100% 100%;
        background-repeat: no-repeat;
        border-radius: 15px;
        cursor: pointer;
    }

    .remove_btn_style {
        position: relative;
        background-image: url("/images/product/remove_btn.png");                                                          
        background-size: 100% 100%;
        background-repeat: no-repeat;
        border-radius: 15px;
        cursor: pointer;
    }

    .history_btn_style {
        position: relative;
        background-image: url("/images/product/order_history_btn.png");                                                          
        background-size: 100% 100%;
        background-repeat: no-repeat;
        border-radius: 15px;
        cursor: pointer;
    }

    .order_btn_style {
        position: relative;
        background-image: url("/images/product/order_btn.png");                                                          
        background-size: 100% 100%;
        background-repeat: no-repeat;
        border-radius: 15px;
        cursor: pointer;
    }
</style>
