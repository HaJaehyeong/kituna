<template>
    <div>
        <table id="tableStyle">
            <thead>
                <th @mousedown="productNameSort" @mouseup="input" :id="thProductName">제품명*</th><th>회사명</th><th>유통기한</th><th>회사재고<br>(BOX)</th><th>최대재고<br>(BOX)</th><th>단가(원)</th><th>필요량<br>(BOX)</th><th>주문</th><th>주문량</th>
            </thead>
            <tbody>
                <tr v-for="contact in contacts" :key="contact.id" :name="contact.stock_id" @mouseover="mouseover(contact.stock_id)" @mouseout="mouseout(contact.stock_id)" @click="trClick(contact.stock_id)" style="border: 1px solid rgb(153, 200, 223);">
                    <td>{{contact.drink_name}}</td>
                    <td>{{contact.cp_name}}</td>
                    <td>{{contact.expiration_date}}</td>
                    <td>{{contact.stock}}</td>
                    <td>{{contact.max_stock}}</td>
                    <td :name="contact.stock_id">{{contact.drink_price}}</td>
                    <td>{{contact.sp_val}}</td>
                    <td><input type="checkbox" :id="contact.stock_id" @click="checkedCheck(contact.cp_name, contact.stock_id)"></td>
                    <td><input type="text" value="0" id="inputOrderCount" :name="contact.stock_id" @keyup="inputNumberCheck(contact.stock_id)"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>총금액(원)</td>
                    <td style="width: 200px;" id="allOrderMoneyTag">0</td>
                    <td>총 주문량(BOX)</td>
                    <td id="allOrderCountTag">0</td>
                    <td>총 회사수</td>
                    <td id="orderCompanyCount">0</td>
                    <td>주문서 수</td>
                    <td id="orderSheetCount">0</td>
                </tr>
            </tfoot>
        </table>
        <br>
        <div>   
            <b-button id="productUpload" @click="onClickButton">제품등록</b-button>
            <b-button id="productModify" @click="onClickButton">제품수정</b-button>
            <b-button id="productRemove" @click="onClickButton">제품삭제</b-button>
            <b-button id="orderHistory" @click="onClickButton">주문내역</b-button>
            <b-button id="orderBtn" @click="onClickButton">주문</b-button>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                saveStockId: "",                    // 선택한 제품의 재고 아이디
                thProductName: "thProductName",     // 제품명 th의 아이디
                sortCheck: false,                   // 정렬 상태
                contacts: [],                       // 제품 정보
                orderCompanyArr: [],                // 주문 체크 박스를 선택한 회사들
                companyNameCheck: false,            // 주문 체크 박스가 체크 되어있는지 확인
                count: 0,                           // 주문한 회사 수
                allOrderCount: 0,                   // 총 주문 수
                allOrderMoney: 0,                   // 총 주문 금액
                clickedTr: "",                      // 체크 되어 있는 tr태그 이름
                clickTr: "",                        // 체크 한 tr 태그 이름
                sortSave: []                        // 정렬하기 전의 값
            }
        },
        mounted(){
            this.getData();                         // 제품 정보 호출
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
            },
            // 제품이름 정렬


            input: function() {
                for(var i = 0; i < this.contacts.length; i++) {
                    document.getElementById(this.contacts[i].stock_id).checked = false;
                    document.getElementsByName(this.contacts[i].stock_id)[2].value = 0;
                }
                
                for(var i = 0; i < this.sortSave.length; i++) {
                    if (this.sortSave[i].checkBoxChecked == true) {
                        document.getElementById(this.sortSave[i].stock_id).checked = true;
                    }

                    if (Number(this.sortSave[i].orderCount) != 0) {
                        document.getElementsByName(this.sortSave[i].stock_id)[2].value = this.sortSave[i].orderCount;
                    }
                }
            },
            // 정렬 전 체크 상태 저장


            mouseover: function(overTr) {
                if (document.getElementsByName(overTr)[0] != this.clickedTr) {
                    document.getElementsByName(overTr)[0].style.backgroundColor = 'rgba(88, 88, 88, 0.4)';
                }
            },
            // 마우스가 있는 tr태그 배경색 변경


            mouseout: function(outTr) {
                if (document.getElementsByName(outTr)[0] != this.clickedTr) {
                    
                    document.getElementsByName(outTr)[0].style.backgroundColor = '#f6f9fa';
                }
            },
            // 마우스 나간 tr태그 배경색 변경


            onClickButton: function(e) {
                if(e.target.attributes[0].nodeValue == "productModify") {
                    if(this.clickTr != "") {
                        this.$emit('clicked', e.target.attributes[0].nodeValue, this.saveStockId);
                    }
                    else {
                        alert("수정 할 제품을 선택하시오");
                    }
                }
                else if(e.target.attributes[0].nodeValue == "productRemove") {
                    if(this.clickTr != "") {
                        this.$emit('clicked', e.target.attributes[0].nodeValue, this.saveStockId);
                    }
                    else {
                        alert("삭제 할 제품을 선택하시오");
                    }
                }
                else if(e.target.attributes[0].nodeValue == "orderBtn") {
                    var orderArr = [];
                    
                    for (var i = 0; i < this.contacts.length; i++) {
                        if (document.getElementById(this.contacts[i].stock_id).checked == true && document.getElementsByName(this.contacts[i].stock_id)[2].value > 0) {
                            orderArr.push({orderProductName: this.contacts[i].drink_name, orderCount: Number(document.getElementsByName(this.contacts[i].stock_id)[2].value),
                                            orderCompanyName: this.contacts[i].cp_name, orderProductPrice: Number(this.contacts[i].drink_price), allMoney: Number(document.getElementsByName(this.contacts[i].stock_id)[2].value) * Number(this.contacts[i].drink_price)});
                        }
                    }
                    

                    if(orderArr.length != 0) {
                        this.$emit('clicked', e.target.attributes[0].nodeValue, orderArr);
                    }
                    else {
                        alert("주문 제품 선택 및 주문량을 입력하시오.");
                    }
                }
                else {
                    this.$emit('clicked', e.target.attributes[0].nodeValue);
                }
            },
            // 화면 이동 버튼 클릭 시 이벤트를 발생시켜 부모에게 값을 전달



            trClick: function(trName) {
                this.clickTr = document.getElementsByName(trName)[0];

                if (this.clickTr.style.backgroundColor == "#5f95a7") {
                    this.clickTr.style.backgroundColor = "#f6f9fa";
                }
                else {
                    if (this.clickedTr != "") {
                        this.clickedTr.style.backgroundColor = "#f6f9fa";
                    }

                    this.saveStockId = trName;
                    this.clickedTr = this.clickTr;
                    this.clickTr.style.backgroundColor = "#5f95a7";
                }
            },
            // 클릭한 tr태그 배경색 변경 및 클린한 태그 name 저장


            getData: function() {
                let url = "product/getProductData";

                this.axios.get(url)
                .then((response) => {
                    this.contacts = response.data;
                })
                .catch()
            },
            // 제품 정보 저장


            checkedCheck: function(orderCompanyName, orderProduct_id) {
                var checkboxTag = document.getElementById(orderProduct_id);
                var orderCompanyCount = document.getElementById("orderCompanyCount");
                var orderSheetCount = document.getElementById("orderSheetCount");

                if(checkboxTag.checked) {
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

                    this.orderCompanyArr.push(orderCompanyName);
                    this.orderCountCalculate(orderProduct_id);
                }
                else {
                    for(var i = 0; i < this.orderCompanyArr.length; i++) {
                        if (this.orderCompanyArr[i] == orderCompanyName) {
                            this.companyNameCheck = true;
                            this.orderCompanyArr.splice(i,1);
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

                    this.orderCountCalculate(orderProduct_id);
                }
            },
            // 주문서 수 및 주문한 회사 수 계산


            inputNumberCheck: function(orderProduct) {
                var orderProductCount = document.getElementsByName(orderProduct)[2].value;
                var checkboxTag = document.getElementById(orderProduct);
                var text = "";
                
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

                this.allOrderCount = 0;
                this.allOrderMoney = 0;
                
                for(var i = 0; i < this.contacts.length; i++) {
                    if(document.getElementById(this.contacts[i].stock_id).checked) {
                        this.allOrderCount += Number(document.getElementsByName(this.contacts[i].stock_id)[2].value);
                        this.allOrderMoney += (Number(document.getElementsByName(this.contacts[i].stock_id)[2].value) * Number(this.contacts[i].drink_price));
                    }
                }

                document.getElementById("allOrderCountTag").innerText = String(this.allOrderCount);
                document.getElementById("allOrderMoneyTag").innerText = String(this.allOrderMoney);
            },
            // 돈 계산


            orderCountCalculate: function(orderProduct) {
                var checkboxTag = document.getElementById(orderProduct);

                if(checkboxTag.checked) {
                    this.allOrderCount += Number(document.getElementsByName(orderProduct)[2].value);
                    document.getElementById("allOrderCountTag").innerText = String(this.allOrderCount);

                    this.allOrderMoney += (Number(document.getElementsByName(orderProduct)[1].innerText) * Number(document.getElementsByName(orderProduct)[2].value));
                    document.getElementById("allOrderMoneyTag").innerText = String(this.allOrderMoney);
                }
                else {
                    this.allOrderCount -= Number(document.getElementsByName(orderProduct)[2].value);
                    document.getElementById("allOrderCountTag").innerText = String(this.allOrderCount);

                    this.allOrderMoney -= (Number(document.getElementsByName(orderProduct)[1].innerText) * Number(document.getElementsByName(orderProduct)[2].value));
                    document.getElementById("allOrderMoneyTag").innerText = String(this.allOrderMoney);
                }
            }
            // 체크박스에 체크 할 시 돈 계산
        }
    }
</script>
<style>
    table {
        text-align: center;
    }


    #inputOrderCount {
        width: 100px;
        background-color: white;
        border: 1px solid rgb(153, 200, 223);
    }


    #tableStyle {
        display: block;
        width: 900px;
        border-collapse: collapse;
    }
    #tableStyle th { background: rgb(153, 200, 223); }
    #tableStyle td { border-top: 0; }
    #tableStyle tbody {
        display: block;
        height: 400px;
        overflow: auto;
    }
    #tableStyle tfoot {
        background: rgb(153, 200, 223); 
    }
    #tableStyle th:nth-of-type(1), #tableStyle td:nth-of-type(1) { width: 110px; }
    #tableStyle th:nth-of-type(2), #tableStyle td:nth-of-type(2) { width: 100px; }
    #tableStyle th:nth-of-type(3), #tableStyle td:nth-of-type(3) { width: 100px; }
    #tableStyle th:nth-of-type(4), #tableStyle td:nth-of-type(4) { width: 100px; }
    #tableStyle th:nth-of-type(5), #tableStyle td:nth-of-type(5) { width: 100px; }
    #tableStyle th:nth-of-type(6), #tableStyle td:nth-of-type(6) { width: 100px; }
    #tableStyle th:nth-of-type(7), #tableStyle td:nth-of-type(7) { width: 100px; }
    #tableStyle th:nth-of-type(8), #tableStyle td:nth-of-type(8) { width: 80px; }
    #tableStyle th:nth-of-type(9), #tableStyle td:nth-of-type(9) { width: 100px; }
    #tableStyle th:last-child { width: 100px; }
    #tableStyle td:last-child { width: calc( 100px - 19px );  }
</style>
