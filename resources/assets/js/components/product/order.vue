<template>
    <div>
        <div id="leftDiv">
            <iframe id="preview" :src="previewFile"></iframe>
        </div>
        <div id="rightDiv">
            <div id="testpdf">
                <div>
                    <h3>{{inputCompanyName}}주문서</h3>
                </div>
                <br>
                <table id="CompanyInfoTable">
                    <tr>
                        <th colspan="4">
                            회사&nbsp;이름
                        </th>
                    </tr>
                    <tr>
                        <td colspan="4">
                            {{inputCompanyName}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            담당자
                        </th>
                        <td>
                            {{inputLeader}}
                        </td>
                        <th>
                            담당자&nbsp;번호
                        </th>
                        <td>
                            {{inputCompanyNumber}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            담장자&nbsp;메일
                        </th>
                        <td>
                            {{inputCompanyMail}}
                        </td>
                        <th>
                            회사&nbsp;팩스
                        </th>
                        <td>
                            {{inputCompanyFax}}
                        </td>
                    </tr>
                </table>
                <br>
                <table id="orderProductTable">
                    <thead>
                        <th>제품명</th><th>제품주문량</th><th>제품단가</th><th>제품총금액</th>
                    </thead>
                    <tbody>
                        <tr v-for="item in oneCompanyProduct" :key="item.id">
                            <td>{{item.orderProductName}}</td>
                            <td>{{item.orderCount}}</td>
                            <td>{{item.orderProductPrice}}</td>
                            <td>{{item.allMoney}}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>주문날짜</th>
                            <td>{{orderDay}}</td>
                            <th>합계금액</th>
                            <td>{{orderMoney}}</td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <h5>요구사항</h5>
                <textarea id="sendText">
                    
                </textarea>
                <br>
                <table id="signTable">
                    <tr>
                        <th>{{inputCompanyName}} 담당자</th>
                        <td>이름:&nbsp;&nbsp;{{inputLeader}}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;인:&nbsp;<img :src="inputSign" style="width: 30px; height: 30px;"></td>
                    </tr>
                </table>
            </div>
            <br>
            <div>
                <img src="images/left.jpg" @click="sheetMove('left')" style="width: 50px; height: 30px;" class="main_common">
                <h5 class="main_common">{{oneCompanyCount}}/{{allCompanyCount}}</h5>
                <img src="images/right.jpg" @click="sheetMove('right')" style="width: 50px; height: 30px;" class="main_common">
            </div>        
            <div v-if="nowCompanyOrderCheck == false">
                <b-button @click="order(nowCompanyId)">주문하기</b-button>
                <b-button @click="cancel">돌아가기</b-button>
            </div>
            <div v-else>
                <b-button>주문완료됨</b-button>
                <b-button @click="cancel">돌아가기</b-button>
            </div>
        </div>
    </div>
</template>
<script>
    import jsPDF from 'jspdf'                       // pdf 생성 
    import html2canvas from 'html2canvas'           // 이미지화

    export default {
        props: ['sendOrderArr'],                    // 주문화 제품 정보
        data() {
            return {
                allCompanyCount: 0,                 // 전체 주문 회사 수
                oneCompanyCount: 1,                 // 주문서 페이지 번호
                oneCompanyProduct: [],              // 한 회사에 주문한 음료들
                companyDataArr: [],                 // 회사 정보
                uniqueCompanyArr: [],               // 중복 없는 주문 제품
                onlyOneCompanyCheck: false,         // 중복 체크
                inputCompanyName: "",               // 회사 이름
                inputCompanyNumber: "",             // 회사 전화번호
                inputCompanyMail: "",               // 회사 이메일
                inputCompanyFax: "",                // 회사 팩스
                inputLeader: "",                    // 담당자
                inputSign: "",                      // 서명
                orderDay: "",                       // 주문 날짜
                orderMoney: "",                     // 금액
                previewFile: "",                    // 주문서
                myCompany: [],                      // 우리회사 정보
                nowCompanyId: "",                   // 현재 회사의 아이디
                orderCheckArr: [],                  // 주문 회사 아이디
                nowCompanyOrderCheck: false,        // 주문 회사 아이디 중복체크
                nowFile: "",                        // 현재 주문서 파일
                saveInputText: []                   // 요구사항
            }
        },
        mounted() {
            this.getCompanyData(0);
        },
        methods: {
            sheetMove: function(move) {
                
                if (move == "left") {
                    if (this.oneCompanyCount != 1) {
                        this.saveInputText[this.oneCompanyCount - 1] = document.getElementById("sendText").value;
                        document.getElementById("sendText").value = "";
                        this.oneCompanyCount--;
                        this.getCompanyData(this.oneCompanyCount - 1);
                    }
                    else {
                        alert("첫 주문서입니다.");
                    }
                }
                else {
                    if (this.oneCompanyCount != this.allCompanyCount) {
                        this.saveInputText[this.oneCompanyCount - 1] = document.getElementById("sendText").value;
                        document.getElementById("sendText").value = "";
                        this.oneCompanyCount++;
                        this.getCompanyData(this.oneCompanyCount - 1);
                    }
                    else {
                        alert("마지막 주문서입니다.");
                    }
                }
            },
            // 주문서 페이지 변경


            cancel: function() {
                this.$emit('clicked', "start");
            },
            // 메인으로 화면 변경


            createPdf: function(saveOrShow) {
                if (saveOrShow == true) {
                    var pdfSendUrl = "product/sendPDF";

                    const formData = new FormData();
                    formData.append("pdfFile", this.nowFile);
                    formData.append("cp_id", this.nowCompanyId);

                    this.axios.post(pdfSendUrl, formData)
                    .then((response) => {
                        if (response.data == "good") {
                            if (this.orderCheckArr.length == this.allCompanyCount) {
                                alert("모든 주문이 완료되었습니다.");
                                this.$emit('clicked', "start");
                            }
                            else {
                                this.nowCompanyOrderCheck = true;
                                alert("메일이 전송되었습니다.");
                            }
                        }
                        else {
                            alert("메일 전송이 실패하였습니다.11");
                        }
                    })
                    .catch((error) => {
                        alert("메일 전송이 실패하였습니다.22");
                        console.log(error.response);
                    })
                }
                else {
                    var obj = this

                    html2canvas(document.getElementById("testpdf")).then(function (canvas) {
                        var imgData = canvas.toDataURL('image/jpeg');
                        var pdf = new jsPDF('p', 'mm', 'a4');
                        
                        pdf.addImage(imgData, 'JPEG', 25, 25, 160, 250);

                        obj.nowFile = pdf.output('blob');
                        
                        var data = pdf.output('datauristring');
                        document.getElementById("preview").src = data;
                    });
                }
            },
            // 주문서 제작

            getCompanyData: function(pageCount) {
                this.oneCompanyProduct = [];
                this.companyDataArr = [];
                this.uniqueCompanyArr = [];
                this.orderMoney = 0;

                let companyDataUrl = 'product/getCompanyInfo';

                this.axios.get(companyDataUrl)
                .then((response) => {
                    for(var i = 0; i < response.data.length; i++) {
                        for(var j = 0; j < this.sendOrderArr.length; j++) {
                            if(this.sendOrderArr[j].orderCompanyName == response.data[i].cp_name) {
                                this.companyDataArr.push(response.data[i]);
                                this.sendOrderArr[j].orderCp_id = response.data[i].cp_id;
                            }
                        }
                    }

                    if (this.companyDataArr.length != 0) {
                        this.uniqueCompanyArr.push(this.companyDataArr[0]);
                    }

                    for (var i = 0; i < this.companyDataArr.length; i++) {
                        this.onlyOneCompanyCheck = false;

                        for (var j = 0; j < this.uniqueCompanyArr.length; j++) {
                            if (this.companyDataArr[i].cp_name == this.uniqueCompanyArr[j].cp_name) {
                                this.onlyOneCompanyCheck = true;
                            }
                        }

                        if (this.onlyOneCompanyCheck == false) {
                            this.uniqueCompanyArr.push(this.companyDataArr[i]);
                        } 
                    }

                    this.allCompanyCount = this.uniqueCompanyArr.length;

                    var myCompanyInfoUrl = "product/getMyCompanyInfo";

                    this.axios.get(myCompanyInfoUrl)
                    .then((response) => {
                        this.myCompany = response.data[0];
                        
                        this.inputCompanyName = this.myCompany.management_company;
                        this.inputCompanyNumber = this.myCompany.phone;
                        this.inputCompanyMail = this.myCompany.mail;
                        this.inputCompanyFax = this.myCompany.fax;
                        this.inputLeader = this.myCompany.leader;
                        this.inputSign = this.myCompany.sign_path;

                        var today = new Date();
                        var day = today.getDate();
                        var month = today.getMonth() + 1;
                        var year = today.getFullYear();

                        this.orderDay = year + "." + month + "." + day;

                        this.createPdf(false);
                    })
                    .catch((error) => {
                        console.log(error.response);
                    })

                    for (var i = 0; i < this.sendOrderArr.length; i++) {
                        if (this.uniqueCompanyArr[pageCount].cp_name == this.sendOrderArr[i].orderCompanyName) {
                            this.oneCompanyProduct.push(this.sendOrderArr[i]);

                            this.orderMoney = Number(this.orderMoney) + Number(this.sendOrderArr[i].allMoney);
                        }    
                    }

                    this.nowCompanyId = this.uniqueCompanyArr[pageCount].cp_id;

                    if (this.saveInputText[this.oneCompanyCount - 1].length == 0) {
                        document.getElementById("sendText").value = "";
                    }
                    else {
                        document.getElementById("sendText").value = this.saveInputText[this.oneCompanyCount - 1];
                    }
                    
                    this.nowCompanyOrderCheck = false;

                    for(var i = 0; i < this.orderCheckArr.length; i++) {
                        if (this.nowCompanyId == this.orderCheckArr[i])  {
                            this.nowCompanyOrderCheck = true;
                            break;
                        }
                    }
                })
                .catch((error) => {
                    console.log(error.response);
                })
            },
            // 주문한 회사 정보 및 우리회사 정보 호출


            order: function(order_cp_id) {
                if (this.orderCheckArr.length == this.allCompanyCount - 1) {
                    var comfirmFlag = confirm("마지막 주문입니다. 계속 진행하시면 메인화면으로 이동합니다. 진행하시겠습니까?")

                    if (comfirmFlag == true) {
                        this.orderCheckArr.push(order_cp_id);

                        var orderUrl = 'product/insertOrderSheetColumn';
                        var controllerSendArr = Array();
                        var obj = {};

                        controllerSendArr.push(order_cp_id);

                        for (var i = 0; i < this.sendOrderArr.length; i++) {
                            if (this.sendOrderArr[i].orderCp_id == order_cp_id) {
                                obj = {
                                    drink_name: this.sendOrderArr[i].orderProductName,
                                    drink_price: this.sendOrderArr[i].orderProductPrice,
                                    order_val: this.sendOrderArr[i].orderCount
                                };

                                controllerSendArr.push(obj);
                            }
                        }

                        var orderInfo = {
                            orderInfo: controllerSendArr
                        }

                        this.axios.post(orderUrl, orderInfo)
                        .then((response) => {
                            if (response.data == "good") {
                                this.createPdf(true);
                            }
                            else {
                                alert("주문 실패 하였습니다.11");
                            }
                        })
                        .catch((error) => {
                            console.log(error.response);
                            alert("주문 실패 하였습니다.22");
                        })
                    }
                }
                else {
                    this.orderCheckArr.push(order_cp_id);

                    var orderUrl = 'product/insertOrderSheetColumn';
                    var controllerSendArr = Array();
                    var obj = {};

                    controllerSendArr.push(order_cp_id);

                    for (var i = 0; i < this.sendOrderArr.length; i++) {
                        if (this.sendOrderArr[i].orderCp_id == order_cp_id) {
                            obj = {
                                drink_name: this.sendOrderArr[i].orderProductName,
                                drink_price: this.sendOrderArr[i].orderProductPrice,
                                order_val: this.sendOrderArr[i].orderCount
                            };

                            controllerSendArr.push(obj);
                        }
                    }

                    var orderInfo = {
                        orderInfo: controllerSendArr
                    }

                    this.axios.post(orderUrl, orderInfo)
                    .then((response) => {
                        if (response.data == "good") {
                            this.createPdf(true);
                        }
                        else {
                            alert("주문 실패 하였습니다.");
                        }
                    })
                    .catch((error) => {
                        console.log(error.response);
                        alert("주문 실패 하였습니다.");
                    })
                }
            }
            // 주문
        }
    }
</script>
<style>
    .main_common {
        display: inline-block;
        width: 100px;
        height: 50px;
    }

    table {
        text-align: center;
    }

    #CompanyInfoTable {
        width: 500px;
    }
    #CompanyInfoTable th { background: rgb(153, 200, 223); }
    #CompanyInfoTable td { border: 1px solid rgb(153, 200, 223); }
    #CompanyInfoTable tr { border: 1px solid rgb(153, 200, 223); }

    #orderProductTable {
        width: 500px;
    }
    #orderProductTable th { background: rgb(153, 200, 223); }
    #orderProductTable td { border: 1px solid rgb(153, 200, 223); }
    #orderProductTable tr { border: 1px solid rgb(153, 200, 223); }
    #orderProductTable tfoot { background: rgb(153, 200, 223); }

    #preview {
        width: 450px;
        height: 500px;
    }

    #signTable {
        width: 500px;
        border: 1px solid rgb(153, 200, 223);
        text-align: center;
    }
    #signTable th {
        background: rgb(153, 200, 223);
        width: 250px;
        height: 50px;
    }
    #signTable td {
        border: 1px solid rgb(153, 200, 223);
        width: 250px;
        height: 50px;
    }

    #testpdf {
        width: 500px;
    }

    #sendText {
        width: 500px;
        height: 100px;
        background-color: white;
        border: 1px solid rgb(153, 200, 223);
    }

    #rightDiv {
        width: 50%;
        float: right;
    }
    #leftDiv {
        width: 50%;
        float: left;
    }
</style>