<template>
    <div class="sells">
        <div class="statistics">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <canvas id="month-sales"></canvas>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="today earning-card text-center">
                                    <div class="mony-title">
                                        Today:
                                    </div>
                                    <div class="mony">
                                        <i class="fas fa-dollar-sign"></i> {{today_mony === 0 ? '0.00' : today_mony}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="total earning-card text-center">
                                    <div class="mony-title">
                                        Yesterday:
                                    </div>
                                    <div class="mony">
                                        <i class="fas fa-dollar-sign"></i> {{yesterday_mony === 0 ? '0.00': yesterday_mony}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="canvas-container">
                                    <canvas id="myChart" width="400" height="400" aria-label="Your browser dosen't support canvas" role="img"></canvas>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="canvas-container">
                                    <canvas id="secondChart" width="400" height="400" aria-label="Your browser dosen't support canvas" role="img"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bills">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="shadow-box sales-bottom-cards">
                            <div class="card-content" v-if="special_members.length !== 0">
                                <h2 class="text-center"> Special members </h2>
                                <div class="container">
                                    <div class="row margin-bottom-20" v-for="member in special_members" :key="member.id">
                                        <div class="col-6">
                                            <span class="name">
                                                <a :href="member.profile_link" v-text="member.name" style="text-decoration: none; color: #fff;"></a>
                                            </span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span v-text="member.total_payments + ' EGP'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-empty" v-if="special_members.length === 0">
                                <div class="empty-message">
                                    There are no special members right now!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="shadow-box sales-bottom-cards">
                            <div class="card-content" v-if="special_clients.length !== 0">
                                <h2 class="text-center"> Special clients </h2>
                                <div class="container">
                                    <div class="row margin-bottom-20" v-for="client in special_clients" :key="client.id">
                                        <div class="col-6">
                                            <span v-text="client.name" class="name"></span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span v-text="client.total_payments + ' EGP'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-empty" v-if="special_clients.length === 0">
                                <div class="empty-message">
                                    There are no special clients right now!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bills-container shadow-box sales-bottom-cards">
                            <h2 class="text-center"> Bills </h2>
                            <div class="no-bill-msg" v-if="bills.length === 0">
                                <p class="text-center" style="font-size:16px;color: #777; margin-top: 50px;"> There are no bills right now. </p>
                            </div>
                            <div class="container">
                                <div class="row" v-for="bill in bills" :key="bill.id">
                                    <div class="col-12" v-if="bills.findIndex(ele => ele.id === bill.id) < 4" >
                                        <div class="bill-container">
                                            <span class="name"> {{bill.customer_name}} </span> <span> {{new Date(bill.date*1000).getDate()}}/{{new Date(bill.date*1000).getMonth() + 1}}/{{new Date(bill.date*1000).getFullYear()}} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="btn btn-danger" style="cursor: pointer" @click="openAllBills=true" v-if="bills.length !== 0">See all</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <transition name="open-all-bills">
            <div class="all-bills" v-if="openAllBills" @click.self="close_bills">
                <Bills :start_bills_out_animation="start_bills_out_animation" :bills="bills" :delete_empty="delete_empty" :compute_edited_bill="compute_edited_bill" :delete_bill_color="delete_bill_color" :add_size="add_size"></Bills>
            </div>
        </transition>

        <transition name="slide-up-from-bottom">
            <div class="connection" v-if="show_connection_status">
                <div class="text-center connection-status connected" v-if="!no_connection"> Connected ! </div>
                <div class="text-center connection-status offline" v-if="no_connection"> Connection lost ! </div>
            </div>
        </transition>

    </div>
</template>

<style lang="scss" scoped>


    .sells
    {
        background-color: #eee;
        height: 100%;
        overflow-Y: auto;
        overflow-X: hidden;
        position: relative;
        padding-bottom: 20px;
        
        .margin-bottom-20 {margin-bottom: 20px}

        .earning-card
        {
            background-color: #2a4054;
            margin-top: 35px;
            height: 120px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            color: #ccc;
            border-radius: 10px;

            .mony-title
            {
                position: absolute;
                top: 0;
                left: 5px;
                font-size: 16px;
                color: #fff;
            }
        }
        
        .canvas-container
        {
            background-color: transparent;
            margin-top: 25px;
        }

        .bills
        {
            margin-top: 20px;

            .shadow-box
            {
                background-color: #fff;
                box-shadow: 0 1px 6px #000;
                border-radius: 5px;
                padding: 5px 0;
                overflow: hidden;
                overflow-y: auto;

                h2
                {
                    color: #888;
                    margin-bottom: 20px;
                    letter-spacing: 5px;
                    font-family: 'Yeon Sung', cursive;
                }

                .bill-container
                {
                    margin-bottom: 10px;
                    display: flex;
                    justify-content: space-between;
                    padding: 5px;
                }
            }
            .sales-bottom-cards > .card-empty
            {
                display: flex;
                width: 100%;
                height: 100%;
                justify-content: center;
                align-items: center;
            }
            .loading-container
            {
                width: 100%;
                height: calc(100% - 17px);
                margin-top: 17px;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 1px 6px #000;
            }
        }

        span.name
        {
            display: inline-block;
            color: #fff;
            background-color: #3490dc;
            text-align: center;
            padding: 0 10px;
        }

        .all-bills
        {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(10,10,10, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .connection
        {
            position: fixed;
            bottom: 0;
            width: calc(100% - 250px);
            color: #eee;
            letter-spacing: 1px;
            font-weight: bold;

            .connected {background-color: #0f0}
            .offline {background-color: #555}
        }

        //Animations
        .open-all-bills-enter-active, .open-all-bills-leave-active {transition: ease background-color .5s}
        .open-all-bills-enter, .open-all-bills-leave-to {background-color: rgba(10,10,10, 0)}
        .open-all-bills-enter-to, .open-all-bills-leave {background-color: rgba(10,10,10, 0.6)}
        
        /* connection details animation */
        .slide-up-from-bottom-enter-active, .slide-up-from-bottom-leave-active
        {
            transition: 0.5s transform ease-in-out;
        }
        .slide-up-from-bottom-enter, .slide-up-from-bottom-leave-to
        {
            transform: translateY(23px);
        }
        .slide-up-from-bottom-enter-to, .slide-up-from-bottom-leave
        {
            transform: translateY(0);
        }
    }

</style>

<script>
import Bills from './bills.vue';
export default {
    name: 'SalesInterface',
    props: [
        'url',
        'no_connection',
        'today_mony',
        'yesterday_mony',
        'the_day_before',
        'months_earnings',
        'this_year',
        'last_year',
        'the_year_before',
        'special_members',
        'special_clients',
        'bills'
    ],
    data: function () {
        return {

            show_connection_status: false,

            //Bills
            openAllBills: false,
            start_bills_out_animation: false,
            bill_loading_error: false,
            deleted_items: {
                bills_IDs: [],
                orders_IDs: [],
                colors_IDs: [],
                sizes_IDs: []
            },
            // end of bills

        }; //end of data function
    }, // end of data

    methods: {
        close_bills: function () {
            this.start_bills_out_animation = true;
            setTimeout(() => {
                this.openAllBills = false;
                this.start_bills_out_animation = false;
                }
            , 500);
        }, //end of close bills

        compute_edited_bill: function (bill_id, order_id, color_id, size_id) {
            
            let index_of_bill  = this.bills.findIndex(bill => bill.id === bill_id),
                selected_bill  = this.bills[index_of_bill],
                index_of_order = selected_bill.orders.findIndex(order => order.id === order_id),
                selected_order = selected_bill.orders[index_of_order],
                index_of_color = selected_order.colors.findIndex(color => color.id === color_id),
                selected_color = selected_order.colors[index_of_color],
                index_of_size  = selected_color.sizes.findIndex(size => size.id === size_id),
                selected_size  = selected_color.sizes[index_of_size];
                selected_size.count ? '' : selected_size.count = 0; // set min val = 0
                selected_size.count.length > 1 && selected_size.count[0] == 0 ? selected_size.count = selected_size.count.slice(1) : ''; // clear 0 on input
            
            selected_order.colors.forEach(color => {
                // calculate total count of colors.
                let total_count = 0;
                color.sizes.forEach(size => total_count += parseInt(size.count));
                color.count = total_count;
            });
            

        }, //end of compute edited bill

        delete_empty: function (bill_id, order_id, color_id, size_id) {
            let index_of_bill  = this.bills.findIndex(bill => bill.id === bill_id),
                selected_bill  = this.bills[index_of_bill],
                index_of_order = selected_bill.orders.findIndex(order => order.id === order_id),
                selected_order = selected_bill.orders[index_of_order],
                index_of_color = selected_order.colors.findIndex(color => color.id === color_id),
                selected_color = selected_order.colors[index_of_color],
                index_of_size  = selected_color.sizes.findIndex(size => size.id === size_id),
                selected_size  = selected_color.sizes[index_of_size];
            selected_size.count ? '' : selected_size.count = 0; // set min value = 0
            
            // clear size if val = 0
            if (selected_size.count == 0) {
                selected_color.sizes.splice(index_of_size, 1);
                this.deleted_items.sizes_IDs.push(selected_size.id);
            }; // end of if condition
            
            // clear color if it had no sizes
            if (selected_color.sizes.length === 0) {
                selected_order.colors.splice(index_of_color, 1);
                this.deleted_items.colors_IDs.push(selected_color.id);
            } //end of if condition
            
        }, //end of compute bill changes

        delete_bill_color: function (bill_id, order_id, color_id) {
            if (!this.no_connection) {
                let selected_bill_index  = this.bills.findIndex(bill => bill.id === bill_id),
                    selected_bill        = this.bills[selected_bill_index],
                    selected_order_index = selected_bill.orders.findIndex(order => order.id === order_id),
                    selected_order       = selected_bill.orders[selected_order_index],
                    selected_color_index = selected_order.colors.findIndex(color => color.id === color_id);

                selected_order.colors.splice(selected_color_index, 1);
                this.deleted_items.colors_IDs.push(color_id);
            }
        }, // End of delete color

        add_size: function (bill_id, order_id, color_id, size) {
            if (!this.no_connection) {
                let selected_bill_index  = this.bills.findIndex(bill => bill.id === bill_id),
                    selected_bill        = this.bills[selected_bill_index],
                    selected_order_index = selected_bill.orders.findIndex(order => order.id === order_id),
                    selected_order       = selected_bill.orders[selected_order_index],
                    selected_color_index = selected_order.colors.findIndex(color => color.id === color_id),
                    selected_color       = selected_order.colors[selected_color_index];

                selected_color.sizes.push({
                    id: Math.random() * 999999,
                    size,
                    count: 0,
                    color_id: selected_color.id,
                    order_id: selected_order.id,
                    bill_id: selected_bill.id,
                });
            }
        }, //end of add size
    }, //end of methods

    watch: {
        //watch the bills changes, make calculations and sent it to server.
        bills: {
            handler: function (new_bills, old_bills) {
                if (!this.no_connection) {
                    new_bills.forEach( (bill, bill_index) => {
                        let total_bill_price = 0;
                        bill.orders.forEach( (order, index) => {
                            total_bill_price += parseInt(order.total_price);
                            order.total_price = parseInt(order.piece_price) * parseInt(order.count); //calculate total order price
                            // calculate total order count
                            let total_count = 0;
                            order.colors.forEach(color => total_count += parseInt(color.count));
                            order.count = total_count;

                            // delete empty order
                            if (order.colors.length === 0) {
                                bill.orders.splice(index, 1);
                                this.deleted_items.orders_IDs.push(order.id);
                            } //end of if condition

                        });
                        bill.total_price = total_bill_price; //calculate total bill price

                        //delete empty bill
                        if (bill.orders.length === 0) {
                            this.bills.splice(bill_index, 1);
                            this.deleted_items.bills_IDs.push(bill.id);
                        } //end of if condition
                    });
                    new_bills.length === 0 ? this.close_bills() : ''; //Close bills window if all bills deleted.

                    //send changes to server
                    axios.post(this.url + '/bills/watch-changes', {
                        deleted_items: this.deleted_items,
                        bills: this.bills,
                    })
                    .then(response => {
                        if (response.data.new_size_created) {
                            let new_size = response.data.new_size_created;
                            let selected_bill_index  = this.bills.findIndex(bill => bill.id === new_size.bill_id),
                                selected_bill        = this.bills[selected_bill_index],
                                selected_order_index = selected_bill.orders.findIndex(order => order.id === new_size.order_id),
                                selected_order       = selected_bill.orders[selected_order_index],
                                selected_color_index = selected_order.colors.findIndex(color => color.id === new_size.color_id),
                                selected_color       = selected_order.colors[selected_color_index],
                                selected_size_index  = selected_color.sizes.findIndex(size => size.id === response.data.old_id),
                                selected_size        = selected_color.sizes[selected_size_index];
                            selected_size.id = new_size.id;
                        } //end of if.
                    })
                    .catch(err => console.log(err));
                } // end of if condition
            },

            deep: true,
        },

        //watch no_connection to get notification if connection lost.
        no_connection: function (value) {
            value ? this.show_connection_status = true : '';
            if (!value) {
                hide_connection_status ? console.log(hide_connection_status) : console.log('hide_connection_status not defined');
                let hide_connection_status = setTimeout(() => this.show_connection_status = false, 5000);
            }
        },

    },//end of watch

    mounted() {

        Chart.defaults.global.defaultFontSize = 13;
        //
        //daily doughnut
        var daily_doughnut = document.getElementById('myChart');
        var myChart = new Chart(daily_doughnut, {
            type: 'doughnut',
            data: {
                labels: ['Today', 'Yesterday', 'The day before'],
                datasets: [{
                    data: [this.today_mony, this.yesterday_mony, this.the_day_before],
                    backgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                responsiveAnimationDuration: 1000,
                title: {
                    display: true,
                    text: 'Daily earnings',
                    fontColor: '#888'
                },
                legend: {
                    display: false,
                }
            }
        });

        //yearly doughnut
        var secondCtx = document.getElementById('secondChart');
        var myChart = new Chart(secondCtx, {
            type: 'doughnut',
            data: {
                labels: ['This year', 'Last year', 'The year before'],
                datasets: [
                    {
                        data: [this.this_year, this.last_year, this.the_year_before],
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                        ],
                        borderWidth: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                responsiveAnimationDuration: 1000,
                title: {
                    display: true,
                    text: 'Yearly earnings',
                    fontColor: '#888'
                },
                legend: {
                    display: false,
                }
            }
        });

        var monthSales = document.querySelector('#month-sales').getContext('2d');
        var monthChart = new Chart(monthSales, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        data: [
                            this.months_earnings.jan,
                            this.months_earnings.feb,
                            this.months_earnings.mar,
                            this.months_earnings.apr,
                            this.months_earnings.may,
                            this.months_earnings.jun,
                            this.months_earnings.jul,
                            this.months_earnings.aug,
                            this.months_earnings.sep,
                            this.months_earnings.oct,
                            this.months_earnings.nov,
                            this.months_earnings.dec
                        ],
                        backgroundColor: '#3ab3f1',
                        borderColor: '#000',
                        borderWidth: 1,
                    }
                ]
            },
            options: {
                responsive: true,
                responsiveAnimationDuration: 1000,
                legend: {display: false},
                title: {
                    display: true,
                    text: `${new Date().getFullYear()} earnings`
                }
            },
        });

        // Set dimentions of elements
        function set_elements_sizes() {
            let window_height       = $('.sells').innerHeight(),
                statistics_height   = $('.statistics').innerHeight();
            if (window_height >= statistics_height)
            {
                $('.sales-bottom-cards').innerHeight(window_height - statistics_height - 60);
            }
        }
        set_elements_sizes();

    },

    components: {
        Bills,
    },
}
</script>