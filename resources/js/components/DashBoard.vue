<template>
    <div class="dashboard">
        <Routes :username="user.full_name" :userID="user.id"></Routes>
        <div class="dashboard-settings">
            <Navbar :username="user.full_name" :userID="user.id"></Navbar>
            <router-view :token="token" :url="url"></router-view>
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
            };
        },

        mounted() {
            this.user = JSON.parse( this.user_json );
        },

        components: {
            Routes,
            Navbar
        },
    }
</script>
