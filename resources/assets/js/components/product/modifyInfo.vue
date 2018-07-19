<template>
    <div>
        <div>
            <center><h1>製品修正</h1></center><br>
            <div id="leftDiv">
                <!-- 업로드 이미지 미리보기 -->
                <picture-input
                    :id="showImg" 
                    ref="pictureInput" 
                    width="300" 
                    height="600" 
                    margin="16" 
                    accept="image/png"
                    size="5"
                    :z-index="zIndexValue" 
                    :prefill="preImage"
                    buttonClass="btn"
                    :customStrings="{
                        upload: '<h1>Bummer!</h1>',
                        drag: 'イメージを入ってください。'
                }"></picture-input>
                <!-- 업로드 이미지 미리보기 -->
            </div>
            <div id="rightDiv">
                <table id="productInfo">
                    <tr>
                        <td>製品名:</td><td><input type="text" :id="productName" :value="productNameValue" class="inputTextStyle"></td>
                    </tr>
                    <tr>
                        <td>最大在庫:</td><td><input type="text" :id="maxCount" :value="maxCountValue" class="inputTextStyle">BOX</td>
                    </tr>
                    <tr>
                        <td>現在在庫:</td><td><input type="text" :id="nowCount" :value="nowCountValue" class="inputTextStyle">BOX</td>
                    </tr>
                    <tr>
                        <td>単価</td><td><input type="text" :id="productPrice" :value="productPriceValue" class="inputTextStyle">円</td>
                    </tr>
                    <tr>
                        <td>保存期限</td>
                        <td><v-btn dark @click.native.stop="dialog = true" style="width: 100%; height: 90%;">{{limitDate}}</v-btn></td>
                    </tr>
                    <tr>
                        <td>会社名</td>
                        <td>
                            <input type="text" :id="companyName" :value="companyNameValue" class="inputTextStyle" readonly>
                            <b-btn v-b-modal.modal1 @click="modalClick">探す</b-btn>
                        </td>
                    </tr>
                </table>
            </div>
            <div>      
                <b-button @click="modify">修正する</b-button>
                <b-button @click="onClickButton">取り消し</b-button>
            </div>
        </div>
        <!-- 달력 모달창-->
        <v-dialog v-model="dialog" max-width="400">
            <v-card>
                <v-date-picker color="green lighten-1" v-model="pickerDate" :landscape="landscape" :reactive="reactive"></v-date-picker>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" flat="flat" @click.native="dialog = false" >取り消し</v-btn>
                    <v-btn color="green darken-1" flat="flat" @click.native="dialog = false" @click="limitDateChange()">確認</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- 달력 모달창-->

        <!-- 회사 검색 모달창 -->
        <b-modal id="modal1" title="회사정보 검색" hide-footer ref="uploadModal">
            <div>
                <table>
                    <tr>
                        <td>
                            <input type="text" :id="inputCompanyName" style="width: 200px;">
                        </td>
                        <td>
                            <b-btn @click="findCompany">検索</b-btn>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <table id="companyList">
                    <thead>
                        <th>選択</th><th>会社名</th><th>製品数</th>
                    </thead>
                    <tbody>
                        <tr v-for="company in searchCompany" :key="company.id">
                            <td><input type="radio" :id="company.cp_name" @click="pickedCompany(company.cp_name)"></td>
                            <td>{{company.cp_name}}</td>
                            <td>{{company.drink_count}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div>
                <b-btn @click="finish">完了</b-btn>
                <b-btn @click="cancel">取り消し</b-btn>
            </div>
        </b-modal>
        <!-- 회사 검색 모달창 -->
    </div>    
</template>
<script>
    import PictureInput from 'vue-picture-input'                // 업로드 이미지 미리보기

    export default {
        components: {
            PictureInput
        },
        // 이미지 태그

        props: ['sendProductId'],                               // 부모에게 받은 제품이름 
        data(){
            return{
                preImage            : "",                       // 바꾸기 전 이미지 url
                zIndexValue         : 1,                        // 이미지 업로드 태그 우선 순위 설정 값
                showImg             : "showImg",                // 이미지 태그 아이디
                productName         : "productName",            // 제품명 입력 태그 아이디
                productNameValue    : "",                       // 제품이름
                maxCount            : "maxCount",               // 최대재고 입력 태그 아이디
                maxCountValue       : "",                       // 최대재고 값
                nowCount            : "nowCount",               // 현재재고 입력 태그 아이디
                nowCountValue       : "",                       // 현재재고 값
                inputCompanyName    : "inputCompanyName",       // 회사명 입력 태그 아이디
                companyName         : "companyName",            // 모달의 회사명 입력 태그 아이디
                companyNameValue    : "",                       // 회사명
                productPrice        : "productPrice",           // 단가 입력 태그 아이디
                productPriceValue   : "",                       // 단가 값
                contacts            : [],                       // 회사 정보
                contactsProduct     : [],                       // 제품 정보
                productInfo         : [],                       // 모달창에 보여 줄 회사정보
                searchCompany       :[],                        // 검색 한 회사 정보
                picked              : "",                       // 회사검색에서 선택한 회사명
                originName          : "",                       // 바뀌기 전 제품명
                limitDate           : "",                       // 유통기한
                dialog              : false,                    // 모달 창 상태
                pickerDate          : null,                     // 모달 안 달력에서 클릭 된 날짜
                landscape           : false,                    // 달력 상태
                reactive            : false,                    // 달력 상태
                companyInfoUrl      : "product/getCompanyData"  // 회사정보 url
            }
        },
        mounted() {
            this.getChoiceProduct();                            // 선택되어진 회사 정보 호출
        },
        methods: {
            limitDateChange(){
                this.limitDate = this.pickerDate;
            },
            // 선택한 날짜 저장


            modalClick: function() {
                this.axios.get(this.companyInfoUrl)
                .then((response) => {
                    this.productInfo.push(response.data);
                    this.searchCompany = [];

                    for(var i = 0; i < this.productInfo[0].length; i++) {
                        this.searchCompany.push({"cp_name":this.productInfo[0][i].cp_name, "drink_count":this.productInfo[0][i].drink_count});
                    }
                    // 모든 회사 정보 저장
                })
                .catch((error) => {
                    console.log(error.response);
                    alert("회사 정보를 가져오는 것에 실패하였습니다.");
                })
            },
            // 모달 클릭시 회사 정보 보여주기


            getChoiceProduct: function() {
                this.axios.get(this.companyInfoUrl)
                .then((response) => {
                    this.contactsProduct = response.data;
                    
                    for (var i = 0; i < this.contactsProduct.length; i++) {
                        if (this.contactsProduct[i].stock_id == this.sendProductId) {
                            // 해당 회사 정보 확인

                            //----------------------- 회사 정보 태그에 넣기-----------------------
                            this.productNameValue = this.contactsProduct[i].drink_name;
                            this.originName = this.contactsProduct[i].drink_name;
                            this.maxCountValue = this.contactsProduct[i].max_stock;
                            this.nowCountValue = this.contactsProduct[i].stock;
                            this.productPriceValue = this.contactsProduct[i].drink_price;
                            this.companyNameValue = this.contactsProduct[i].cp_name;
                            this.preImage = 'images/drink/' + this.productNameValue + '.png';
                            var date = this.contactsProduct[i].expiration_date;

                            this.limitDate = date.split(" ")[0];
                            //----------------------- 회사 정보 태그에 넣기-----------------------

                            break;
                        }
                    }
                    
                })
            },
            // 회사정보에서 회사명에 맞는 데이터들을 태그에 넣어주기


            onClickButton: function() {
                this.$emit('clicked', "start");
            },
            // 메인화면으로 이동


            findCompany: function() {
                this.axios.get(this.companyInfoUrl)
                .then((response) => {
                    this.contacts.push(response.data);

                    this.picked = "";
                    this.searchCompany = [];
                    var inputText = document.getElementById(this.inputCompanyName).value;
                    // 검색된 정보 초기화
            
                    for(var i = 0; i < this.contacts[0].length; i++) {
                        if(this.contacts[0][i].cp_name.toLowerCase().match(inputText.toLowerCase())) {
                            // 검색된 회사 정보 확인

                            this.searchCompany.push({"cp_name":this.contacts[0][i].cp_name, "drink_count":this.contacts[0][i].drink_val_of_company});
                        }
                    }
                })
            },
            // 회사 검색에서 검색된 회사에 맞는 데이터 저장


            finish: function() {
                if (document.getElementById(this.inputCompanyName).value != "") {
                    document.getElementById(this.inputCompanyName).value = "";
                }
                
                if (this.picked != "") {
                    document.getElementById(this.picked).checked = false;
                }
                // 모달창 초기화

                this.searchCompany = [];        
                this.companyNameValue = this.picked;
                this.$refs.uploadModal.hide();
            },
            // 모달창 확인 시 선택한 회사명 저장 및 검색한 값들 초기화


            cancel : function(){
                if (document.getElementById(this.inputCompanyName).value != "") {
                    document.getElementById(this.inputCompanyName).value = "";
                }
                
                if (this.picked != "") {
                    document.getElementById(this.picked).checked = false;
                }
                // 모달창 초기화

                this.searchCompany = [];
                this.$refs.uploadModal.hide();
            },
            // 모달창 취소 시 검색한 값 초기화 및 모달창 끄기


            pickedCompany : function(companyName){
                for(var i = 0; i < this.searchCompany.length; i++) {
                    if(this.searchCompany[i]["cp_name"] == companyName) {
                        continue;
                    }
                    else {
                        document.getElementById(this.searchCompany[i]["cp_name"]).checked = false;
                    }
                }

                this.picked = companyName;
            },
            // 회사 검색에서 선택한 회사명 저장


            modify: function() {
                var inputProdctName = document.getElementById(this.productName).value;
                var inputMaxCount = document.getElementById(this.maxCount).value;
                var inputNowCount = document.getElementById(this.nowCount).value;
                var inputProductPrice = document.getElementById(this.productPrice).value;
                var inputCompanyName = document.getElementById(this.companyName).value;
                // 현재 값 가져오기

                if(inputProdctName == "") {
                    alert("제품명을 입력하시오");
                }
                else if (inputMaxCount == "") {
                    alert("최대재고를 입력하시오");
                }
                else if (inputNowCount == "") {
                    alert("현재재고를 입력하시오");
                }
                else if (inputProductPrice == "") {
                    alert("단가를 입력하시오");
                }
                else if (inputCompanyName == "") {
                    alert("회사명을 입력하시오");
                }
                else {
                    // 값 유무확인

                    var fileInput = document.getElementById(this.showImg);
                    
                    const formData = new FormData();

                    if (fileInput.value != "") {
                        formData.append('fileUse', true);
                        formData.append('image', fileInput.files[0]);
                    }
                    else {
                        formData.append('fileUse', false);
                    }
                    // 파일 변경 유무 확인

                    formData.append('name', inputProdctName);
                    formData.append('stock_id', this.sendProductId);
                    formData.append('original_name', this.originName);
                    formData.append('drink_name', inputProdctName);
                    formData.append('cp_name', inputCompanyName);
                    formData.append('drink_price', inputProductPrice);
                    formData.append('stock', inputNowCount);
                    formData.append('max_stock', inputMaxCount);
                    formData.append('expiration_date', this.limitDate);
                    // 전송할 데이터

                    this.axios.post("product/updateProduct", formData)
                    .then((response) => {
                        if(response.data == inputProdctName) {
                            alert("같은 이름의 제품이 있습니다. 이름을 변경해주세요");
                            document.getElementById(this.showImg).value = "";
                        }
                        else {
                            console.log(response);
                            alert("수정되었습니다.");
                            this.onClickButton();
                        }
                    })
                    .catch((error) => {
                        console.log(error.response);
                        alert("수정에 실패하였습니다.");
                        this.onClickButton();
                    })
                }                
            }
            // 수정하기
        }
    }
</script>
<style>
    .inputTextStyle {
        width: 180px;
        background-color: white;
        border: 1px solid rgb(153, 200, 223);
    }

    #productInfo {
        font-size: 20px;
        text-align: left;
        width: 500px;
        height: 500px;
    }
    #productInfo th { width: 100px; height: 100px; }
    #productInfo td { width: 100px; height: 100px; }
    #productInfo tr { width: 100px; height: 100px; }

    #companyList {
        text-align: center;
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