<template>
    <div class="routes">
        <h2 class="text-center"> Double <span>Daby</span> </h2>
        <div class="user">
            <img :src="user_img" alt="user photo"> <span> {{ username }} </span>
        </div>
        <div class="divider"></div>
        <div class="links">
            <div> <router-link tag="a" to="/dashboard/admins"> <i class="fas fa-user-lock"></i> Admins </router-link> </div>
            <div> <router-link tag="a" to="/dashboard/members"> <i class="fas fa-user"></i> Members </router-link> </div>
            <div> <router-link tag="a" to="/dashboard/visitors"> <i class="fas fa-users"></i> Visitors </router-link> </div>
            <div> <router-link tag="a" to="/dashboard/posts"> <i class="fas fa-clipboard"></i> Posts </router-link> </div>
            <div> <router-link tag="a" to="/dashboard/store"> <i class="fas fa-store"></i> Store </router-link> </div>
            <div> <router-link tag="a" to="/dashboard/sales"> <i class="fas fa-dollar-sign"></i> Sales </router-link> </div>
            <div> <router-link tag="a" to="/dashboard/account-setting"> <i class="fas fa-user-cog"></i> Account settings </router-link> </div>
            <div> <router-link tag="a" to="/dashboard/site-settings"> <i class="fas fa-sliders-h"></i> Site settings </router-link> </div>
        </div>
        <div class="divider"></div>
        <div class="links">
            <div @click="reload()"> <router-link tag="a" to="/" exact> <i class="fas fa-home"></i> Home Page </router-link> </div>
            <div class="text-center" @click="reload()"> <router-link tag="a" to="/logout" class="btn btn-danger"> <i class="fas fa-power-off"></i> Logout </router-link> </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    
    .routes::-webkit-scrollbar
    {
        background-color: #2a4054;
        width: 5px;
    }

    .routes::-webkit-scrollbar-thumb
    {
        width: 100%;
        background-color: #ff0011;
        border-radius: 10px;
    }

    .routes
    {
        max-width: 100%;
        background-color: #2a4054;
        padding-right: 50px;
        padding-left: 10px;
        overflow-y: auto;
        
        h2
        {
            margin-top: 15px;
            color: #3ab3f1;

            span
            {
                color: #ff0011;
            }
        }

        .user
        {
            max-width: 100%;
            padding-top: 30px;
            padding-bottom: 30px;

            img
            {
                width: 55px;
                height: 55px;
                border-radius: 50%;
                border: 3px solid #3ab3f1;
                margin-right: 5px;
            }

            span
            {
                color: #ddd;
                
            }
        }

        .divider
        {
            border-top: 1px solid #777;
        }

        .links
        {
            padding-top: 20px;
            padding-bottom: 20px;
            padding-left: 10px;
            padding-right: 10px;

            a
            {
                color: #eee;
                display: block;
                padding-top: 10px;
                padding-bottom: 10px;
                transition: color 0.3s ease-in-out;
                text-decoration: none;
            }

            a:hover
            {
                color: #3ab3f1;
            }

            a.btn
            {
                display: inline-block;
                margin-left: 20px;
                margin-top: 30px;
                width: 150px;
                position: relative;
                bottom: 0;
                color: #eee;
            }

            a.router-link-active {
                color: #ff0011;
            }
        }

    }
</style>

<script>
export default {
    name: 'Routes',
    props: ['username','userID'],
    data: function () {
        return {
            user_img: 'https://scontent-hbe1-1.xx.fbcdn.net/v/t1.0-9/67280996_2563554197011524_1230062866749456384_n.jpg?_nc_cat=102&_nc_oc=AQm7u6XOUMVqTqDsxPZ4kipIoWLTBbsQ6V8zIJfW8loeXhuSJV2glq4A7MXcg5jVasU&_nc_ht=scontent-hbe1-1.xx&oh=8047ff84f4e95ac738123191ead07012&oe=5E2B8CF6'
        };
    },

    methods: {
        reload: function () {
            window.location.reload();
        }
    },
}
</script>