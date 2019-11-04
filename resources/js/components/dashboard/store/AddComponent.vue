<template>
    <div class="add-product">
        <transition name="err-animate">
            <div v-if="add_status" class="send-error" @click="add_status = null"> <i class="fas fa-exclamation-triangle"></i> Oops, Can't connect. </div>
        </transition>
        <transition name="frm-animate">
            <form @submit.prevent="e => send_data(e)" v-if="!add_status">
                <h2 class="text-center"> Add product </h2>
                <div><input type="text" name="name" placeholder="Name" required/></div>
                <div><input type="text" name="company" placeholder="Company" required/></div>
                <div><input type="number" name="price" placeholder="Price" required/></div>
                <div class="gender">
                    <span class="label"> Gender: </span>
                    <span>
                        <span><input type="radio" name='gender' value="male" id='male' v-model="gender" /> <label for="male"> Male </label></span>
                        <span><input type="radio" name='gender' value="female" id='female' v-model="gender" /> <label for="female"> Female </label></span>
                        <span><input type="radio" name='gender' value="all" id='all' v-model="gender" /> <label for="all"> All </label></span>
                    </span>
                </div>
                <div class="colors-header">
                    <span>Colors:</span>
                    <span>
                        <button @click="add_color" type="button" class="btn btn-success" v-if="colors_count.length !== 5"> <i class="fas fa-plus"></i> </button>
                        <button @click="remove_color" class="btn btn-danger" type="button" v-if="colors_count.length !== 1"> <i class="fas fa-trash"></i> </button>
                    </span>
                </div> 
                <div v-for="color in colors_count" :key="color.color" class="colors">
                    <fieldset> <legend> Color {{color.color}} </legend> </fieldset>
                    <input type="text" name="colors[]" :placeholder="'color ' + color.color" required>
                    <input type="number" :name="'color' + color.color + '_count[]'" :value="color.count" disabled>
                    <div class="sizes"> Sizes: </div>
                    <div v-for="size in color.sizes" :key="size">
                        <input type="text" :name="'color' + color.color + '_' + 'sizes[]'" placeholder="Size" required>
                        <input type="number" :name="'color' + color.color + '_' + 'size' + size + '_count[]'" placeholder="Count" :class="'for-color-' + color.color.toString()" value='0' @input="e => calc_total_count(color.color, e)" required>
                        <button @click="add_size(color.color)" class='btn btn-success' type="button" v-if="size === color.sizes[color.sizes.length - 1]"> <i class="fas fa-plus"></i> </button>
                        <button @click="remove_size(color.color)" class="btn btn-danger" type="button" v-if="size === color.sizes[color.sizes.length - 1] && size !== 1"> <i class="fas fa-trash"></i> </button>
                    </div>
                </div>
                <div class="text-center"><button type="submit" class="btn btn-success" name="submit"> <i class="fas fa-plus"></i> Add product </button></div>
            </form>
        </transition>
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

    .add-product
    {
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        .send-error
        {
            background: #fff;
            padding: 10px;
            font-size: 30px;
            color: #f00;
            border-radius: 20px;
            position: absolute;
        }
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
            }
            
            div.gender
            {
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

            div.colors-header, div.gender
            {
                display: flex;
                align-items: center;
                justify-content: space-between;
            
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

            div.colors
            {
                input[type="text"], input[type="number"]
                {
                    width: calc(50% - 50px);
                    margin: 0;
                    box-sizing: border-box;
                }
                .btn
                {
                    width: 43px;
                }
            }
        }

        /* Animations */
        
        .frm-animate-enter-active, .frm-animate-leave-active
        {
            transition: 1s transform ease-in-out;
        }

        .frm-animate-enter, .frm-animate-leave-to
        {
            transform: translateY(1000px);
        }
        .frm-animate-enter-to, .frm-animate-leave
        {
            transform: translateY(0px);
        }
    }

    

</style>

<script>
export default {
    name: 'AddComponent',
    props: ['token', 'url', 'get_added_product_data'],
    data: function () {
        return {
            colors_count: [{
                color: 1,
                sizes: [1],
                count: 0
            }],

            gender: '',
            add_status: null,
        };
    },

    methods: {
        add_color: function () {
            this.colors_count.push({
                color: this.colors_count[this.colors_count.length - 1].color + 1,
                sizes: [1],
                count: 0
            });
        },
        remove_color: function () {
            this.colors_count.pop();
        },
        add_size: function (color) {
            let color_index = color - 1;
            this.colors_count[color_index].sizes.push( this.colors_count[color_index].sizes[this.colors_count[color_index].sizes.length - 1] + 1 );
        },
        remove_size: function (color) {
            let color_index = color - 1;
            let deleted_size = this.colors_count[color_index].sizes.pop();
            let deleted_size_count = document.querySelector('form input[name="color' + color + '_' + 'size' + deleted_size + '_count[]"]').value;
            this.colors_count[color_index].count -= parseInt(deleted_size_count);
        },

        send_data: function (e) {
            if (this.gender === '') {
                document.querySelector('.gender').style.color = '#f00';
                document.querySelector('.gender span.label').innerText = '* Gender';
                return;
            }
            e.target.submit.innerHTML = `
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        `;
            e.target.submit.setAttribute('disabled', 'disabled');
            let colors = document.querySelectorAll('form input[name="colors[]"]'),
                counter = 0,
                data = [],
                all_details = {
                    id: null,
                    name: e.target.name.value,
                    company: e.target.company.value,
                    price: e.target.price.value,
                    for: this.gender,
                    colorsAndSizes: data
                };
            colors.forEach(color => {
                counter++ ;
                let color_data = {
                    color: color.value,
                    count: document.querySelector('form input[name="color' + counter + '_count[]"]').value,
                    sizes: []
                };
                let sizes = document.querySelectorAll('form input[name="color' + counter + '_' + 'sizes[]"]');
                let size_counter = 0;
                sizes.forEach(size => {
                    size_counter++ ;
                    let size_obj = {
                        size: size.value,
                        count: document.querySelector('form input[name="color' + counter + '_' + 'size' + size_counter + '_count[]"]').value,
                    };
                    color_data.sizes.push(size_obj);
                });
                data.push(color_data);
            });

            

            axios.post(this.url + '/addproduct', all_details)
            .then(response => {
                all_details.id = response.data.product_id;
                all_details.count = response.data.product_count;
                this.get_added_product_data(all_details);
            })
            .catch(err => {
                e.target.submit.innerHTML = `<i class="fas fa-plus"></i> Add product`;
                e.target.submit.removeAttribute('disabled');
                this.add_status = 'failed';
                setTimeout(() => {
                    this.add_status = null;
                }, 10000);
            })

        },

        calc_total_count: function (color_id, e) {
            e.target.value === '' ? e.target.value = 0 : '';
            e.target.value[0] == 0 && e.target.value.length > 1 ? e.target.value = e.target.value.slice(1) : '';
            let sizes_counts = document.querySelectorAll('.for-color-' + color_id.toString());
            let total_color_count = 0;
            sizes_counts.forEach(count => total_color_count += parseInt(count.value));
            let color_index = this.colors_count.findIndex(color => color.color === color_id);
            this.colors_count[color_index].count = parseInt(total_color_count);
        },
    },
}
</script>