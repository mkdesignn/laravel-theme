<template>
    <div class="grid_wrapper">
        <div class="grid_header" style="overflow:hidden;">
            <div class="col-md-3 align_right">
                <div class="form-group">
                    <label for="search">جستجو</label>
                    <div class="input-group" id="search">
                        <input v-model="search" type="text" class="form-control"/>
                        <span class="input-group-addon icomoon-uniE04B" style="font-size: large;"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-md-push-6 align_right">
                <div class="form-group">
                    <label for="row_per_page">تعداد در صفحه</label>
                    <div class="input-group" id="row_per_page">
                        <select v-model="row_per_page" @change="changeRowPerPage" class="form-control">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="input-group-addon icomoon-uniE04B" style="font-size: large;"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid_content" style="clear:both;overflow:hidden;">
            <div class="col-md-12" v-loading="loading.init">
                <!--<pulse-loader :loading="loading.init" :color="loading.color" :size="loading.size"></pulse-loader>-->
                <table class="data_grid">
                    <thead>
                        <tr>
                            <td v-for="h in head">
                                {{h}}
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                       <tr v-for="(row, index) in rows">
                           <td v-for="cell in Object.keys(head)">
                               {{ row[cell] }}
                           </td>
                       </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="grid_footer" style="overflow:hidden;">
            <div class="col-md-12">
                <paginate
                    :page-count="last_page"
                    :page-range="3"
                    :prev-text="'Prev'"
                    :next-text="'Next'"
                    :click-handler="changePaginate"
                    :container-class="'pagination'">
                </paginate>
            </div>
        </div>
    </div>
</template>

<script>

    import axios from 'axios';
    import Paginate from 'vuejs-paginate';
    import loadings from './loading-dirctive.vue';
    axios.defaults.headers['X-Requested-With'] = 'XMLHttpRequest';

    export default{
        data: function(){
            return {
                row_per_page: 10,
                search: '',
                rows: '',
                total: 0,
                page_id: 0,
                last_page:0,
                loading: {
                    init: false,
                    size: '20px',
                    color: 'rgba(42,42,42,.3)'
                }
            }
        },
        components:{
            paginate: Paginate
        },
        props: {
            head:'',
            url: ''
        },
        watch:{
            search: function(){
                this.fillGrid(1);
            }
        },
        methods:{
            changePaginate: function(pageNum){
                this.page_id = pageNum;
                this.fillGrid(pageNum);
            },
            fillGrid: function(page_num){

                var vm = this;
                vm.loading.init = true;
                axios.get(this.url,{
                    params:{
                        page: page_num,
                        row_per_page: this.row_per_page,
                        search: this.search
                    }
                }).then(function(response){
                    vm.rows = response.data[0].data;
                    vm.last_page = response.data[0].last_page;
                    vm.total = response.data[0].total;

                    // filter the rows id
                    vm.filterRowsId();

                    vm.loading.init = false;
                });
            },
            filterRowsId: function(){
                var vm = this;
                this.rows.forEach(function(value, index){
                    value.id = ( (vm.page_id * vm.row_per_page) - vm.row_per_page ) + index + 1;
                })
            },
            changeRowPerPage: function(){
                this.page_id = 1;
                this.fillGrid(1);
            }
        },
        mounted: function(){
            this.page_id = 1;
            this.fillGrid(1);
        }
    }
</script>