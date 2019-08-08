<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <br>
                <div class="card">
                    <div class="card-header">Tasks</div>

                    <div class="card-body">
                        <!-- Form -->
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <input type="text" class="form-control" @keyup.enter.native="submit()" v-model="name">
                                    <small class="text-danger" v-if="hasError('name')">{{ errors.name[0] }}</small>
                                </div>

                                <div class="form-group col-md-2">
                                    <button type="submit" class="btn btn-primary" @click.prevent="submit()">Save</button>
                                </div>
                            </div>
                        </form>
                        <!-- Form -->

                        <!-- Filters -->
                        <h6>
                            <button class="badge" :class="filterTasks('all')" @click.prevent="selectFilter('all')">All</button>
                            <button class="badge" :class="filterTasks('unfinished')" @click.prevent="selectFilter('unfinished')">Unfinished</button>
                        </h6>
                        <!-- Filters -->

                        <!-- Task list -->
                        <div class="alert alert-success alert-dismissible" v-for="item in tasks">
                            <span class="finished" title="finish" @click.prevent="finish(item.id)" v-if="item.finished === 0">&#9744;</span>
                            <span class="finished" v-else-if="item.finished === 1">&#x2713;</span>
                            <button type="button" title="delete" class="close" @click.prevent="destroy(item.id)">&times;</button>
                            <span>{{ item.name }}</span>
                        </div>
                        <!-- Task list -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                tasks: [],
                errors: null,
                name: null,
                selectedFilter: 'all'
            }
        },
        mounted() {
            this.getTasks();
        },
        methods: {
            getTasks() {
                if (this.selectedFilter === 'all') this.getAll();
                else this.getUnfinished();
            },
            getAll() {
                axios.get('api/tasks').then(response => {
                    this.tasks = response.data.tasks;
                })
            },
            getUnfinished() {
                axios.get('api/tasks/filter/unfinished').then(response => {
                    this.tasks = response.data.tasks;
                })
            },
            submit() {
                this.errors = null;
                axios.post('api/tasks', { name: this.name }).then(response => {
                    this.getTasks();
                    this.name = null;
                }).catch(e => {
                    this.errors = e.response.data.errors;
                })
            },
            destroy(id) {
                axios.delete('api/tasks/' + id).then(response => {
                    this.getTasks();
                })
            },
            finish(id) {
                axios.post('api/tasks/' + id, { _method: 'PUT' }).then(response => {
                    this.getTasks();
                }).catch(e => {
                    this.errors = e.response.data.errors;
                })
            },
            hasError(field) {
                if (this.errors !== null && this.errors.hasOwnProperty(field)) return true;
            },
            filterTasks(filter) {
                return this.selectedFilter === filter ? 'badge-primary' : '';
            },
            selectFilter(filter) {
                this.selectedFilter = filter;
                this.getTasks();
            }
        }
    }
</script>

<style>
    .finished {
        cursor: pointer;
    }
</style>
