<template>
    <div id="admins">
        <transition name="fade">
            <AdminsInterface 
                v-if="admins_loaded && !admins_loading_error"
                :admins="admins"
                :members="members"
                :userID="userID"
                :delete_account="delete_account"
                :UI_messages="UI_messages"
            >
            </AdminsInterface>
            <div v-if="!admins_loaded && !admins_loading_error" class="loading-screen">
                <div class="sk-chase">
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                </div>
            </div>
            <div class="error-screen" v-if="admins_loading_error">
                <p>  <i class="fas fa-exclamation-triangle"></i> Can’t connect right now </p>
            </div>
        </transition>
    </div>
</template>

<style lang="scss">
#admins
{
    height: calc(100% - 64px);
    background-color: #eee;
    padding: 20px 0 0 0;
    overflow: hidden;
    overflow-y: auto;
    .btn {cursor: pointer}

    .loading-screen
    {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .error-screen
    {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #ff0011;
        font-size: 25px;
    }
    
    /* Animations */
    //vue transitions animations
    .fade-enter-active, .fade-leave-active {transition: opacity ease .5s, transform ease .5s}
    .fade-enter, .fade-leave-to {opacity: 0; transform: scale(0.9)}
    .fade-enter-to, .fade-leave {opacity: 1;  transition-delay: .5s; transform: scale(1)}

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
import AdminsInterface from './admins/interface.vue';
export default {
    name: 'Admins',
    props: ['url', 'no_connection','userID'],
    data: function () {
        return {
            admins_loaded: false,
            admins_loading_error: false,
            admins: [],
            members: [],
            UI_messages: [],
        }
    },//end of data
    
    methods: {
        get_admins_data: function () {
            axios.get(`${this.url}/users/get-admins`)
            .then(response => {
                this.admins = response.data;
                this.admins_loaded = true;
            })
            .catch(err => this.admins_loading_error = true); //End of axios
        }, //End of get_admins_data
        delete_account: function (user_id) {
            if (user_id === this.userID) {
                this.UI_messages.unshift({msg: 'You can\'t delete yourself', type:'error'});
                setTimeout(() => {
                    this.UI_messages.findIndex(msg => msg.type === 'error') != -1 ? this.UI_messages.splice(this.UI_messages.findIndex(msg => msg.type === 'error'), 1) : '';
                }, 5000);
            } else {
                this.UI_messages.unshift({msg: 'Deleting', type:'loading'});
                axios.post(`${this.url}/users/delete-admin`, {id: user_id})
                .then(response => {
                    this.get_admins_data();
                    this.UI_messages[this.UI_messages.findIndex(msg => msg.type === 'loading')].msg = 'Deleted';
                    setTimeout(() => this.UI_messages.splice(this.UI_messages.findIndex(msg => msg.type === 'loading'), 1), 5000);
                })
                .catch(error => {
                    this.UI_messages.unshift({msg: 'Server error', type: 'error'});
                    setTimeout(() => this.UI_messages.splice(this.UI_messages.findIndex(msg => msg.msg === 'Server error'), 1), 5000)
                });
            } //End of if/else
        }, //End of delete_account method
    }, //end of methods
    watch: {
        no_connection: function (value) {
            if (value) {
                this.admins_loading_error = true;
            } else {
                this.admins_loading_error = false;
                this.get_admins_data();
            }
        },//End of no_connection
    }, //End of watch
    
    mounted () {
        this.get_admins_data();

    }, //end of mounted
    components: {
        AdminsInterface
    },//end of components
}
</script>