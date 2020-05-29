<template>
<div class="card">
    <div class="card-header">
        Register
    </div>
    <div class="card-body">
        <form v-on:submit.prevent="Register">
            <div class="alert alert-danger" v-if="this.errors.length">
                <ul class="mb-0">
                    <li v-for="(error, index) in this.errors" :key="index">
                        {{ error }}
                    </li>
                </ul>
            </div>
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                <div class="col-md-6">
                    <input id="name" type="text" v-model="name" class="form-control" name="name"required autocomplete="name" autofocus>
                </div>
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

            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm password</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" v-model="passwordConfirm" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Register
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    export default {
        name: "Register",
        props: ['app'],
        data() {
            return {
                name: '',
                email: '',
                password: '',
                passwordConfirm: '',
                errors: []
            }
        },
        methods: {
            Register() {
                this.errors = [];

                if(this.password !== this.passwordConfirm){
                    this.errors.push("Passwords does not match");
                }

                if(!this.errors.length){
                    const data = {
                        email: this.email,
                        name: this.name,
                        password: this.password
                    }

                    this.app.req.post('api/auth/register', data).then(response => {
                        this.$router.push('/lgoin');
                    }).catch(error => {
                       this.errors.push(error.response.data);
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>
