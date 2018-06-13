<template>
    <div>
        <div>
            <center><h1>제품&nbsp;삭제</h1></center>
        </div>
        <br>
        <div>
            <div>
                <table id="removeTable1">
                    <thead>
                        <th>제품명</th>
                        <th>회사명</th>
                        <th>회사재고(BOX)</th>
                        <th>최대재고(BOX)</th>
                        <th>단가(원)</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{productName}}
                            </td>
                            <td>
                                {{companyName}}
                            </td>
                            <td>
                                {{nowCount}}
                            </td>
                            <td id="maxStock">

                            </td>
                            <td id="prodcutPrice">

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br><br><br>
            <div>
                <table id="removeTable2">
                    <thead>
                        <th>회사명</th>
                        <th>제품수</th>
                    </thead>
                    <tbody>
                        <td>
                            {{companyName}}
                        </td>
                        <td id="productCount">

                        </td>
                    </tbody>
                </table>
            </div>
        </div>
        <br><br>
        <div>
            <b-button @click="productRemove">제품삭제</b-button>
            <b-button @click="remove">재고삭제</b-button>
            <b-button @click="cancel">취소</b-button>
        </div>
    </div>    
</template>
<script>
    export default {
        props: ['sendProductId'],       // 부모에게 전달 받은 삭제할 제품명
        data() {
            return {
                contactsProduct: [],    // 제품의 정보
                contactsCompany: [],    // 회사의 정보
                companyName: "",        // 회사 이름
                productName: "",        // 제품 이름
                nowCount: 0             // 현재 재고
            }
        },
        mounted() {
            this.getData();             // 정보 호출
        },
        methods: {
            getData: function() {
                let urlProduct = "product/getProductData";   
                let urlCompany = "product/getCompanyData";        

                this.axios.get(urlProduct)
                .then((response) => {
                    this.contactsProduct = response.data;

                    for (var i = 0; i < this.contactsProduct.length; i++) {
                        if (this.contactsProduct[i].stock_id == this.sendProductId) {
                            this.productName = this.contactsProduct[i].drink_name
                            this.companyName = this.contactsProduct[i].cp_name;
                            this.nowCount = this.contactsProduct[i].stock;
                            document.getElementById("maxStock").innerText = this.contactsProduct[i].max_stock;
                            document.getElementById("prodcutPrice").innerText = this.contactsProduct[i].drink_price;
                            break;
                        }
                    }
                })

                this.axios.get(urlCompany)
                .then((response) => {
                    this.contactsCompany = response.data;

                    for (var i = 0; i < this.contactsCompany.length; i++) {
                        if (this.contactsCompany[i].cp_name == this.companyName) {
                            document.getElementById("productCount").innerText = this.contactsCompany[i].drink_val_of_company;
                           
                            break;
                        }
                    }
                })
            },
            // 회사 정보 및 제품 정보 중 해당 되는 회사의 데이터 입력


            productRemove: function() {
                const formData = new FormData();
                formData.append('stock_id', this.sendProductId);
                formData.append('drink_name', this.productName);

                this.axios.post('product/deleteProduct', formData)
                .then((response) => {
                    console.log(response.data);
                    
                    if (response.data == "good") {
                        alert("제품삭제 되었습니다.");
                        this.$emit('clicked', "start");
                    }
                    else {
                        alert("아직 재고가 남았습니다.");
                    }
                })
                .catch((error) => {
                    console.log(error.response);
                    alert("삭제에 실패하였습니다.");
                })
            },
            // 제품 삭제

            remove: function() {
                var removeUrl = "product/deleteStockManagement/" + this.sendProductId;

                this.axios.get(removeUrl)
                .then((response) => {
                    console.log(response.data);

                    if (response.data == "good") {
                        alert("재고삭제 되었습니다.");
                        this.$emit('clicked', "start");
                    }
                    else {
                        if (this.nowCount > 0) {
                            alert("아직 재고가 남아있습니다.");
                        }
                        else {
                            alert("마지막 남은 제품 목록입니다. 재고삭제는 할 수 없습니다.");
                        }
                    }
                })
                .catch((error) => {
                    console.log(error.response);
                    alert("삭제에 실패하였습니다.");
                }) 
            },
            // 재고 삭제


            cancel: function() {
                this.$emit('clicked', "start");
            }
            // 메인 화면으로 이동
        }
    }
</script>
<style>
    table {
        text-align: center;
    }

    #removeTable1 {
        width: 700px;
    }

    #removeTable1 th { background: rgb(153, 200, 223); }
    #removeTable1 td { border: 1px solid rgb(153, 200, 223); }
    #removeTable1 tr { border: 1px solid rgb(153, 200, 223); }
    #removeTable1 td { border: 1px solid rgb(153, 200, 223); }

    #removeTable2 {
        width: 200px;
    }

    #removeTable2 th { background: rgb(153, 200, 223); }
    #removeTable2 td { border: 1px solid rgb(153, 200, 223); }
    #removeTable2 tr { border: 1px solid rgb(153, 200, 223); }
    #removeTable2 td { border: 1px solid rgb(153, 200, 223); }

</style>