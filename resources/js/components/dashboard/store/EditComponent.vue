<template>
    <div class="edit">
        <div class="sk-chase" v-if="page_status === 'loading'">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>
        <form @submit.prevent="e => saving_data(e)" v-if="page_status === 'loaded'">
            <h2 class="text-center"> {{ product_data.name }} </h2>
            <div> <input type="text" name="name" v-model="product_data.name" required> </div>
            <div> <input type="text" name="company" v-model="product_data.company" required> </div>
            <div> <input type="number" name="price" v-model="product_data.price" required> </div>
            <div class="gender">
                <span>Gender</span>
                <span>
                    <span><input type="radio" name="for" value="male" id="male" v-model="product_data.gender" required> <label for="male"> Male </label></span>
                    <span><input type="radio" name="for" value="female" id="female" v-model="product_data.gender" required> <label for="female"> Female </label></span>
                    <span><input type="radio" name="for" value="all" id="all" v-model="product_data.gender" required> <label for="all"> All </label></span>
                </span>
            </div>
            <div class="colors-header">
                <span>Colors:</span>
                <span>
                    <button @click="add_color" type="button" class="btn btn-success" v-if="product_data.colors.length !== 5"> <i class="fas fa-plus"></i> </button>
                    <button @click="remove_color" class="btn btn-danger" type="button" v-if="product_data.colors.length !== 1"> <i class="fas fa-trash"></i> </button>
                </span>
            </div>
            <div class="colors" v-for="color in product_data.colors" :key="color.id">
                
                <fieldset> <legend> {{color.color}} </legend> </fieldset>
                <div>
                    <input type="text" v-model="color.color" required>
                    <input type="number" v-model="color.count" disabled required>
                </div>

                <div> Sizes: </div>

                <div class="sizes" v-for="size in color.sizes" :key="size.id">
                    <input type="number" v-model="size.size" required>
                    <input type="number" v-model="size.count" @input="calc_total_color_count(color.id)" required>
                    <button @click="add_size(color.id)" type="button" class="btn btn-success" v-if="color.sizes[color.sizes.length - 1] === size"> <i class="fas fa-plus"></i> </button>
                    <button @click="remove_size(color.id)" class="btn btn-danger" type="button" v-if="color.sizes.length !== 1 && color.sizes[color.sizes.length - 1] === size"> <i class="fas fa-trash"></i> </button>
                </div>

            </div>

            <div class="text-center"> <button class="btn btn-success" type="submit" name="submit"> <i class="fa fa-save"></i> Save </button> </div>

        </form>
    </div>
</template>


<style lang="scss" scoped>

*::-webkit-scrollbar
    {
        background-color: transparent;
        width: 5px;
    }

    *::-webkit-scrollbar-track
    {
        border-radius: 50%;
    }

    *::-webkit-scrollbar-thumb
    {
        width: 100%;
        background-color: #ff0011;
        border-radius: 10px;
    }

    .edit
    {
        form
        {
            background-color: #fff;
            width: 550px;
            height: auto;
            padding: 40px 60px;
            max-height: 500px;
            overflow-y: auto;
            overflow-x: hidden;

            h2
            {
                color: #888;

            }

            div
            {
                margin-top: 10px;
                input[type="text"], input[type="number"]
                {
                    width: 100%;
                    height: 37px;
                }
                i {margin-right: 5px}
            }

            div.gender, div.colors-header
            {
                display: flex;
                justify-content: space-between;
            }
            div.gender {
                margin-bottom: 20px;
                input[type="radio"]
                {
                    display: none;
                }

                label
                {
                    margin-left: 20px;
                    position: relative;
                    padding-left: 25px;
                    cursor: pointer;
                }
                label::before
                {
                    content: '';
                    display: inline-block;
                    border: 1px solid #3ab3f1;
                    width: 17px;
                    height: 17px;
                    border-radius: 50%;
                    position: absolute;
                    top:5px;
                    left: 0;
                    box-shadow: 0px 0px 0px 0px #3ab3f1;
                    transition: box-shadow .3s linear;
                }
                label::after
                {
                    content: '';
                    display: inline-block;
                    background-color: #3ab3f1;
                    width: 11px;
                    height: 11px;
                    border-radius: 50%;
                    position: absolute;
                    top:8px;
                    left: 3px;
                    opacity: 0;
                    transition: opacity .3s linear;
                }
                input:checked ~ label::after {opacity: 1;}
                input:checked ~ label::before {box-shadow: 0px 0px 10px 3px #3ab3f1;}
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
            }

            div.colors, div.sizes
            {
                input[type="text"], input[type="number"]
                {
                    width: calc(50% - 60px);
                    margin: 0;
                    box-sizing: border-box;
                }
                .btn
                {
                    width: 43px;
                }
            }
        }
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
            background-color: #fff;
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

</style>


<script>
export default {
    name: 'Edit',
    props: ['id', 'url', 'get_edited_data', 'alert_if_error'],

    data: function() {
        return {
            product_data: {
                colors: []
            },
            deleted_colors: [],
            deleted_sizes: [],
            page_status: null,
        };
    },

    methods: {
        add_color: function () {
            let last_color_id = this.product_data.colors[this.product_data.colors.length - 1].id + 1;
            let color_data = {
                id: last_color_id,
                new: true,
                color: 'New color',
                count: 0,
                sizes: [
                    {
                        size: '',
                        count: 0,
                        id: 1,
                        new: true,
                    },
                ],
            };
            this.product_data.colors.push(color_data);
        },
        remove_color: function () {
            let removed_color = this.product_data.colors.pop();
            !removed_color.new ? this.deleted_colors.push(removed_color.id) : '';
        },
        add_size: function (color_id) {
            let color_index  = this.product_data.colors.findIndex(color => color.id === color_id),
                last_size_id = this.product_data.colors[color_index].sizes[this.product_data.colors[color_index].sizes.length - 1].id;
            this.product_data.colors[color_index].sizes.push({
                color_id,
                id: last_size_id + 1,
                size: '',
                count: 0,
                new: true,
            });
        },
        remove_size: function (color_id) {
            let color_index = this.product_data.colors.findIndex(color => color.id === color_id);
            let removed_size = this.product_data.colors[color_index].sizes.pop();
            !removed_size.new ? this.deleted_sizes.push(removed_size.id) : '';
            this.product_data.colors[color_index].count -= removed_size.count;
        },

        calc_total_color_count: function (color_id)
        {
            let color_index = this.product_data.colors.findIndex(color => color.id === color_id);
            let color_sizes = this.product_data.colors[color_index].sizes;
            let total_count = 0;
            color_sizes.forEach(size => {
                total_count += parseInt(size.count);
            });
            this.product_data.colors[color_index].count = total_count;
        },

        saving_data: function (e) {
            e.target.submit.innerHTML = `
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        `;
            e.target.submit.setAttribute('disabled', 'disabled');
            this.product_data.id = this.id;
            this.product_data.deleted_colors = this.deleted_colors;
            this.product_data.deleted_sizes = this.deleted_sizes;
            axios.post(`${this.url}/edit-product`, this.product_data)
            .then(res => {
                this.get_edited_data(res.data);
            })
            .catch(err => this.alert_if_error());
        },
    },

    created() {
        this.page_status = 'loading';
        axios.get(`${this.url}/get-product-by-id/${this.id}`)
        .then(res => {
            this.product_data = res.data;
            this.page_status = 'loaded';
        })
        .catch(err => {
            this.alert_if_error();
        });
    },

}
</script>