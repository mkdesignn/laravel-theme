import Vue from 'vue';
import datagridview from './components/datagrid.vue';

Vue.component("datagridview", datagridview);


new Vue({
    el: '#user_wrapper',
    data: user_data
});

