<template>
    <div class="dashboard">
        <Routes :username="user.full_name" :userID="user.id"></Routes>
        <div class="dashboard-settings">
            <Navbar :username="user.full_name" :userID="user.id"></Navbar>
            <router-view :token="token" :url="url" :no_connection="no_connection" :userID="user.id"></router-view>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .dashboard
    {
        display: flex;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        overflow: hidden;
    }

    .dashboard-settings
    {
        flex-grow: 1;
        overflow-y: auto;
    }
</style>

<script>

    import Routes from './dashboard/routes.vue';
    import Navbar from './dashboard/navbar.vue';

    export default {
        name: 'DashBoard',

        props: ['token', 'user_json', 'url'],

        data: function() {
            return {
                counter: 0,
                user: {},
                no_connection: false,
            };
        },

        mounted() {
            this.user = JSON.parse( this.user_json );
            let check_connection;
            let check_the_connection = () => {
                check_connection = setInterval(() => {
                    axios.post(this.url + '/check-connection')
                    .then(response => {
                        this.no_connection = false;
                        $('input').removeAttr('disabled');
                    })
                    .catch(err => {
                        this.no_connection = true;
                        $('input').attr('disabled', 'disabled');
                    });
                }, 5000);
            }
            check_the_connection();
        },

        components: {
            Routes,
            Navbar
        },
    }
</script>
