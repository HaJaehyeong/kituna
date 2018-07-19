<template>
    <div>
        <div>
            <center><h1>製品削除</h1></center>
        </div>
        <br>
        <div>
            <div>
                <table id="removeTable1">
                    <thead>
                        <th>製品名</th>
                        <th>会社名</th>
                        <th>会社在庫(BOX)</th>
                        <th>最大在庫(BOX)</th>
                        <th>単価(円)</th>
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
                        <th>会社名</th>
                        <th>製品数</th>
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
            <b-button @click="productRemove">製品削除</b-button>
            <b-button @click="remove">在庫削除</b-button>
            <b-button @click="cancel">取り消し</b-button>
        </div>
    </div>    
</template>
<script>
    export default {
        props: ['sendProductId'],            // 부모에게 전달 받은 삭제할 제품명
        data() {
            return {
                contactsProduct     : [],    // 제품의 정보
                contactsCompany     : [],    // 회사의 정보
                companyName         : "",    // 회사 이름
                productName         : "",    // 제품 이름
                nowCount            : 0      // 현재 재고
            }
        },
        mounted() {
            this.getCompanyProductData();    // 정보 호출
        },
        methods: {
            getCompanyProductData: function() {
                let urlProduct = "product/getProductData";      // 제품 정보 url
                let urlCompany = "product/getCompanyData";      // 회사 정보 url

                this.axios.get(urlProduct)
                .then((response) => {
                    this.contactsProduct = response.data;

                    for (var i = 0; i < this.contactsProduct.length; i++) {
                        if (this.contactsProduct[i].stock_id == this.sendProductId) {
                            // 해당 되는 제품 정보 찾기

                            this.productName = this.contactsProduct[i].drink_name
                            this.companyName = this.contactsProduct[i].cp_name;
                            this.nowCount = this.contactsProduct[i].stock;
                            document.getElementById("maxStock").innerText = this.contactsProduct[i].max_stock;
                            document.getElementById("prodcutPrice").innerText = this.contactsProduct[i].drink_price;

                            break;
                        }
                    }
                })
                .catch((error) => {
                    console.log(error.response);
                    alert("제품정보를 가져오는 것에 실패하였습니다.");
                })

                this.axios.get(urlCompany)
                .then((response) => {
                    this.contactsCompany = response.data;

                    for (var i = 0; i < this.contactsCompany.length; i++) {
                        if (this.contactsCompany[i].cp_name == this.companyName) {
                            // 해당되는 회사 정보 찾기

                            document.getElementById("productCount").innerText = this.contactsCompany[i].drink_val_of_company;
                           
                            break;
                        }
                    }
                })
                .catch((error) => {
                    console.log(error.response);
                    alert("회사정보를 가져오는 것에 실패하였습니다.");
                })
            },
            // 회사 정보 및 제품 정보 중 해당 되는 회사의 데이터 입력


            productRemove: function() {
                let productRemoveUrl = 'product/deleteProduct';     // 제품 삭제 url

                const formData = new FormData();
                formData.append('stock_id', this.sendProductId);
                formData.append('drink_name', this.productName);
                // 전송할 데이터

                this.axios.post(productRemoveUrl, formData)
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
                var removeUrl = "product/deleteStockManagement/" + this.sendProductId;      // 재고 삭제 url

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