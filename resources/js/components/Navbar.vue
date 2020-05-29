<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'tasks' }">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'Admin' }">Admin</router-link>
                </li>
                <div v-if="!app.user">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'Login' }">Login</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'Register' }">Register</router-link>
                    </li>
                </div>
                <div v-else>
                    <li class="nav-item">
                        <a href="#" class="nav-link" @click="logout">Logout ( {{ app.user.name }} )</a>
                    </li>
                </div>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "navbar",
        props: ['app'],
        methods: {
            logout(){
                this.app.req.post('api/auth/logout', {token: this.$parent.$data.token}).then(() => {
                    this.app.user = null;
                    localStorage.removeItem('auth');
                    alert("Logging out");
                    this.$router.push('/login');
                }).catch(error => console.log(error.response.data.error));
            }
        }
    }
</script>

<style scoped>

</style>
