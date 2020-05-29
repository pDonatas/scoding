import axios from 'axios'
export const f = {
    data() {
        return {
            user: null,
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
    methods:{
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
