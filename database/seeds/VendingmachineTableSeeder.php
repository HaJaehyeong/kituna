<?php

use Illuminate\Database\Seeder;

class VendingmachineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $vd_name = [
            '영진전문대 본관 1층 화장실 앞', '영진전문대 우체국', '영진전문대 유아교육관 1층 문 앞',        '대구 새발자전거 유치원 앞', '영진전문대 도서관', '복현 시영2차아파트 11동 앞', '국민은행 복현동지점', '영진전문대 백호체육관', '대구 북중학교', '대구 1-3 벽산이수1단지아파트',
            '경상고등학교', 'CU 대구복현 원룸점', '배화여자대학교 본관', '송림 회사 안',       '서울청운초등학교 앞', '숙명여자대학교 우체국', '숙명여자대학교 순헌관', '동국 회사 안',	
            '환일고등학교 앞', '동국대학교', '유경 회사 안', '롯데호텔', '덕수궁', '경희궁','대신고등학교 앞', '서울성심병원', '제일 회사 안', '서울시립대학교', '명성병리과의원', '중앙한의원', '삼진 회사 안', '고려대학교 자연계캠퍼스', '성신여자대학교', '성신의원', '길음 회사 안', '창경궁', 
            '서울대학교병원', '삼청공원', '종묘정전', '동대문성곽공원', '월암근린공원', '사직공원', '경북궁','경복고등학교 앞', '청운초등학교 앞', '청운중학교 안', '서울여자간호대학교 앞', '구산중학교 안','온세아파트 단지 내', '미도파 회사 안', '아띠 호텔', '기업 은행', '곡성 회사', '북악산공원',
            '서울아파트', '성균관대학교', '서울혜화초등학교 앞', '경신중학교 옆', '운석 회사 안', '홍연아파트', '준상 회사 앞', '신일 회사 앞', '태화당한의원', '경북대학교 안', '산격초등학교', 
            '대구북구보건소', '칠성고등학교', '영산 회사 안', '홈플러스 밖', '대구은행 2호 본점', '유길 한의원', '영진고등학교 안', '영진전문대학 창조관 1층', '경북대학교 조형관', '경북대학교 대강당',
            '경북대학교 우체국', '경북대학교 문화관', '경북대학교 동물병원', '경북대학교 사회과학대학', 	
            '대현어린이 공원', '대구 실내 체육관', '연암공원', '유통단지내 국민은행', '신기공원', '인터불고 호텔', '헤성병원', '열린아동병원', '감삼 회사 안', '라이온즈 태권도 체육관', '기업은행 달서구점',
            '대구신용산우체국', '이곡장미공원', '보람타운 앞', '성서고등학교 안', '계명대학교 안', '영남중학교 안', '송현공원 안'		
        ];

        $vd_place = [
            'school, university', 'school, university, postOffice', 'school, university', 'kindergarten', 'school, university, library', 'apart', 'bank', 'school, university, gym',
            'school, middleSchool', 'apart', 'school, highSchool', 'convenienceStore', 'school, university', 'company', 'school, elementarySchool', 'school, university', 'school, university', 'company', 'school, highSchool', 'school, university', 'company', 'hotel', 'culturalHeritage', 'culturalHeritage', 'school, highSchool', 'hospital', 'company', 'school, university', 'hospital', 'hospital', 'company', 'school, university', 'school, university', 'hospital', 'company', 'culturalHeritage', 'hospital', 'park', 'culturalHeritage', 'park', 'park', 'park', 'culturalHeritage', 'school, highSchool', 'school, elementarySchool', 'school, middleSchool', 'school, university', 'school, middleSchool', 'apart', 'company', 'hotel', 'bank', 'company', 'park', 'apart', 'school, university', 'school, elementarySchool', 'school, middleSchool', 'company', 'apart', 'company', 'company', 'hospital', 'school, university', 'school, elementarySchool',  
            'hospital', 'school, highSchool', 'company', 'mart', 'bank', 'hospital', 'school, highSchool', 'school, university', 'school, university', 'school, university', 	
            'school, university, postOffice', 'school, university', 'school, university', 	
            'school, university', 'park', 'gym', 'park', 'bank', 'park', 'hotel', 'hospital', 'hospital', 'company', 'gym', 'bank', 'postOffice', 'park', 'apart', 'school, highSchool', 'school, university', 'school, middleSchool', 'park'				
        ];

        $vd_latitude = [
            '35.896810', '35.896954', '35.895619', '35.896179', '35.895114', '35.896437', '35.895752',
            '35.897076', '35.897341', '35.897529', '35.902304', '35.894177', '37.578939', '37.580812', '37.585596', '37.545569', '37.546423', '37.549786', '37.553782', '37.557532', '37.556541', '37.565313', '37.565822', '37.570691', '37.572888', '37.584111', '37.582795', '37.583905', '37.563285', '37.571253', '37.575588', '37.585012', '37.591299', '37.611695', '37.611307', '37.578701', '37.579652', '37.590349', '37.574875', '37.571931', '37.571151', '37.575774', '37.578033', '37.587113', '37.585548', '37.588786', '37.597461', '37.609329', '37.607881', '37.559843', '37.560586', '37.562568', '37.563959', '37.593363', '37.595761', '37.588219', '37.589071', '37.591456', '37.591936', '37.576641', '37.581064', '37.580946', '37.570192', '35.890044', '35.894039', '35.891822', '35.880985', '35.879411', '35.881355', '35.882849', '35.899931', '35.894336', '35.896913', '35.893221', '35.892781', '35.889152', '35.886021', '35.886702', '35.888391', '35.884388', '35.893146', '35.899762', '35.908031', '35.907515', '35.906964', '35.855112', '35.849578', '35.849431', '35.856742', '35.856604', '35.856656',
            '35.858065', '35.850973', '35.859424', '35.855788', '35.819932', '35.832026'	
        ];

        $vd_longitude = [
            '128.620927', '128.623241', '128.620687', '128.619605', '128.622492', '128.619570', '128.616845', '128.622635', '128.620484', '128.623463', '128.622764', '128.624061', '126.967064', '126.968812', '126.969486', '126.965068', '126.964725', '126.957849',	
            '126.962165', '127.000779', '126.995056', '126.980947', '126.975228', '126.968861',	
            '126.961576', '127.049754', '127.053774', '127.059027', '127.053652', '127.048353',	
            '127.047977', '127.026356', '127.022145', '127.023851', '127.027125', '126.995293',	
            '126.999032', '126.985789', '126.993874', '127.009083', '126.965494', '126.967637',	
            '126.976881', '126.971385', '126.969527', '126.970977', '126.947405', '126.908938',	
            '126.911883', '126.996698', '126.997165', '126.998034', '126.998842', '126.977783',	
            '126.989094', '126.993594', '126.999856', '127.001202', '127.002443', '126.931711',	
            '126.927629', '126.927148', '126.915975', '128.611422', '128.606955', '128.588901',	
            '128.590896', '128.594855', '128.595763', '128.589671', '128.615717', '128.622394',	
            '128.623181', '128.612411', '128.610804', '128.614389', '128.609603', '128.613601',	
            '128.615563', '128.610397', '128.603404', '128.600526', '128.609363', '128.608862',	
            '128.611296', '128.502841', '128.535656', '128.533612', '128.541843', '128.537696',	
            '128.530922', '128.514737', '128.520229', '128.516367', '128.496754', '128.537938',	
            '128.556892'
        ];

        $vd_address = [
            'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea',
            'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Yongsan-gu, Seoul, Republic of Korea', 'Yongsan-gu, Seoul, Republic of Korea', 'Mapo-gu, Seoul, Republic of Korea', 'Jung-gu, Seoul, Republic of Korea', 'Jung-gu, Seoul, Republic of Korea', 'Jung-gu, Seoul, Republic of Korea', 'Jung-gu, Seoul, Republic of Korea', 'Jung-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Dongdaemun-gu, Seoul, Republic of Korea', 'Dongdaemun-gu, Seoul, Republic of Korea', 'Dongdaemun-gu, Seoul, Republic of Korea', 'Seongdong-gu, Seoul, Republic of Korea', 'Dongdaemun-gu, Seoul, Republic of Korea', 'Dongdaemun-gu, Seoul, Republic of Korea', 'Seongbuk-gu, Seoul, Republic of Korea', 'Seongbuk-gu, Seoul, Republic of Korea', 'Seongbuk-gu, Seoul, Republic of Korea', 'Seongbuk-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Seodaemun-gu, Seoul, Republic of Korea', 'Eunpyeong-gu, Seoul, Republic of Korea', 'Eunpyeong-gu, Seoul, Republic of Korea', 'Jung-gu, Seoul, Republic of Korea', 'Jung-gu, Seoul, Republic of Korea', 'Jung-gu, Seoul, Republic of Korea',  'Jung-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Seongbuk-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Jongno-gu, Seoul, Republic of Korea', 'Seongbuk-gu, Seoul, Republic of Korea', 'Seodaemun-gu, Seoul, Republic of Korea', 'Seodaemun-gu, Seoul, Republic of Korea', 'Seodaemun-gu, Seoul, Republic of Korea', 'Seodaemun-gu, Seoul, Republic of Korea','Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea','Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea','Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea','Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea','Buk-gu, Daegu, Republic of Korea', 'Buk-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea', 'Dalseo-gu, Daegu, Republic of Korea'
        ];
        
        for ($i = 0 ;$i < count($vd_name) ;$i++) {
            DB::table('vendingmachine')->insert([
                'vd_id'             => NULL,
                'vd_name'           => $vd_name[$i],
                'vd_latitude'       => $vd_latitude[$i],
                'vd_longitude'      => $vd_longitude[$i],
                'vd_address'        => $vd_address[$i],
                'vd_place'          => $vd_place[$i],
                'vd_supplementer'   => 'hajae',
                'vd_soldout'        => 0,
                'money_in_vd'       => 5000,
                'out_money'         => 1000
            ]);
        }

        DB::table('vendingmachine')->where('vd_address', 'like', '%Seoul%')
        ->update(['vd_supplementer' => 'dahui']);
        DB::table('vendingmachine')->where('vd_address', 'like', '%Dalseo-gu%')
        ->update(['vd_supplementer' => 'kihyeok']);
    }
}
