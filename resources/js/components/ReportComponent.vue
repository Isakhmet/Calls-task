<template>
    <div class="flex-center position-ref full-height">
        <div class="container">
            <div class="row">
                <div class="col-md-9" id="table">
                    <div class="data-table">
                        <div class="main-table">
                            <table class="ui single line table">
                                <thead>
                                <tr>
                                    <th class="table-head">#</th>
                                    <th v-for="column in columns" :key="column"
                                        class="table-head">
                                        {{ column | columnHead }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="" v-if="tableData.length === 0">
                                    <td class="lead text-center" :colspan="columns.length + 1">No data
                                        found.{{tableData.length}}
                                    </td>
                                </tr>
                                <tr v-for="(data, key1) in tableData" :key="data.id" class="m-datatable__row" v-else>
                                    <td>{{ key1 }}</td>
                                    <td v-for="(value, key) in data" v-if="key !== 'link'">{{value}}</td>
                                    <td class="link" v-else>
                                        <a :href="value" v-if="value !== ''">Скачать запись</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:    {
            fetchUrl: {type: String, required: true}
        },
        data() {
            return {

                showModal:    false,
                columns:      [],
                tableData:    [],
                url:          '',
                type:         '',
                report_id:    '',
                date_end:     '',
                date_start:   '',

            }
        },
        watch:    {
            fetchUrl: {
                handler:   function (fetchUrl) {
                    this.url = fetchUrl
                    this.fetchData()
                },
                immediate: true
            }
        },
        mounted() {
            //this.$on('report', 'mega');
            var socket = io('http://127.0.0.1:3000');
            socket.on("laravel_database_calls:App\\Events\\OnlineEvent", function(data){
                this.fetchData();
                alert('Hello');
                console.log(data);
            }.bind(this));
        },
        computed: {

        },
        methods:  {

            link:   function (value) {
                console.log(value);
                this.amounts = value;
                this.$modal.push('example')
            },

            sendDate:    function (dates) {

                this.date_start = dates[0]
                this.date_end   = dates[1]
                this.fetchData()
            },
            fetchData() {
                axios.post(this.url)
                    .then(({data}) => {
                        this.tableData  = data.data
                        this.columns    = data.headers

                        console.log(this.columns);
                    }).catch(error => this.tableData = [])
            },

            submitClick(data) {
                this.report    = data[0]
                this.type      = data[1]
                this.report_id = data[2]
            }
        },
        filters:  {
            columnHead(value) {
                return value.split('_').join(' ').toUpperCase()
            }
        },
        name:     'DataTable'
    }
</script>
