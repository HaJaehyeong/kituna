<template>
  <div id="analysisLineChartBackgroundDiv">
    <!-- 빠르게 이동가능한 speed-dial로서, 오른쪽 하단에 버튼을 생성. -->
    <v-speed-dial v-model="fab" :bottom="bottom" :right="right" :direction="direction" :open-on-hover="true" :transition="transition">
      <v-btn slot="activator" v-model="fab" dark fab hover>
        <v-icon>import_export</v-icon>
        <v-icon>close</v-icon>
      </v-btn>

      <!-- ID가 scrollLine인 태그를 찾아 바로 그 태그로 이동한다. -->
      <a href="#" v-scroll-to="'#scrollLine'">
        <v-btn fab dark small> <v-icon>show_chart</v-icon> </v-btn>
      </a>
      <!-- ID가 scrollBar인 태그를 찾아 바로 그 태그로 이동한다. -->
      <a href="#" v-scroll-to="'#scrollBar'">
        <v-btn fab dark small> <v-icon>bar_chart</v-icon> </v-btn>
      </a>
      <!-- ID가 div_AnalysisHeader인 DIV태그를 찾아 바로 그 태그로 이동한다. -->
      <a href="#" v-scroll-to="'#div_AnalysisHeader'">
        <v-btn fab dark small> <v-icon>mode_comment</v-icon> </v-btn>
      </a>
    </v-speed-dial>

    <!-- 자식 DIV 두개를 7:3비율로 나누어, Analysis글씨와, 실시간 판매량DIV로 구성한다. -->
    <div class="collums64DivideDiv">
      <div style="text-align:left;font-family:'Dosis'; margin-left: 10%;" >
        <h2><strong>　　Real time Management </strong></h2>
      </div>
      <!-- 실시간 판매량 데이터를 나타내는 DIv로 자식 DIV를 1:9로 나누어 제목과, 데이터창을 구분한다. -->
      <div id="anaylsisRealDataSecondDiv">
        <div>
          <h4 style="text-align:right; font-weight: bold; margin-top: 1%; margin-right: 20px; color:#0064C8;">실시간 판매량</h4>
          <img src="/images/analysis/refresh.png">
        </div>
        <!-- 3:7 비율로 데이터 안의 현재판매량이란 글자와, 데이터를 나타내는 DIV들의 비율을 나눈다. -->
        <div id="anaylsisRealDataSecondContentDiv">
          <!-- <div>
            <h6 style="color:#0064C8; font-weight: bold; text-align:left; margin-left: 50px;">현재 판매량(개)</h6>
          </div> -->
          <!-- 3:3:3으로 DIV를 나누어 데이터를 적는다. -->
          
          <div style="color:#FFFFFF; font-weight: bold; padding-top: 20px;">
            월 판매량 <br><h3>{{nowMonthProduct}}</h3>     
          </div>
          <div style="color:#FFFFFF; font-weight: bold; padding-top: 20px;">
            일 판매량 <h3>{{nowDayProduct}}</h3>
          </div>
          <div style="color:#FFFFFF; font-weight: bold; padding-top: 20px;">
            일 매출액 <h3>{{nowDaySales}}</h3>
          </div>   
        </div>
      </div>
    </div>
    <!-- 좌측 장소 버튼들과 메인 DIV를 구분하기 위해 1:9로 나누어 DIV구성. -->
    <div id="placeDivisionDiv">
      <div class="collums55DivideDiv">
        <!-- DIV안에 빈공간을 많이 만들어 화면 좌측에 장소 버튼들이 메인DIV에 붙도록 한다. -->
        <div>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <div>
          <br>
          <img :src="allBtnSrc" class="btnRightDiv" id="allBtn" @click="placeChange('all')">
          <img :src="schoolBtnSrc" class="btnRightDiv" id="schoolBtn" @click="placeChange('school')">
          <img :src="companyBtnSrc" class="btnRightDiv" id="companyBtn" @click="placeChange('company')">
          <img :src="hospitalBtnSRc" class="btnRightDiv" id="hospitalBtn" @click="placeChange('hospital')">
          <img :src="parkBtnSrc" class="btnRightDiv" id="parkBtn" @click="placeChange('park')">
        </div>
      </div>
      <div>
        <div id="analysisVdDiv">
          <div>
            <div class="collums55DivideDiv">
              <div>
                <span class="mainContents">매출순 자판기</span>
                <img :src="rankVdSortImgSrc" @click="rankVdSortChange()">
              </div>
              <div>
                <v-chip label :selected="yearChip" outline color="blue" @click="rankVdDateChange('year')">년간</v-chip>
                <v-chip label :selected="monthChip" outline color="blue" @click="rankVdDateChange('month')">월간</v-chip>
                <v-chip label :selected="weekChip" outline color="blue" @click="rankVdDateChange('week')">주간</v-chip>
              </div> 
            </div>
            <div id="tableArrowDiv">
              <div>
                <table class="table table-hover" style="color: #0064C8;">
                  <thead style="font-weight: bold; font-family: Nanum+Gothic; border-bottom: 2px solid #0064C8;">
                    <tr>
                      <td style="padding-top: 10px;">순위</td>
                      <td style="padding-top: 10px;">자판기명</td>
                      <td style="padding-top: 10px;">판매량</td>
                      <td style="padding-top: 10px;">매출액</td>
                    </tr>
                  </thead>
                  <!-- rankVdDataArray배열을 for문을 돌며, 값을 테이블에 한줄 씩 넣는다. -->
                  <tbody v-for="vdData in rankVdDataArray" :key="vdData.vd_id">
                    <!-- 처음 로딩 시 가장 위의 tr은 클릭 되어 있어 배경색을 다르게 해주며, TR을 클릭시, 클릭 된 자판기 정보를 우측 분석에서 볼 수 있게 한다. -->
                    <tr v-if="vdData.num == 1" style="background-color:#E5EFF9; border: 3px solid #0064C8;" :id="vdData.vd_name"  @click="vdIdChange(vdData.vd_id, vdData.vd_name, vdData.vd_supplementer, vdData.num)">
                      <th style="padding-top: 10px;"> {{vdData.num}}</th>
                      <td style="font-weight: bold; font-size: 15px; padding-top: 10px;">{{vdData.vd_name}}</td>
                      <th style="padding-top: 10px;">{{vdData.count}}</th>
                      <th style="padding-top: 10px;">{{vdData.getSales}}</th>
                    </tr>
                    <tr v-else-if="(vdData.num%2) !== 0" style="background-color:#E5EFF9;" @click="vdIdChange(vdData.vd_id, vdData.vd_name, vdData.vd_supplementer, vdData.num)">
                      <th style="padding-top: 10px;"> {{vdData.num}}</th>
                      <td  style="font-weight: bold; font-size: 15px; padding-top: 10px;">{{vdData.vd_name}}</td>
                      <th style="padding-top: 10px;">{{vdData.count}}</th>
                      <th style="padding-top: 10px;">{{vdData.getSales}}</th>
                    </tr>  
                    <tr v-else @click="vdIdChange(vdData.vd_id, vdData.vd_name, vdData.vd_supplementer, vdData.num)">
                      <th style="padding-top: 10px;"> {{vdData.num}}</th>
                      <td  style="font-weight: bold; font-size: 15px; padding-top: 10px;">{{vdData.vd_name}}</td>
                      <th style="padding-top: 10px;">{{vdData.count}}</th>
                      <th style="padding-top: 10px;">{{vdData.getSales}}</th>
                    </tr>  
                  </tbody>
                </table>
                <!-- 관심 자판기의 데이터 테이블로, 한 자판기만을 보게 된다. -->
                <div>
                  <table class="table" id="interestVdTableDiv"> 
                    <thead style="font-weight: bold;">
                      <tr>
                        <td style="padding-top: 10px;">자판기명</td>
                        <td style="padding-top: 10px;">담당 보충기사</td>
                        <td style="padding-top: 10px;">매출액 차이</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="vdData in interestVdDataArray" :id="vdData.vd_name" :key="vdData.vd_id" @click="vdIdChange(vdData.vd_id, vdData.vd_name, vdData.vd_supplementer)">
                        <th style="padding-top: 10px;">★ {{vdData.vd_name}}</th>
                        <th style="padding-top: 10px;">{{vdData.vd_supplementer}}</th>
                        <th style="padding-top: 10px;">{{vdData.value}}</th>
                      </tr>
                    </tbody>
                </table>
                </div>
              </div>
              <div>
                <table class="table borderless" > 
                    <thead>
                      <tr style="border-bottom: none; border-top: none; pointer-events: none;">
                        <td></td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr style="border-bottom: none; border-top: none; pointer-events: none; ">
                        <td style="padding-top: 7%;"><img :src="clickVdSrc1" id="clickVd1"></td>
                      </tr>
                      <tr style="border-bottom: none; border-top: none; pointer-events: none;">
                        <td style="padding-top: 10%;"><img :src="clickVdSrc2" id="clickVd2"></td>
                      </tr>
                      <tr style="border-bottom: none; border-top: none; pointer-events: none;">
                        <td style="padding-top: 10%;"><img :src="clickVdSrc3" id="clickVd3"></td>
                      </tr>
                      <tr style="border-bottom: none; border-top: none; pointer-events: none;">
                        <td style="padding-top: 10%;"><img :src="clickVdSrc4" id="clickVd4"></td>
                      </tr>
                      <tr style="border-bottom: none; border-top: none; pointer-events: none;">
                        <td style="padding-top: 10%;"><img :src="clickVdSrc5" id="clickVd5"></td>
                      </tr>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- 데이터 제목과 데이터를 구분하기 위해 1:9로 DIV를 분할. -->
          <div style="height:550px;">
            <div>
              <!-- 현재 선택된 자판기 이름이 analysisVdName에 저장되어 있다. -->
              <h2 id="clickVdName">{{analysisVdName}} 자판기</h2>
            </div>
            <div id="graphDrinkRankDivideDiv">
              <div class="collums64DivideDiv">
                <div>
                  <h4 style="font-weight: bold; color:#0064C8; margin-top: 1%;">자판기 내 음료별 판매량</h4>
                  <!-- radar차트를 이용해, 데이터 비교가 가능하게 해두었다. -->
                  <radar-chart :chart-data="radarChart" :options="radarOption" class="chartData" id="chartData" :width="350" :height="350"></radar-chart>  
                </div>
                <div> 
                  <h4 style="font-weight: bold; color:#0064C8; margin-top: 1%;">그 외 음료순위 및 판매량</h4>
                  <!-- 클릭 된 자판기 내에 없는 음료들의 순위를 데이터 테이블을 이용해 순위순으로 나타내었다. -->
                  <table class="table table-hover" style="color: #0064C8;">
                    <tbody v-for="drinkList in theRestDrinkRankArray" :key="drinkList.num">
                      <tr v-if="(drinkList.num%2) !== 0" style="background-color:#E5EFF9;">
                        <th style="color: #0064C8; padding-top: 10px;">{{drinkList.num}}</th>
                        <td style="padding-top: 7px;"><img :src="'images/drink/' + drinkList.drink_name + '.png'" style="width:22px; height: 35px;"></td>
                        <td style="font-weight: bold; font-size: 20px; padding-top: 10px;">{{drinkList.count}}</td>
                      </tr>
                      <tr v-else>
                        <th style="color: #0064C8; padding-top: 10px;">{{drinkList.num}}</th>
                        <td style="padding-top: 7px;"><img :src="'images/drink/' + drinkList.drink_name + '.png'" style="width:22px; height: 35px;"></td>
                        <td style="font-weight: bold; font-size: 20px; padding-top: 10px;">{{drinkList.count}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <div>
                    <span style="float: left; font-size: 20px; color:#0064C8;">평균보충주기 : </span><span style="float: right; font-size: 20px; color:#0064C8;">일</span><span style="float: right; font-size: 20px; font-weight: bold;">{{avgSupplementCycle}}</span><br><br>
                    <span style="float: left; font-size: 20px; color:#0064C8;">컴플레인 : </span><span style="float: right; font-size: 20px; color:#0064C8;">회</span><span style="float: right; font-size: 20px; font-weight: bold;">{{complain}}</span><br><br>
                    <span style="float: left; font-size: 20px; color:#0064C8;">판매장소 : </span><span style="float: right; font-size: 20px; font-weight: bold;">{{sellPlace}}</span><br>
                    <div class="collums55DivideDiv">
                      <v-btn @click.native.stop="jobOrderDialog = true" style="height: 156px;">
                        <img src="images/analysis/jobOrderImg.png" style="height: 156px;">
                      </v-btn>
                   
                      <v-btn @click="dialogOpen()" style="height: 156px;"> 
                        <img src="images/analysis/lineChangeImg.png" style="height: 156px;">
                      </v-btn>
                    </div>

            <!-- 작업지시서 모달창  -->
            <v-dialog v-model="jobOrderDialog" max-width="520" max-height="300">
              <v-card>
                <v-card-title>
                  <h2>작업 지시창</h2>
                </v-card-title>
                <v-card-text>
                  <!-- 작업 지시를 하는 자판기 이름과 해당 자판기 보충기사를 보여준다.  -->
                  <v-card-text>
                    ● 현재 자판기 : {{analysisVdName}}
                    <v-spacer></v-spacer>
                    ● 보충 기사 : {{analysisVdSp}}
                  </v-card-text>
                  <v-select
                    :items="jobOrderSelect"
                    label="Please Select List"
                    item-value="text"
                    v-model ="selectedItem">
                  </v-select>
                  <div v-if="selectedItem=='기타'">
                    <v-text-field  label="작업지시를 적어주세요" v-model="selectedItem_etc"></v-text-field>
                  </div>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="green darken-1" flat="flat" @click.native="jobOrderDialog = false">cancel</v-btn>
                  <v-btn color="green darken-1" flat="flat" @click.native="jobOrderDialog = submit(selectedItem,selectedItem_etc)">submit</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <!-- 작업지시서 모달창  -->
    
            <!-- 라인변경 모달창 -->
            <v-layout row justify-center>
              <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">            
                <v-card>
                  <v-toolbar dark color="cyan">
                    <!-- 음료 라인 변경 취소 시, lineChangeDrinkReset메서드를 호출해, 데이터를 초기화 시켜줍니다. -->
                    <v-btn icon dark @click.native="dialog = false" @click="lineChangeDrinkReset()">
                      <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{analysisVdName}} 자판기 라인 변경</v-toolbar-title>
                    <v-spacer></v-spacer>   
                    <v-toolbar-items>
                      <!-- 음료 라인 변경 취소 시, lineChangeDrinkReset메서드를 호출해, 데이터를 초기화 시켜줍니다. -->
                      <v-btn dark flat @click.native="dialog = false" @click="lineChangeDrinkReset()">변경 취소</v-btn>
                      <!-- 음료 라인 변경 지시 시, setLineChangeNote메서드를 호출해, 작업지시서에 추가되도록 한다.. -->
                      <v-btn dark flat @click.native="dialog = false, snackbar = true" @click="setLineChangeNote(analysisVdId, vdDrinkId, changeDrinkId)">변경 지시</v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-list style="width: 30%;">
                    <h4 style="font-weight: bold; color:black;">{{analysisVdName}} 자판기 내 음료 리스트</h4>
                    <div class="collums25DivideDiv">
                        <div v-for="(drink, index) in vendingDrinkList" :key="index">
                          <v-card :hover="true">
                            <img :src="drink.drink_img_path" @click="lineChangeBeforeDrink(drink.drink_id, drink.drink_name, drink.drink_img_path)" style=" height:100px; width:50px; margin-left: auto; margin-right: auto; display: block;">
                          </v-card>
                        </div>
                    </div>   
                  </v-list>
                  <v-divider></v-divider>
                  <v-list style="width: 30%;">
                    <h4 style="font-weight: bold; color:black;">추천 음료 리스트</h4>
                    <div class="collums25DivideDiv">
                        <div v-for="(drink, index) in theRestDrinkRankArray" :key="index">
                          <v-card :hover="true">
                            <img :src="drink.drink_img_path" @click="lineChangeAfterDrink(drink.drink_id, drink.drink_name, drink.drink_img_path)" style=" height:100px; width:50px; margin-left: auto; margin-right: auto; display: block;">
                          </v-card>
                        </div>
                    </div>   
                  </v-list>
                  <v-list>
                    <h4 style="font-weight: bold; color:black;">교체 작업</h4>
                    <div class="collums25DivideDiv">
                        <div>
                          현재음료
                          <v-card :hover="true">
                            {{vdDrinkName}}
                            <img :src="vdDrinkPath" style="height:100px; width:50px; margin-left: auto; margin-right: auto; display: block;">
                          </v-card>
                        </div>
                        <div>
                          바뀔 음료
                          <v-card :hover="true">
                            {{changeDrinkName}}
                            <img :src="changeDrinkPath" style="height:100px; width:50px; margin-left: auto; margin-right: auto; display: block;">
                          </v-card>
                        </div>
                    </div>   

                  </v-list>
                </v-card>
                <v-card>
                </v-card>
              </v-dialog>
            </v-layout>
            <!-- 라인변경 모달창 -->

            <!-- 라인변경 지시 완료시 뜨는 스낵바 -->
            <v-snackbar :timeout="timeout" :top="y === 'top'" v-model="snackbar" >
            {{analysisVdName}} 자판기 라인 변경 지시 완료
              <v-btn flat color="pink" @click.native="snackbar = false">Close</v-btn>
            </v-snackbar>
            <!-- 라인변경 지시 완료시 뜨는 스낵바 -->

              </div>

                </div>
                
              </div>
            </div>
          </div>
        </div>
        <!-- 메인 DIV로, 5:5 비율로 나누어 데이터를 구분하여 나오게 하였다. -->
        <div class="collums55DivideDiv">
          <!-- 데이터 제목과 데이터를 구분하기 위해 1:9로 DIV를 분할. -->
          
          <div class="titleContentDivideDiv" id="titleContentDivideDiv1">
            <div class="collums55DivideDiv" id="scrollBar" style="margin-top: 15px;">
              <div>
                <span class="mainContents">음료별 판매순위</span>
              </div>
              <div>
                <v-chip label :selected="drinkYearChip" outline color="blue" @click="drinkRankDateChange('year')">년간</v-chip>
                <v-chip label :selected="drinkMonthChip" outline color="blue" @click="drinkRankDateChange('month')">월간</v-chip>
                <v-chip label :selected="drinkWeekChip" outline color="blue" @click="drinkRankDateChange('week')">주간</v-chip>
              </div>
            </div>
            <div>
              <bar-chart :chart-data="barChart" :options="barOptions" class="chartData" :width="600" :height="500"></bar-chart>
            </div>
          </div>
          <div class="titleContentDivideDiv" id="titleContentDivideDiv2">
            <div class="collums55DivideDiv"  style="margin-top: 15px;">
              <div>
                <span class="mainContents">음료 정보</span>
              </div>
              <div></div>
            </div>
            <div class="rows82DivideDiv">
              <div>
                <table class="table table-hover" style="color: #0064C8;">
                  <thead style="font-weight: bold;">
                    <tr>
                      <td style="padding-top: 10px;">음료</td>
                      <td style="padding-top: 10px;">평균 소진량(개)</td>
                      <td style="padding-top: 10px;">예상 소진일(일)</td>
                      <td style="padding-top: 10px;">잔량(개)</td>
                      <td style="padding-top: 10px;">주문</td>
                    </tr>
                  </thead>
                  <tbody v-for="array in drinkExhaustAnalysisArray" :key="array.drink_name">
                    <tr v-if="(array.num%2) !== 0" style="background-color:#E5EFF9;">
                        <td style="padding-top: 7px;"><img :src="'images/drink/' + array.drink_name + '.png'" style="width:22px; height: 35px;"></td>
                        <th style="padding-top: 10px;">{{array.avgSales}}</th>
                        <th style="padding-top: 10px;">{{array.prediction}}</th>
                        <th style="padding-top: 10px;">{{array.sum}}</th>
                        <td v-if="array.prediction < 4" style="padding-top: 10px;"><img src="images/analysis/check.png" @click="checkImgChange(array.stock_id)" :id="array.stock_id" name="drinkCheck" ></td>
                        <td v-else style="padding-top: 10px;"><img src="images/analysis/uncheck.png"  @click="checkImgChange(array.stock_id)" :id="array.stock_id" name="drinkCheck"></td>
                    </tr>
                    <tr v-else>
                        <td style="padding-top: 7px;"><img :src="'images/drink/' + array.drink_name + '.png'" style="width:22px; height: 35px;"></td>
                        <th style="padding-top: 10px;">{{array.avgSales}}</th>
                        <th style="padding-top: 10px;">{{array.prediction}}</th>
                        <th style="padding-top: 10px;">{{array.sum}}</th>
                        <td v-if="array.prediction < 4" style="padding-top: 10px;"><img src="images/analysis/check.png" @click="checkImgChange(array.stock_id)" :id="array.stock_id" name="drinkCheck"></td>
                        <td v-else style="padding-top: 10px;"><img src="images/analysis/uncheck.png"  @click="checkImgChange(array.stock_id)" :id="array.stock_id" name="drinkCheck"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="collums64DivideDiv">
                <div>
                  <a @click="checkedProductOrder()">
                    <img src="images/analysis/productOrder.png">
                  </a>
                </div>
                <div  style="margin-top: 35px;">
                  <table class="table borderless">
                    <tr>
                      <td>선택한 음료 수</td>
                      <td>{{chooseDrinkProductCount}}</td>
                      <td>개</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="collums333DivideDiv">
          
            <div id="scrollLine" class="rows19DivideDiv">
              <div>
              <v-menu :nudge-width="200" v-model="yearProductMenu">
                <v-btn slot="activator" round outline dark style="font-weight: bold; color:#0064C8;" @click="statistic('year')">년간 판매량</v-btn>
                <v-card>
                  <v-list>
                    <v-list-tile avatar>
                      <v-list-tile-content>
                        <v-list-tile-title>년간 판매량 통계</v-list-tile-title>
                      </v-list-tile-content>
                    </v-list-tile>
                  </v-list>
                  <v-list>
                    <v-list-tile>
                      <v-list-tile-title>이번달 판매량</v-list-tile-title>
                      <v-list-tile-title>{{statisticFirstData}}개</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile>
                      <v-list-tile-title>저번달 판매량</v-list-tile-title>
                      <v-list-tile-title>{{statisticFSecondData}}개</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile>
                      <v-list-tile-title>1년간 최고 판매달</v-list-tile-title>
                      <v-list-tile-title>{{statisticThirdData}}개</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile>
                      <v-list-tile-title>1년간 최저 판매달</v-list-tile-title>
                      <v-list-tile-title>{{statisticFourthData}}개</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile>
                      <v-list-tile-title>월 평균 판매량</v-list-tile-title>
                      <v-list-tile-title>{{statisticFifthData}}개</v-list-tile-title>
                    </v-list-tile>
                  </v-list>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn flat @click="menu = false">Cancel</v-btn>
                    <v-btn color="primary" flat @click="menu = false">Ok</v-btn>
                  </v-card-actions>
                </v-card>
              </v-menu>
              </div>
              <div>
              <line-chart :chart-data="lineChart" :options="lineOptions" class="chartData" :width="300" :height="300"></line-chart>
              </div>
            </div>
            <div class="rows19DivideDiv">
              <div>
                <v-menu :nudge-width="200" v-model="monthProductMenu">
                  <v-btn slot="activator" round outline dark style="font-weight: bold; color:#0064C8;" @click="statistic('month')">월간 판매량</v-btn>
                  <v-card>
                    <v-list>
                      <v-list-tile avatar>
                        <v-list-tile-content>
                          <v-list-tile-title>월간 판매량 통계</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    </v-list>
                    <v-list>
                      <v-list-tile>
                        <v-list-tile-title>이번주 판매량</v-list-tile-title>
                        <v-list-tile-title>{{statisticFirstData}}개</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-title>저번주 판매량</v-list-tile-title>
                        <v-list-tile-title>{{statisticFSecondData}}개</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-title>한 달간 최고 판매주</v-list-tile-title>
                        <v-list-tile-title>{{statisticThirdData}}개</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-title>한 달간 최저 판매주</v-list-tile-title>
                        <v-list-tile-title>{{statisticFourthData}}개</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-title>주 평균 판매량</v-list-tile-title>
                        <v-list-tile-title>{{statisticFifthData}}개</v-list-tile-title>
                      </v-list-tile>
                    </v-list>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn flat @click="menu = false">Cancel</v-btn>
                      <v-btn color="primary" flat @click="menu = false">Ok</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-menu>
              </div>
              <div>
                <line-chart2 :chart-data="lineChart2" :options="lineOptions2" class="chartData" :width="300" :height="300"></line-chart2>  
              </div>
              
            </div>
       
            <div class="rows19DivideDiv">
              <div>
                <v-menu :nudge-width="200" v-model="weekProductMenu">
                  <v-btn slot="activator" round outline dark style="font-weight: bold; color:#0064C8;" @click="statistic('week')">주간 판매량</v-btn>
                  <v-card>
                    <v-list>
                      <v-list-tile avatar>
                        <v-list-tile-content>
                          <v-list-tile-title>주간 판매량 통계</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    </v-list>
                    <v-list>
                      <v-list-tile>
                        <v-list-tile-title>오늘 판매량</v-list-tile-title>
                        <v-list-tile-title>{{statisticFirstData}}개</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-title>어제 판매량</v-list-tile-title>
                        <v-list-tile-title>{{statisticFSecondData}}개</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-title>주간 최고 판매일</v-list-tile-title>
                        <v-list-tile-title>{{statisticThirdData}}개</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-title>주간 최저 판매일</v-list-tile-title>
                        <v-list-tile-title>{{statisticFourthData}}개</v-list-tile-title>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-title>일 평균 판매량</v-list-tile-title>
                        <v-list-tile-title>{{statisticFifthData}}개</v-list-tile-title>
                      </v-list-tile>
                    </v-list>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn flat @click="menu = false">Cancel</v-btn>
                      <v-btn color="primary" flat @click="menu = false">Ok</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-menu>
              </div>
              <div>
                <line-chart3 :chart-data="lineChart2" :options="lineOptions3" class="chartData" :width="300" :height="300"></line-chart3>
              </div>
            </div>
          
        </div>
        <div class="collums55DivideDiv" id="drinkProductBackground">
          <div class="collums55DivideDiv">
            <div>
              <h4 style="font-weight: bold; color:#0064C8;">학교 음료별 판매 비율</h4>
              <pie-chart :chart-data="pieChart" :options="pieOptions" class="chartData" :width="300" :height="300"></pie-chart>
            </div>
            <div>
              <h4 style="font-weight: bold; color:#0064C8;">공원 음료별 판매 비율</h4>
              <pie-chart2 :chart-data="pieChart2" :options="pieOptions2" class="chartData" :width="300" :height="300"></pie-chart2>
            </div>
          </div>
          <div class="collums55DivideDiv">
            <div>
              <h4 style="font-weight: bold; color:#0064C8;">회사 음료별 판매 비율</h4>
              <pie-chart3 :chart-data="pieChart3" :options="pieOptions3" class="chartData" :width="300" :height="300"></pie-chart3>
            </div>
            <div>
              <h4 style="font-weight: bold; color:#0064C8;">병원 음료별 판매 비율</h4>
              <pie-chart4 :chart-data="pieChart4" :options="pieOptions4" class="chartData" :width="300" :height="300"></pie-chart4>
            </div>
          </div>

        </div>
          
        
      </div>
    </div>
    
  </div>
    
  
</template>
<script>
  import LineChart from './LineChart.vue';
  import LineChart2 from './LineChart2.vue';
  import LineChart3 from './LineChart3.vue';
  import RadarChart from './RadarChart.vue';
  import BarChart from './BarChart.vue';
  import PieChart from './PieChart.vue';
  import PieChart2 from './PieChart2.vue';
  import PieChart3 from './PieChart3.vue';
  import PieChart4 from './PieChart4.vue';
  
  export default {
    components: {
      LineChart,
      LineChart2,
      LineChart3,
      BarChart,
      RadarChart,
      PieChart,
      PieChart2,
      PieChart3,
      PieChart4,
    },
    props: ['realtimeVdId'],
    data () {
      return { 
        snackbar: false,
        y: 'top',
        x: null,
        timeout: 3000,

      /* ----- 스피드다이얼로그 ----- */
        direction: 'top',
        fab: false,
        tabs: null,
        transition: 'slide-y-reverse-transition',
        bottom: true,
        right: true,
      /* ----- 스피드다이얼로그 ----- */

      /* ----- 상단 실시간 데이터 ----- */  
        nowMonthProduct : 0, // 이번달 판매량
        nowDayProduct   : 0, // 오늘 판매량
        nowDaySales     : 0, // 오늘 매출액
      /* ----- 상단 실시간 데이터 ----- */  

      /* ----- 좌측 장소 버튼 이미지 src 저장 데이터 ----- */  
        allBtnSrc       : '/images/analysis/all1.png',
        schoolBtnSrc    : '/images/analysis/school.png',
        companyBtnSrc   : '/images/analysis/company.png',
        hospitalBtnSRc  : '/images/analysis/hospital.png',
        parkBtnSrc      : '/images/analysis/park.png',
      /* ----- 좌측 장소 버튼 이미지 src 저장 데이터 ----- */  

      /* ----- 자판기 랭킹순으로 정렬 데이터 ----- */

        clickVdSrc1 : '/images/analysis/arrow.png',
        clickVdSrc2 : '',
        clickVdSrc3 : '',
        clickVdSrc4 : '',
        clickVdSrc5 : '',

        rankVdSortImgSrc : '/images/analysis/descendingOrder.png',
        yearChip  : false, // 연간 버튼
        monthChip : true,  // 월간 버튼
        weekChip  : false, // 주간 버튼
        ascChip   : false, // 정렬 내림차순 버튼
        descChip  : true,  // 정렬 오름차순 버튼

        choosePlace : 'all',      // 현재 선택된 장소를 의미.

        rankVdDataArray : [],     // 자판기들을 랭킹순으로 담는 배열
        interestVdDataArray : [], // 관심 자판기 하나를 갖고 온다.
        rankVdDate      : 'month',
        rankVdSort      : 'desc',

        analysisVdId    : 0,      // 현재 클릭 된 자판기 ID
        analysisVdName  : '',     // 현재 클릭 된 자판기 name
        analysisVdSp    : '',
        allVendingCount : 0,
        radarChart       : null, 
        radarOption      : null,
      
        vendingDrinkList : [],       // 클릭 된 자판기 내 음료 리스트
        theRestDrinkRankArray : [],  // 클릭 된 자판기 내에 없는 음료

        // ----- 라인변경 모달창 필요 변수 -----
        dialog      : false,
        vdDrinkId : '',
        changeDrinkId : '',
        vdDrinkName : '',
        changeDrinkName : '',
        vdDrinkPath : '/images/default.png',
        changeDrinkPath : '/images/default.png',
        // ----- 라인변경 모달창 필요 변수 -----

        // ----- 작업지시 모달창 필요 변수 -----
        jobOrderDialog : false,
        jobOrderSelect : [ 
                            { text: '긴급! 음료 재고 부족' },
                            { text: '긴급! 잔고 부족' },
                            { text: '축제 기간 ( 재고잔고 확인 요망 )' },
                            { text: '기계 이상 및 고장' },
                            { text: '기타' }
                          ],
        selectedItem : '',
        selectedItem_etc : '',
        // ----- 작업지시 모달창 필요 변수 -----

        avgSupplementCycle : 0,      // 평균 보충 주기
        complain  : 0,               // 컴플레인 횟수
        sellPlace : '',              // 판매 장소
      /* ----- 자판기 랭킹순으로 정렬 데이터 ----- */  

      /* ----- 음료별 판매순위 데이터 ----- */  
        drinkExhaustAnalysisArray   : [],
        chooseDrinkProductCount     : 0,

        drinkYearChip   : false,
        drinkMonthChip  : true,
        drinkWeekChip   : false,
        drinkRankDate   : 'month',

        barChart       : null,
        barOptions     : null,
      /* ----- 음료별 판매순위 데이터 ----- */  

      /* ----- 음료별 판매량 분석 차트 데이터 ----- */  
        DrinkSumProduct     : 0,    // 음료별 판매량 합계
        topProductDrinkNo   : 0,    // 가장 많이 팔린 음료의 판매량
        topProductDrinkRate : 0,    // 가장 많이 팔린 음료의 판매비율
        topProductDrinkName : null, // 가장 많이 팔린 음료 이름

        productOrderUrl : '',       // 음료 소진량을 계산해 product로 가서 음료를 주문하는 URL을 담는 변수.
      /* ----- 음료별 판매량 분석 차트 데이터 ----- */

      /* ----- 년/월/주간별 판매량 데이터 ----- */
        lineChart      :  null,      // line차트 데이터를 담는 변수로, 년간 판매량의 데이터가 담긴다.
        lineChart2     :  null,      // line차트 데이터를 담는 변수로, 월간 판매량의 데이터가 담긴다.
        lineChart3     :  null,      // line차트 데이터를 담는 변수로, 주간 판매량의 데이터가 담긴다.
        lineOptions    :  null,      // line차트의 옵션을 정하는 변수로, 년간 판매량의 옵션을 의미.
        lineOptions2   :  null,      // line차트의 옵션을 정하는 변수로, 월간 판매량의 옵션을 의미.
        lineOptions3   :  null,      // line차트의 옵션을 정하는 변수로, 주간 판매량의 옵션을 의미.

        // 년/월/주 판매량 메뉴 모달창 
        yearProductMenu  : false,    
        monthProductMenu : false,
        weekProductMenu  : false,

        // 년/월/주간 판매량 데이터를 담기 위한 변수
        yearDataArray  : null,        
        monthDataArray : null,       
        weekDataArray  : null,        

        // 년/월/주 판매량의 메뉴창 안 변수 값들
        statisticFirstData   : null,  
        statisticFSecondData : null,  
        statisticThirdData   : null,  
        statisticFourthData  : null,  
        statisticFifthData   : null, 
      /* ----- 년/월/주간별 판매량 데이터 ----- */

        pieChart     : null,
        pieChart2    : null,
        pieChart3    : null,
        pieChart4    : null,      
        pieOptions   : null,
        pieOptions2  : null,
        pieOptions3  : null,
        pieOptions4  : null,
      }
    },
    mounted () {
      this.realTimeData();
      this.rankVdData();
      this.drinkSalesRank();
      this.yearMonthWeekSalesData();
      this.placeSalesData();
    },
    methods: {
      /* <----- 년간 / 월간 / 주간 판매량 통계 메뉴 모달창 -----> */
      statistic(period){
        let count = 0;
        let regexp = /\B(?=(\d{3})+(?!\d))/g;
        switch(period){  
          case 'year' : 
            // 배열 전체 값을 더한다. 평균을 구해주기 위해,
            for(let i = 0; i < this.yearDataArray.length; i++){
              count += this.yearDataArray[i];
            }
            this.statisticFirstData   = this.yearDataArray[this.yearDataArray.length-1].toString().replace(regexp, ',');
            this.statisticFSecondData = this.yearDataArray[this.yearDataArray.length-2].toString().replace(regexp, ',');
            this.statisticThirdData   = Math.max.apply(null, this.yearDataArray).toString().replace(regexp, ',');
            this.statisticFourthData  = Math.min.apply(null, this.yearDataArray).toString().replace(regexp, ',');
            this.statisticFifthData   = (count/this.yearDataArray.length).toFixed(1).toString().replace(regexp, ',');
            break;
          case 'month' : 
            // 배열 전체 값을 더한다. 평균을 구해주기 위해,
            for(let i = 0; i < this.monthDataArray.length; i++){
              count += this.monthDataArray[i];
            }
            this.statisticFirstData   = this.monthDataArray[this.monthDataArray.length-1].toString().replace(regexp, ',');
            this.statisticFSecondData = this.monthDataArray[this.monthDataArray.length-2].toString().replace(regexp, ',');
            this.statisticThirdData   = Math.max.apply(null, this.monthDataArray).toString().replace(regexp, ',');
            this.statisticFourthData  = Math.min.apply(null, this.monthDataArray).toString().replace(regexp, ',');
            this.statisticFifthData   = (count/this.monthDataArray.length).toFixed(1).toString().replace(regexp, ',');
            break;
          case 'week' : 
            // 배열 전체 값을 더한다. 평균을 구해주기 위해,
            for(let i = 0; i < this.weekDataArray.length; i++){
              count += this.weekDataArray[i];
            }
            this.statisticFirstData   = this.weekDataArray[this.weekDataArray.length-1].toString().replace(regexp, ',');
            this.statisticFSecondData = this.weekDataArray[this.weekDataArray.length-2].toString().replace(regexp, ',');
            this.statisticThirdData   = Math.max.apply(null, this.weekDataArray).toString().replace(regexp, ',');
            this.statisticFourthData  = Math.min.apply(null, this.weekDataArray).toString().replace(regexp, ',');
            this.statisticFifthData   = (count/this.weekDataArray.length).toFixed(1).toString().replace(regexp, ',');
            break;
        }
      },
      /* <----- 년간 / 월간 / 주간 판매량 통계 메뉴 모달창 -----> */

      /* <----- 모달창 내용 db전송 -----> */
      submit(selectedItem,selectedItem_etc){

        if(selectedItem_etc == ''){
          const formData = new FormData();
          formData.append('ven_id',this.analysisVdId);
          formData.append('ven_note',selectedItem);
        
          this.axios.post("management/addJobOrderVerTwo", formData)
          .then((Response) => {
             alert("작업지시가 완료되었습니다.");
          })
          .catch(ex=>{
            console.log(ex.response);
          }) 
        } 
        else if(selectedItem_etc != ''){
          const formData = new FormData();
          formData.append('ven_id',this.analysisVdId);
          formData.append('ven_note',selectedItem_etc);
        
          this.axios.post("management/addJobOrder", formData)
          .then((Response) => {
            // console.log(Response.data);
            alert("작업지시가 완료되었습니다.");
          }).catch(ex => {
            console.log(ex.response);
          }) 
        }

        this.dialog = false;
      },
      /* <----- 모달창 내용 db전송 -----> */

      /* <----- 음료 주문으로 넘어가는 메서드 -----> */
      checkedProductOrder(){
        let drinkCheck = document.getElementsByName('drinkCheck');
        let array = [];
 
        for(let i = 0 ; i < drinkCheck.length ; i++){
          if(drinkCheck[i].src == "http://localhost:8000/images/analysis/check.png"){
              array.push(drinkCheck[i].id);
          }
        }
        this.productOrderUrl = "/product?"  + "value=" + array
        location.replace(this.productOrderUrl);

      },
      checkImgChange(stock_id){
        let stockId = document.getElementById(stock_id);
        
        if(stockId.src == "http://localhost:8000/images/analysis/check.png"){
          stockId.src = "http://localhost:8000/images/analysis/uncheck.png";
          this.chooseDrinkProductCount--;
        }else{
          stockId.src = "http://localhost:8000/images/analysis/check.png";
          this.chooseDrinkProductCount++;
        }
        
      },
      /* <----- 음료 주문으로 넘어가는 메서드 -----> */

      /* <----- 음료별 판매순위 년/월/주 버튼 누를 시 넘어오는 메서드-----> */
      drinkRankDateChange(date){
        switch(date){
          case 'year':
            this.drinkYearChip  = true;
            this.drinkMonthChip = false;
            this.drinkWeekChip  = false;
            this.drinkRankDate  = 'year';
            break;
          case 'month':
            this.drinkYearChip  = false;
            this.drinkMonthChip = true;
            this.drinkWeekChip  = false;
            this.drinkRankDate  = 'month';
            break;
          case 'week':
            this.drinkYearChip  = false;
            this.drinkMonthChip = false;
            this.drinkWeekChip  = true;
            this.drinkRankDate  = 'week';
            break;
        }
         this.drinkSalesRank();
      },
      /* <-----음료별 판매순위 년/월/주 버튼 누를 시 넘어오는 메서드-----> */

      /* <-----매출순 자판기 년/월/주 버튼 누를 시 넘어오는 메서드-----> */
      rankVdDateChange(date){
        switch(date){
          case 'year':
            this.yearChip = true;
            this.monthChip = false;
            this.weekChip = false;
            this.rankVdDate = 'year';
            break;
          case 'month':
            this.yearChip = false;
            this.monthChip = true;
            this.weekChip = false;
            this.rankVdDate = 'month';
            break;
          case 'week':
            this.yearChip = false;
            this.monthChip = false;
            this.weekChip = true;
            this.rankVdDate = 'week';
            break;
        }
         this.rankVdData();
      },
      /* <-----매출순 자판기 년/월/주 버튼 누를 시 넘어오는 메서드-----> */

      /* <----- sort 작업 시, 넘어오는 메서드-----> */
      rankVdSortChange(){
        if(this.rankVdSortImgSrc == '/images/analysis/descendingOrder.png'){
          this.rankVdSort = 'asc';
          this.rankVdSortImgSrc = '/images/analysis/ascendingOrder.png';
        }else{
          this.rankVdSort = 'desc';
          this.rankVdSortImgSrc = '/images/analysis/descendingOrder.png';
        }
        this.rankVdData();
      },
      /* <----- sort 작업 시, 넘어오는 메서드-----> */

      /* <----- 장소 버튼을 클릭 시, 넘어오는 메서드-----> */
      placeChange(place){
        switch(place){
          case 'all':
            this.allBtnSrc       = '/images/analysis/all1.png';
            this.schoolBtnSrc    = '/images/analysis/school.png';
            this.companyBtnSrc   = '/images/analysis/company.png';
            this.hospitalBtnSRc  = '/images/analysis/hospital.png';
            this.parkBtnSrc      = '/images/analysis/park.png';
            this.choosePlace     = 'all';
            break;
          case 'school':
            this.allBtnSrc       = '/images/analysis/all.png';
            this.schoolBtnSrc    = '/images/analysis/school1.png';
            this.companyBtnSrc   = '/images/analysis/company.png';
            this.hospitalBtnSRc  = '/images/analysis/hospital.png';
            this.parkBtnSrc      = '/images/analysis/park.png';
            this.choosePlace     = 'school';
            break;
          case 'company':
            this.allBtnSrc       = '/images/analysis/all.png';
            this.schoolBtnSrc    = '/images/analysis/school.png';
            this.companyBtnSrc   = '/images/analysis/company1.png';
            this.hospitalBtnSRc  = '/images/analysis/hospital.png';
            this.parkBtnSrc      = '/images/analysis/park.png';          
            this.choosePlace     = 'company';
            break;
          case 'hospital':
            this.allBtnSrc       = '/images/analysis/all.png';
            this.schoolBtnSrc    = '/images/analysis/school.png';
            this.companyBtnSrc   = '/images/analysis/company.png';
            this.hospitalBtnSRc  = '/images/analysis/hospital1.png';
            this.parkBtnSrc      = '/images/analysis/park.png';
            this.choosePlace     = 'hospital';
            break;
          case 'park':
            this.allBtnSrc       = '/images/analysis/all.png';
            this.schoolBtnSrc    = '/images/analysis/school.png';
            this.companyBtnSrc   = '/images/analysis/company.png';
            this.hospitalBtnSRc  = '/images/analysis/hospital.png';
            this.parkBtnSrc      = '/images/analysis/park1.png';
            this.choosePlace = 'park';
            break;
        }

        this.realTimeData();
        this.rankVdData();
        this.yearMonthWeekSalesData();
      },
      /* <----- 장소 버튼을 클릭 시, 넘어오는 메서드-----> */

      /* <----- 자판기가 클릭 될 시 현재 클릭 된 자판기 변경 메서드-----> */
      vdIdChange(vd_id, vd_name, vd_supplementer, vd_num){
        this.analysisVdId   = vd_id;
        this.analysisVdName = vd_name;
        this.analysisVdSp   = vd_supplementer;
        

        if(this.rankVdSort == 'asc'){
          let url = "/analysis/getVdCount";

          //전체 자판기 수를 알기 위한 axios이다
          this.axios.get(url).then((response) =>{
            this.allVendingCount = response.data;  
          }); 
          vd_num = Math.abs(vd_num - (this.allVendingCount+1));
          switch(vd_num){
            case 1:
              this.clickVdSrc1 = '/images/analysis/arrow.png';
              this.clickVdSrc2 = '';
              this.clickVdSrc3 = '';
              this.clickVdSrc4 = '';
              this.clickVdSrc5 = '';
              break;
            case 2:
              this.clickVdSrc1 = '';
              this.clickVdSrc2 = '/images/analysis/arrow.png';
              this.clickVdSrc3 = '';
              this.clickVdSrc4 = '';
              this.clickVdSrc5 = '';
              break;
            case 3:
              this.clickVdSrc1 = '';
              this.clickVdSrc2 = '';
              this.clickVdSrc3 = '/images/analysis/arrow.png';
              this.clickVdSrc4 = '';
              this.clickVdSrc5 = '';
              break;
            case 4:
              this.clickVdSrc1 = '';
              this.clickVdSrc2 = '';
              this.clickVdSrc3 = '';
              this.clickVdSrc4 = '/images/analysis/arrow.png';
              this.clickVdSrc5 = '';
              break;
            case 5:
              this.clickVdSrc1 = '';
              this.clickVdSrc2 = '';
              this.clickVdSrc3 = '';
              this.clickVdSrc4 = '';
              this.clickVdSrc5 = '/images/analysis/arrow.png';
             break;
          }
        }else{
          switch(vd_num){
            case 1:
              this.clickVdSrc1 = '/images/analysis/arrow.png';
              this.clickVdSrc2 = '';
              this.clickVdSrc3 = '';
              this.clickVdSrc4 = '';
              this.clickVdSrc5 = '';
              break;
            case 2:
              this.clickVdSrc1 = '';
              this.clickVdSrc2 = '/images/analysis/arrow.png';
              this.clickVdSrc3 = '';
              this.clickVdSrc4 = '';
              this.clickVdSrc5 = '';
              break;
            case 3:
              this.clickVdSrc1 = '';
              this.clickVdSrc2 = '';
              this.clickVdSrc3 = '/images/analysis/arrow.png';
              this.clickVdSrc4 = '';
              this.clickVdSrc5 = '';
              break;
            case 4:
              this.clickVdSrc1 = '';
              this.clickVdSrc2 = '';
              this.clickVdSrc3 = '';
              this.clickVdSrc4 = '/images/analysis/arrow.png';
              this.clickVdSrc5 = '';
              break;
            case 5:
              this.clickVdSrc1 = '';
              this.clickVdSrc2 = '';
              this.clickVdSrc3 = '';
              this.clickVdSrc4 = '';
              this.clickVdSrc5 = '/images/analysis/arrow.png';
             break;
          }
        }

        this.vendingAnalysis(this.analysisVdId);
      },
      /* <----- 자판기가 클릭 될 시 현재 클릭 된 자판기 변경 메서드-----> */

/* <----- 음료 라인 변경 클릭시 호출 되는 전체 메서드 -----> */

      /* <----- 음료 라인변경 모달창 오픈 메서드 -----> */
      dialogOpen(){
        this.dialog = true;
      },
      /* <----- 음료 라인변경 모달창 오픈 메서드 -----> */

      /* <----- 변경 전 음료 정보를 저장 -----> */ 
      lineChangeBeforeDrink(id, name, path){
        this.vdDrinkId = id;
        this.vdDrinkName = name;
        this.vdDrinkPath = path;
      },
      /* <----- 변경 전 음료 정보를 저장 -----> */ 

      /* <----- 변경 될 음료 정보를 저장 -----> */ 
      lineChangeAfterDrink(id, name, path){
        this.changeDrinkId = id;
        this.changeDrinkName = name;
        this.changeDrinkPath = path;
      },      
      /* <----- 변경 될 음료 정보를 저장 -----> */ 

      /* <----- 라인 변경 DB로 전송 -----> */ 
      setLineChangeNote(vd_id,existingDrink,changeDrink){
         let url = "/analysis/setLineChangeNote/" + vd_id + "/" + existingDrink + "/" + changeDrink;
         this.axios.get(url).then((response) => {
         });
      },
      /* <----- 라인 변경 DB로 전송 -----> */ 
      
      /* <----- 라인 변경 취소 시 다시 초기화 메서드 -----> */ 
      lineChangeDrinkReset(){
        this.vdDrinkId = '';
        this.changeDrinkId = '';
        this.vdDrinkName = '';
        this.changeDrinkName = '';
        this.vdDrinkPath = '/images/default.png';
        this.changeDrinkPath = '/images/default.png';
      },
      /* <----- 라인 변경 취소 시 다시 초기화 메서드-----> */ 

/* <------ 음료 라인 변경 클릭시 호출 되는 전체 메서드 ------> */   

      /* <----- 화면 최상단 실시간 데이터 넣는 메서드-----> */ 
      realTimeData(place){
        let url = "/analysis/analysisData/" + this.choosePlace;
        let regexp = /\B(?=(\d{3})+(?!\d))/g;  // 정규식을 통해 3자리마다 ,를 찍어준다.
        //만약 place버튼 클릭시, 해당 클릭에 맞는 값으로 변경
        this.axios.get(url).then((response) => {
          this.nowMonthProduct  = response.data[0][0].count.toString().replace(regexp, ',');    //이번달 팔린 판매량을 대입.
          this.nowDayProduct    = response.data[1][0].count.toString().replace(regexp, ',');    // 오늘 팔린 판매량을 대입.
          this.nowDaySales      = response.data[1][0].getSales.toString().replace(regexp, ','); // 오늘 매출액을 대입.
        });
      },
      /* <----- 화면 최상단 실시간 데이터 넣는 메서드 -----> */ 

      /* <----- 자판기 내 팔고 있지 않는 음료 리스트 가져오는 메서드 -----> */ 
      theOtherAnalysis(vd_id){
        let url = "/analysis/vendingmachineAnalysis/" + vd_id;
        let regexp = /\B(?=(\d{3})+(?!\d))/g;  // 정규식을 통해 3자리마다 ,를 찍어준다.
        this.axios.get(url).then((response) =>{
          for(let i = 0 ; i < response.data[1].length ; i++){
            response.data[1][i].count = response.data[1][i].count.toString().replace(regexp, ',');
          };
          this.vendingDrinkList = response.data[0];
          this.theRestDrinkRankArray = response.data[1]; // 자판기 내 없는 음료들을 담는다.
          this.avgSupplementCycle = parseFloat(response.data[2][0].spInterval).toFixed(1);
          this.complain = response.data[3][0].count;
          this.sellPlace = response.data[4][0].place;
        });
      },
      /* <----- 자판기 내 팔고 있지 않는 음료 리스트 가져오는 메서드 -----> */ 

      /* <----- 한 자판기에 대한 분석 통계 가져오는 메서드 -----> */ 
      vendingAnalysis(vd_id){
          let nowRadarLabelArray = [];
          let beforRadarLabelArray = [];
          let nowRadarDataArray = [];
          let beforRadarDataArray = [];
          
          let pushDate = '';
          let nowDate = '';
          let beforDate = '';
          
          if(this.yearChip == true){
            pushDate = 'year';
            nowDate = '올해';
            beforDate = '작년';
          }else if(this.monthChip == true){
            pushDate = 'month';
            nowDate = '이번달';
            beforDate = '저번달';
          }else{
            pushDate = 'week';
            nowDate = '이번주';
            beforDate = '저번주';
          }

          this.theOtherAnalysis(vd_id);  
          let url = "/analysis/differenceVdAnalysis/" + vd_id + '/' + pushDate;
          let regexp = /\B(?=(\d{3})+(?!\d))/g;  // 정규식을 통해 3자리마다 ,를 찍어준다.
          this.axios.get(url).then((response) =>{
          for(let i = 0 ; i < response.data[0].length ; i++){
            beforRadarLabelArray.push(response.data[0][i].drink_name);
            beforRadarDataArray.push(response.data[0][i].count);
          }
          for(let i = 0 ; i < response.data[1].length ; i++){
            nowRadarLabelArray.push(response.data[1][i].drink_name);
            nowRadarDataArray.push(response.data[1][i].count);
          }

            // -----차트 정보 부분 -----
            this.radarChart = {
              labels: nowRadarLabelArray,
              datasets: [
                {
                  label: nowDate,
                  labelColor: '#FF0000',
                  pointBorderColor : '#FF0000',
                  borderColor: '#FF0000',
                  data: nowRadarDataArray
                },
                 {
                  label: beforDate,
                  labelColor: '#0054FF',
                  pointBorderColor : '#0054FF',
                  borderColor: '#0054FF',
                  data: beforRadarDataArray
                }
              ]
            };
            // -----차트 정보 부분 -----

            // -----차트 옵션 부분 -----
            this.radarOption = {
              responsive : false,
              maintainAspectRatio : false,
              legend :{
                position: 'bottom'
              }
              // scales: {
              //   yAxes: [{
              //     ticks: {
              //       // Include a dollar sign in the ticks
              //       callback: function(value, index, values) {
              //         // y축에서 1000자리의 숫자에는 ','을 찍는다.
              //         if(value.toString().length > 4){
              //           value = value.toString().slice(0,2)+','+value.toString().slice(2,5);
              //         }
              //         else if(value.toString().length > 3){
              //           value = value.toString().slice(0,1)+','+value.toString().slice(1,4);
              //         }
              //         return value;
              //       }
              //     }
              //   }]
              // }
            };
            // -----차트 옵션 부분 -----
          });
      },
      /* <----- 한 자판기에 대한 분석 통계 가져오는 메서드 -----> */ 
      realtimeVdSearch(num){
        this.realtimeVdId = 0;
        num = num + 1;
        let url = "/analysis/ListOfDrinkSales/" + this.choosePlace + "/" + this.rankVdDate + "/" + this.rankVdSort + "/" + num;
        this.axios.get(url).then((response) => {
          this.rankVdDataArray = response.data[0];                   // 처음 최상단에 올라오는 자판기 데이터
          this.analysisVdId = response.data[0][0].vd_id;             
          this.analysisVdName = response.data[0][0].vd_name;
          this.analysisVdSp = response.data[0][0].vd_supplementer;
          this.vendingAnalysis(this.analysisVdId);
        });
      },
      /* <----- 자판기 매출순 테이블에 값 가져오는 메서드 -----> */ 
      rankVdData(){
        //만약 실시간페이지에서 한 자판기에 대한 값을 가져 올 경우, 해당 자판기의 값을 본다.
        let url = "/analysis/ListOfDrinkSales/" + this.choosePlace + "/" + this.rankVdDate + "/" + this.rankVdSort + "/0";
        let regexp = /\B(?=(\d{3})+(?!\d))/g;  // 정규식을 통해 3자리마다 ,를 찍어준다.
        this.axios.get(url).then((response) => {
          for(let i = 0 ; i < response.data[0].length ; i++){
            response.data[0][i].count = response.data[0][i].count.toString().replace(regexp, ',');
            response.data[0][i].getSales = response.data[0][i].getSales.toString().replace(regexp, ',');
          };
          response.data[1][0].value = response.data[1][0].value.toString().replace(regexp, ',');
    
     
          if(this.realtimeVdId !== 0){
            for(let i = 0 ; i < response.data[2].length ; i++){
              if(response.data[2][i].vd_id == this.realtimeVdId){
                this.realtimeVdSearch(response.data[2][i].num);
              }
            }
          }else{
            this.rankVdDataArray = response.data[0];                   // 처음 최상단에 올라오는 자판기 데이터
            this.analysisVdId = response.data[0][0].vd_id;             
            this.analysisVdName = response.data[0][0].vd_name;
            this.analysisVdSp = response.data[0][0].vd_supplementer;
            this.vendingAnalysis(this.analysisVdId);

          }

          this.interestVdDataArray = response.data[1];               // 관심 자판기 데이터
        });       
      },
      /* <----- 자판기 매출순 테이블에 값 가져오는 메서드 -----> */ 

      /* <----- 음료 랭킹 순 값 가져오는 메서드 -----> */ 
      drinkSalesRank(){
        let barLabelsArray = [];
        let barDataArray = [];
        let url = "/analysis/drinkSalesRank/" + this.drinkRankDate;
        this.axios.get(url).then((response) =>{
          for(let i = 0 ; i < response.data[0].length ; i++){
            barLabelsArray.push(response.data[0][i].drink_name);
            barDataArray.push(response.data[0][i].count);
          };
          for(let i = 0 ; i < response.data[1].length ; i++){
            response.data[1][i].avgSales = parseFloat(response.data[1][i].avgSales).toFixed(1);
          };
          
          this.drinkExhaustAnalysisArray = response.data[1];
          // 예상 소진일이 다되어 주문이 필요한 음료의 갯수를 카운트 해놓는다.
          for(let i = 0 ; i < this.drinkExhaustAnalysisArray.length ; i++){
            if(this.drinkExhaustAnalysisArray[i].prediction < 4)
              this.chooseDrinkProductCount++;
          }

          // -----차트 정보 부분 -----
            this.barChart = {
              labels: barLabelsArray,
              datasets: [
                {
                  //label: '음료 판매량(개)',
                  backgroundColor:['#EB6000', '#4374D9'],
                  data: barDataArray
                }
              ]
            };
            // -----차트 정보 부분 -----

            // -----차트 옵션 부분 -----
            this.barOptions = {
              responsive : false,
              maintainAspectRatio : false,
              legend :{
                display: false
              },
              scales: {
                xAxes: [{
                    barPercentage: 0.4
                }],
                yAxes: [{
                  ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                      // y축에서 1000자리의 숫자에는 ','을 찍는다.
                      if(value.toString().length > 4){
                        value = value.toString().slice(0,2)+','+value.toString().slice(2,5);
                      }
                      else if(value.toString().length > 3){
                        value = value.toString().slice(0,1)+','+value.toString().slice(1,4);
                      }
                      return value;
                    }
                  }
                }]
              }
            };
            // -----차트 옵션 부분 -----
          });
      },
      /* <----- 음료 랭킹 순 값 가져오는 메서드 -----> */ 
      
      /* <----- 학교, 공원, 회사, 병원 등 장소별 판매량 PieChart로 확인 -----> */ 
      placeSalesData(){
        // 장소별 label에 적힐 배열들
        let schoolLabelsArray = [];
        let parkLabelsArray = [];
        let companyLabelsArray = [];
        let hospitalLabelsArray = [];

        // 장소별 data에 적힐 배열들
        let schoolDataArray = [];
        let parkDataArray = [];
        let companyDataArray = [];
        let hospitalDataArray = [];

        let url = "/analysis/pieGraphData";
        this.axios.get(url).then((response) =>{
          for(let i = 0 ; i < response.data[0].length/2 ; i++){
            schoolLabelsArray.push(response.data[0][i].drink_name);
            schoolDataArray.push(response.data[0][i].count)
          };
          for(let i = 0 ; i < response.data[1].length/2 ; i++){
            parkLabelsArray.push(response.data[1][i].drink_name);
            parkDataArray.push(response.data[1][i].count)
          };
          for(let i = 0 ; i < response.data[2].length/2 ; i++){
            companyLabelsArray.push(response.data[2][i].drink_name);
            companyDataArray.push(response.data[2][i].count)
          };
          for(let i = 0 ; i < response.data[3].length/2 ; i++){
            hospitalLabelsArray.push(response.data[3][i].drink_name);
            hospitalDataArray.push(response.data[3][i].count)
          };
          this.pieChart = {
              labels: schoolLabelsArray,
              datasets: [
                {
                  label: '음료 판매량(개)', 
                  backgroundColor: ['#FF0000','#0066FF','#FF9900','#339900','#0064C8'],
                  data: schoolDataArray // push된 배열 데이터를 data에 저장.
                }
              ]
            };
          this.pieChart2 = {
            labels: parkLabelsArray,
            datasets: [
              {
                label: '음료 판매량(개)', 
                backgroundColor: ['#FF0000','#0066FF','#FF9900','#339900','#0064C8'],
                data: parkDataArray // push된 배열 데이터를 data에 저장.
              }
            ]
          };
          this.pieChart3 = {
            labels: companyLabelsArray,
            datasets: [
              {
                label: '음료 판매량(개)', 
                backgroundColor: ['#FF0000','#0066FF','#FF9900','#339900','#0064C8'],
                data: companyDataArray // push된 배열 데이터를 data에 저장.
              }
            ]
          };
          this.pieChart4 = {
            labels: hospitalLabelsArray,
            datasets: [
              {
                label: '음료 판매량(개)', 
                backgroundColor: ['#FF0000','#0066FF','#FF9900','#339900','#0064C8'],
                data: hospitalDataArray // push된 배열 데이터를 data에 저장.
              }
            ]
          };

          this.pieOptions = {
              responsive : false,
              maintainAspectRatio : false,
              legend :{
                position: 'left'
              }
          };
          this.pieOptions2 = {
              responsive : false,
              maintainAspectRatio : false,
              legend :{
                position: 'left'
              }
          };
          this.pieOptions3 = {
              responsive : false,
              maintainAspectRatio : false,
              legend :{
                position: 'left'
              }
          };
          this.pieOptions4 = {
              responsive : false,
              maintainAspectRatio : false,
              legend :{
                position: 'left'
              }
          };
         
        });
      },
      /* <----- 학교, 공원, 회사, 병원 등 장소별 판매량 PieChart로 확인 -----> */ 
      
      /* <----- 년/월/주간 데이터 가져오는 메서드 -----> */ 
      yearMonthWeekSalesData(){
        let yearLabelsArray = [];
        let monthLabelsArray = [];
        let weekLabelsArray = [];

        let yearDataArray = [];
        let monthDataArray = [];
        let weekDataArray = [];

        let url = "/analysis/yearMonthWeekData/" + this.choosePlace;
        this.axios.get(url).then((response) =>{
          for(let i = response.data[0].length - 1 ; i >= 0 ; i--){
            yearLabelsArray.push(response.data[0][i].date);
            yearDataArray.push(response.data[0][i].count)
          };
          for(let i = response.data[1].length - 1 ; i >= 0 ; i--){
            monthLabelsArray.push(response.data[1][i].date);
            monthDataArray.push(response.data[1][i].count)
          };
          for(let i = response.data[2].length - 1 ; i >= 0 ; i--){
            weekLabelsArray.push(response.data[2][i].date);
            weekDataArray.push(response.data[2][i].count)
          };

          this.yearDataArray = yearDataArray;
          this.monthDataArray = monthDataArray;
          this.weekDataArray = weekDataArray;
          // -----년간 판매량 차트 정보 부분 -----
          this.lineChart = {
              labels: yearLabelsArray,
              datasets: [
                {
                  label: '음료 판매량(개)', 
                  borderColor: "#0064C8",
                  pointBorderColor: "#0064C8",
                  pointBackgroundColor: "#80b6f4",
                  pointHoverBackgroundColor: "#80b6f4",
                  pointHoverBorderColor: "#80b6f4",
                  pointBorderWidth: 3,
                  pointHoverRadius: 3,
                  pointHoverBorderWidth: 1, 
                  pointRadius: 2,
                  borderWidth: 3,

                  data: yearDataArray // push된 배열 데이터를 data에 저장.
                }
              ]
            };
            // -----년간 판매량 차트 정보 부분 -----
            // -----년간 판매량 차트 추가 옵션 부분 -----
            this.lineOptions = {
              responsive : false,
              maintainAspectRatio : false,
              scales: {
                yAxes: [{
                  ticks: {
                    // 콜백함수로써, value는 차트 y축에 적힐 값들을 의미.
                    callback: function(value, index, values) {
                      //y축에서 1000자리의 숫자에는 ','을 찍는다.
                      if(value.toString().length > 4){
                        value = value.toString().slice(0,2)+','+value.toString().slice(2,5);
                      }
                      else if(value.toString().length > 3){
                        value = value.toString().slice(0,1)+','+value.toString().slice(1,4);
                      }
                      return value;
                      
                    }
                  }
                }]
              },
            };
            // -----년간 판매량 차트 추가 옵션 부분 -----


            // -----월간 판매량 차트 정보 부분 -----
          this.lineChart2 = {
              labels: monthLabelsArray,
              datasets: [
                {
                  label: '음료 판매량(개)', 
                  borderColor: "#0064C8",
                  pointBorderColor: "#0064C8",
                  pointBackgroundColor: "#80b6f4",
                  pointHoverBackgroundColor: "#80b6f4",
                  pointHoverBorderColor: "#80b6f4",
                  pointBorderWidth: 3,
                  pointHoverRadius: 3,
                  pointHoverBorderWidth: 1, 
                  pointRadius: 2,
                  borderWidth: 3,

                  data: monthDataArray // push된 배열 데이터를 data에 저장.
                }
              ]
            };
            // -----월간 판매량 차트 정보 부분 -----
            // -----월간 판매량 차트 추가 옵션 부분 -----
            this.lineOptions2 = {
              responsive : false,
              maintainAspectRatio : false,
              scales: {
                yAxes: [{
                  ticks: {
                    // 콜백함수로써, value는 차트 y축에 적힐 값들을 의미.
                    callback: function(value, index, values) {
                      //y축에서 1000자리의 숫자에는 ','을 찍는다.
                      if(value.toString().length > 4){
                        value = value.toString().slice(0,2)+','+value.toString().slice(2,5);
                      }
                      else if(value.toString().length > 3){
                        value = value.toString().slice(0,1)+','+value.toString().slice(1,4);
                      }
                      return value;
                      
                    }
                  }
                }]
              },
            };
            // -----월간 판매량 차트 추가 옵션 부분 -----


            // -----주간 판매량 차트 정보 부분 -----
          this.lineChart3 = {
              labels: weekLabelsArray,
              datasets: [
                {
                  label: '음료 판매량(개)', 
                  borderColor: "#0064C8",
                  pointBorderColor: "#0064C8",
                  pointBackgroundColor: "#80b6f4",
                  pointHoverBackgroundColor: "#80b6f4",
                  pointHoverBorderColor: "#80b6f4",
                  pointBorderWidth: 3,
                  pointHoverRadius: 3,
                  pointHoverBorderWidth: 1, 
                  pointRadius: 2,
                  borderWidth: 3,

                  data: weekDataArray // push된 배열 데이터를 data에 저장.
                }
              ]
            };
            // -----주간 판매량 차트 정보 부분 -----
            // -----주간 판매량 차트 추가 옵션 부분 -----
            this.lineOptions3 = {
              responsive : false,
              maintainAspectRatio : false,
              scales: {
                yAxes: [{
                  ticks: {
                    // 콜백함수로써, value는 차트 y축에 적힐 값들을 의미.
                    callback: function(value, index, values) {
                      //y축에서 1000자리의 숫자에는 ','을 찍는다.
                      if(value.toString().length > 4){
                        value = value.toString().slice(0,2)+','+value.toString().slice(2,5);
                      }
                      else if(value.toString().length > 3){
                        value = value.toString().slice(0,1)+','+value.toString().slice(1,4);
                      }
                      return value;
                      
                    }
                  }
                }]
              },
            };
            // -----주간 판매량 차트 추가 옵션 부분 -----
        });
      },
      /* <----- 년/월/주간 데이터 가져오는 메서드 -----> */ 
     

    }
  }

</script>


<style>
/* ----- DIV 스타일 정의 ----- */
#analysisLineChartBackgroundDiv{
  display     : grid;
  grid-template-rows: 0.05fr 0.95fr;
  margin-right: 5%;
  
}
#analysisLineChartBackgroundDiv .speed-dial {
  position: fixed;
}
.collums64DivideDiv{
  display     : grid;
  grid-template-columns: 0.6fr 0.4fr;
  width: 100%;
  height: 100%;
  padding-bottom: 1%;
}
.rows82DivideDiv{
  display     : grid;
  grid-template-rows: 0.8fr 0.2fr;
  width: 100%;
  height: 100%;

}
#anaylsisRealDataSecondDiv{
  display     : grid;
  grid-template-columns: 0.3fr 0.7fr;
  width: 100%;
  height: 100%;
  margin-top: 1%;
}
#anaylsisRealDataSecondContentDiv{
  border-radius: 20px 20px 20px 20px; 
  border-top: 2px solid #0064C8;
  border-right: 2px solid #0064C8;
  border-left: 2px solid #0064C8;
  background-color: #0064C8;
  display     : grid;
  grid-template-columns: 0.33fr 0.33fr 0.33fr;
  font-weight: bold ;
  width: 100%;
  height: 100%;
}

.collums25DivideDiv{
  display     : grid;
  grid-template-columns: 0.25fr 0.25fr 0.25fr 0.25fr;
  width: 100%;
  height: 100%;
}
.collums55DivideDiv{
  display     : grid;
  grid-template-columns: 0.5fr 0.5fr;
  font-weight: bold ;
  color: #0064C8;
  width: 100%;
}
.collums333DivideDiv{
  display     : grid;
  grid-template-columns: 0.33fr 0.33fr 0.33fr;
  font-weight: bold ;
  color: #0064C8;
  width: 100%;
  background-color:  #ffffff;
  border-radius: 10px 10px 10px 10px; 
  margin-top: 2%;
  margin-bottom: 2%;
  padding: 1%;
  box-shadow: 1px 1px 5px;
}

#drinkProductBackground{
  background-color:  #ffffff;
  border-radius: 10px 10px 10px 10px; 
  box-shadow: 1px 1px 5px;
  padding: 1%;
  margin-bottom: 10%;
}
.rows19DivideDiv{
  display     : grid;
  width: 100%;
  height: 100%;
  grid-template-rows: 0.1fr 0.9fr;
}
#analysisVdDiv{
  display     : grid;
  grid-template-columns: 0.5fr 0.5fr;
  background-color:  #ffffff;
  border-radius: 10px 10px 10px 10px; 
  margin-bottom: 2%;
  padding: 1%;
  height: 550px;
  box-shadow: 1px 1px 5px;
}

#tableArrowDiv{
  display     : grid;
  grid-template-columns: 0.99fr 0.01fr;
  width: 100%;
  height: 100%;
}
/* ----- 상단 실시간 데이터 부분 DIV ----- */

/* ----- 매출순자판기 데이터 부분 DIV ----- */
#placeDivisionDiv{
  display     : grid;
  grid-template-columns: 0.1fr 0.9fr;
  width: 100%;
  height: 100%;
}

.btnRightDiv{
  float: right;
}


.titleContentDivideDiv{
  display     : grid;
  width: 100%;
  height: 100%;
  grid-template-rows: 0.1fr 0.9fr;
  background-color:  #ffffff;
  border-radius: 10px 10px 10px 10px; 
  box-shadow: 1px 1px 5px;
}
#interestVdTableDiv{
  background-color:  #0064C8;
  border-radius: 0px 0px 10px 10px; 
  color: white;
}
#graphDrinkRankDivideDiv{
  display     : grid;
  grid-template-rows: 0.7fr 0.3fr;
  width: 100%;
  height: 100%;
}

#clickVdName{
  font-weight: bold; 
  background-color:#0064C8;
  margin-top: 0.5%;
  border-radius: 30px 30px 30px 30px; 
  color: white;
  padding-top: 0.5%;
  padding-bottom: 0.5%;
  min-width: 70%;
  height: 100%;
}
/* ----- 매출순자판기 데이터 부분 DIV ----- */


a:link { text-decoration: none; color: #000000;}


.btnRightDiv:hover{
  -ms-transform: scale(1.1); /* IE 9 */
  -webkit-transform: scale(1.1); /* Safari 3-8 */
  transform: scale(1.1); 
}
.borderless td, .borderless th {
    border: none;
    text-align: left;
    font-size: 13pt;
    color: #0064C8;
}

#titleContentDivideDiv1{
  margin-right: 2%;
  margin-bottom: 2%;
}
#titleContentDivideDiv2{
  margin-left: 2%;
  margin-bottom: 2%;

}

.mainContents{
  font-family: "Nanum Gothic";
  font-size: 20pt;
  text-align: left;
}
.table{
  font-size: 15pt;
}
#chartData{
  float: left;
}
/* 테이블 내 글자 무조건 한 줄로 다나오게 하는것 */
thead{
  white-space: nowrap;
}

</style>