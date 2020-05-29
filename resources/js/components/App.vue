<template>
    <div>
        <navbar :app="this"></navbar>
        <spinner v-if="loading"></spinner>
        <div v-else-if="initiated">
            <router-view :app="this"/>
        </div>
    </div>
</template>

<script>
    import Navbar from "./Navbar";
    import axios from 'axios'
    export default {
        name: 'app',
        components: {
            Navbar
        },
        data() {
            return {
                user: null,
                loading: false,
                initiated: false,
                token: '',
                req: axios.create({
                    baseUrl: '/',
                })
            }
        },
        mounted() {
          //Patikrinam ar vartotojas buvo prisijungęs
          if(localStorage.getItem('auth')){
              this.token = localStorage.getItem('auth');
          }
          this.init();
        },
        methods: {
            init(){
                this.loading = true;
                if(this.token !== '') {
                    console.log("Starting relogging");
                    this.req.post('api/auth/me', {
                        token: this.token
                    }).then(response => {
                        console.log("Relog completed");
                        this.user = response.data.user;
                        this.$parent.$data.user = response.data.user;
                        this.$parent.$data.token = this.token;
                        this.$f.data().user = response.data.user;
                        this.loading = false;
                        this.initiated = true;
                    }).catch(error => console.log(error.response.data.error));
                }else{
                    this.loading = false;
                    this.initiated = true;
                }
            }
        }
    }
</script>

<style scoped>

</style>
