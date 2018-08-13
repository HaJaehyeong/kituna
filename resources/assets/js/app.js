import Vue from 'vue';

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);
export const EventBus = new Vue(); //이벤트 버스 생성

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify);


import App from './app.vue';

//메인 전 페이지
import Register from './components/login/register.vue';
import Login from './components/login/login.vue';

//메인 페이지

import Main from './components/main/main.vue';
import MainHeader from './components/main/mainHeader.vue'

//-실시간 모니터링
import RealtimeBackground from './components/realtime/realtimeBackground.vue';
import GoogleMap from "./components/realtime/googleMap.vue";
import * as VueGoogleMaps from "vue2-google-maps";
import RealtimeVendingMachine from "./components/realtime/realtimeVendingMachine.vue";
import Local from './components/realtime/Local.vue';
//

//<------------------ 분석 페이지 -------------------------->
import VueCharts from 'vue-chartjs'
import { Bar, Line } from 'vue-chartjs'
import VCalendar from 'v-calendar';
import 'v-calendar/lib/v-calendar.min.css';

import VueScrollTo from 'vue-scrollto'
Vue.use(VueScrollTo);
import AnalysisBackground from './components/analysis/analysisBackground.vue'
import AnalysisLineChart from './components/analysis/analysisLineChart.vue'
//<------------------ 분석 페이지 -------------------------->


//자판기 캔 변경
import Datatable from 'vuejs-table-component'


//자판기 보충기사 관리
import ManageBackground from "./components/management/managementBackground.vue";
import ManagementSide from "./components/management/managementSide.vue";
import ManagementContents from "./components/management/managementContents.vue";


//제품관리 및 주문
import CompanyInformation from './components/product/companyInformation.vue'
import ProductCompanyBackground from './components/product/productCompanyBackground.vue'
import ProductInformation from './components/product/productInformation.vue'

import UploadInfo from './components/product/uploadInfo.vue'
import ModifyInfo from './components/product/modifyInfo.vue'
import ProductRemove from './components/product/productRemove.vue'
import OrderHistory from './components/product/orderHistory.vue'
import Order from './components/product/order.vue'

Vue.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyBg0LRr7Zw8yIyAy2C2FHR7OzCYfVJ_wEM",
        language: 'ja',
        libraries: ['places'] // necessary for places input
    }
});

Vue.use(VCalendar);

const routes = [{
        path: '/',
        component: Login
    }, {
        path: '/register',
        component: Register
    }, {
        path: '/mainPage',
        component: Main,

    }, {
        path: '/realtime',
        component: RealtimeBackground,
        children: [{
            path: '',
            components: {
                MainHeader: MainHeader,
                Local: Local,
                GoogleMap: GoogleMap,
                RealtimeVendingMachine: RealtimeVendingMachine,
            }
        }]
    }, {
        path: '/management',
        component: ManageBackground,
        children: [{
            path: '',
            components: {
                MainHeader: MainHeader,
                ManagementSide: ManagementSide,
                ManagementContents: ManagementContents,

            }
        }]
    }, {
        path: '/analysis',
        component: AnalysisBackground,
        children: [{
            path: '',
            components: {
                MainHeader: MainHeader,
                AnalysisLineChart: AnalysisLineChart
            }
        }]
    }, {
        path: '/product',
        component: ProductCompanyBackground,
        children: [{
            path: '',
            components: {
                ProductCompanyBackground: ProductCompanyBackground,
                MainHeader: MainHeader,
                CompanyInformation: CompanyInformation,
                ProductInformation: ProductInformation,
                UploadInfo: UploadInfo,
                ModifyInfo: ModifyInfo,
                ProductRemove: ProductRemove,
                OrderHistory: OrderHistory,
                Order: Order
            }

        }]
    }

];



const router = new VueRouter({ mode: 'history', routes });
new Vue(Vue.util.extend({ router }, App)).$mount('#app');