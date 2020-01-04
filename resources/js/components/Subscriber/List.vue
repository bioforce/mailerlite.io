<template>
    <div class="box-body">
        <div class="py-md-3">
            <a class="btn btn-primary" href="#" role="button" @click.prevent="showAddForm">Add subscriber</a>
        </div>
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Name</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <subscriber-list-item v-for="(subscriber,index) in subscribers" :key="index" :subscriber="subscriber" v-on:show:edit="showEditForm(subscriber)" v-on:update="fetchSubscribers"></subscriber-list-item>
            </tbody>
        </table>
        <div class="d-flex justify-content-center" v-if="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="fetchSubscribers()"></pagination>
        <subscriber-create-form v-on:update="fetchSubscribers" ref="create"></subscriber-create-form>
        <subscriber-edit-form v-on:update="fetchSubscribers" :subscriber.sync="subscriber" :title="subscriber.email" ref="edit"></subscriber-edit-form>
    </div>
</template>
<script>
import axios from 'axios'
import SubscriberListItem from './ListItem.vue'
import SubscriberCreateForm from './Create.vue'
import SubscriberEditForm from './Edit.vue'
import Pagination from '../Pagination.vue'

export default {
    name: 'SubscriberList',
    components: {
        'subscriber-list-item': SubscriberListItem,
        'subscriber-create-form': SubscriberCreateForm,
        'subscriber-edit-form': SubscriberEditForm,
        'pagination': Pagination
    },
    created() {
    },
    data() {
        return {
            pagination: {
                'current_page': 1
            },
            subscribers: [
            ],
            subscriber: {                
            },
            loading: false
        }
    },
    methods: {
        fetchSubscribers() {
            this.loading = true
            this.subscribers = []
            var urlGet = '/api/subscribers?page=' + this.pagination.current_page+'&type='+this.filterType
            axios.get(urlGet)
                .then(response => {
                    this.subscribers = response.data.data
                    this.pagination = response.data.meta
                    this.loading = false
                })
                .catch(error => {
                    this.loading = false
                    console.log(error.response.data)
                });
        },
        showAddForm()
        {
            this.$refs.create.updateFields()
            this.$modal.show('modal-create-subscriber')
            return true
        },
        showEditForm(subscriber)
        {
            this.$refs.edit.updateFields()
            this.subscriber = subscriber
            this.$modal.show('modal-edit-subscriber')
        }
    },
    mounted() {
        this.fetchSubscribers()
    }
}
</script>