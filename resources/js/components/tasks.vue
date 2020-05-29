<template>
<div class="card">
    <div class="card-header">Tasks</div>
    <div class="card-body">
        <form @submit.prevent="addTask" class="mb-4">
            <div class="form-group">
                <label for="task">Task</label>
                <input :disabled="this.$parent.$data.user.type !== 1" id="task" class="form-control" placeholder="Task" v-model="task.task" required>
            </div>

            <div class="form-group">
                <label for="user">User</label>
                <select :disabled="this.$parent.$data.user.type !== 1" class="form-control" id="user" v-model="task.user" required>
                    <option disabled>Select user task is for</option>
                    <option v-for="u in users" v-bind:value="u.id" :selected="u === task.user">{{u.name}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" v-model="task.status" required>
                    <option disabled>Select task status</option>
                    <option v-bind:value="0">New</option>
                    <option v-bind:value="1">In progress</option>
                    <option v-bind:value="2">Done</option>
                </select>
            </div>
            <button class="btn btn-info btn-block" type="submit">Save</button>
        </form>
        <ul class="list-group">
            <li class="list-group-item" v-for="task in tasks" v-bind:key="task.id">
                {{task.task}} ::
                <span v-if="task.status == 0">New</span>
                <span v-else-if="task.status == 1">In progress</span>
                <span v-else-if="task.status == 2">Done</span>
                <span v-if="$parent.$data.user.type === 1">
                    Task for:
                    <span v-for="user in users">
                        <span v-if="user.id == task.user">{{user.name}}</span>
                    </span>
                </span>
                <button @click="deleteTask(task.id)" class="btn btn-danger">Delete</button>
                <button @click="editTask(task)" class="btn btn-info">Edit</button>
            </li>
        </ul>
        <nav aria-label="Tasks navigation">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchTasks(pagination.prev_page_url)">Previous</a>
                </li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page}} of {{pagination.last_page}}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchTasks(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
        <nav aria-label="Tasks navigation">
            <ul class="pagination">
                <li class="page-item disabled"><a class="page-link text-dark" href="#">Sort by</a></li>
                <li v-bind:class="[{disabled: this.order === 'status'}]" @click="sort('status')" class="page-item"><a href="#" class="page-link">Status</a></li>
                <li v-bind:class="[{disabled: this.order === 'created_at'}]" @click="sort('created_at')" class="page-item"><a href="#" class="page-link">Date</a></li>
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
                tasks:[],
                users:{},
                task: {
                    id: '',
                    task: '',
                    user: '',
                    status: ''
                },
                pagination: {},
                edit: false,
                order: 'created_at',
            }
        },
        created() {
            this.fetchTasks();
            this.fetchUsers();
        },

        methods:{
            fetchTasks(page){
                page = page || '/api/tasks'
                let vm = this;
                const info = {
                    token: this.$parent.$data.token,
                    order: this.order
                };
                this.$parent.req.post(page, info).then(response => {
                    this.tasks = response.data.data;
                    vm.makePagination(response.data.meta, response.data.links);
                }).catch(error => {
                    console.error(error);
                });
            },

            fetchUsers(){
                const info = {
                    token: this.$parent.$data.token
                };
                this.$parent.req.post('api/allusers', info).then(response => {
                    this.users = response.data.data;
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
            deleteTask(id) {
               if(confirm('Are you sure?')){
                   fetch(`/api/task/${id}`,{
                       method: "DELETE",
                       credentials: "same-origin",
                       //Būtina nepamiršti, kitaip - 419 erroras
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       }
                   })
                   .then(res =>
                   {
                       if(res.status === 200){
                           return res.json();
                       }else{
                           alert(res.statusText)
                       }
                   })
                   .then(data => {
                       alert('Task removed');
                       this.fetchTasks();
                   })
                   .catch(err => console.log(err));
               }
            },
            addTask(){
                if(this.edit === false){
                    // Add
                    if(this.$parent.$data.user.type === 1) {
                        fetch(`api/task`, {
                            method: 'POST',
                            body: JSON.stringify(this.task),
                            headers: {
                                'content-type': 'application/json',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).then(res => {
                            if (res.status === 200) {
                                return res.json();
                            }
                        }).then(data => {
                            this.task.status = '';
                            this.task.task = '';
                            this.task.user = '';
                            alert('Task added');
                            this.fetchTasks();
                        })
                            .catch(error => console.log(error))
                    }else{
                        alert("You can't add tasks!")
                    }
                }else{
                    //Update
                    fetch(`api/task`,{
                        method: 'PUT',
                        body: JSON.stringify(this.task),
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then(res =>
                    {
                        if(res.status === 200){
                            return res.json();
                        }else{
                            alert(res.status);
                        }
                    }).then(data => {
                        this.task.status = '';
                        this.task.task = '';
                        this.task.user = '';
                        this.edit = false;
                        alert('Task updated');
                        this.fetchTasks();
                    }).catch(error => console.log(error))

                }
            },
            editTask(task){
                this.edit = true;
                this.task.id = task.id;
                this.task.status = task.status;
                this.task.task = task.task;
                this.task.user = task.user;
            },
            sort(by){
                this.order = by;
                this.fetchTasks();
            }
        }
    }
</script>

<style scoped>

</style>
