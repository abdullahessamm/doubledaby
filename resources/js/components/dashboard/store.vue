<template>
    <div class="store">

        <div class="add-product-btn" v-if="products.length !== 0 || added_product_data.length !== 0" @click="show_add_component = true">
            <i class="fas fa-plus"></i>
        </div>

        <div class="sk-chase" v-if="page_status === 'loading'">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>

        <div class="error" v-if="page_status === 'error'">
            <h3 style="color:#ff0011"> <i class="fas fa-exclamation-triangle"></i> Some thing went error! </h3>
        </div>

        <transition name="errorAnimate">
            <div class="delete-error" v-if="delete_status === 'failed'">
                <i class="fas fa-exclamation-circle fa-2x"></i> Error while deleting.
            </div>
            <div class="delete-error" v-if="editing_error">
                <i class="fas fa-exclamation-circle fa-2x"></i> Connection error.
            </div>
        </transition>

        <div class="no-posts text-center" v-if="products.length === 0 && added_product_data.length === 0 && page_status === 'loaded'">
            <h3> Your store is empty! </h3>
            <div class="btn btn-success" @click="show_add_component = true"> <i class="fas fa-plus"></i> Add Product </div>
        </div>

        <div class="products" v-if="products.length !== 0 || added_product_data.length !== 0">
            <div class="text-center">
                <input type="text" placeholder="Search" v-model="search" @input="find_product">
            </div>
            <section class="added" v-if="added_product_data.length !== 0">
                <fieldset>
                    <legend> New </legend>
                    <div class="products">
                        <table>
                            <tr class="tr-head">
                                <td> Name </td>
                                <td> Company </td>
                                <td> Price </td>
                                <td> Count </td>
                                <td>  </td>
                            </tr>
                            <tr v-for="product in added_product_data" :key="product.id">
                                <td> {{ product.name }} </td>
                                <td> {{ product.company }} </td>
                                <td> {{ product.price }} pounds </td>
                                <td> {{ product.count }} </td>
                                <td>
                                    <span class="edit" :id="'edit' + product.id" @click="edit_product(product.id)"><i class="fas fa-pen-square"></i></span>
                                    <span class="delete" @click="Delete_new(product.id)" :id="'delete' + product.id"><i class="fas fa-trash"></i></span>
                                </td>
                            </tr>                          

                        </table>
                    </div>
                </fieldset>
            </section>
            <h3 class="search-not-found text-center" style="margin-top: 100px; color: #666;" v-if="filtered_products.length === 0">
                ({{search}}) not found!
            </h3>
            <section class="old-products" v-if="filtered_products.length !== 0">
                <fieldset>
                    <legend v-if="added_product_data.length !==0"> Old </legend>
                    <div class="products">
                        <table>
                            <tr class="tr-head">
                                <td> Name </td>
                                <td> Company </td>
                                <td> Price </td>
                                <td> Count </td>
                                <td> Created at </td>
                                <td> Updated at </td>
                                <td>  </td>
                            </tr>
                            <tr v-for="product in filtered_products" :key="product.id">
                                <td> {{ product.name }} </td>
                                <td> {{ product.company }} </td>
                                <td> {{ product.price }} pounds </td>
                                <td> {{ product.count }} </td>
                                <td> {{ convert_date(product.created_at) }} </td>
                                <td> {{ convert_date(product.updated_at) }} </td>
                                <td>
                                    <span class="edit" @click="edit_product(product.id)" :id="'edit' + product.id"><i class="fas fa-pen-square"></i></span>
                                    <span class="delete" @click="Delete_old(product.id)" :id="'delete' + product.id"><i class="fas fa-trash"></i></span>
                                </td>
                            </tr>                          

                        </table>
                    </div>
                </fieldset>
            </section>
        
        </div>

        <transition name="fade">
            <div class="add-comp" v-if="show_add_component" @click.self="show_add_component = false">
                <AddComponent :token="token" :url="url" :get_added_product_data ="get_added_product_data"></AddComponent>
            </div>
        </transition>
        <transition name="fade">
            <div class="edit-comp" v-if="show_edit_component" @click.self="show_edit_component = false">
                <EditComponent :alert_if_error="alert_if_error" :get_edited_data="get_edited_data" :token="token" :url="url" :get_added_product_data ="get_added_product_data" :id="edit_product_id"></EditComponent>
            </div>
        </transition>
    </div>
</template>

<style lang="scss" scoped>

    .store
    {


        .add-product-btn
        {
            position: fixed;
            bottom: 5px;
            right: 5px;
            color: #fff;
            width: 50px;
            height: 50px;
            background-color: #0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            cursor: pointer;
        }

        .delete-error
        {
            color: red;
            position: absolute;
            top: 20px;
            right: 5px;
            background: #fff;
            padding: 10px 50px;
            border-radius: 9px;
            box-shadow: 0px 1px 10px #000;
            display: flex;
            justify-content: center;
            align-items: center;

            i{margin-right: 10px;}
        }

        background: #eee;
        width: 100%;
        height: calc(100% - 64px);
        overflow-y: auto;
        overflow-x: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;

        h3 {color: #777;}

        .add-comp, .edit-comp
        {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .products
        {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y:auto;
            padding: 20px 40px;
            
            input[placeholder='Search']
            {
                border: none;
                border-bottom: 1px solid #aaa;
                background: transparent;
                width: 200px;
                height: 26px;
                padding: 1px 10px;
                outline: none;
                box-shadow: 0px 0px 0px #fff;
                transition: border .2s ease-in-out, box-shadow .2s ease-in-out;
            }

            input[placeholder='Search']:focus
            {
                border-bottom: 1px solid #3ab3f1;
                box-shadow: 0px 2px 0px #3ab3f1;
            }

            input[placeholder='Search']::placeholder { transition: 0.2s color ease-in-out }

            input[placeholder='Search']:focus::placeholder
            {
                color: transparent;
            }

            fieldset
            {
                border-top: 1px solid #ccc;
                margin-top: 20px;

                legend
                {
                    width: auto;
                    text-align: center;
                    padding: 0 10px;
                    color: #777;
                    font-size: 1.3rem;
                }

                table
                {
                    width: 100%;

                    tr
                    {
                        width: 100%;
                        background-color: #ddd;

                        td
                        {
                            text-align: center;

                            span.edit
                            {
                                color: #0f0;
                                font-size: 24px;
                                vertical-align: middle;
                                margin-right: 10px;
                                cursor: pointer;
                            }

                            span.delete
                            {
                                color: #f00;
                                margin-bottom: 0px;
                                display: inline-block;
                                font-size: 20px;
                                vertical-align: inherit;
                                cursor: pointer;
                            }
                        }


                    }


                    tr:nth-of-type(odd)
                    {
                        background-color: #ccc;
                    }

                    tr.tr-head
                    {
                        background-color: #DC143C;
                        color: #fff;
                        font-weight: bolder;
                    }
                }

            }

        }

        

        /* Animations */
        /* Fade animation */
        .fade-enter-active, .fade-leave-active
        {
            transition: opacity .5s ease-in-out;
        } 

        .fade-enter, .fade-leave-to
        {
            opacity: 0;
        }

        .fade-enter-to, .fade-leave
        {
            opacity: 1;
        }

        .spinner-border-sm {
            width: 1.5rem;
            height: 1.5rem;
            border-width: 0.2em;
        }

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
        /* Delete error animation */
        .errorAnimate-enter-active, .errorAnimate-leave-active
        {
            transition: transform 1.1s ease-in-out, opacity 1.1s ease-in-out;
        }

        .errorAnimate-enter, .errorAnimate-leave-to
        {
            transform: translateX(275px);
            opacity: 0;
        }

        .errorAnimate-enter-to, .errorAnimate-leave
        {
            transform: translateX(0px);
            opacity: 1;
        }

    }

</style>

<script>
import AddComponent from './store/AddComponent.vue';
import EditComponent from './store/EditComponent.vue';
export default {

    name: 'Store',
    props:['token', 'url'],
    data: function () {
        return {
            products: [],
            filtered_products: [],
            show_add_component: false,
            show_edit_component: false,
            edit_product_id: null,
            added_product_data: [],
            search: '',
            delete_status: null,
            page_status: null,
            editing_error: false,
        };
    },

    methods: {

        //Rendering data section
        convert_date: function (stamp) {
            let date = new Date(stamp*1000),
                month = parseInt(date.getMonth())+1,
                time = date.getDate() + '/' + month + '/' + date.getFullYear();
            return time;
            
        },

        //Add product section
        get_added_product_data: function (data) {
            this.added_product_data.push(data);
            this.show_add_component = false;
        },

        //Delete section
        Delete_new: function (id) {
            $('#delete' + id).html(`<div class="spinner-border spinner-border-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>`);
            
            axios.post(this.url + '/delete-product', {id})
            .then(res => {


                if (res.data.status === 'success') {
                    var i = 0;
                    for (i; i<=this.added_product_data.length-1; i++){
                        if (this.added_product_data[i].id === id) break;
                    }

                    var after_deleted = this.added_product_data.slice(0,i).concat(this.added_product_data.slice(i + 1));
                    this.added_product_data = after_deleted;
                    
                    this.delete_status = 'success';
                }
                
            })
            .catch(err => {
                this.delete_status = 'failed';
                setTimeout(() => {
                    this.delete_status = null;
                }, 5000);
                
                $('#delete' + id).html(`<i class="fas fa-trash"></i>`);
            });
            
        },

        Delete_old: function (id) {
            $('#delete' + id).html(`<div class="spinner-border spinner-border-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>`);
            
            axios.post(this.url + '/delete-product', {id})
            .then(res => {


                if (res.data.status === 'success') {
                    var i = 0;
                    for (i; i<=this.products.length-1; i++){
                        if (this.products[i].id === id) break;
                    }

                    var after_deleted = this.products.slice(0,i).concat(this.products.slice(i + 1));
                    this.products = after_deleted;
                    this.filtered_products = this.products;
                    
                    this.delete_status = 'success';
                }
                
            })
            .catch(err => {
                this.delete_status = 'failed';
                setTimeout(() => {
                    this.delete_status = null;
                }, 5000);
                
                $('#delete' + id).html(`<i class="fas fa-trash"></i>`);

            });
            
        },

        //edit product section
        edit_product: function (id) {
            this.edit_product_id = id;
            this.show_edit_component = true;
        },

        get_edited_data: function (data) {
            this.show_edit_component = false;
            let product_index = this.products.findIndex(product => product.id === data.product_data.id);
            this.products[product_index] = data.product_data;
            this.products[product_index].created_at = data.created_at;
            this.products[product_index].updated_at = data.updated_at;
        },
        alert_if_error: function() {
            this.show_edit_component = false;
            this.editing_error = true;
            setTimeout(() => {
                this.editing_error = false;
            }, 5000);
        },

        //Search section
        find_product: function () {
            let word = new RegExp(this.search, 'i');
            this.filtered_products = this.products.filter(product => product.name.match(word));
        },

    },

    mounted() {

        var retring;

        this.page_status = 'loading';

        let send_request = () => {
            axios.get(this.url + '/get-products')
            .then(res => {
                clearInterval(retring);
                this.products = res.data;
                this.filtered_products = res.data;
                this.page_status = 'loaded';
            })
            .catch(err => {
                clearInterval(retring);
                this.page_status = 'error';
                retring = setInterval(() => {
                    send_request();
                }, 5000);
            });

        }

        send_request();

    },


    components: {
        AddComponent,
        EditComponent
    },
}
</script>