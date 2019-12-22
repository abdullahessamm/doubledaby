<template>
    <div id="sales">
        <SalesInterface 
            :url="url" 
            :no_connection="no_connection"
            :today_mony="today_mony"
            :yesterday_mony="yesterday_mony"
            :the_day_before="the_day_before"
            :months_earnings="months_earnings"
            :this_year="this_year"
            :last_year="last_year"
            :the_year_before="the_year_before"
            :bills="bills"
            :special_members="special_members"
            :special_clients="special_clients"
            v-if="all_items_loaded"
        >
        </SalesInterface>
        <div class="loading-animation-container" v-if="!all_items_loaded">
            <div class="sk-chase">
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    #sales
    {
        width: 100%;
        height: calc(100% - 64px);
        .loading-animation-container
        {
            display:flex;
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
        }

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
    }
</style>

<script>
import SalesInterface from './sales/interface';
export default {
    name: 'Sales',
    props: ['url', 'no_connection'],
    data: function () {
        return {
            //loaded items from server
            loaded_items: [],
            all_items_loaded: false,

            show_connection_status: false,
            //days earnings
            today_mony: 0,
            yesterday_mony: 0,
            the_day_before: 0,

            //monthly earnings
            months_earnings: {
                jan: 0,
                feb: 0,
                mar: 0,
                apr: 0,
                may: 0,
                jun: 0,
                jul: 0,
                aug: 0,
                sep: 0,
                oct: 0,
                nov: 0,
                dec: 0,
            },
            
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
            // end of bills

            special_members: [],

            special_clients: [
                {
                    id: Math.floor(Math.random() * 999999),
                    name: 'Mohammad',
                    total_payments: 2000,
                }
            ],

        }; //end of data function
    }, // end of data

    watch: {
        loaded_items: function (val) {
            if (val.length === 21)
                this.all_items_loaded = true;
        }, //end of loaded items
    }, //end of watch

    mounted() {

        //get bills from server
        axios.get(this.url + '/bills/get-all')
        .then(response => {
            this.is_loading = false;
            this.bills = response.data.reverse();
            this.loaded_items.push('bills');
        })
        .catch(err => {
            this.is_loading = false;
            this.bill_loading_error = true;
        });

        /* Daily section */
        //get today earnings
        let date        = new Date(),
            current_day = `${date.getFullYear()}-${(date.getMonth() + 1).toString().length < 2 ? '0' + (date.getMonth() + 1).toString() : date.getMonth() + 1}-${date.getDate().toString().length < 2 ? '0' + date.getDate().toString() : date.getDate()}`;
        axios.post(`${this.url}/bills/day-earning`, {day: current_day})
        .then(response => {
            this.today_mony = response.data;
            this.loaded_items.push('today_mony');
        })
        .catch(error => window.location.reload());

        //get yesterday earnings
        let yesterday_date        = new Date(date.getTime() - (1 * 24 * 60 * 60 * 1000)),
            yesterday_date_format = `${yesterday_date.getFullYear()}-${(yesterday_date.getMonth() + 1).toString().length < 2 ? '0' + (yesterday_date.getMonth() + 1).toString() : yesterday_date.getMonth() + 1}-${yesterday_date.getDate().toString().length < 2 ? '0' + yesterday_date.getDate().toString() : yesterday_date.getDate()}`;
        axios.post(`${this.url}/bills/day-earning`, {day: yesterday_date_format})
        .then(response => {
            this.yesterday_mony = response.data;
            this.loaded_items.push('yesterday_mony');
        })
        .catch(error => console.log(error));

        //get the day before earnings
        let the_day_before_date        = new Date(date.getTime() - (2 * 24 * 60 * 60 * 1000)),
            the_day_before_date_format = `${the_day_before_date.getFullYear()}-${(the_day_before_date.getMonth() + 1).toString().length < 2 ? '0' + (the_day_before_date.getMonth() + 1).toString() : the_day_before_date.getMonth() + 1}-${the_day_before_date.getDate().toString().length < 2 ? '0' + the_day_before_date.getDate().toString() : the_day_before_date.getDate()}`;
        axios.post(`${this.url}/bills/day-earning`, {day: the_day_before_date_format})
        .then(response => {
            this.the_day_before = response.data;
            this.loaded_items.push('the_day_before');
        })
        .catch(error => console.log(error));
        /* End of daily section */

        /* monthly section */
        //get all months in current year
        let current_year = new Date().getFullYear();
        let get_all_year_earnings = async () => {
            for(let month=1; month<=12; month++) {
            await axios.post(`${this.url}/bills/month-earnings`, {month: `${current_year}-${month}`})
            .then(res => {
                switch (month) {
                    case 1:
                        this.months_earnings.jan = res.data;
                        break;
                    
                    case 2:
                        this.months_earnings.feb = res.data;
                        break;

                    case 3:
                        this.months_earnings.mar = res.data;
                        break;

                    case 4:
                        this.months_earnings.apr = res.data;
                        break;

                    case 5:
                        this.months_earnings.may = res.data;
                        break;

                    case 6:
                        this.months_earnings.jun = res.data;
                        break;

                    case 7:
                        this.months_earnings.jul = res.data;
                        break;

                    case 8:
                        this.months_earnings.aug = res.data;
                        break;

                    case 9:
                        this.months_earnings.sep = res.data;
                        break;

                    case 10:
                        this.months_earnings.oct = res.data;
                        break;

                    case 11:
                        this.months_earnings.nov = res.data;
                        break;

                    case 12:
                        this.months_earnings.dec = res.data;
                        break;
                
                    default:
                        console.error('Error in months axios response month: ' + month);
                        break;
                }//end of switch
                this.loaded_items.push(`month_${month}`);
            })//end of axios.then
            .catch(err => console.log('error in month ' + month));//end of axios
        }//end of for
        };// end of get_all_year_earnings function
        get_all_year_earnings();
        /* end monthly section */

        /* Yearly section */
        //get this year earnings
        axios.post(`${this.url}/bills/year-earnings`, {year: new Date().getFullYear()})
        .then(res => {
            this.this_year = res.data;
            this.loaded_items.push('this_yaer');
        })
        .catch(err => console.log(err));
        
        //Get last year earnings
        axios.post(`${this.url}/bills/year-earnings`, {year: new Date().getFullYear() - 1})
        .then(response => {
            this.last_year = response.data;
            this.loaded_items.push('last_year');
        })
        .catch(error => console.log(error));

        //Get the year before earnings
        axios.post(`${this.url}/bills/year-earnings`, {year: new Date().getFullYear() - 2})
        .then(response => {
            this.the_year_before = response.data;
            this.loaded_items.push('the_year_before');
        })
        .catch(error => console.log(error));

        /* End yearly section */

        // Get special members
        axios.post(`${this.url}/bills/special-customers`, {wanted: 'members'})
        .then(response => {
            this.special_members = response.data;
            this.loaded_items.push('special_members');
        })
        .catch(err => console.log(err));

        // Get special clients
        axios.post(`${this.url}/bills/special-customers`, {wanted: 'clients'})
        .then(response => {
            this.special_clients = response.data;
            this.loaded_items.push('special_clients');
        })
        .catch(error => console.log(error));

    },

    components: {
        SalesInterface,
    },
}
</script>