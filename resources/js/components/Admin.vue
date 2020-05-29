<template>
    <div class="card">
        <div class="card-header">Admin</div>
        <div class="card-body">
            <form @submit.prevent="addUser" class="mb-4">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" class="form-control" placeholder="name" v-model="user.name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="email" v-model="user.email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" class="form-control" placeholder="password" v-model="user.password">
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="form-control" id="type" v-model="user.type" required>
                        <option disabled>Select user type</option>
                        <option v-bind:value="0">User</option>
                        <option v-bind:value="1">Admin</option>
                    </select>
                </div>
                <button class="btn btn-info btn-block" type="submit">Save</button>
            </form>
            <ul class="list-group">
                <li class="list-group-item" v-for="user in users" v-bind:key="user.id">
                    {{user.name}} :: <span v-if="user.type == 0">User</span>
                    <span v-else-if="user.type == 1">Admin</span>
                    <button @click="deleteUser(user.id)" class="btn btn-danger">Delete</button>
                    <button @click="editUser(user)" class="btn btn-info">Edit</button>
                </li>
            </ul>
            <nav aria-label="Users navigation">
                <ul class="pagination">
                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                        <a class="page-link" href="#" @click="fetchUsers(pagination.prev_page_url)">Previous</a>
                    </li>

                    <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page}} of {{pagination.last_page}}</a></li>

                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                        <a class="page-link" href="#" @click="fetchUsers(pagination.next_page_url)">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['csrf'],
        data(){
            return{
                users:[],
                user: {
                    id: '',
                    name: '',
                    email: '',
                    password: ''
                },
                pagination: {},
                edit: false
            }
        },
        created() {
            this.fetchUsers();
        },

        methods:{
            fetchUsers(page){
                const info = {
                    token: this.$parent.$data.token
                };
                this.$parent.req.post('api/users', info).then(response => {
                    this.users = response.data.data;
                    this.makePagination(response.data.meta, response.data.links);
                }).catch(error => {
                    console.error(error.response.data.error);
                });
            },
            makePagination(meta, links){
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                }

                this.pagination = pagination;
            },
            deleteUser(id) {
                if(confirm('Are you sure?')){
                    this.$parent.req.delete(`/api/user/${id}`, {params: {token: this.$parent.$data.token}}).then(response => {
                        alert('User removed');
                        this.fetchUsers();
                    }).catch(error => {
                        console.error(error.response.data.error);
                    });
                }
            },
            addUser(){
                let error = 0;
                if(this.edit === false){
                    // Add
                    fetch(`api/user`,{
                        method: 'POST',
                        body: JSON.stringify({
                            token: this.$parent.$data.token,
                            id: this.user.id,
                            name: this.user.name,
                            password: this.user.password,
                            email: this.user.email
                        }),
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then(res =>
                    {
                        if(res.status !== 201){
                            res.json().then(function(parsedJson) {
                                console.log(res.status);
                                console.log(parsedJson);
                                error = 1;
                                return error;
                            })
                        }
                        return res.json();
                    }).then(data => {
                        if(error == 0) {
                            this.user.name = '';
                            this.user.email = '';
                            this.user.password = '';
                            alert('User added');
                            this.fetchUsers();
                        }
                    })
                        .catch(error => console.log(error))
                }else{
                    //Update
                    fetch(`api/user`,{
                        method: 'PUT',
                        body: JSON.stringify({
                            token: this.$parent.$data.token,
                            id: this.user.id,
                            name: this.user.name,
                            password: this.user.password,
                            email: this.user.email
                        }),
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then(res =>
                    {
                        if(res.status != 200){
                            res.json().then(function(parsedJson) {
                                console.log('This is the parsed json', parsedJson);
                                error = 1;
                                return error;
                            })
                        }
                        return res.json();
                    }).then(data => {
                        if(error == 0) {
                            this.user.name = '';
                            this.user.email = '';
                            this.user.password = '';
                            alert('User updated');
                            this.fetchUsers();
                        }
                    }).catch(error => console.error(error))

                }
            },
            editUser(user){
                this.edit = true;
                this.user.id = user.id;
                this.user.email = user.email;
                this.user.name = user.name;
                this.user.type = user.type;
            }
        }
    }
</script>

<style scoped>

</style>
