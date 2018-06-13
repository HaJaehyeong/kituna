<template>
     <div>
        <table id="companyInfoTable">
            <thead>
                <tr>
                    <th>회사명</th><th>제품수</th><th>담당자</th><th>전화번호</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="contact in contacts" :key="contact.id" :id="contact.cp_name" @click="clickTr(contact.cp_name)" @mouseover="trColorChange(contact.cp_name)" @mouseout="trOriginColorChange(contact.cp_name)">
                    <td>{{contact.cp_name}}</td>
                    <td>{{contact.drink_val_of_company}}</td>
                    <td>{{contact.cp_leader}}</td>
                    <td>{{contact.cp_phone}}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <div>
            <b-btn v-b-modal.modal1 @click="companyRegistration">회사등록</b-btn>
            <b-btn @click="companyInfoUpdate">회사수정</b-btn>
            <b-btn @click="companyRemove">회사삭제</b-btn>
        </div>
        <div>
            <b-modal id="modal1" :title="modalTitle" hide-footer ref="company">
                <div>
                    <table>
                        <tr>
                            <th>회사명</th><td><input type="text" :id="inputCompanyName" class="inputOrderCount"></td>
                        </tr>
                        <tr>
                            <th>담당자</th><td><input type="text" :id="inputCompanyLeader" class="inputOrderCount"></td>
                        </tr>
                        <tr>
                            <th>전화번호</th><td><input type="text" :id="inputCompanyPhone" class="inputOrderCount"></td>
                        </tr>
                        <tr>
                            <th>메일</th>
                            <td>
                                <input type="text" :id="inputLeaderMail_id" class="inputOrderCount">@<input type="text" :id="inputLeaderMail_site" class="inputOrderCount">
                                <select id="site" @change="changeSite">
                                    <option value="input">직접입력</option>
                                    <option value="naver.com">naver</option><option value="google.com">google</option>
                                    <option value="daum.net">daum</option><option value="nate.com">nate</option>
                                    <option value="yjp.co.kr">yjp</option><option value="yahoo.com">yahoo</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>팩스번호</th><td><input type="text" :id="inputCompanyFax" class="inputOrderCount"></td>
                        </tr>
                    </table>
                </div>
                <br>
                <div v-if="registration == true">
                    <b-btn @click="finish">등록</b-btn>
                    <b-btn @click="cancel">취소</b-btn>
                </div>
                <div v-else>
                    <b-btn @click="finish">수정</b-btn>
                    <b-btn @click="cancel">취소</b-btn>
                </div>
            </b-modal>
        </div>
        <div>
            <b-modal id="modal2" :title="modalTitle" hide-footer ref="companyRemove">
                <div>
                    <table>
                        <tr>
                            <th>회사명</th><td>{{removeInfoArr[0]}}</td>
                        </tr>
                        <tr>
                            <th>담당자</th><td>{{removeInfoArr[1]}}</td>
                        </tr>
                        <tr>
                            <th>전화번호</th><td>{{removeInfoArr[2]}}</td>
                        </tr>
                        <tr>
                            <th>메일</th><td>{{removeInfoArr[3]}}</td>
                        </tr>
                        <tr>
                            <th>팩스번호</th><td>{{removeInfoArr[4]}}</td>
                        </tr>
                    </table>
                </div>
                <br>
                <div>
                    <b-btn @click="finish">삭제</b-btn>
                    <b-btn @click="cancel">취소</b-btn>
                </div>
            </b-modal>
        </div>
    </div>    
</template>
<script>
    export default {
        data(){
            return{
                contacts: [],                                   // 회사정보
                clickedTrId: "",                                // 클릭한 tr 아이디
                inputCompanyName: "inputCompanyName",           // 회사이름 입력 태그 아이디
                inputCompanyLeader: "inputCompanyLeader",       // 담당자 입력 태그 아이디
                inputCompanyPhone: "inputCompanyPhone",         // 전화번호 입력 태그 아이디
                inputCompanyFax: "inputCompanyFax",             // 팩스 입력 태그 아이디
                inputLeaderMail_id: "inputLeaderMail_id",       // 이메일 아이디 입력 태그 아이디
                inputLeaderMail_site: "inputLeaderMail_site",   // 이메일 사이트 입력 태그 아이디
                companyInfo: [],                                // 수정할 회사 정보
                uploadCp_id: "",                                // 수정할 회사 아이디
                removeCp_id: "",                                // 삭제할 회사 아이디
                registration: false,                            // 회사 등록 모달창 상태
                upload: false,                                  // 회사 정보 수정 모달창 상태
                remove: false,                                  // 회사 삭제 모달창 상태
                modalTitle: "",                                 // 모달창 제목
                removeInfoArr: []                               // 삭제할 회사 정보
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
            },
            // 회사 등록 모달창 오픈


            finish: function() {
                if (this.registration == true) {
                    var companyName = document.getElementById(this.inputCompanyName).value;
                    var companyLeader = document.getElementById(this.inputCompanyLeader).value;
                    var companyPhone = document.getElementById(this.inputCompanyPhone).value;
                    var inputMail_id = document.getElementById(this.inputLeaderMail_id).value;
                    var inputMail_site = document.getElementById(this.inputLeaderMail_site).value;
                    var companyFax = document.getElementById(this.inputCompanyFax).value;

                    if (Number(companyFax) == NaN) {
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
                        else {
                            var registrationUrl = "product/registerCompanyInfo";
                            var mail = inputMail_id + "@" + inputMail_site;

                            const formData = new FormData();
                            formData.append("cp_name", companyName);
                            formData.append("cp_leader", companyLeader);
                            formData.append("cp_phone", companyPhone);
                            formData.append("cp_mail", mail);
                            formData.append("cp_fax", companyFax);

                            this.axios.post(registrationUrl, formData)
                            .then((response) => {
                                if (response.data == "good") {
                                    alert("등록되었습니다.");
                                    this.getData();
                                    this.registration = false;
                                    this.$refs.company.hide();
                                }
                                else {
                                    alert("등록에 실패하였습니다.");
                                }
                            })
                            .catch((error) => {
                                console.log(error.response);
                                alert("등록에 실패하였습니다.");
                            })
                        }
                    }
                }
                else if (this.upload == true) {
                    var companyName = document.getElementById(this.inputCompanyName).value;
                    var companyLeader = document.getElementById(this.inputCompanyLeader).value;
                    var companyPhone = document.getElementById(this.inputCompanyPhone).value;
                    var inputMail_id = document.getElementById(this.inputLeaderMail_id).value;
                    var inputMail_site = document.getElementById(this.inputLeaderMail_site).value;
                    var companyFax = document.getElementById(this.inputCompanyFax).value;

                    if (Number(companyFax) == NaN) {
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
                        else {
                            var uploadUrl = "product/updateCompanyInfo";
                            var mail = inputMail_id + "@" + inputMail_site;

                            const formData = new FormData();
                            formData.append("cp_id", this.uploadCp_id);
                            formData.append("cp_name", companyName);
                            formData.append("cp_leader", companyLeader);
                            formData.append("cp_phone", companyPhone);
                            formData.append("cp_mail", mail);
                            formData.append("cp_fax", companyFax);

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

                                    this.upload = false;
                                    this.$refs.company.hide();
                                }
                                else {
                                    
                                }
                            })
                            .catch((error) => {
                                console.log(error.response);
                            })
                        }
                    }
                }
                else {
                    var removeUrl = "product/deleteCompanyInfo/" + this.removeCp_id;

                    this.axios.get(removeUrl)
                    .then((response) => {
                        if (response.data == "good") {
                            alert("삭제되었습니다.");
                            this.getData();
                            this.$refs.companyRemove.hide();
                        }
                        else {
                            alert("아직 회사의 제품이 남아있습니다.");
                        }
                    })
                    .catch((error) => {
                        console.log(error.response);
                        alert("삭제에 실패하였습니다.");
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
                    document.getElementById(trCompnayName).style.backgroundColor = 'rgba(88, 88, 88, 0.4)';
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
                })
            },
            // 모든 회사 정보 데이터 저장


            companyInfoUpdate: function() {
                if (this.clickedTrId == "") {
                    alert("회사를 선택하시오.");
                }
                else {
                    this.upload = true;

                    var getCompanyInfo = "product/getCompanyInfo";

                    this.axios.get(getCompanyInfo)
                    .then((response) => {
                        this.companyInfo = response.data;

                        for (var i = 0; i < this.companyInfo.length; i++) {
                            if (this.clickedTrId == this.companyInfo[i].cp_name) {
                                this.uploadCp_id = this.companyInfo[i].cp_id;
                                document.getElementById(this.inputCompanyName).value = this.companyInfo[i].cp_name;
                                document.getElementById(this.inputCompanyLeader).value = this.companyInfo[i].cp_leader;
                                document.getElementById(this.inputCompanyPhone).value = this.companyInfo[i].cp_phone;
                                document.getElementById(this.inputCompanyFax).value = this.companyInfo[i].cp_fax;

                                var mailFrontEnd = this.companyInfo[i].cp_mail.split("@");
                                
                                document.getElementById(this.inputLeaderMail_id).value = mailFrontEnd[0];
                                document.getElementById(this.inputLeaderMail_site).value = mailFrontEnd[1];
                                
                                break;
                            }
                        }

                        this.modalTitle = "회사수정";

                        this.$refs.company.show();
                    })
                    .catch((error) => {

                    })
                }
            },
            // 수정 모달창에 넣을 회사 정보


            companyRemove: function() {
                if (this.clickedTrId == "") {
                    alert("회사를 선택하시오.");
                }
                else {
                    this.remove = true;

                    var getCompanyInfo = "product/getCompanyInfo";

                    this.axios.get(getCompanyInfo)
                    .then((response) => {
                        this.companyInfo = response.data;

                        for (var i = 0; i < this.companyInfo.length; i++) {
                            if (this.clickedTrId == this.companyInfo[i].cp_name) {
                                this.removeCp_id = this.companyInfo[i].cp_id;

                                this.removeInfoArr.push(this.companyInfo[i].cp_name);
                                this.removeInfoArr.push(this.companyInfo[i].cp_leader);
                                this.removeInfoArr.push(this.companyInfo[i].cp_phone);
                                this.removeInfoArr.push(this.companyInfo[i].cp_mail);
                                this.removeInfoArr.push(this.companyInfo[i].cp_fax);
                                
                                break;
                            }
                        }

                        this.modalTitle = "회사삭제";

                        this.$refs.companyRemove.show();
                    })
                    .catch((error) => {

                    })
                }
            },
            // 삭제 모달창에 넣을 회사 정보


            clickTr: function(clickTrId) {
                if (this.clickedTrId != "") {
                    document.getElementById(this.clickedTrId).style.backgroundColor = '#f6f9fa';
                }

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