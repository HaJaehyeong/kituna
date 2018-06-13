<?php
//Route::post('/postLoginUser', 'testController@postt');
Route::get('/', function () {    return view('welcome'); });
Route::get('/login', function () {    return view('welcome'); });
Route::get('/main', function () {    return view('welcome'); });
Route::get('/realtime', function ()   {    return view('welcome'); });
Route::get('/analysis', function ()   {    return view('welcome'); });
Route::get('/management', function () {    return view('welcome'); });
Route::get('/product', function () {    return view('welcome'); });


// <----------- analysis ----------->
Route::get('analysis/analysisData/{place}', 'analysisController@analysisData'); //실시간 데이터 뽑아내기 월 판매량, 일 판매량, 일 매출액
Route::get('analysis/ListOfDrinkSales/{place}/{date}/{sort}/{offset}', 'analysisController@ListOfDrinkSales'); // 매출순 자판기 테이블 뽑아내기
Route::get('analysis/vendingmachineAnalysis/{vd_id}', 'analysisController@vendingmachineAnalysis'); // 매출순에서 클릭된 자판기 분석정보
Route::get('analysis/drinkSalesRank/{date}', 'analysisController@drinkSalesRank'); // 음료 판매 순위
Route::get('analysis/yearMonthWeekData/{place}', 'analysisController@yearMonthWeekData'); // year, month, week 라인차트 데이터
Route::get('analysis/pieGraphData', 'analysisController@pieGraphData'); // pie 차트 데이터
Route::get('analysis/setLineChangeNote/{vd_id}/{existingDrink}/{changeDrink}', 'analysisController@setLineChangeNote');  // 자판기의 음료 변경 지시한 것을 작업지시서에 추가 
Route::get('analysis/differenceVdAnalysis/{vd_id}/{date}', 'analysisController@differenceVdAnalysis'); // 관심 자판기 데이터 
Route::get('analysis/setLineChangeVerTwo/{vd_id}/{existingDrink}/{changeDrink}', 'analysisController@setLineChangeVerTwo');

// <----------- realtime ----------->
Route::get('realtime/list/{local}/{subLocal}', 'realtimeController@vdList');
Route::get('realtime/listSP/{spName}', 'realtimeController@vdListSP'); /* realtime 보충기사별 */
Route::get('realtime/getVdStock/{vd_id}', 'realtimeController@getVdStock');
Route::get('realtime/coinStock/{vd_id}', 'realtimeController@coinStock');
Route::get('realtime/list/{local}/{subLocal}', 'realtimeController@vdList');
Route::get('realtime/getSellDataList', 'realtimeController@getSellDataList'); /* 판매내역 */
Route::get('realtime/getNumOfVd', 'realtimeController@getNumOfVd'); /* 지역별 자판기 총 개수 및 매진임박 개수 */
Route::get('realtime/clickAButton/{vd_soldout}', 'realtimeController@clickAButton'); /* 구글 마커 버튼 */
Route::get('realtime/sendedDataNum/{line_num}', 'realtimeController@sendedDataNum');
Route::get('realtime/sendDataFromVdVersionTwo/{vd_id}/{line}', 'realtimeController@sendDataFromVdVersionTwo');


// <----------- management ----------->
Route::get('management/getSP', 'managementController@getSP'); // 보충기사 리스트 요청
Route::post('management/addSP', 'managementController@addSP');  // 보충기사 등록
Route::post('management/updateSP', 'managementController@updateSP');     // 보충기사 수정
Route::get('management/deleteSP/{sp_id}', 'managementController@deleteSP');     // 보충기사 삭제

Route::get('management/getVdInfo/{sp_name}', 'managementController@getVdInfo');
Route::get('management/jobOrder/{spName}/{order_date}', 'managementController@getJobOrder');

Route::post('management/registrationVD', 'managementController@registrationVD');/* insert DB */
Route::get('management/getVD/{vd_id}', 'managementController@getVD');
Route::post('management/updateVD', 'managementController@updateVD'); /* update DB */
Route::post('management/deleteVD', 'managementController@deleteVD');
Route::get('management/createJobOrder/{sp_id}', 'managementController@createJobOrder');
Route::get('management/getVdStock/{vd_id}', 'managementController@getVdStock');
Route::get('management/coinStock/{vd_id}', 'managementController@coinStock');
Route::get('management/getProductInfo', 'managementController@getProductInfo');
Route::post('management/addJobOrder', 'managementController@addJobOrder');/* 작업지시서 비고내용 추가 */
Route::get('management/getJoInfo/{jo_id}', 'managementController@getJoInfo');   // 작업지시서 목록 가져오기
Route::post('management/addJobOrderVerTwo', 'managementController@addJobOrderVerTwo');

// <----------- product ----------->
Route::get('product/getCompanyData', 'productController@getCompanyData');                               // 회사 정보
Route::get('product/getProductData', 'productController@getProductData');                               // 제품 정보
Route::post('product/productImgSave','productController@imgStore');                                     // 제품 등록
Route::post('product/updateProduct', 'productController@updateProduct');                                // 제품 정보 수정
Route::post('product/deleteProduct', 'productController@deleteProduct');                                // 제품 삭제
Route::get('product/getCompanyInfo', 'productController@getCompanyInfo');                               // 주문회사 정보
Route::get('product/getOrderSheet', 'productController@getOrderSheet');                                 // 전체 주문 내역
Route::post('product/insertOrderSheetColumn', 'productController@insertOrderSheetColumn');              // 음료 주문 저장
Route::post('product/getOrderInfo', 'productController@getOrderInfo');                                  //주문 내역 한개 클릭 시 회사 및 제품 정보

Route::get('product/getedStockInCompany/{os_id}', 'productController@getedStockInCompany');             // 주문 확인
// 수령확인 누르면 style이 2 -> 1 되고 stock_management에 재고가 쌓인다.

Route::get('product/deleteStockManagement/{stock_id}', 'productController@deleteStockManagement');      // 재고 삭제
Route::post('product/registerCompanyInfo', 'productController@registerCompanyInfo');                    // 회사 등록
Route::post('product/updateCompanyInfo', 'productController@updateCompanyInfo');                        // 회사정보 수정
Route::get('product/deleteCompanyInfo/{cp_id}', 'productController@deleteCompanyInfo');                 // 회사 삭제
Route::post('product/sendPDF', 'productController@sendPDF');                                            // 회사정보 수정
Route::get('product/getMyCompanyInfo', 'productController@getMyCompanyInfo');                           // 내 회사 정보