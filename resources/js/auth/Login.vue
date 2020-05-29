<template>
    <div class="card">
        <div class="card-header">
            Login
        </div>
        <div class="card-body">
            <form v-on:submit.prevent="Register">
                <input type="hidden" :value="csrfToken" name="_token"/>
                <div class="alert alert-danger" v-if="this.errors.length">
                    <ul class="mb-0">
                        <li v-for="(error, index) in this.errors" :key="index">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                    <div class="col-md-6">
                        <input id="email" type="email" v-model="email" class="form-control" name="email" required autocomplete="email">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" v-model="password" class="form-control" name="password" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",
        props: ['app'],
        data() {
            return {
                email: '',
                password: '',
                csrfToken: $('meta[name="csrf-token"]').attr('content'),
                errors: []
            }
        },
        methods: {
            Register() {
                this.errors = [];

                if(!this.errors.length){
                    const data = {
                        email: this.email,
                        password: this.password
                    }

                    this.app.req.post('api/auth/login', data).then(response => {
                        this.app.user = response.data.user;
                        localStorage.setItem('auth', response.data.token); // Išsaugom token'ą
                        this.$parent.$data.user = response.data.user;
                        this.$parent.$data.token = response.data.token;
                        this.$router.go('/home');
                    }).catch(error => {
                        this.errors.push(error.response.data.error);
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>
