<template>
    <div class="interface">
        <!-- Upper section contains search and add admin button -->
        <div class="upper-section">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <input type="text" placeholder="Search by name" v-model="search_admin">
                    </div>
                    <div class="col-6 text-right">
                        <div class="btn btn-success" @click="admins.unshift({full_name:'Abo lolo',id:Math.random()});"> <i class="fas fa-plus"></i> Add admin </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="container">
                <transition-group name="slice">
                    <div class="row admin" v-for="admin in admins" :key="admin.id">
                        <div class="col-6">
                            <img :src="admin.photo_link" style="width:40px;height:40px;border-radius:50%;border:1px solid #ff0011;">
                            <span style="margin-left:7px;"> {{userID === admin.id ? admin.full_name + ' (you)' : admin.full_name}} </span>
                        </div>
                        <div class="col-6 text-right">
                            <div class="btn btn-primary" v-if="userID !== admin.id"> <i class="fas fa-user"></i> Set as user </div>
                            <div class="btn btn-danger" @click="delete_account(admin.id)"> <i class="fas fa-trash"></i> Remove </div>
                        </div>
                    </div>
                </transition-group>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

    .upper-section
    {
        input
        {
            height: 100%;
            padding: 0 5px;
            font-size: 15px;
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #999;
            outline: none;
            transition: border linear 0.1s, box-shadow linear 0.1s;
        }
        input::placeholder {transition: color 0.1s linear}
        input:focus::placeholder {color: transparent}
        input:focus
        {
            border-color: #3ab3f1;
            box-shadow: 0px 2px 0px #3ab3f1;
        }
    }

    .body
    {
        margin-top: 30px;

        .admin
        {
            background-color: #fff;
            padding-top: 15px;
            padding-bottom: 15px;
            margin-top: 7px;
            
        }
        .admin:first-of-type {margin-top: 0}
    }
    /* Animations */
    .slice-enter-active, .slice-leave-active {transition: opacity linear 0.4s, transform linear 0.4s}
    .slice-enter, .slice-leave-to {opacity: 0; transform: translateX(50px)}
    .slice-enter-to, .slice-leave {opacity: 1; transform: translateX(0)}
</style>

<script>
export default {
    name: 'AdminsInterface',
    props: [
        //user data
        'admins',
        'members',
        'userID',
        'UI_messages', //Alerts, loading and errors messages.
        //functions
        'delete_account',
        ],
    data: function () {
        return {
            //search section
            search_admin: '',
        }
    }, // end of data
    methods: {
        
    } //End of methods

}
</script>