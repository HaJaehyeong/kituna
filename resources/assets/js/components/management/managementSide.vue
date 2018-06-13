<template>
  <div>
    <v-tabs v-model="active" color="gray" dark slider-color="yellow" v-if="TrueOrFalse==false">
      <v-tab v-for="choose in spOrVdCard" :key="choose" ripple @click="contentPageChange(choose)"> {{ choose }} 조회</v-tab>
      <v-tab-item v-for="choose in spOrVdCard" :key="choose">
        <v-card flat v-if="choose=='보충기사'">
          <div>
            <v-btn dark @click="insertSupple = true, spIn = true">보충기사 등록</v-btn>
            <v-btn dark @click="spUpRe(true, false)">보충기사 수정</v-btn>
            <v-btn dark @click="spUpRe(false, true)">보충기사 삭제</v-btn>
            <v-dialog v-model="insertSupple" persistent max-width="500px">
              <v-card>
                <v-card-title>
                  <span v-if="spIn == true" class="headline">보충기사 등록</span>
                  <span v-else class="headline">보충기사 수정</span>
                </v-card-title>
                <v-card-text>
                  <img id="blah" src="http://placehold.it/180" alt="your image" style="width: 180px; height: 180px;">
                  <input type="file" accept=".png" id="inputFile" @change="readURL('inputFile')">
                </v-card-text>
                <v-card-text v-if="spIn == true">
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12>
                        <v-text-field label="Input your name" required id="spInputName"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your Id" required id="spInputId"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your password" required id="spInputPasswd"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your mail" required id="spInputMail"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your phone" required id="spInputPhone"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your address" required id="spInputAddress"></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-text v-else>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12>
                        <v-text-field label="Input your name" required id="spInputNameChange" :value="spInputNameChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your Id" required id="spInputIdChange" :value="spInputIdChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your password" required id="spInputPasswdChange" :value="spInputPasswdChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your mail" required id="spInputMailChange" :value="spInputMailChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your phone" required id="spInputPhoneChange" :value="spInputPhoneChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your address" required id="spInputAddressChange" :value="spInputAddressChange"></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-actions v-if="spIn == true">
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click="spInFunc">등록</v-btn>
                  <v-btn color="blue darken-1" flat @click.native="insertSupple = false, spIn = false">취소</v-btn>
                </v-card-actions>
                <v-card-actions v-else>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click="spUpFunc">수정</v-btn>
                  <v-btn color="blue darken-1" flat @click.native="insertSupple = false, spUp = false">취소</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <div class="fixed-table-container">
              <div class="header-bg"></div>
              <div class="table-wrapper">
                <table>
                  <thead> 
                    <th width="8%"> <!-- %넓이 값을 지정을 위해 div를 포함시키기. -->
                      <div class="th-text">번호</div>
                    </th>
                    <th width="25%">
                      <div class="th-text">사진</div>
                    </th>
                    <th width="17%">
                      <div class="th-text">이름</div>
                    </th>
                  </thead>
                  <tbody>
                    <tr v-for="sp in spArray" :key="sp.No" @click="spInfor(sp.Id, sp.sp_id)" :id="sp.sp_id">
                      <td>{{sp.No}}</td>
                      <td><img :src="sp.Pic" id="spImage"></td>
                      <td>{{sp.Name}}</td>
                    </tr>
                  </tbody>
                </table>      
              </div>
            </div>
          </div>
        </v-card>
        <v-card flat v-else-if="choose=='자판기'">
          <v-card>
            <!-- ********************** 자판기 등록창********************** -->
            <v-layout row justify-center>
              <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition" :overlay="false">
                <v-btn slot="activator" @click="DrinkList">자판기 등록</v-btn>
                <v-card  color="light-blue darken-1">
                  <v-toolbar >
                    <v-btn icon @click.native="dialog = false" >
                      <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn color="white" flat @click.native="close">Cancel</v-btn>
                      <v-btn color="white" flat @click.native="save">Save</v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <br>
                <v-card>
                <v-card-title>
                  <span class="headline">자판기 기본정보</span>
                </v-card-title>
                <v-card-text>
                  <!-- ********************** 자판기 기본 등록 및 수정 내부 창 ********************** -->
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs2 order-md1 order-xs3>
                        <v-text-field color="light-blue darken-4"  name="input-3-4" label="자판기 이름"  class="input-group--focused"  :rules="[() => editedItem.Ven_Name.length > 0 || 'Vendingmachine name is required']"  required v-model="editedItem.Ven_Name"></v-text-field>
                      </v-flex>
                      <v-flex xs2 order-md1 order-xs4>
                        <v-text-field color="light-blue darken-4 " name="input-3-4" label="위치"  class="input-group--focused"  :rules="[() => editedItem.Location.length > 0 || 'ex)Buk-gu, Daegu, Republic of Korea']"  required   v-model="editedItem.Location"></v-text-field>
                      </v-flex> 
                      <v-flex xs2 order-md1 order-xs3>
                        <v-select
                          :items="select"
                          label="관리자명"
                          item-value="text"
                          v-model ="editedItem.Manager">
                        </v-select>
                      </v-flex>                
                      <v-flex xs3 order-md1  order-xs6>
                        <label>
                          <gmap-autocomplete @place_changed="setPlace"></gmap-autocomplete>
                          <button @click="addMarker">Add</button>
                        </label>
                      </v-flex>
                      <v-flex xs2 order-md1  order-xs5 >
                        <gmap-map :center="center" :zoom="zoom" style="width:350px;  height: 250px;">
                          <gmap-marker
                            :key="index"
                            v-for="(m, index) in markers"
                            :position="m.position"
                            :markers="m.markers"
                            @click="center=m.position"
                            :draggable="m.draggable">
                          </gmap-marker>
                        </gmap-map>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-actions></v-card-actions>
                </v-card>
                <!-- ********************** 자판기 음료 등록 및 수정 ********************** -->
                <v-card>
                  <v-card-title><span class="headline">{{ formSubTitle }}</span></v-card-title>
                  <v-container fluid grid-list-md>
                    <v-layout row wrap>
                      <v-flex xs1>
                        <v-subheader>음료라인 수</v-subheader>
                      </v-flex>
                      <v-flex xs1>
                        <b-form-select v-model="selected" :options="options" class="mb-1"  />
                      </v-flex>
                    </v-layout>
                    <v-layout row wrap v-if="editedIndex!=-1">
                      <v-flex xs1>
                        <!-- Edit일 경우 뜨는 현재 음료 리스트  -->
                        <v-subheader>현재 음료 리스트</v-subheader>
                      </v-flex>
                      <v-layout  xs2 md2 lg2 v-for="(drinkItem, index) in editedItem_drink" :key="index">
                        <v-flex  v-for="(item2, index)  in drinkItem" :key="index">
                          <v-card-title>{{index}}</v-card-title>
                          <v-card-media   height="70px">   
                            <v-card><img xs3 md3 lg3 :src="item2" style=" height:70px; width:30px;"></v-card>
                            <v-spacer></v-spacer>
                          </v-card-media>  
                        </v-flex> 
                      </v-layout>
                    </v-layout>
                    <br />
                    <v-layout row wrap>
                      <v-flex xs1>
                        <v-subheader>음료 설정</v-subheader>
                      </v-flex>
                      <!-- Set Drink List Card -->
                      <v-flex xs5 md5 lg5>
                        <v-layout row wrap>
                          <v-flex xs3 md3 lg3 v-for="(value, index) in InputDrinkItem" :key="index">
                            <v-card width="150px" height="200px">
                              <draggable :id="index" v-model="itemList_All" :options="{group:'itemList_All'}" @start="drag=true" 
                                @end="drag=false" @add="newLine">
                                <v-card-title class="headline"> 
                                  {{ capLetter(index) }}
                                </v-card-title>
                                <v-card v-for="(item2, index)  in value" :key="index">
                                  <v-card-media  height="70px">  
                                    <v-spacer></v-spacer>  
                                    <img :src="item2" style=" height:70px; width:30px;" >
                                    <v-spacer></v-spacer>
                                  </v-card-media>
                                </v-card> 
                              </draggable>
                            </v-card>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                      <v-flex xs1></v-flex>
                        <!-- All Drink list Card -->
                        <v-flex xs5>  총 음료 리스트 (원하는 음료를 드래그해주세요)
                          <v-layout row wrap>
                            <v-flex xs2 md2 lg2 v-for="(drinkItem, index) in itemList_All" :key="index">
                              <draggable :id="index" v-model="itemList_All" :options="{group: { name:'itemList_All', pull:'clone', put:'false'}}" @start="drag=true" 
                                @end="drag=false" :move="chooseItem">
                                <v-card class="ma-2 pa-1">
                                  <v-card-media  height="70px">
                                    <v-spacer></v-spacer>
                                    <img :src="drinkItem.path" style=" height:70px; width:30px;">
                                    <v-spacer></v-spacer>
                                  </v-card-media>
                                </v-card>
                              </draggable>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card>   
                </v-card>
              </v-dialog>
            </v-layout>
          </v-card>
          <!-- vending machine List -->
          <v-card-title>
            <v-text-field
              append-icon="search"
              label="Please enter search keywords"
              single-line
              hide-details
              v-model="search">
            </v-text-field>
          </v-card-title>
          <v-data-table :headers="headers" :items="items" :search="search">
            <template slot="items" slot-scope="props">
              <tr :id="props.item.No" @mouseover="mouseover(props.item.No)" @mouseout="mouseout(props.item.No)" @click="trClick(props.item.No)">
                <td class="text-xs-right" @click="routerLinkToDetails_s(props.item.No,props.item.Ven_Name,props.item.Manager);routerLinkToDetails_c(props.item.No);">{{ props.item.No }}</td>
                <td class="text-xs-right" @click="routerLinkToDetails_s(props.item.No,props.item.Ven_Name,props.item.Manager);routerLinkToDetails_c(props.item.No);">{{ props.item.Ven_Name }}</td>
                <td class="text-xs-right" @click="routerLinkToDetails_s(props.item.No,props.item.Ven_Name,props.item.Manager); routerLinkToDetails_c(props.item.No);">{{ props.item.Location }}</td>
                <td class="text-xs-right" @click="routerLinkToDetails_s(props.item.No,props.item.Ven_Name,props.item.Manager); routerLinkToDetails_c(props.item.No);">{{ props.item.Manager }}</td>
                <td class="justify-center layout px-0">
                  <v-btn icon class="mx-0" @click="editItem(props.item)">
                    <v-icon color="teal">edit</v-icon>
                  </v-btn>
                  <v-btn icon class="mx-0" @click="deleteItem(props.item)">
                    <v-icon color="pink">delete</v-icon>
                  </v-btn>
                </td>
              </tr>
            </template>
            <template slot="no-data">
              <v-btn color="primary" @click="vendingList">Reset</v-btn>
            </template>
            <v-alert slot="no-results" :value="true" color="error" icon="warning">
              Your search for "{{ search }}" found no results.
            </v-alert>
          </v-data-table>
        </v-card>
      </v-tab-item>
    </v-tabs>
    <!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^66. -->

    <!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^66. -->

    <!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^66. -->
 <v-tabs v-model="active" color="gray" dark slider-color="yellow" v-if="TrueOrFalse==true">
      <v-tab v-for="choose in spOrVdCard" :key="choose" ripple @click="contentPageChange(choose)"> {{ choose }} 조회</v-tab>
      <v-tab-item v-for="choose in spOrVdCard" :key="choose">
        <v-card flat v-if="choose=='자판기'">
          <div>
            <v-btn dark @click="insertSupple = true, spIn = true">보충기사 등록</v-btn>
            <v-btn dark @click="spUpRe(true, false)">보충기사 수정</v-btn>
            <v-btn dark @click="spUpRe(false, true)">보충기사 삭제</v-btn>
            <v-dialog v-model="insertSupple" persistent max-width="500px">
              <v-card>
                <v-card-title>
                  <span v-if="spIn == true" class="headline">보충기사 등록</span>
                  <span v-else class="headline">보충기사 수정</span>
                </v-card-title>
                <v-card-text>
                  <img id="blah" src="http://placehold.it/180" alt="your image" style="width: 180px; height: 180px;">
                  <input type="file" accept=".png" id="inputFile" @change="readURL('inputFile')">
                </v-card-text>
                <v-card-text v-if="spIn == true">
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12>
                        <v-text-field label="Input your name" required id="spInputName"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your Id" required id="spInputId"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your password" required id="spInputPasswd"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your mail" required id="spInputMail"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your phone" required id="spInputPhone"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your address" required id="spInputAddress"></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-text v-else>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12>
                        <v-text-field label="Input your name" required id="spInputNameChange" :value="spInputNameChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your Id" required id="spInputIdChange" :value="spInputIdChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your password" required id="spInputPasswdChange" :value="spInputPasswdChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your mail" required id="spInputMailChange" :value="spInputMailChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your phone" required id="spInputPhoneChange" :value="spInputPhoneChange"></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-text-field label="Input your address" required id="spInputAddressChange" :value="spInputAddressChange"></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-actions v-if="spIn == true">
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click="spInFunc">등록</v-btn>
                  <v-btn color="blue darken-1" flat @click.native="insertSupple = false, spIn = false">취소</v-btn>
                </v-card-actions>
                <v-card-actions v-else>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click="spUpFunc">수정</v-btn>
                  <v-btn color="blue darken-1" flat @click.native="insertSupple = false, spUp = false">취소</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <div class="fixed-table-container">
              <div class="header-bg"></div>
              <div class="table-wrapper">
                <table>
                  <thead> 
                    <th width="8%"> <!-- %넓이 값을 지정을 위해 div를 포함시키기. -->
                      <div class="th-text">번호</div>
                    </th>
                    <th width="25%">
                      <div class="th-text">사진</div>
                    </th>
                    <th width="17%">
                      <div class="th-text">이름</div>
                    </th>
                  </thead>
                  <tbody>
                    <tr v-for="sp in spArray" :key="sp.No" @click="spInfor(sp.Id, sp.sp_id)" :id="sp.sp_id">
                      <td>{{sp.No}}</td>
                      <td><img :src="sp.Pic" id="spImage"></td>
                      <td>{{sp.Name}}</td>
                    </tr>
                  </tbody>
                </table>      
              </div>
            </div>
          </div>
        </v-card>
        <v-card flat v-else-if="choose=='보충기사'">
          <v-card>
            <!-- ********************** 자판기 등록창********************** -->
            <v-layout row justify-center>
              <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition" :overlay="false">
                <v-btn slot="activator" @click="DrinkList">자판기 등록</v-btn>
                <v-card  color="light-blue darken-1">
                  <v-toolbar >
                    <v-btn icon @click.native="dialog = false" >
                      <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn color="white" flat @click.native="close">Cancel</v-btn>
                      <v-btn color="white" flat @click.native="save">Save</v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <br>
                <v-card>
                <v-card-title>
                  <span class="headline">자판기 기본정보</span>
                </v-card-title>
                <v-card-text>
                  <!-- ********************** 자판기 기본 등록 및 수정 내부 창 ********************** -->
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs2 order-md1 order-xs3>
                        <v-text-field color="light-blue darken-4"  name="input-3-4" label="자판기 이름"  class="input-group--focused"  :rules="[() => editedItem.Ven_Name.length > 0 || 'Vendingmachine name is required']"  required v-model="editedItem.Ven_Name"></v-text-field>
                      </v-flex>
                      <v-flex xs2 order-md1 order-xs4>
                        <v-text-field color="light-blue darken-4 " name="input-3-4" label="위치"  class="input-group--focused"  :rules="[() => editedItem.Location.length > 0 || 'ex)Buk-gu, Daegu, Republic of Korea']"  required   v-model="editedItem.Location"></v-text-field>
                      </v-flex> 
                      <v-flex xs2 order-md1 order-xs3>
                        <v-select
                          :items="select"
                          label="관리자명"
                          item-value="text"
                          v-model ="editedItem.Manager">
                        </v-select>
                      </v-flex>
                      <v-flex xs3 order-md1  order-xs6>
                        <label>
                          <gmap-autocomplete @place_changed="setPlace"></gmap-autocomplete>
                          <button @click="addMarker">Add</button>
                        </label>
                      </v-flex>
                      <v-flex xs2 order-md1  order-xs5 >
                        <gmap-map :center="center" :zoom="zoom" style="width:350px;  height: 250px;">
                          <gmap-marker
                            :key="index"
                            v-for="(m, index) in markers"
                            :position="m.position"
                            :markers="m.markers"
                            @click="center=m.position"
                            :draggable="m.draggable">
                          </gmap-marker>
                        </gmap-map>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-actions></v-card-actions>
                </v-card>
                <!-- ********************** 자판기 음료 등록 및 수정 ********************** -->
                <v-card>
                  <v-card-title><span class="headline">{{ formSubTitle }}</span></v-card-title>
                  <v-container fluid grid-list-md>
                    <v-layout row wrap>
                      <v-flex xs1>
                        <v-subheader>음료라인 수</v-subheader>
                      </v-flex>
                      <v-flex xs1>
                        <b-form-select v-model="selected" :options="options" class="mb-1"  />
                      </v-flex>
                    </v-layout>
                    <v-layout row wrap v-if="editedIndex!=-1">
                      <v-flex xs1>
                        <!-- Edit일 경우 뜨는 현재 음료 리스트  -->
                        <v-subheader>현재 음료 리스트</v-subheader>
                      </v-flex>
                      <v-layout  xs2 md2 lg2 v-for="(drinkItem, index) in editedItem_drink" :key="index">
                        <v-flex  v-for="(item2, index)  in drinkItem" :key="index">
                          <v-card-title>{{index}}</v-card-title>
                          <v-card-media   height="70px">   
                            <v-card><img xs3 md3 lg3 :src="item2" style=" height:70px; width:30px;"></v-card>
                            <v-spacer></v-spacer>
                          </v-card-media>  
                        </v-flex> 
                      </v-layout>
                    </v-layout>
                    <br />
                    <v-layout row wrap>
                      <v-flex xs1>
                        <v-subheader>음료 설정</v-subheader>
                      </v-flex>
                      <!-- Set Drink List Card -->
                      <v-flex xs5 md5 lg5>
                        <v-layout row wrap>
                          <v-flex xs3 md3 lg3 v-for="(value, index) in InputDrinkItem" :key="index">
                            <v-card width="150px" height="200px">
                              <draggable :id="index" v-model="itemList_All" :options="{group:'itemList_All'}" @start="drag=true" 
                                @end="drag=false" @add="newLine">
                                <v-card-title class="headline"> 
                                  {{ capLetter(index) }}
                                </v-card-title>
                                <v-card v-for="(item2, index)  in value" :key="index">
                                  <v-card-media  height="70px">  
                                    <v-spacer></v-spacer>  
                                    <img :src="item2" style=" height:70px; width:30px;" >
                                    <v-spacer></v-spacer>
                                  </v-card-media>
                                </v-card> 
                              </draggable>
                            </v-card>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                      <v-flex xs1></v-flex>
                        <!-- All Drink list Card -->
                        <v-flex xs5>  총 음료 리스트 (원하는 음료를 드래그해주세요)
                          <v-layout row wrap>
                            <v-flex xs2 md2 lg2 v-for="(drinkItem, index) in itemList_All" :key="index">
                              <draggable :id="index" v-model="itemList_All" :options="{group: { name:'itemList_All', pull:'clone', put:'false'}}" @start="drag=true" 
                                @end="drag=false" :move="chooseItem">
                                <v-card class="ma-2 pa-1">
                                  <v-card-media  height="70px">
                                    <v-spacer></v-spacer>
                                    <img :src="drinkItem.path" style=" height:70px; width:30px;">
                                    <v-spacer></v-spacer>
                                  </v-card-media>
                                </v-card>
                              </draggable>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card>   
                </v-card>
              </v-dialog>
            </v-layout>
          </v-card>
          <!-- vending machine List -->
          <v-card-title>
            <v-text-field
              append-icon="search"
              label="Please enter search keywords"
              single-line
              hide-details
              v-model="search">
            </v-text-field>
          </v-card-title>
          <v-data-table :headers="headers" :items="items" :search="search">
            <template slot="items" slot-scope="props">
              <tr :id="props.item.No" @mouseover="mouseover(props.item.No)" @mouseout="mouseout(props.item.No)" @click="trClick(props.item.No)">
                <td class="text-xs-right" @click="routerLinkToDetails_s(props.item.No,props.item.Ven_Name,props.item.Manager);routerLinkToDetails_c(props.item.No);">{{ props.item.No }}</td>
                <td class="text-xs-right" @click="routerLinkToDetails_s(props.item.No,props.item.Ven_Name,props.item.Manager);routerLinkToDetails_c(props.item.No);">{{ props.item.Ven_Name }}</td>
                <td class="text-xs-right" @click="routerLinkToDetails_s(props.item.No,props.item.Ven_Name,props.item.Manager); routerLinkToDetails_c(props.item.No);">{{ props.item.Location }}</td>
                <td class="text-xs-right" @click="routerLinkToDetails_s(props.item.No,props.item.Ven_Name,props.item.Manager); routerLinkToDetails_c(props.item.No);">{{ props.item.Manager }}</td>
                <td class="justify-center layout px-0">
                  <v-btn icon class="mx-0" @click="editItem(props.item)">
                    <v-icon color="teal">edit</v-icon>
                  </v-btn>
                  <v-btn icon class="mx-0" @click="deleteItem(props.item)">
                    <v-icon color="pink">delete</v-icon>
                  </v-btn>
                </td>
              </tr>
            </template>
            <template slot="no-data">
              <v-btn color="primary" @click="vendingList">Reset</v-btn>
            </template>
            <v-alert slot="no-results" :value="true" color="error" icon="warning">
              Your search for "{{ search }}" found no results.
            </v-alert>
          </v-data-table>
        </v-card>
      </v-tab-item>
    </v-tabs>
  </div>
</template>


<script>
import { EventBus } from '../../app.js';
import draggable from 'vuedraggable';

//  -----자판기 조회-----
let response;
let obj = []; 
let obj_c=[]; 
let array = [];
let array_c =[];
let array_a =[];
let obj_A=[];
let selectLine;
let selcetDrink;
let selectDrinkId;

let click_lat;
let click_lng;
//  -----자판기 조회-----

export default {
  data() {
    return {
      //보충기사와 자판기 조회 버튼을 위한 변수
      spOrVdCard : { 0 : '보충기사', 1 : '자판기'},
      active: null,

      //보충 기사 정보를 담기 위한 배열
      spArray: [],
      
      selected:[],
      itemList_All:[], /* 음료리스트 담는 배열 */
      search: '',
      dialog: false,
      //<------------ 자판기 리스트 테이블 헤더부분 담기위한 배열 -------------->
      headers: [
          {
            text: 'No',
            align: 'left',
            sortable: true,
            value: 'No'
          },
          { text: '자판기 이름',sortable: true, value: 'Ven_Name' },
          { text: '위치', value: 'Location' },
          { text: '관리자',sortable: true, value: 'Manager' }
        ],
       options: [  { value: null, text: '8개' }],
      vendingName:"",
      items:[],
      select: [  /* 모달창 선택지 */
        ],
      editedIndex: -1,
      editedItem: {
        No: '',
        Ven_Name: '',
        Location: '',
        Manager: ''
      },
      defaultItem: {
        No: '',
        Ven_Name: '',
        Location: '',
        Manager: ''
      },
    
      editedItem_drink: {  /* 수정시 기존 라인당 음료 리스트  */
        Line1 : null,
        Line2 : null,
        Line3 : null,
        Line4 : null,
        Line5 : null,
        Line6 : null,
        Line7 : null,
        Line8 : null
      },
      defaultItem_drink: {
        Line1 : null,
        Line2 : null,
        Line3 : null,
        Line4 : null,
        Line5 : null,
        Line6 : null,
        Line7 : null,
        Line8 : null
      },


      clickedTr:'',
      clickTr:'',
      tempMarker:{lat:'',lng:''},
      /* <--------------------- VendingMachine Google map --------------------> */
      center: {
        lat: 36.323836,
        lng: 127.398077 
      }, 
      markers: [],
      marker_a : null,
      places: [],
      currentPlace: null,
      zoom:parseFloat(6.8),
      targetItem:'',
      
      draggable:true,

      /* empty drink itme list */
      InputDrinkItem: {
                    Line1: [],
                    Line2: [],
                    Line3: [],
                    Line4: [],
                    Line5: [],
                    Line6: [],
                    Line7: [],
                    Line8: []
       },
      selctTempItem:[],
      /* send drink item list */
      drink_Arr:{
        Line1 : null,
        Line2 : null,
        Line3 : null,
        Line4 : null,
        Line5 : null,
        Line6 : null,
        Line7 : null,
        Line8 : null,
      },
      TrueOrFalse :false,
      insertSupple: false,
      clickedSpTrId: "",
      spIn: false,
      spUp: false,
      spRe: false,
      spInputNameChange: "",
      spInputIdChange: "",
      spInputPasswdChange: "",
      spInputMailChange: "",
      spInputPhoneChange: "",
      spInputAddressChange: "",
      originPhoneNum: ""
    }
  },
  components: {
    draggable
  },
  mounted(){
    var link = document.location.href;
    var queryID=this.$route.query.id;
    if(link=="management?id="+queryID){
      this.specific();
    };
    // 보충 기사 정보를 화면에 바로 보이게 하기 위해 mouted에서 메서드 바로 호출
    this.spList();
    this.geolocate();
    this.DrinkList();
  }, 
  //<------------------ search filtering ----------------------->
  computed : {
      formTitle () {
        return this.editedIndex === -1 ? '새 자판기 등록' : '자판기 정보 수정'
      },
      formSubTitle(){ 
        return this.editedIndex === -1 ? '자판기 음료 등록' : '자판기 음료 수정'
      }
  },
  watch: {
      dialog (val) {
        val || this.close()
      }
    },
  methods: {
    readURL(input) {
      var fileTag = document.getElementById(input);

      if (fileTag.files && fileTag.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };

        reader.readAsDataURL(fileTag.files[0]);
      }
    },
    spInFunc() {
        var saveSpName = document.getElementById("spInputName").value;
        var saveSpId = document.getElementById("spInputId").value;
        var saveSpPasswd = document.getElementById("spInputPasswd").value;
        var saveSpMail = document.getElementById("spInputMail").value;
        var saveSpPhone = document.getElementById("spInputPhone").value;
        var saveSpAddress = document.getElementById("spInputAddress").value;
        var saveImage = document.getElementById("inputFile").value;

        if (saveSpName == "") {
          alert("이름을 입력해주십시오.");
        }
        else if (saveSpId == "") {
          alert("아이디를 입력해주십시오.");
        }
        else if (saveSpPasswd == "") {
          alert("비밀번호를 입력해주십시오.");
        }
        else if (saveSpMail == "") {
          alert("메일을 입력해주십시오.");
        }
        else if (saveSpPhone == "") {
          alert("휴대전화번호를 입력해주십시오.");
        }
        else if (saveSpAddress == "") {
          alert("주소를 입력해주십시오.");
        }
        else if (saveImage == "") {
          alert("이미지를 넣어주세요");
        }
        else {
          const fileInput = document.getElementById("inputFile");
          const formData = new FormData();
          formData.append('sp_login_id', saveSpId);
          formData.append('sp_password', saveSpPasswd);
          formData.append('sp_name', saveSpName);
          formData.append('sp_mail', saveSpMail);
          formData.append('sp_phone', saveSpPhone);
          formData.append('sp_address', saveSpAddress);
          formData.append('sp_address', saveSpAddress);
          formData.append('imageFile', fileInput.files[0]);

          this.axios.post("management/addSP", formData)
          .then((response) => {
            if(response.data == "good") {
              alert("등록되었습니다.");
              this.insertSupple = false;
              this.spIn = false;
              location.reload();
            }
            else {
              alert("등록에 실패하였습니다.");
            }
          })
          .then((error) => {
            console.log(error);
          })
        }
    },
    spUpFunc() {
      this.spInputNameChange = document.getElementById('spInputNameChange').value;
      this.spInputIdChange = document.getElementById('spInputIdChange').value;
      this.spInputPasswdChange = document.getElementById('spInputPasswdChange').value;
      this.spInputMailChange = document.getElementById('spInputMailChange').value;
      this.spInputPhoneChange = document.getElementById('spInputPhoneChange').value;
      this.spInputAddressChange = document.getElementById('spInputAddressChange').value;


      if (this.spInputNameChange == "") {
        alert("이름을 입력해주십시오.");
      }
      else if (this.spInputIdChange == "") {
        alert("아이디를 입력해주십시오.");
      }
      else if (this.spInputPasswdChange == "") {
        alert("비밀번호를 입력해주십시오.");
      }
      else if (this.spInputMailChange == "") {
        alert("메일을 입력해주십시오.");
      }
      else if (this.spInputPhoneChange == "") {
        alert("휴대전화번호를 입력해주십시오.");
      }
      else if (this.spInputAddressChange == "") {
        alert("주소를 입력해주십시오.");
      }
      else {
        const formData = new FormData();
        formData.append('sp_id', this.clickedSpTrId);
        formData.append('sp_login_id', this.spInputIdChange);
        formData.append('sp_password', this.spInputPasswdChange);
        formData.append('sp_name', this.spInputNameChange);
        formData.append('sp_mail', this.spInputMailChange);
        formData.append('sp_phone', this.spInputPhoneChange);
        formData.append('sp_address', this.spInputAddressChange);

        if (document.getElementById("inputFile").value != "") {
          const fileInput = document.getElementById("inputFile");
          formData.append('imageFile', fileInput.files[0]);
          formData.append('is_file', "ok");
        }
        else {
          formData.append('is_file', "no");
        }

        this.axios.post("management/updateSP", formData)
        .then((response) => {
          if(response.data == "good") {
            alert("수정되었습니다.");
            this.insertSupple = false;
            this.spUp = false;
            location.reload();
          }
          else {
            alert("수정에 실패하였습니다.");
          }
        })
        .catch((error) => {
          console.log(error);
        })
      }
    },
    spUpRe(up, re) {
      this.spUp = up;
      this.spRe = re;

      if (this.spUp == true) {
        if (this.clickedSpTrId == "") {
          alert("수정할 보충기사를 선택하십시오.");
          this.spUp = false;
        }
        else{
          this.insertSupple = true;

          let url = 'management/getSP';
          this.axios.get(url)
          .then((response) => {
            for (var i = 0; i < response.data.length; i++) {
              if (this.clickedSpTrId == response.data[i].sp_id) {
                
                var showImage = document.getElementById("blah");

                this.spInputNameChange = response.data[i].sp_name;
                this.spInputIdChange = response.data[i].sp_login_id;
                this.spInputPasswdChange = response.data[i].sp_password;
                this.spInputMailChange = response.data[i].sp_mail;
                this.spInputPhoneChange = response.data[i].sp_phone;
                this.originPhoneNum = response.data[i].sp_phone;
                this.spInputAddressChange = response.data[i].sp_address;
                showImage.src = response.data[i].sp_img_path;

                break;
              }
            }
          })
          .catch((error) => {

          })
        }
      }
      else {
        if (this.clickedSpTrId == "") {
          alert("삭제할 보충기사를 선택하십시오.");
          this.spRe = false;
        }
        else{
          var removeCheck = confirm("해당 보충기사를 정말로 삭제하시겠습니까?");

          if (removeCheck == true) {
            let spRemoveUrl = 'management/deleteSP/' + this.clickedSpTrId;
            this.axios.get(spRemoveUrl)
            .then((response) => {
              if (response.data == "good") {
                alert("삭제되었습니다.");
                this.spRe = false;
                location.reload();
              }
              else {
                alert("담당하고 있는 자판기가 아직 있습니다.");
              }
            })
            .catch((error) => {

            })
          }
        }
      }
    },

    //서버 DB에서 보충기사 정보를 가지고 온다.
    spList(){
      this.axios.get("realtime/list/all/all").then((response) =>{
        response = response.data;
        for(let key in response){
          array_c[key] = {
          name : response[key].vd_name}
          obj_c.push({
            name:array_c[key].name})
        }
        });

      let url = 'management/getSP';
      this.axios.get(url).then((response) =>{
        //보충 기사 3명의 정보를 배열에 담는다.
        for(let i = 0 ; i < response.data.length; i++){

          var spObj = {
            No: i + 1,
            Pic: response.data[i]['sp_img_path'],
            Name: response.data[i]['sp_name'],
            Id: response.data[i]['sp_login_id'],
            sp_id: response.data[i]['sp_id']
          };

          this.spArray.push(spObj);
          this.select.push({
            text:response.data[i]['sp_login_id']});
          
          //console.log(this.select);
        }
      });
      //보충기사 관리를 누를 시, 이벤트를 발생시켜 상위 컴포넌트인 백그라운드로 가서 메서드 실행.
      //this.$emit('vending-list');
    },
    
    vendingList :function(){
      this.axios.get("realtime/list/all/all").then((response) =>{
        response = response.data;
        if(obj==[]){ 
           for(let key in response){
         array[key] = {
            id : response[key].vd_id,
            name : response[key].vd_name,
            address : response[key].vd_address,
            supplementer : response[key].vd_supplementer, 
          }
          obj.push({
            No:array[key].id,
            isActive: true,
            Ven_Name:array[key].name,
            Location:array[key].address,
            Manager:array[key].supplementer
          }); 
           EventBus.$emit('vending_name', response); 
        }}
        else{
           obj.splice(0,);
           obj=[];
        for(let key in response){
         array[key] = {
            id : response[key].vd_id,
            name : response[key].vd_name,
            address : response[key].vd_address,
            supplementer : response[key].vd_supplementer, 
          }
          obj.push({
            No:array[key].id,
            isActive: true,
            Ven_Name:array[key].name,
            Location:array[key].address,
            Manager:array[key].supplementer
          }); 
        }
        }       
        this.items = obj;
         EventBus.$emit('vending_name', response); 
        this.$emit('vending-list');                     
      })
    },

    spInfor(spId, realSp_id){
      if (this.clickedSpTrId == "") {
        var clickSpTr = document.getElementById(realSp_id);
        clickSpTr.style.backgroundColor = "skyblue";
        this.clickedSpTrId = realSp_id;
      }
      else {
        document.getElementById(this.clickedSpTrId).style.backgroundColor = "white";
        var clickSpTr = document.getElementById(realSp_id);
        clickSpTr.style.backgroundColor = "skyblue";
        this.clickedSpTrId = realSp_id;
      }
      

      EventBus.$emit('jobOrder', spId); 
    },

    contentPageChange(page){
      EventBus.$emit('contentPageChange', page); 
      this.vendingList();
    },

       // <------------ Stock EventBus --------------> 
     routerLinkToDetails_s(items,arg1,arg2) {

       EventBus.$emit('vendingInfo',arg1,arg2,items); 
        this.axios.get("realtime/getVdStock/"+items)
        .then(function (response) {
          
             response=response.data;
             EventBus.$emit('StockEventBus',response); 
        })  
    },
         // <------------ Coin EventBus --------------> 
     routerLinkToDetails_c(items) {
        this.axios.get("realtime/coinStock/"+items)
        .then(function (response) {
             response=response.data;
             EventBus.$emit('CoinEventBus',response); 
             
        })  
    },

/* <---------------- editItem Methods --------------> */  
      editItem (item) {
        this.editedIndex = this.items.indexOf(item)
        this.editedItem = Object.assign({}, item)
        
        //기존 자판기의 음료 목록 불러오기 
        this.axios.get("management/getVdStock/"+this.editedItem.No).then((response) =>{
        response = response.data;
        //console.log(response);
        var obj_drink=[];
        for(let key in response){
          array[key] = {
          drinkPath : response[key].drink_img_path
          }
        } 
        obj_drink.push({
            Line1:array[0].drinkPath,
            Line2:array[1].drinkPath,
            Line3:array[2].drinkPath,
            Line4:array[3].drinkPath,
            Line5:array[4].drinkPath,
            Line6:array[5].drinkPath,
            Line7:array[6].drinkPath,
            Line8:array[7].drinkPath
            }) ,


             this.editedItem_drink= Object.assign({}, obj_drink)
        });
        
      

        this.dialog = true      
        
      },
     /* <---------------- deleteItem Methods --------------> */    
      deleteItem (item) {
        const index = this.items.indexOf(item)
        confirm('정말로 자판기 삭제하시겠습니까?') && this.items.splice(index, 1)
        /* <------------ delete db값 변경 -----------> */
        const formData_d = new FormData();
        formData_d.append('ven_id',item.No);
        this.axios.post("management/deleteVD",formData_d).then((Response)=>{
        })  
      },
     /* <---------------- close Methods --------------> */      
       close () {
        this.dialog = false
        this.markers.splice(0,),
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          //this.InputDrinkItem = Object.assign({}, this.defaultItem_drink)
          this.editedIndex = -1
        }, 300)       
        
      },
      /* <---------------- save Methods --------------> */     
      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.items[this.editedIndex], this.editedItem)
          /* <------------update db값 변경 -----------> */
         const formData_i = new FormData();
        formData_i.append('ven_name',this.editedItem.Ven_Name);
        formData_i.append('ven_no',this.editedItem.No);
        formData_i.append('ven_Location',this.editedItem.Location);
        formData_i.append('ven_Manager',this.editedItem.Manager);
        let drink_Arr = this.drink_Arr;
        formData_i.append('drink_line', JSON.stringify(drink_Arr));
       
         this.axios.post("management/updateVD", formData_i).then((Response)=>{
            //console.log(Response.data);
        }).catch(ex=>{
          console.log(ex);
        })  
        } else {
          this.items.push(this.editedItem)
            /* <------------insert db값 변경 -----------> */
         const formData_u = new FormData();
        formData_u.append('ven_name',this.editedItem.Ven_Name);
        formData_u.append('ven_Location',this.editedItem.Location);
        formData_u.append('ven_Manager',this.editedItem.Manager);
        let drink_Arr = this.drink_Arr;
        formData_u.append('drink_line', JSON.stringify(drink_Arr));
        formData_u.append('clickLat',click_lat);
        formData_u.append('clickLng',click_lng);
        
       
         this.axios.post("management/registrationVD", formData_u).then((Response)=>{
          //console.log(Response.data);
        }).catch(ex=>{
          console.log(ex);
        }) 
        } 
                                          
        this.close()
      },
      /* <---------------- Mouse Over Methods --------------> */
      mouseover: function(overTr) {
                if (document.getElementById(overTr)!= this.clickedTr) {
                    document.getElementById(overTr).style.backgroundColor = 'rgba(88, 88, 88, 0.4)';
                }
            },
       /* <---------------- Mouse Out Methods  --------------> */
      mouseout: function(outTr) {
                if (document.getElementById(outTr) != this.clickedTr) {
                    
                    document.getElementById(outTr).style.backgroundColor = '#f6f9fa';
                }
            },
      /* <----------------  trClick Methods  -----------------> */
      trClick: function(trName) {
                this.clickTr = document.getElementById(trName);
                if (this.clickTr.style.backgroundColor == "#5f95a7") {
                    this.clickTr.style.backgroundColor = "#f6f9fa";
                }
                else {
                    if (this.clickedTr != "") {
                        this.clickedTr.style.backgroundColor = "#f6f9fa";
                    }  
                    this.clickedTr = this.clickTr;
                    this.clickTr.style.backgroundColor = "#5f95a7";
                }
    },

    /* <------------------- google map Methods ----------------------> */  
    setPlace(place) {
      this.currentPlace = place;
    },
    addMarker() {
      if (this.currentPlace) {
        const marker = {
          lat: this.currentPlace.geometry.location.lat(),
          lng: this.currentPlace.geometry.location.lng()
        };
       this.markers.push({ position: marker });
        
        var obj_marker=[];

        obj_marker.push({position: marker});
        
         this.zoom = parseInt(15); 
        click_lat=obj_marker[0].position.lat; //click한 좌표의 위도
        click_lng=obj_marker[0].position.lng; //click한 좌표의 경도
        
        this.tempMarker= obj_marker
        this.places.push(this.currentPlace);
        this.center = marker;
        this.currentPlace = null;
      }
    },
    /* <----------------- Default 좌표값 메서드 --------------> */
    geolocate: function(position) {
      navigator.geolocation.getCurrentPosition(position => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
      });
    },
    /* <---------------- 수정 버튼 클릭 시 음료 리스트 -----------------> */
    DrinkList:function(){

        this.axios.get("management/getProductInfo").then((response) =>{
        response = response.data;
        if(obj_A==[]){
        for(let key in response){
          array[key] = {
          id : response[key].drink_id,  
          name : response[key].drink_name,
          path: response[key].drink_img_path}
          obj_A.push({
           id:array[key].id,
           name:array[key].name,
           path:array[key].path
           
           })
        }}else{
           obj_A.splice(0,);
           for(let key in response){
          array[key] = {
          id : response[key].drink_id,  
          name : response[key].drink_name,
          path: response[key].drink_img_path}
          obj_A.push({
           id:array[key].id,
           name:array[key].name,
           path:array[key].path
           
           })
        }
        }
         this.itemList_All = obj_A;
        });
    },


//<--------------- Give an id on select One Drink method ------------------>
    chooseItem: function (event) {                   
                   selcetDrink = event.from.id;
                   var id =  event.from.id;
                   var number = parseInt(id);
                   selectDrinkId= number;

                   this.selctTempItem=this.itemList_All[number].path;
    },
    capLetter: function (input) {
                return input.charAt(0).toUpperCase() + input.slice(1);
    },
    //<--------------- Give an id on the left empty line method ------------------>
    newLine: function (event) {
                selectLine= event.to.id;
                this.lineInDrink();
    },
     //<---------------------- Setting Line and Drink method ---------------------->
    lineInDrink:function(){
      if(this.InputDrinkItem[selectLine]==''){
      this.InputDrinkItem[selectLine].push(this.selctTempItem);                          
      }
      else if(this.InputDrinkItem[selectLine]!=''){
        this.InputDrinkItem[selectLine].splice(0,1);
        this.InputDrinkItem[selectLine].push(this.selctTempItem);  
      }     
      //this.drink_Arr.splice(0,);
      this.drink_Arr[selectLine] = selectDrinkId;
      this.itemList_All.splice(0,);
      this.DrinkList();
   },
   //<------------------ specific page (realtime->management) ----------------->
    specific(){
      var id = this.$route.query.id;
      
      this.spOrVdCard = { 0 : '보충기사', 1 : '자판기'}, 
      this.TrueOrFalse = true;
      console.log(this.TrueOrFalse );
      this.contentPageChange("자판기");

      /*  해당 자판기 음료 재고  */
      this.axios.get("realtime/getVdStock/"+id)
        .then(function (response) {
          
             response=response.data;
             EventBus.$emit('StockEventBus',response); 
        }) 

      /*  해당 자판기 동전 잔고  */  
      this.axios.get("realtime/coinStock/"+id)
        .then(function (response) {
             response=response.data;
             EventBus.$emit('CoinEventBus',response); 
             
        })    
   }
  }
}
</script>

<style>
 #spImage{
   width: 50px;
   height: 65px;
 }

 .scroll-area {
  position: relative;
  margin: auto;
  width: 300px;
  height: 500px;
}.fixed-table-container {
        width: 400px;
        height: 600px;
        border: 1px solid #000;
        position: relative;
        padding-top: 30px; /* header-bg height값 */
    }
    .header-bg {
        background: skyblue;
        height: 30px; /* header-bg height값 */
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        border-bottom: 1px solid #000;
    }
    .table-wrapper {
        overflow-x: hidden;
        overflow-y: auto;
        height: 100%;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    td {
        border-bottom: 1px solid #ccc;
        padding: 5px;
    }
    td + td {
        border-left: 1px solid #ccc;
    }
    th {
        padding: 0px; /* reset */
    }
    .th-text {
        position: absolute;
        top: 0;
        width: inherit;
        line-height: 30px; /* header-bg height값 */
        border-left: 1px solid #000;
    }
    th:first-child .th-text {
        border-left: none;
    }
</style>