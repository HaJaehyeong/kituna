<template>
     <div style="width: 100%">
        <!-- 회사 정보 테이블 -->
        <div style="text-align:left; font-family:'Dosis';">
            <h2><strong>Product</strong></h2>
        </div>
        <div style="width: 100%; height: 650px; background-color: #0064c8; padding-top: 15px; border-radius: 15px; position: relative;">
            <div v-for="contact in contacts" :key="contact.id" style="width: 90%; height: 100px; margin: 15px; display: grid; grid-template-columns: 0.7fr 0.3fr;">
                <div :id="contact.cp_name" @click="clickTr(contact.cp_name)" @mouseover="trColorChange(contact.cp_name)" @mouseout="trOriginColorChange(contact.cp_name)" style="margin-right: 5px; border-radius: 15px; width: 95%; background-color: white;">
                    <h4 style="font-family: 'Dosis'; color: #0064c8; margin-top: 5px;">{{contact.cp_name}}</h4>
                    <h4 style="font-family: 'Dosis';">{{contact.cp_leader}}</h4>
                    <h4 style="font-family: 'Fugaz+One'; color: #0064c8;"><strong>{{contact.cp_phone}}</strong></h4>
                </div>
                <div style="border-radius: 15px; width: 100%; background-color: white;">
                    <h4 style="margin-top: 10px;">製品数</h4>
                    <strong><font size="6" style="font-family: 'Fugaz+One';" color="#0064c8">{{contact.drink_val_of_company}}</font></strong>
                </div>
            </div>
            <div v-b-modal.modal1 @click="companyRegistration" style="align: center; width: 90%; border-radius: 15px; height: 100px; background-color: white; color: #0064c8;">
                <font size="6">+</font>
                <h4>新しい会社を追加する</h4>
            </div>
            <div style="position: absolute; left: 70px; bottom: 5px;">
                <v-btn @click="companyInfoUpdate" style="border-radius: 8px;"><h6 style="color: #0064c8;">会社情報修正</h6></v-btn>
                <v-btn @click="companyRemove" color="error" style="border-radius: 8px;"><h6>会社削除</h6></v-btn>
            </div>
        </div>
        <div>
            <!-- 회사 등록, 수정 모달창 -->
            <b-modal id="modal1" :title="modalTitle" hide-footer ref="company">
                <div>
                    <table>
                        <tr>
                            <th>会社名</th><td><input type="text" :id="inputCompanyName" class="inputOrderCount"></td>
                        </tr>
                        <tr>
                            <th>担当者</th><td><input type="text" :id="inputCompanyLeader" class="inputOrderCount"></td>
                        </tr>
                        <tr>
                            <th>電話番号</th><td><input type="text" :id="inputCompanyPhone" class="inputOrderCount"></td>
                        </tr>
                        <tr>
                            <th>メール</th>
                            <td>
                                <input type="text" :id="inputLeaderMail_id" class="inputOrderCount">@<input type="text" :id="inputLeaderMail_site" class="inputOrderCount">
                                <select id="site" @change="changeSite">
                                    <option value="input">直接入力</option>
                                    <option value="naver.com">naver</option><option value="google.com">google</option>
                                    <option value="daum.net">daum</option><option value="nate.com">nate</option>
                                    <option value="yjp.co.kr">yjp</option><option value="yahoo.com">yahoo</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>ファックス番号</th><td><input type="text" :id="inputCompanyFax" class="inputOrderCount"></td>
                        </tr>
                    </table>
                </div>
                <br>
                <div v-if="registration == true">
                    <b-btn @click="finish">登録する</b-btn>
                    <b-btn @click="cancel">取り消し</b-btn>
                </div>
                <div v-else>
                    <b-btn @click="finish">修正する</b-btn>
                    <b-btn @click="cancel">取り消し</b-btn>
                </div>
            </b-modal>
            <!-- 회사 등록, 수정 모달창 -->
        </div>
        <div>
            <!-- 회사 삭제 모달창 -->
            <b-modal id="modal2" :title="modalTitle" hide-footer ref="companyRemove">
                <div>
                    <table>
                        <tr>
                            <th>会社名</th><td>{{removeInfoArr[0]}}</td>
                        </tr>
                        <tr>
                            <th>担当者</th><td>{{removeInfoArr[1]}}</td>
                        </tr>
                        <tr>
                            <th>電話番号</th><td>{{removeInfoArr[2]}}</td>
                        </tr>
                        <tr>
                            <th>メール</th><td>{{removeInfoArr[3]}}</td>
                        </tr>
                        <tr>
                            <th>ファックス番号</th><td>{{removeInfoArr[4]}}</td>
                        </tr>
                    </table>
                </div>
                <br>
                <div>
                    <b-btn @click="finish">削除する</b-btn>
                    <b-btn @click="cancel">取り消し</b-btn>
                </div>
            </b-modal>
            <!-- 회사 삭제 모달창 -->
        </div>
    </div>    
</template>
<script>
    export default {
        data(){
            return{
                contacts                : [],                           // 회사정보
                clickedTrId             : "",                           // 클릭한 tr 아이디
                inputCompanyName        : "inputCompanyName",           // 회사이름 입력 태그 아이디
                inputCompanyLeader      : "inputCompanyLeader",         // 담당자 입력 태그 아이디
                inputCompanyPhone       : "inputCompanyPhone",          // 전화번호 입력 태그 아이디
                inputCompanyFax         : "inputCompanyFax",            // 팩스 입력 태그 아이디
                inputLeaderMail_id      : "inputLeaderMail_id",         // 이메일 아이디 입력 태그 아이디
                inputLeaderMail_site    : "inputLeaderMail_site",       // 이메일 사이트 입력 태그 아이디
                companyInfo             : [],                           // 수정할 회사 정보
                uploadCp_id             : "",                           // 수정할 회사 아이디
                removeCp_id             : "",                           // 삭제할 회사 아이디
                registration            : false,                        // 회사 등록 모달창 상태
                upload                  : false,                        // 회사 정보 수정 모달창 상태
                remove                  : false,                        // 회사 삭제 모달창 상태
                modalTitle              : "",                           // 모달창 제목
                removeInfoArr           : []                            // 삭제할 회사 정보
            }
        },
        mounted(){
            this.getData();                                     // 회사 정보 호출
        },
        methods: {
            changeSite: function () {
                document.getElementById(this.inputLeaderMail_site).value = document.getElementById("site").value;
            },
            // 사이트 변경

            companyRegistration: function () {
                this.registration = true;

                document.getElementById(this.inputCompanyName).value = "";
                document.getElementById(this.inputCompanyLeader).value = "";
                document.getElementById(this.inputCompanyPhone).value = "";
                document.getElementById(this.inputLeaderMail_id).value = "";
                document.getElementById(this.inputLeaderMail_site).value = "";
                document.getElementById(this.inputCompanyFax).value = "";
                document.getElementById("site").value = "input";
                this.modalTitle = "회사등록";
                // 모달창 초기화
            },
            // 회사 등록 모달창 오픈


            finish: function() {
                if (this.registration == true) {
                    // 회사 등록

                    var companyName = document.getElementById(this.inputCompanyName).value;
                    var companyLeader = document.getElementById(this.inputCompanyLeader).value;
                    var companyPhone = document.getElementById(this.inputCompanyPhone).value;
                    var inputMail_id = document.getElementById(this.inputLeaderMail_id).value;
                    var inputMail_site = document.getElementById(this.inputLeaderMail_site).value;
                    var companyFax = document.getElementById(this.inputCompanyFax).value;
                    // 현재 값 가져오기

                    if (Number(companyFax) == NaN) {
                        // 팩스번호가 숫자가 아닌 경우

                        alert("숫자를 입력하시오.");
                        document.getElementById(this.inputCompanyFax).value = "";
                    }
                    else {
                        if(companyName == "") {
                            alert("회사명을 입력하시오.");
                        }
                        else if (companyLeader == "") {
                            alert("담당자를 입력하시오.");
                        }
                        else if (companyPhone == "") {
                            alert("전화번호를 입력하시오.");
                        }
                        else if (inputMail_id == "") {
                            alert("아이디를 입력하시오.");
                        }
                        else if (inputMail_site == "") {
                            alert("메일을 입력하시오.");
                        }
                        else if (companyFax == "") {
                            alert("팩스를 입력하시오.");
                        }
                        // 값 유무확인

                        else {
                            var registrationUrl = "product/registerCompanyInfo";    // 등록 url
                            var mail = inputMail_id + "@" + inputMail_site;         // 입력한 이메일 주소

                            const formData = new FormData();
                            formData.append("cp_name", companyName);
                            formData.append("cp_leader", companyLeader);
                            formData.append("cp_phone", companyPhone);
                            formData.append("cp_mail", mail);
                            formData.append("cp_fax", companyFax);
                            // 전송할 데이터

                            this.axios.post(registrationUrl, formData)
                            .then((response) => {
                                if (response.data == "good") {
                                    alert("등록되었습니다.");
                                    this.getData();
                                    this.registration = false;
                                    this.$refs.company.hide();
                                }
                                else {
                                    console.log("등록에 실패하였습니다.");
                                }
                            })
                            .catch((error) => {
                                console.log(error.response);
                                console.log("등록에 실패하였습니다.");
                            })
                        }
                    }
                }
                else if (this.upload == true) {
                    // 회사 정보 수정

                    var companyName = document.getElementById(this.inputCompanyName).value;
                    var companyLeader = document.getElementById(this.inputCompanyLeader).value;
                    var companyPhone = document.getElementById(this.inputCompanyPhone).value;
                    var inputMail_id = document.getElementById(this.inputLeaderMail_id).value;
                    var inputMail_site = document.getElementById(this.inputLeaderMail_site).value;
                    var companyFax = document.getElementById(this.inputCompanyFax).value;
                    // 현재값 가져오기

                    if (Number(companyFax) == NaN) {
                        // 팩스번호가 숫자가 아닌 경우

                        alert("숫자를 입력하시오.");
                        document.getElementById(this.inputCompanyFax).value = "";
                    }
                    else {
                        if(companyName == "") {
                            alert("회사명을 입력하시오.");
                        }
                        else if (companyLeader == "") {
                            alert("담당자를 입력하시오.");
                        }
                        else if (companyPhone == "") {
                            alert("전화번호를 입력하시오.");
                        }
                        else if (inputMail_id == "") {
                            alert("아이디를 입력하시오.");
                        }
                        else if (inputMail_site == "") {
                            alert("메일을 입력하시오.");
                        }
                        else if (companyFax == "") {
                            alert("팩스를 입력하시오.");
                        }
                        // 값 유무확인

                        else {
                            var uploadUrl = "product/updateCompanyInfo";        // 수정 url
                            var mail = inputMail_id + "@" + inputMail_site;     // 입력한 이메일 주소

                            const formData = new FormData();
                            formData.append("cp_id", this.uploadCp_id);
                            formData.append("cp_name", companyName);
                            formData.append("cp_leader", companyLeader);
                            formData.append("cp_phone", companyPhone);
                            formData.append("cp_mail", mail);
                            formData.append("cp_fax", companyFax);
                            // 전송할 데이터

                            this.axios.post(uploadUrl, formData)
                            .then((response) => {
                                if(response.data == "good") {
                                    alert("수정되었습니다.");

                                    companyName = "";
                                    companyLeader = "";
                                    companyPhone = "";
                                    inputMail_id = "";
                                    inputMail_site = "";
                                    companyFax = "";
                                    document.getElementById("site").value = "input";
                                    // 모달창 값 초기화

                                    this.upload = false;
                                    this.$refs.company.hide();
                                }
                                else {
                                    console.log("수정에 실패하였습니다.");
                                }
                            })
                            .catch((error) => {
                                console.log(error.response);
                                console.log("수정에 실패하였습니다.");
                            })
                        }
                    }
                }
                else {
                    // 회사 삭제

                    var removeUrl = "product/deleteCompanyInfo/" + this.removeCp_id;    // 삭제 url

                    this.axios.get(removeUrl)
                    .then((response) => {
                        if (response.data == "good") {
                            alert("삭제되었습니다.");
                            this.getData();
                            this.$refs.companyRemove.hide();
                        }
                        else {
                            console.log("아직 회사의 제품이 남아있습니다.");
                        }
                    })
                    .catch((error) => {
                        console.log(error.response);
                        console.log("삭제에 실패하였습니다.");
                    })
                }
            },
            // 회사 등록, 수정, 삭제


            cancel: function() {
                if (this.registration == true || this.upload == true) {
                    this.registration = false;
                    this.upload = false;
                    this.$refs.company.hide();
                }
                else {
                    this.remove = false;
                    this.$refs.companyRemove.hide();
                }
            },
            // 모달창 닫기


            trOriginColorChange: function(trCompnayName) {
                if (this.clickedTrId != trCompnayName) {
                    document.getElementById(trCompnayName).style.backgroundColor = '#f6f9fa';
                }
            },
            // 지나간 tr 태그 원래 색으로 배경색 변경


            trColorChange: function(trCompnayName) {
                if (this.clickedTrId != trCompnayName) {
                    document.getElementById(trCompnayName).style.backgroundColor = 'skyblue';
                }
            },
            // 현재 마우스가 있는 tr 태그 원래 색으로 배경색 변경


            getData: function() {
                let url = "product/getCompanyData";

                this.axios.get(url)
                .then((response) => {
                    this.contacts = response.data;
                })
                .catch((error) => {
                    console.log(error.response);
                    console.log("회사 정보를 가져오기가 실패하였습니다.");
                })
            },
            // 모든 회사 정보 데이터 저장


            companyInfoUpdate: function() {
                if (this.clickedTrId == "") {
                    // 선택한 회사가 없을 경우

                    alert("회사를 선택하시오.");
                }
                else {
                    this.upload = true;

                    var getCompanyInfoUrl = "product/getCompanyInfo";      // 선택한 회사 정보 가져올 url

                    this.axios.get(getCompanyInfoUrl)
                    .then((response) => {
                        this.companyInfo = response.data;

                        for (var i = 0; i < this.companyInfo.length; i++) {
                            if (this.clickedTrId == this.companyInfo[i].cp_name) {
                                // 선택한 회사 찾기

                                this.uploadCp_id = this.companyInfo[i].cp_id;
                                document.getElementById(this.inputCompanyName).value = this.companyInfo[i].cp_name;
                                document.getElementById(this.inputCompanyLeader).value = this.companyInfo[i].cp_leader;
                                document.getElementById(this.inputCompanyPhone).value = this.companyInfo[i].cp_phone;
                                document.getElementById(this.inputCompanyFax).value = this.companyInfo[i].cp_fax;

                                var mailFrontEnd = this.companyInfo[i].cp_mail.split("@");
                                
                                document.getElementById(this.inputLeaderMail_id).value = mailFrontEnd[0];
                                document.getElementById(this.inputLeaderMail_site).value = mailFrontEnd[1];
                                // 찾은 회사 정보 입력
                                
                                break;
                            }
                        }

                        this.modalTitle = "회사수정";

                        this.$refs.company.show();
                    })
                    .catch((error) => {
                        console.log(error.response);
                        console.log("선택한 회사정보를 가져오는 것에 실패하였습니다.");
                    })
                }
            },
            // 수정 모달창에 넣을 회사 정보


            companyRemove: function() {
                if (this.clickedTrId == "") {
                    // 선택한 회사가 없을 경우

                    alert("회사를 선택하시오.");
                }
                else {
                    this.remove = true;

                    var getCompanyInfoUrl = "product/getCompanyInfo";   // 선택한 회사 정보 가져올 url

                    this.axios.get(getCompanyInfoUrl)
                    .then((response) => {
                        this.companyInfo = response.data;

                        for (var i = 0; i < this.companyInfo.length; i++) {
                            if (this.clickedTrId == this.companyInfo[i].cp_name) {
                                // 선택한 회사 찾기

                                this.removeCp_id = this.companyInfo[i].cp_id;

                                this.removeInfoArr.push(this.companyInfo[i].cp_name);
                                this.removeInfoArr.push(this.companyInfo[i].cp_leader);
                                this.removeInfoArr.push(this.companyInfo[i].cp_phone);
                                this.removeInfoArr.push(this.companyInfo[i].cp_mail);
                                this.removeInfoArr.push(this.companyInfo[i].cp_fax);
                                // 찾은 회사 정보 입력
                                
                                break;
                            }
                        }

                        this.modalTitle = "회사삭제";

                        this.$refs.companyRemove.show();
                    })
                    .catch((error) => {
                        console.log(error.response);
                        console.log("선택한 회사정보를 가져오는 것에 실패하였습니다.");
                    })
                }
            },
            // 삭제 모달창에 넣을 회사 정보


            clickTr: function(clickTrId) {
                if (this.clickedTrId != "") {
                    document.getElementById(this.clickedTrId).style.backgroundColor = '#f6f9fa';
                }
                // 원래 선택되어 있던 tr 원래 색으로 변경

                this.clickedTrId = clickTrId
                document.getElementById(clickTrId).style.backgroundColor = "#5f95a7";
            }
            // 클릭한 tr 태그 배경색 변경
        }
    }
</script>
<style>
    table {
        text-align: center;
    }

    .inputOrderCount {
        width: 100px;
        background-color: white;
        border: 1px solid rgb(153, 200, 223);
    }

    #companyInfoTable {
        width: 400px;
        border: 1px solid rgb(153, 200, 223);
    }
    #companyInfoTable th {
        border: 1px solid rgb(153, 200, 223);
        background-color: rgb(153, 200, 223);
    }
    #companyInfoTable tr {
        border: 1px solid rgb(153, 200, 223);
    }
</style>