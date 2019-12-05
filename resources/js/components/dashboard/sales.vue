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
                    <div class="col-md-6 col-lg-8">
                        <div class="trend-product-container">

                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4" v-show="is_loading || bill_loading_error">
                        <div class="loading-container">
                            <div class="sk-chase" v-if="is_loading">
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                            </div>
                            <div v-if="bill_loading_error"> Can't connect to the server </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4" v-show="!is_loading && !bill_loading_error">
                        <div class="bills-container">
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
        height: calc(100% - 64px);
        overflow-Y: auto;
        overflow-X: hidden;
        position: relative;
        padding-bottom: 20px;
        
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

            .bills-container, .trend-product-container
            {
                background-color: #fff;
                margin-top: 15px;
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

                    span.name
                    {
                        color: #fff;
                        background-color: #3490dc;
                        min-width: 80px;
                        text-align: center;
                    }
                }
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

        //Loading animation
        /* Loading */
        .sk-chase {
            width: 40px;
            height: 40px;
            position: relative;
            animation: sk-chase 2.5s infinite linear both;
            }

            .sk-chase-dot {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0; 
            animation: sk-chase-dot 2.0s infinite ease-in-out both; 
            }

            .sk-chase-dot:before {
            content: '';
            display: block;
            width: 25%;
            height: 25%;
            background-color: #ff0011;
            border-radius: 100%;
            animation: sk-chase-dot-before 2.0s infinite ease-in-out both; 
            }

            .sk-chase-dot:nth-child(1) { animation-delay: -1.1s; }
            .sk-chase-dot:nth-child(2) { animation-delay: -1.0s; }
            .sk-chase-dot:nth-child(3) { animation-delay: -0.9s; }
            .sk-chase-dot:nth-child(4) { animation-delay: -0.8s; }
            .sk-chase-dot:nth-child(5) { animation-delay: -0.7s; }
            .sk-chase-dot:nth-child(6) { animation-delay: -0.6s; }
            .sk-chase-dot:nth-child(1):before { animation-delay: -1.1s; }
            .sk-chase-dot:nth-child(2):before { animation-delay: -1.0s; }
            .sk-chase-dot:nth-child(3):before { animation-delay: -0.9s; }
            .sk-chase-dot:nth-child(4):before { animation-delay: -0.8s; }
            .sk-chase-dot:nth-child(5):before { animation-delay: -0.7s; }
            .sk-chase-dot:nth-child(6):before { animation-delay: -0.6s; }

            @keyframes sk-chase {
            100% { transform: rotate(360deg); } 
            }

            @keyframes sk-chase-dot {
            80%, 100% { transform: rotate(360deg); } 
            }

            @keyframes sk-chase-dot-before {
            50% {
                transform: scale(0.4); 
            } 100%, 0% {
                transform: scale(1.0); 
            } 
        }
        
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
import Bills from './sales/bills.vue';
export default {
    name: 'Sales',
    props: ['url', 'no_connection'],
    data: function () {
        return {
            show_connection_status: false,
            //days earnings
            today_mony: 2000.00,
            yesterday_mony: 1000.00,
            the_day_before: 3500.00,
            
            //years earnings
            this_year: 20000,
            last_year: 30000,
            the_year_before: 9000,

            //Bills
            openAllBills: false,
            start_bills_out_animation: false,
            is_loading: true,
            bill_loading_error: false,
            bills: [],
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
        no_connection: function (value) {
            value ? this.show_connection_status = true : '';
            if (!value) {
                hide_connection_status ? console.log(hide_connection_status) : console.log('hide_connection_status not defined');
                let hide_connection_status = setTimeout(() => this.show_connection_status = false, 5000);
            }
        },
    },

    mounted() {

        //get data from server
        axios.get(this.url + '/bills/get-all')
        .then(response => {
            this.is_loading = false;
            this.bills = response.data;

        })
        .catch(err => {
            this.is_loading = false;
            this.bill_loading_error = true;
        });

        Chart.defaults.global.defaultFontSize = 13;
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
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
                        data: [10,20,100,30,90,50,40,120,70,110,200,120],
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
                    text: 'This year earnings'
                }
            },
        });

        // Set dimentions of elements
        function set_elements_sizes() {
            let window_height       = $('.sells').innerHeight(),
                statistics_height   = $('.statistics').innerHeight();
            if (window_height >= statistics_height)
            {
                $('.bills-container, .trend-product-container').innerHeight(window_height - statistics_height - 60);
            }
        }

        set_elements_sizes();

        //create beautiful numbers counter
        let today_mony = this.today_mony;
        this.today_mony = 0;
        let counter = setInterval(() => {
            if (today_mony >= 1000 && today_mony % 10 === 0)
                this.today_mony += 10;
            else if (today_mony !== 0)
                this.today_mony += 1;
            if (this.today_mony >= today_mony)
                clearInterval(counter);
            }, 1);
        let yesterday_mony = this.yesterday_mony;
        this.yesterday_mony = 0;
        let yesterday_counter = setInterval(() => {
                if (yesterday_mony >= 1000 && yesterday_mony % 10 === 0)
                    this.yesterday_mony += 10;
                else if (yesterday_mony !== 0)
                    this.yesterday_mony += 1;
                if (this.yesterday_mony >= yesterday_mony)
                    clearInterval(yesterday_counter);
            }, 1);


    },

    components: {
        Bills,
    },
}
</script>