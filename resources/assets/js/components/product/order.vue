<template>
    <div>
        <div id="leftDiv">
            <div style="width: 100%;">
                <div style="text-align:left; font-family:'Dosis'; margin-bottom: 10px;">
                    <h2><strong>Product Order</strong></h2>
                </div>
                <div style="width: 100%; margin-bottom: 10px;">
                    <v-btn color="primary" style="border-radius: 15px; width: 100%;">
                        <font size="5" style="font-family:'Dosis'; margin-bottom: 8px;">HAJAE Company</font>
                    </v-btn>
                </div>
                <div style="width: 100%; margin-bottom: 20px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: left;">
                                <font color="#0064c8" size="4">
                                    担当者:
                                </font>
                            </td>
                            <td style="text-align: right;">
                                <font size="4" style="font-family: 'Dosis';">
                                    <strong>{{inputLeader}}</strong>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <font color="#0064c8" size="4">
                                    電話番号:
                                </font>    
                            </td>
                            <td style="text-align: right;">
                                <font size="4" style="font-family: 'Fugaz+One';">
                                    <strong>{{inputCompanyNumber}}</strong>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <font color="#0064c8" size="4">
                                    メール:
                                </font>
                            </td>
                            <td style="text-align: right;">
                                <font size="4" style="font-family: 'Dosis';">
                                    <strong>{{inputCompanyMail}}</strong>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <font color="#0064c8" size="4">
                                    ファックス番号:
                                </font>
                            </td>
                            <td style="text-align: right;">
                                <font size="4" style="font-family: 'Fugaz+One';">
                                    <strong>{{inputCompanyFax}}</strong>
                                </font>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="width: 100%;">
                    <table class="orderProductTable" style="width: 100%;">
                        <thead style="text-align: left; color: #0064c8; border-bottom: 3px solid #0064c8;">
                            <tr>
                                <th></th>
                                <th><font size="4">製品<br>注文量(box)</font></th>
                                <th><font size="4">製品<br>単価(円)</font></th>
                                <th><font size="4">合計<br>金額(円)</font></th>
                            </tr>
                        </thead>
                        <tbody style="text-align: left;">
                            <tr v-for="item in oneCompanyProduct" :key="item.id">
                                <td><img :src="'/images/drink/' + item.orderProductName + '.png'" style="width: 30px; height: 40px; margin: 5px;"></td>
                                <td>
                                    <font size="5" style="font-family: 'Fugaz+One';">
                                        <strong>{{item.orderCount}}</strong>
                                    </font>
                                </td>
                                <td>
                                    <font size="5" style="font-family: 'Fugaz+One';">
                                        <strong>{{item.orderProductPrice}}</strong>
                                    </font>
                                </td>
                                <td>
                                    <font size="5" style="font-family: 'Fugaz+One';">
                                        <strong>{{item.allMoney}}</strong>
                                    </font>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="text-align: right;">
                                <td colspan="4">
                                    <div style="border-radius: 15px; width: 100%; height: 70px; background-color: #0064c8; color: white; padding-top: 8px;">
                                        <div style="margin-right: 30px;">
                                            <h5>総合計(円)</h5>
                                            <font size="5" color="white" style="font-family: 'Fugaz+One';">{{orderMoney}}</font>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div><br>
                <div style="width: 100%;">
                    <h5 style="text-align: left; color: #0064c8;">要求事項:</h5>
                    <textarea id="sendText" style="width: 100%; border-radius: 15px; border: 3px solid #0064c8;">
                        
                    </textarea>
                </div><br>
                <div v-if="nowCompanyOrderCheck == false">
                    <v-btn color="primary" style="width: 40%; height: 60px; border-radius: 8px;" @click="order(nowCompanyId)">
                        <font size="4" style="margin-bottom: 10px;">注文する</font>
                    </v-btn>
                    <v-btn color="blue-grey" style="width: 40%; height: 60px; border-radius: 8px;" @click="cancel">
                        <font color="white" size="4" style="margin-bottom: 10px;">戻る</font>
                    </v-btn>
                </div>
                <div v-else>
                    <v-btn style="width: 40%; height: 60px; border-radius: 8px;" disabled>
                        <font size="4" style="margin-bottom: 10px;">注文完了なった</font>
                    </v-btn>
                    <v-btn color="blue-grey" style="width: 40%; height: 60px; border-radius: 8px;" @click="cancel">
                        <font color="white" size="4" style="margin-bottom: 10px;">戻る</font>
                    </v-btn>
                </div>
            </div>
        </div>
        <div id="rightDiv">
            <div>
                <div v-for="n in allCompanyCount" :key="n.index" style="float: left; margin-left: 20px;">
                    <v-btn v-if="oneCompanyCount == n" color="orange" @click="sheetMove(n)">
                        <font color="white" size="5" style="font-family: 'Fugaz+One'; margin-bottom: 5px;">{{n}}</font>
                    </v-btn>
                    <v-btn v-else color="grey darken-4" @click="sheetMove(n)">
                        <font color="white" size="5" style="font-family: 'Fugaz+One'; margin-bottom: 5px;">{{n}}</font>
                    </v-btn>
                </div>
            </div>
            <div style="width: 95%;">
                <iframe id="preview" :src="previewFile"></iframe>   <!-- 주문서 미리보기 -->
            </div>
        </div>

        <v-dialog v-model="checkedImage" width="1300px">
            <v-card style="width: 100%; display: grid; grid-template-columns: 0.05fr 0.9fr 0.05fr;">
                <div>
                    
                </div>
                <div id="testpdf" style="margin: 60px;">
                    <div style="display: grid; grid-template-columns: 0.2fr 0.8fr;">
                        <div style="border-top: 5px solid #0064c8; margin-right: 20px;">
                            {{orderDay}}
                        </div>
                        <div style="text-align: right; border-top: 5px solid #0064c8;">
                            Print in {{orderDay}}
                        </div>
                    </div><br><br>
                    <div style="display: grid; grid-template-columns: 0.2fr 0.8fr;">
                        <div>
                        
                        </div>
                        <div>
                            <div>
                                <img src="/images/logo.png" style="width: 100px; height: 40px;">
                            </div>
                            <div>
                                <font size="10" style="font-family: 'Dosis';">HAJAE Company</font>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 50px; display: grid; grid-template-columns: 0.2fr 0.8fr;">
                        <div style="text-align: right; margin-right: 20px; margin-top: 20px; font-family: 'Dosis';">
                            <font size="4">HAJAE Company</font>
                        </div>
                        <div style="width: 100%;">
                            <table style="margin-top: 0px; width: 100%;">
                                <tr style="width: 100%; height: 50%;">
                                    <td style="height: 90px;">
                                        <div style="height: 100%; border-top: 5px solid #0064c8;">
                                            <font size="5">担当者</font>
                                        </div>
                                    </td>
                                    <td style="height: 90px;">
                                        <div style="height: 100%; border-top: 5px solid #0064c8; margin-right: 20px;">
                                            <font size="5" style="font-family: 'Dosis';">{{inputLeader}}</font>
                                        </div>
                                    </td>
                                    <td style="height: 90px;">
                                        <div style="height: 100%; border-top: 5px solid #0064c8;">
                                            <font size="5">電話番号</font>
                                        </div>
                                    </td>
                                    <td style="height: 90px;">
                                        <div style="height: 100%; border-top: 5px solid #0064c8;">
                                            <font size="5" style="font-family: 'Fugaz+One';"><strong>{{inputCompanyNumber}}</strong></font>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="width: 100%; height: 50%;">
                                    <td style="height: 90px;">
                                        <div style="height: 100%; border-top: 5px solid #0064c8;">
                                            <font size="5">メール</font>
                                        </div>
                                    </td>
                                    <td style="height: 90px;">
                                        <div style="height: 100%; border-top: 5px solid #0064c8; margin-right: 20px;">
                                            <font size="5" style="font-family: 'Dosis';">{{inputCompanyMail}}</font>
                                        </div>
                                    </td>
                                    <td style="height: 90px;">
                                        <div style="height: 100%; border-top: 5px solid #0064c8;">
                                            <font size="5">ファックス番号</font>
                                        </div>
                                    </td>
                                    <td style="height: 90px;">
                                        <div style="height: 100%; border-top: 5px solid #0064c8;">
                                            <font size="5" style="font-family: 'Fugaz+One';"><strong>{{inputCompanyFax}}</strong></font>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 0.2fr 0.8fr;">
                        <div style="text-align: right; margin-right: 20px;">
                            <font size="4"><strong>注文表</strong></font>
                        </div>
                        <div style="width: 100%;">
                            <table class="orderProductTable" style="width: 100%; text-align: left;">
                                <thead style="text-align: left; color: #0064c8; border-bottom: 3px solid #0064c8;">
                                    <tr>
                                        <th>製品名</th><th>製品注文量(box)</th><th>製品単価(円)</th><th>合計 金額(円)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in oneCompanyProduct" :key="item.id">
                                        <td>
                                            <font size="5" style="font-family: 'Dosis';">
                                                <strong>{{item.orderProductName}}</strong>
                                            </font>
                                        </td>
                                        <td>
                                            <font size="5" style="font-family: 'Fugaz+One';">
                                                <strong>{{item.orderCount}}</strong>
                                            </font>
                                        </td>
                                        <td>
                                            <font size="5" style="font-family: 'Fugaz+One';">
                                                <strong>{{item.orderProductPrice}}</strong>
                                            </font>
                                        </td>
                                        <td>
                                            <font size="5" style="font-family: 'Fugaz+One';">
                                                <strong>{{item.allMoney}}</strong>
                                            </font>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td><td></td>
                                        <td>
                                            <div style="width: 100%; border-top: 3px solid #0064c8;">
                                                <font size="5"><strong>総合計(円)</strong></font>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width: 100%; border-top: 3px solid #0064c8;">
                                                <font size="5" style="font-family: 'Fugaz+One';"><strong>{{orderMoney}}</strong></font>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div><br><br>
                    <div style="display: grid; grid-template-columns: 0.2fr 0.8fr;">
                        <div style="text-align: right; margin-right: 20px;">
                            <font size="4"><strong>要求事項</strong></font>
                        </div>
                        <div style="width: 100%; height: 100px; background-color: #CFD8DC;">
                            
                        </div>
                    </div><br><br>
                    <div style="text-align: center; width: 100%; border-top: 3px solid #0064c8; display: grid; grid-template-columns: 0.3fr 0.4fr 0.3fr;">
                        <div>
                            <font size="5" style="font-family: 'Dosis';">HAJAE Company</font><font size="5"> 担当者</font>
                        </div>
                        <div>
                            <font size="5">名前: </font><font size="5" style="font-family: 'Dosis';">{{inputLeader}}</font>
                        </div>
                        <div>
                            <font size="5">(印)</font>
                            <img :src="inputSign" style="width: 50px; height: 50px;">
                        </div>
                    </div>
                </div>
                <div>
                    <v-btn color="primary" @click="checkedImage = false" style="width: 80%; height: 100px; border-radius: 10px; margin-top: 100px;"><h3>確認</h3></v-btn>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import jsPDF from 'jspdf'                       // pdf 생성 
    import html2canvas from 'html2canvas'           // 이미지화

    export default {
        props: ['sendOrderArr'],                    // 주문화 제품 정보
        data() {
            return {
                allCompanyCount         : 0,        // 전체 주문 회사 수
                oneCompanyCount         : 1,        // 주문서 페이지 번호
                oneCompanyProduct       : [],       // 한 회사에 주문한 음료들
                companyDataArr          : [],       // 회사 정보
                uniqueCompanyArr        : [],       // 중복 없는 주문 제품
                onlyOneCompanyCheck     : false,    // 중복 체크
                inputCompanyName        : "",       // 회사 이름
                inputCompanyNumber      : "",       // 회사 전화번호
                inputCompanyMail        : "",       // 회사 이메일
                inputCompanyFax         : "",       // 회사 팩스
                inputLeader             : "",       // 담당자
                inputSign               : "",       // 서명
                orderDay                : "",       // 주문 날짜
                orderMoney              : "",       // 금액
                previewFile             : "",       // 주문서
                myCompany               : [],       // 우리회사 정보
                nowCompanyId            : "",       // 현재 회사의 아이디
                orderCheckArr           : [],       // 주문 회사 아이디
                nowCompanyOrderCheck    : false,    // 주문 회사 아이디 중복체크
                nowFile                 : "",       // 현재 주문서 파일
                saveInputText           : [],       // 요구사항
                checkedImage            : true      // 이미지 파일 생성 확인
            }
        },
        mounted() {
            this.getCompanyData(0);                 // 주문한 회사 정보 및 키츠나 회사 정보 호출
        },
        methods: {
            sheetMove: function(moveNum) {
                this.oneCompanyCount = moveNum;
                this.saveInputText[this.oneCompanyCount - 1] = document.getElementById("sendText").value;       // 요구사항 저장
                document.getElementById("sendText").value = "";                                                 // 요구사항 초기화
                this.getCompanyData(this.oneCompanyCount - 1);                                                  // 페이지 변경
                this.checkedImage = true;
            },
            // 주문서 페이지 변경


            cancel: function() {
                this.$emit('clicked', "start");
            },
            // 메인화면으로 이동


            createPdf: function(saveOrShow) {
                if (saveOrShow == true) {
                    // 주문

                    var pdfSendUrl = "product/sendPDF";             // pdf파일 저장 url

                    const formData = new FormData();
                    formData.append("pdfFile", this.nowFile);
                    formData.append("cp_id", this.nowCompanyId);
                    // 전송할 데이터

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
                        console.log(error.response);
                        alert("메일 전송이 실패하였습니다.22");
                    })
                }
                else {
                    // pdf 파일 생성

                    var obj = this;

                    html2canvas(document.getElementById("testpdf")).then(function (canvas) {
                        var imgData = canvas.toDataURL('image/jpeg');       // 이미지 데이터화
                        var pdf = new jsPDF('p', 'mm', 'a4');               // pdf 파일 생성
                        
                        pdf.addImage(imgData, 'JPEG', 25, 25, 160, 250);    // pdf 파일에 이미지 넣기

                        obj.nowFile = pdf.output('blob');                   // pdf 파일화
                        
                        var data = pdf.output('datauristring');             // pdf 파일 스트링화
                        document.getElementById("preview").src = data;
                    });
                    // 해당 div를 이미지화 한 후 pdf에 넣기
                }
            },
            // 주문서 제작

            getCompanyData: function(pageCount) {
                this.oneCompanyProduct = [];
                this.companyDataArr = [];
                this.uniqueCompanyArr = [];
                this.orderMoney = 0;
                // 초기화

                let companyDataUrl = 'product/getCompanyInfo';      // 회사정보 url

                this.axios.get(companyDataUrl)
                .then((response) => {
                    for(var i = 0; i < response.data.length; i++) {
                        for(var j = 0; j < this.sendOrderArr.length; j++) {
                            if(this.sendOrderArr[j].orderCompanyName == response.data[i].cp_name) {
                                // 주문한 회사정보 찾기

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
                    // 회사 정보 중복 제거

                    this.allCompanyCount = this.uniqueCompanyArr.length;    // 주문한 전체 회사 수

                    var myCompanyInfoUrl = "product/getMyCompanyInfo";      // 키츠나 회사 정보 url

                    this.axios.get(myCompanyInfoUrl)
                    .then((response) => {
                        this.myCompany = response.data[0];
                        
                        this.inputCompanyName = this.myCompany.management_company;
                        this.inputCompanyNumber = this.myCompany.phone;
                        this.inputCompanyMail = this.myCompany.mail;
                        this.inputCompanyFax = this.myCompany.fax;
                        this.inputLeader = this.myCompany.leader;
                        this.inputSign = this.myCompany.sign_path;
                        // 키츠나 회사 정보 입력

                        var today = new Date();
                        var day = today.getDate();
                        var month = today.getMonth() + 1;
                        var year = today.getFullYear();

                        this.orderDay = year + "." + month + "." + day;
                        // 주문 날짜

                        this.createPdf(false);      // pdf 파일 생성
                    })
                    .catch((error) => {
                        console.log(error.response);
                        alert("키츠나 회사정보를 가져오는 것에 실패하였습니다.");
                    })

                    for (var i = 0; i < this.sendOrderArr.length; i++) {
                        if (this.uniqueCompanyArr[pageCount].cp_name == this.sendOrderArr[i].orderCompanyName) {
                            this.oneCompanyProduct.push(this.sendOrderArr[i]);

                            this.orderMoney = Number(this.orderMoney) + Number(this.sendOrderArr[i].allMoney);
                            // 주문 금액 계산
                        }    
                    }

                    this.nowCompanyId = this.uniqueCompanyArr[pageCount].cp_id;     // 현재 주문서를 받는 회사 이름

                    if (this.saveInputText[this.oneCompanyCount - 1].length == 0) {
                        document.getElementById("sendText").value = "";
                    }
                    else {
                        document.getElementById("sendText").value = this.saveInputText[this.oneCompanyCount - 1];
                    }
                    // 요구사항 유무에 따라 입력
                    
                    this.nowCompanyOrderCheck = false;

                    for(var i = 0; i < this.orderCheckArr.length; i++) {
                        if (this.nowCompanyId == this.orderCheckArr[i])  {
                            this.nowCompanyOrderCheck = true;
                            break;
                        }
                    }
                    // 주문 유무 확인
                })
                .catch((error) => {
                    console.log(error.response);
                })
            },
            // 주문한 회사 정보 및 우리회사 정보 호출


            order: function(order_cp_id) {
                if (this.orderCheckArr.length == this.allCompanyCount - 1) {
                    // 마지막 주문 시

                    var comfirmFlag = confirm("마지막 주문입니다. 계속 진행하시면 메인화면으로 이동합니다. 진행하시겠습니까?")

                    if (comfirmFlag == true) {
                        // 주문 진행 시

                        this.orderCheckArr.push(order_cp_id);               // 주문한 회사 추가

                        var orderUrl = 'product/insertOrderSheetColumn';    // 주문 url
                        var controllerSendArr = Array();                    // 주문하는 회사와 제품을 넣을 배열
                        var obj = {};                                       // 제품의 정보를 넣을 배열

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
                        // 주문형식으로 변경

                        var orderInfo = {
                            orderInfo: controllerSendArr
                        }
                        // 주문 객체 생성

                        this.axios.post(orderUrl, orderInfo)
                        .then((response) => {
                            if (response.data == "good") {
                                this.createPdf(true);       // pdf 파일 전송
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
                    // 마지막 주문이 아닐 시
                    
                    this.orderCheckArr.push(order_cp_id);                   // 주문한 회사 추가

                    var orderUrl = 'product/insertOrderSheetColumn';        // 주문 url
                    var controllerSendArr = Array();                        // 주문하는 회사와 제품을 넣을 배열
                    var obj = {};                                           // 제품의 정보를 넣을 배열

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
                    // 주문형식으로 변경


                    var orderInfo = {
                        orderInfo: controllerSendArr
                    }
                    // 주문 객체 생성

                    this.axios.post(orderUrl, orderInfo)
                    .then((response) => {
                        if (response.data == "good") {
                            this.createPdf(true);       // pdf 파일 전송
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
    table.orderProductTable tr:nth-child(even) {
        background: #E3F2FD;
    }

    table {
        text-align: center;
    }

    #preview {
        width: 100%;
        height: 600px;
    }

    #sendText {
        width: 500px;
        height: 100px;
        background-color: white;
        border: 1px solid rgb(153, 200, 223);
    }

    #leftDiv {
        width: 30%;
        float: left;
    }
    #rightDiv {
        display: grid;
        grid-template-rows: 0.05fr 0.95fr;
        width: 70%;
        float: right;
    }
</style>