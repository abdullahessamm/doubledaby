<template>
    <div class="bills-component">    
        <transition name="first-open">
            <div class="bills-all" v-if="show_component">
                <h2 class="text-center"> Bills </h2>
                <div class="container bill-container" v-for="bill in bills" :key="bill.id">
                    <input type="radio" name="bills[]" :id="'bill' + bill.id">
                    <label :for="'bill' + bill.id">
                        <div class="row the-row">
                            <div class="col-6">
                                <span> {{ bill.customer_name }} </span>
                                <span v-if="bill.is_member != '0'" class="member"> #Member </span>
                            </div>
                            <div class="col-6 text-right">
                                <div class="date" style="display:flex;justify-content:flex-end;align-items:center;height:100%"> {{new Date(bill.date*1000).getDate()}}/{{new Date(bill.date*1000).getMonth() + 1}}/{{new Date(bill.date*1000).getFullYear()}} </div>
                            </div>
                        </div>
                    </label>
                    <div class="row details">
                        <div class="container">
                            <div class="content">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="span-red" style="max-width: inherit"> #Total price </span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="default-width text-left">
                                                <b style="color: #777"> {{ bill.total_price }} pounds </b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="span-red"> #Phone </span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="default-width text-left">
                                                <input type="text" :value="bill.customer_phone" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="span-red"> #Email </span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="default-width text-left">
                                                <b style="color: #777"> {{ bill.customer_email }} </b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="row">
                                        <div class="col-12"> <h5 class="text-center"> Orders </h5> </div>
                                    </div>
                                    <div class="row order-row" v-for="order in bill.orders" :key="order.id">
                                        <div class="col-12">
                                            <input type="checkbox" name="orders" :id="'order' + order.id">
                                            <label :for="'order' + order.id" class="order-label container">
                                                <div class="row">
                                                    <div class="col-6"> {{order.name}} </div>
                                                    <div class="col-6 text-right"> <i class="fas fa-plus"></i> </div>
                                                </div>
                                            </label>
                                            <div class="order-content container">
                                                <div class="row">
                                                    <div class="col-6"> <span> #Count </span> </div>
                                                    <div class="col-6 text-right"> {{ order.count == 1 ? 'One piece' : order.count + ' pieces'}} </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6"> <span style="max-width: inherit"> #Piece price </span> </div>
                                                    <div class="col-6 text-right"> {{order.piece_price}} pounds </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6"> <span style="max-width: inherit"> #Total price </span> </div>
                                                    <div class="col-6 text-right"> {{order.total_price}} pounds </div>
                                                </div>
                                                <div class="divider"></div>
                                                <div class="row" style="margin-bottom: 10px">
                                                    <div class="col-6"> <span class="span-red"> #Colors </span> </div>
                                                </div>
                                                <div class="row" v-for="color in order.colors" :key="color.id">
                                                    <div class="col-12">
                                                        <div class="container color">
                                                            <div class="row">
                                                                <div class="col-6"> <span> #Color </span> </div>
                                                                <div class="col-6 text-right"> {{color.color}} </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6"> <span style="max-width: inherit"> #Total count </span> </div>
                                                                <div class="col-6 text-right"> {{color.count == 1 ? 'One piece' : color.count + ' pieces'}} </div>
                                                            </div>
                                                            <div class="row" v-for="size in color.sizes" :key="size.id">
                                                                <div class="col-6"> <span style="background-color: gray"> #Size {{ size.size }} </span> </div>
                                                                <div class="col-6 text-right"> <input type="number" v-model="size.count" @blur="delete_empty(bill.id, order.id, color.id, size.id)" @input="compute_edited_bill(bill.id, order.id, color.id, size.id)"> </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6 text-right" style="cursor: pointer" v-if="!show_input_size || show_input_size != color.id"> <div class="btn btn-success" @click="show_input_size = color.id"> <i class="fas fa-plus"></i> Add size </div> </div>
                                                                <div class="col-6 text-right" v-if="show_input_size == color.id"> <input type="number" value="0" @input="e => validate_input(e)" @keydown.enter="e => {add_size(bill.id, order.id, color.id, e.target.value); show_input_size = false;}"> </div>
                                                                <div class="col-6"> <div class="btn btn-danger" style="cursor: pointer" @click="delete_bill_color(bill.id, order.id, color.id)"> <div class="fas fa-trash"></div> Delete {{ color.color }} </div> </div>
                                                            </div>
                                                            <div class="divider" v-if="color.id !== order.colors[order.colors.length - 1].id"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>


<style lang="scss" scoped>


    

    
    *::-webkit-scrollbar
    {
        background-color: transparent;
        width: 4px;
        cursor: pointer;
    }

    *::-webkit-scrollbar-thumb
    {
        border-radius: 20px;
        background-color: #2a4054;
    }

    .order-content::-webkit-scrollbar-thumb {background-color: #ff0011 }

    

    .bills-all
    {
        width: 800px;
        height: 600px;
        background-color: #fff;
        padding: 10px;
        overflow-y: auto;

        .divider
        {
            border: 1px solid #ccc;
            margin: 20px 0;
        }

        h2
        {
            margin-top: 20px;
            margin-bottom: 40px;
        }

        h2, h5
        {
            color: #888;
            letter-spacing: 5px;
            font-family: 'Yeon Sung', cursive;
        }

        .bill-container
        {
            span.member
            {
                background-color: #e3342f;
            }

            label
            {
                display: inline-block;
                width: 100%;
                padding: 10px;
                cursor: pointer;
                margin: 0 !important;
            }

            label:hover {background-color: #eee}

            input[type="radio"], input[type="checkbox"] { display: none }

            .details
            {
                height: 0px;
                overflow: hidden;
                overflow-y: auto;
                transition: height .3s ease-in-out;
                margin-bottom:10px;

                .content
                {
                    background-color: #eee;
                    height: 100%;

                    .span-red {background-color: #ff0011 !important}
                    .default-width {width: 180px; display: inline-block}
                    .row {padding-top: 7px}
                    label
                    {
                        background-color: #2a4054;
                        color: #fff;
                        padding-top: 5px;

                        i
                        {
                            transition: .5s transform ease;
                        }
                    }

                    label:last-of-type, .order-content:last-of-type {margin-bottom: 20px}
                    input[type="checkbox"]:checked ~ label { background-color: #2a4054; }
                    input[type="checkbox"]:checked ~ label i { transform: rotate(225deg) }
                    .order-content
                    {
                        background-color: #2a4054ee;
                        color: #fff;
                        height: 0;
                        overflow-y: auto;
                        transition: .3s height ease-in-out, .3s padding ease-in-out;
                    }
                    input[type="checkbox"]:checked ~ .order-content 
                    {
                        height: 300px;
                        padding-top: 10px;
                        padding-bottom: 10px;
                    }
                }
            }

            input[type="radio"]:checked ~ .details, input[type="radio"]:checked ~ .order-details { height: 500px }

            input[type="radio"]:checked ~ label { background-color: #eee }



            span
            {
                background-color: #3490dc;
                color: #fff;
                max-width: 80px;
                text-align: center;
                display: inline-block;
                padding: 3px 10px;
            }
            
        }


    }

    //Animations
    .first-open-enter-active, .first-open-leave-active
    {
        transition: .4s ease transform, .4s ease opacity;
    }
    .first-open-enter, .first-open-leave-to
    {
        transform: translateY(50px);
        opacity: 0;
    }
    .first-open-enter-to, .first-open-leave
    {
        transform: translateY(0);
        opacity: 1;
    }
</style>

<script>
export default {
    name: 'Bills',
    props: ['start_bills_out_animation', 'bills', 'delete_empty', 'compute_edited_bill', 'delete_bill_color', 'add_size'],
    data: function () {
        return {
            show_component: false,
            show_input_size: false,
        };
    },

    methods: {
        validate_input: function (e) {
            e.target.value[0] === '0' && e.target.value.length !== 0 ? e.target.value = e.target.value.slice(1) : '';
            if (e.target.value === '')
                e.target.value = 0
        } // end of validate input
    }, //end of methods

    watch: {
        start_bills_out_animation: function (value) {
            value ? this.show_component = false : '';
        }, //end of component animation
    }, //end of watch

    mounted(){
        this.show_component = true;
    },
}
</script>