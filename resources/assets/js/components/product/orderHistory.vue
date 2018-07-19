<template>
    <div>
        <div><center><h1>注文履歴</h1></center></div><br>
        <div>
            <!-- 주문 내역 -->
            <table id="historyTable">
                <thead>
                    <th>会社名</th>
                    <th>注文書</th>
                    <th>作成の日</th>
                    <th>注文した飲料の数</th>
                    <th>注文情報</th>
                    <th>完了確認</th>
                </thead>
                <tbody>
                    <tr v-for="item in allHistory" :key="item.id">
                        <td>
                            {{item.cp_name}}
                        </td>
                        <td>
                            {{item.os_name}}
                        </td>
                        <td>
                            {{item.os_date}}
                        </td>
                        <td>
                            {{item.all_kind_of_drink}}
                        </td>
                        <td>
                            <b-button v-b-modal.modal1 @click="orderInfo(item.os_id, item.cp_id, item.os_date)">注文情報</b-button>
                        </td>
                        <td>
                            <div v-if="item.style == 1">
                                完了なった
                            </div>
                            <div v-else>
                                <b-button @click="orderComplete(item.os_id)">完了する</b-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- 주문 내역 -->
            <div>
            <!-- 주문 정보 모달창 -->
            <b-modal id="modal1" title="注文情報みる" hide-footer ref="infoModal">
                <div>
                    <iframe :src="orderSheet" style="width: 450px; height: 250px;"></iframe>
                </div>
                <div>
                    <table>
                        <tr>
                            <th colspan="4">
                                会社名
                            </th>
                        </tr>
                        <tr>
                            <td colspan="4">
                                {{company_name}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                担当者
                            </th>
                            <td>
                                {{company_leader}}
                            </td>
                            <th>
                                電話番号
                            </th>
                            <td>
                                {{leader_phone}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                メール
                            </th>
                            <td>
                                {{leader_mail}}
                            </td>
                            <th>
                                ファックス番号
                            </th>
                            <td>
                                {{company_fax}}
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <div>
                    <table>
                        <thead>
                            <th>製品名</th><th>製品注文量</th><th>製品単価</th><th>製品総金額(円)</th>
                        </thead>
                        <tbody>
                            <tr v-for="drink in oneOrderInfo[1]" :key="drink.id">
                                <td>{{drink.drink_name}}</td>
                                <td>{{drink.order_val}}</td>
                                <td>{{drink.drink_price}}</td>
                                <td>{{Number(drink.order_val) * Number(drink.drink_price)}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>注文日</td>
                                <td>{{oneOrderInfo[2]}}</td>
                                <td>合計金額</td>
                                <td>{{allProductMoney}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div>
                    <b-btn @click="okay">確認</b-btn>
                </div>
            </b-modal>
            <!-- 주문 정보 모달창 -->
        </div>
        </div>
        <br>
        <div>
            <b-button @click="cancel">戻る</b-button>
        </div>
    </div>    
</template>
<script>
    export default {
        data() {
            return {
                allHistory          : [],        // 모든 주문내역
                oneOrderInfo        : [],        // 하나의 주문 내역 정보
                allProductMoney     : 0,         // 총 금액
                orderSheet          : "",        // 주문서
                company_name        : "",        // 회사 이름
                company_leader      : "",        // 담당자
                leader_phone        : "",        // 전화번호
                leader_mail         : "",        // 회사 메일
                company_fax         : ""         // 회사 팩스
            }
        },
        mounted() {
            this.getOrderHistory();              // 모든 주문 내역 호출
        },
        methods: {
            orderComplete: function(order_os_id) {
                var orderCompleteUrl = "product/getedStockInCompany/" + order_os_id;        // 주문 확인 url

                this.axios.get(orderCompleteUrl)
                .then((response) => {
                    console.log(response);

                    if (response.data == "good") {
                        this.getOrderHistory();         // 모든 주문 내역 호출
                        alert("주문 확인되었습니다.");
                    }
                    else {
                        alert("주문 확인에 실패하였습니다.");
                    }
                })
                .catch((error) => {
                    console.log(error.response);
                    alert("주문확인이 실패하였습니다.");
                })
            },
            // 주문확인


            okay: function() {
                this.$refs.infoModal.hide();
            },
            // 모달창 닫기


            orderInfo: function(order_id, company_id, order_date) {
                var getOneOrderInfoUrl = "product/getOrderInfo";            // 하나의 주문정보 url
                this.allProductMoney = 0;                                   // 금액 초기화

                const formData = new FormData();
                formData.append('os_id', order_id);
                formData.append('cp_id', company_id);
                // 전송할 데이터

                this.axios.post(getOneOrderInfoUrl, formData)
                .then((response) => {
                    this.oneOrderInfo = response.data;
                    this.oneOrderInfo.push(order_date);

                    for (var i = 0; i < this.allHistory.length; i++) {
                        if (this.allHistory[i].os_id == order_id) {
                            this.orderSheet =  this.allHistory[i].os_path;
                            break;
                        }
                    }
                    // 해당 회사 주문서 호출

                    this.company_name = this.oneOrderInfo[0][0].cp_name;
                    this.company_leader = this.oneOrderInfo[0][0].cp_leader;
                    this.leader_phone = this.oneOrderInfo[0][0].cp_phone;
                    this.leader_mail = this.oneOrderInfo[0][0].cp_mail;
                    this.company_fax = this.oneOrderInfo[0][0].cp_fax;
                    // 주문한 회사 정보 입력

                    for (var i = 0; i < this.oneOrderInfo[1].length; i++) {
                        this.allProductMoney = this.allProductMoney + (Number(this.oneOrderInfo[1][i].order_val) * Number(this.oneOrderInfo[1][i].drink_price));
                    }
                    // 금액 계산
                })
                .then((error) => {
                    console.log(error.response);
                    alert("주문정보를 가져오는 것에 실패하였습니다.");
                })
            },
            // 주문내역 하나의 정보


            getOrderHistory: function() {
                var getOrderHistoryUrl = "product/getOrderSheet";       // 모든 주문내역 url

                this.axios.get(getOrderHistoryUrl)
                .then((response) => {
                    this.allHistory = response.data;

                    this.allHistory.sort(function(a, b) {
                        return a.os_date > b.os_date ? -1 : a.os_date < b.os_date ? 1 : 0;
                    });
                    // 모든 주문내역 최근 주문 순으로 정렬
                })
                .catch((error) => {
                    console.log(error.response);
                    alert("모든 주문내역을 가져오는 것에 실패하였습니다.");
                })
            },
            // 모든 주문 내역


            cancel: function() {
                this.$emit('clicked', "start");
            }
            // 메인화면으로 이동
        }
    }
</script>
<style>
    table {
        text-align: center;
    }

    #historyTable {
        width: 850px;
    }
    #historyTable th { background: rgb(153, 200, 223); }
    #historyTable td { border: 1px solid rgb(153, 200, 223); }
    #historyTable tr { border: 1px solid rgb(153, 200, 223); }
</style>