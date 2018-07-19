<template>
    <div>
        <div>
            <center><h1>製品登録</h1></center><br>
            <!-- 업로드한 이미지 미리보기 -->
            <div id="leftDiv">
                <picture-input
                    :id="showImg" 
                    ref="pictureInput" 
                    width="300" 
                    height="600" 
                    margin="16" 
                    accept="image/png"
                    :z-index="zIndexValue"
                    size="5" 
                    buttonClass="btn"
                    :customStrings="{
                        upload: '<h1>Bummer!</h1>',
                        drag: 'イメージを入ってください。'
                    }">
                </picture-input>
            </div>
            <!-- 업로드한 이미지 미리보기 -->
            <div id="rightDiv">
                <v-container fluid>
                    <v-layout row>
                        <v-flex xs2 md2>
                            <v-subheader>製品名</v-subheader>
                        </v-flex>
                        <v-flex xs4 md4>
                            <v-text-field class="input-group--focused" :id="productName"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row>
                        <v-flex xs2 md2>
                            <v-subheader>最大在庫</v-subheader>
                        </v-flex>
                        <v-flex xs4 md4>
                            <v-text-field class="input-group--focused" :id="maxCount"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row>
                        <v-flex xs2 md2>
                            <v-subheader>現在在庫</v-subheader>
                        </v-flex>
                        <v-flex xs4 md4>
                            <v-text-field class="input-group--focused" :id="nowCount"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row>
                        <v-flex xs2 md2>
                            <v-subheader>単価</v-subheader>
                        </v-flex>
                        <v-flex xs4 md4>
                            <v-text-field class="input-group--focused" :id="productPrice"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row>
                        <v-flex xs2 md2>
                            <v-subheader>保存期間</v-subheader>
                        </v-flex>
                        <v-flex xs4 md4>
                            <v-btn dark @click.native.stop="dialog = true, calendar = true" style="width: 100%; height: 90%;">{{limitDate}}</v-btn>
                        </v-flex>
                    </v-layout>
                    <v-layout row>
                        <v-flex xs2 md2>
                            <v-subheader>会社名</v-subheader>
                        </v-flex>
                        <v-flex xs2 md2>
                            <v-text-field class="input-group--focused" :id="companyName" readonly :value="findCompanyName"></v-text-field>
                        </v-flex>
                        <v-flex xs2 md2>
                            <v-btn  dark @click.native.stop="dialog = true" style="width: 100%; height: 60%;" @click="modalClick">探す</v-btn>
                        </v-flex>
                    </v-layout>
                    <v-layout row>
                        <v-flex xs2 md3>
                            <v-btn @click="upload">登録する</v-btn>
                        </v-flex>
                        <v-flex xs2 md3>
                            <v-btn @click="onClickButton">取り消し</v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>

                <!-- 달력 및 회사 검색 모달창 -->
                <v-dialog v-model="dialog" max-width="400">
                    <v-card v-if="calendar == true">
                        <v-date-picker color="green lighten-1" v-model="pickerDate" :landscape="landscape" :reactive="reactive"></v-date-picker>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" flat="flat" @click.native="dialog = false, calendar = false" >取り消し</v-btn>
                            <v-btn color="green darken-1" flat="flat" @click.native="dialog = false, calendar = false" @click="limitDateChange()">確認</v-btn>
                        </v-card-actions>
                    </v-card>
                    <v-card v-else>
                        <v-container fluid>
                            <v-layout row>
                                <v-flex xs8>
                                    <v-text-field class="input-group--focused" :id="inputCompanyName"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-btn dark @click="getData" style="width: 100%; height: 50%;">検索</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-container fluid>
                            <v-layout row>
                                <v-flex xs5>
                                    <v-subheader>会社名</v-subheader>
                                </v-flex>
                                <v-flex xs3>
                                    <v-subheader>製品数</v-subheader>
                                </v-flex>
                            </v-layout>
                            <v-layout row v-for="company in searchCompany" :key="company.id">
                                <v-flex xs5>
                                    <v-radio-group v-model="findCompanyName">
                                        <v-radio :label="company.cp_name" :value="company.cp_name"></v-radio>
                                    </v-radio-group>
                                </v-flex>
                                <v-flex xs3>
                                    {{company.drink_count}}
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" flat="flat" @click.native="dialog = false">確認</v-btn>
                            <v-btn color="green darken-1" flat="flat" @click.native="dialog = false">取り消し</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <!-- 달력 및 회사 검색 모달창 -->
            </div>
        </div>
    </div>    
</template>
<script>
    import PictureInput from 'vue-picture-input'            // 업로드한 이미지 미리보기

    export default {
        components: {
            PictureInput
        },
        data(){
            return{
                zIndexValue         : 1,                    // 이미지 업로드 태그 우선 순위 설정 값
                showImg             : "showImg",            // 업로드할 이미지 태그 아이디
                productName         : "productName",        // 제품명 입력 태그 아이디
                maxCount            : "maxCount",           // 최대재고 입력 태그 아이디
                nowCount            : "nowCount",           // 현재재고 입력 태그 아이디
                inputCompanyName    : "inputCompanyName",   // 회사 검색에서 회사명 입력 태그 아이디
                companyName         : "companyName",        // 회사명 입력 태그 아이디
                contacts            : [],                   // 회사 정보
                searchCompany       :[],                    // 검색한 회사의 정보
                productPrice        : "productPrice",       // 단가 입력 태그 아이디
                contactsProduct     : [],                   // 검색 모달 창에 보여줄 회사 정보
                limitDate           : "",                   // 유통기한
                dialog              : false,                // 모달 창 상태
                pickerDate          : null,                 // 모달 안 달력에서 클릭 되는 날짜
                landscape           : false,                // 달력 상태
                reactive            : false,                // 달력 상태
                calendar            : false,                // 달력 모달창 상태
                findCompanyName     : ""                    // 선택한 회사 이름
            }
        },
        mounted() {
            var today = new Date();
            var day = today.getDate();
            var month = today.getMonth() + 1;
            var year = today.getFullYear();
            
            if (day < 10) {
                day = "0" + day;
            }

            if (month < 10) {
                month = "0" + month;
            }

            this.limitDate = year + "." + month + "." + day;
            // 현재 날짜 계산
        },
        methods: {
            limitDateChange(){
                this.limitDate = this.pickerDate;
            },
            // 선택한 날짜 저장


            modalClick: function() {
                let getCompanyUrl = "product/getCompanyData";       // 회사 정보 url

                this.axios.get(getCompanyUrl)
                .then((response) => {
                    this.contactsProduct.push(response.data);
                    
                    this.searchCompany = [];
            
                    for(var i = 0; i < this.contactsProduct[0].length; i++) {
                        this.searchCompany.push({"cp_name":this.contactsProduct[0][i].cp_name, "drink_count":this.contactsProduct[0][i].drink_val_of_company});
                    }
                })
            },
            // 모달 클릭시 회사 정보 보여주기


            onClickButton: function() {
                this.$emit('clicked', "start");
            },
            // 메인 화면으로 이동


            getData: function() {
                let getCompanyUrl = "product/getCompanyData";       // 회사 정보 url

                this.axios.get(getCompanyUrl)
                .then((response) => {
                    this.contacts.push(response.data);

                    this.searchCompany = [];                                                    // 입력한 값이 들어간 회사 정보 배열 초기화
                    var inputText = document.getElementById(this.inputCompanyName).value;       // 입력한 값

                    for(var i = 0; i < this.contacts[0].length; i++) {
                        if(this.contacts[0][i].cp_name.toLowerCase().match(inputText.toLowerCase())) {
                            // 입력한 값이 들어간 회사 찾기

                            this.searchCompany.push({"cp_name":this.contacts[0][i].cp_name, "drink_count":this.contacts[0][i].drink_count});
                        }
                    }
                })
            },
            // 회사 검색시 입력한 값과 같은 회사 정보 출력


            upload: function() {
                var inputProdctName = document.getElementById(this.productName).value;
                var inputMaxCount = document.getElementById(this.maxCount).value;
                var inputNowCount = document.getElementById(this.nowCount).value;
                var inputProductPrice = document.getElementById(this.productPrice).value;
                var inputCompanyName = document.getElementById(this.companyName).value;
                var inputImg = document.getElementById(this.showImg).value;
                // 현재 입력된 값

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
                else if (inputImg == "") {
                    alert("이미지파일을 입력하시오");
                }
                else {
                    // 값 유무 확인

                    let uploadUrl = 'product/productImgSave';       // 업로드 url

                    const fileInput = document.getElementById(this.showImg);
                    const formData = new FormData();
                    formData.append('image', fileInput.files[0]);
                    formData.append('name', inputProdctName);
                    formData.append('drink_name', inputProdctName);
                    formData.append('cp_name', inputCompanyName);
                    formData.append('drink_price', inputProductPrice);
                    formData.append('stock', inputNowCount);
                    formData.append('max_stock', inputMaxCount);
                    formData.append('expiration_date', this.limitDate);
                    // 전송할 데이터

                    this.axios.post (uploadUrl, formData)
                    .then((response) => {
                        if(response.data == inputProdctName) {
                            alert("같은 이름의 제품이 있습니다. 이름을 변경해주세요");
                        }
                        else {
                            alert("등록되었습니다.");
                            this.onClickButton();
                        }
                    })
                    .catch((error) => {
                        console.log(error.response);
                        alert("업로드에 실패하였습니다.");
                    })
                }                
            }
            // 제품 등록
        }
    }
</script>
<style>
    #productInfo {
        text-align: left;
    }
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