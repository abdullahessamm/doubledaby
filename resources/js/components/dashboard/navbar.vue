<template>
    <div class="navbar">
        <div class="nav-container">
            <span class="user" @mouseenter.self="user_menu = true" @mouseleave.self="user_menu = false">
                <i class="fas fa-angle-down"></i>
                <span>{{ username }}</span>
                <img src="https://scontent-hbe1-1.xx.fbcdn.net/v/t1.0-9/67280996_2563554197011524_1230062866749456384_n.jpg?_nc_cat=102&_nc_oc=AQm7u6XOUMVqTqDsxPZ4kipIoWLTBbsQ6V8zIJfW8loeXhuSJV2glq4A7MXcg5jVasU&_nc_ht=scontent-hbe1-1.xx&oh=8047ff84f4e95ac738123191ead07012&oe=5E2B8CF6" :alt="username">
                <transition name="slideDown">
                    <div v-if="user_menu" class="menu-container">
                        <div @click="close_menu()"> <router-link tag="a" to="/dashboard/posts"> Posts <i class="fas fa-clipboard"></i> </router-link> </div>
                        <div @click="close_menu()"> <router-link tag="a" to="/dashboard/account-setting"> Account settings <i class="fas fa-user-cog"></i> </router-link> </div>
                        <div @click="close_menu()"> <router-link tag="a" to="/dashboard/site-settings"> Site settings <i class="fas fa-sliders-h"></i> </router-link> </div>
                        <div @click="close_menu()"> <router-link tag="a" to="/dashboard/store"> Store <i class="fas fa-store"></i> </router-link> </div>
                        <div class="divider"></div>
                        <div @click="reload()"> <router-link tag="a" to="/logout" class="button"> Logout <i class="fas fa-power-off"></i> </router-link> </div>
                    </div>
                </transition>
            </span>

            <span class="orders" @mouseenter.self="order_menu = true" @mouseleave.self="order_menu = false">
                <i class="fas fa-flag"></i>
                <transition name="slideDown">
                    <div v-if="order_menu" class="menu-container">
                        <!-- orders here -->
                    </div>
                </transition>
            </span>

            <span class="messages" @mouseenter.self="messages_menu = true" @mouseleave.self="messages_menu = false">
                <i class="fas fa-comment"></i>
                <transition name="slideDown">
                    <div v-if="messages_menu" class="menu-container">
                        <!-- Messages here -->
                    </div>
                </transition>
            </span>
            <span class="noti" @mouseenter.self="notifications_menu = true" @mouseleave.self="notifications_menu = false">
                <i class="fas fa-bell"></i>
                <transition name="slideDown">
                    <div v-if="notifications_menu" class="menu-container">
                        <!-- notifications here -->
                    </div>
                </transition>
            </span>
        </div>
    </div>
</template>

<style lang="scss" scoped>

    .navbar
    {
        direction: rtl;
        border-bottom: 1px solid #ccc;
        padding: 0;
        .nav-container { display: flex; }
    }

    span
    {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        position: relative;
        margin-right: 15px;
        color: #3ab3f1;
        padding-top: 10px;
        padding-bottom: 10px;
        cursor: pointer;
    }

    span.user
    {

        color: #aaa;
        margin-left: 20px;
        position: relative;

        img
        {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
        }
        span
        {
            margin-left: 10px;
            margin-right: 5px;
            color: #aaa;
        }

    }

    span.orders
    {
        cursor: pointer;

        .menu-container
        {
            width: 200px;
        }
    }

    span.orders:hover, span.messages:hover, span.noti:hover, span.user:hover
    {
        color: #ff0011;
    }

    .fas
    {
        font-size: 22px;
    }
    
    .menu-container
        {
            background-color: #2a4054;
            z-index: 999;
            color: #eee;
            padding: 15px;
            position: absolute;
            top: 55px;
            border-radius: 10px;
            cursor: default;

            div
            {
                
                i
                {
                    font-size: 10px;
                }
                
                a
                {
                    display: block;
                    color: #fff;
                    text-decoration: none;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    transition: color 0.3s ease;
                }

                a:hover
                {
                    color: #3ab3e7;
                }
                
                a.router-link-active
                {
                    color: #ff0011;
                }


            }

            .divider
            {
                border-top: 1px solid #777;
            }
            
        }

    .slideDown-enter-active, .slideDown-leave-active
    {
        transition: .5s all ease;
    }

    .slideDown-enter-to, .slideDown-leave
    {
        transform: scale(1);
        opacity: 1;
    }

    .slideDown-leave-to, .slideDown-enter
    {
        transform: scale(0.9);
        opacity: 0;
    }

</style>

<script>
export default {
    name: 'Navbar',
    props: ['username', 'userID'],
    data: function () {
        return {
            user_menu:          false,
            order_menu:         false,
            messages_menu:      false,
            notifications_menu: false,
        };
    },
    methods: {
        reload: function () {
            window.location.reload();
        },

        close_menu: function () {
            this.user_menu = false;
        },
    },
}
</script>