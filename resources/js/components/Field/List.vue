<template>
    <div class="box-body">
        <div class="py-md-3">
            <a class="btn btn-primary" href="#" role="button" @click.prevent="showAddForm">Add field</a>
        </div>
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <field-list-item v-for="(field,index) in fields" :key="index" :field="field" v-on:show:edit="showEditForm(field)" v-on:update="fetchFields"></field-list-item>
            </tbody>
        </table>
        <div class="d-flex justify-content-center" v-if="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="fetchFields()"></pagination>
        <field-create-form v-on:update="fetchFields"></field-create-form>
    </div>
</template>
<script>
import axios from 'axios'
import FieldListItem from './ListItem.vue'
import FieldCreateForm from './Create.vue'
import Pagination from '../Pagination.vue'

export default {
    name: 'FieldList',
    components: {
        'field-list-item': FieldListItem,
        'field-create-form': FieldCreateForm,
        'pagination': Pagination
    },
    created() {
    },
    data() {
        return {
            pagination: {
                'current_page': 1
            },
            fields: [
            ],
            field: {                
            },
            loading: false
        }
    },
    methods: {
        fetchFields() {
            this.loading = true
            this.fields = []
            var urlGet = '/api/fields?page=' + this.pagination.current_page+'&type='+this.filterType
            axios.get(urlGet)
                .then(response => {
                    this.fields = response.data.data
                    let {data, ...pagination} = response.data
                    this.pagination = pagination
                    this.loading = false
                })
                .catch(error => {
                    this.loading = false
                    console.log(error.response.data)
                });
        },
        showAddForm()
        {
            this.$modal.show('modal-create-field')
            return true
        },
        showEditForm(field)
        {
            this.field = field
            this.$modal.show('modal-edit-field')
        }
    },
    mounted() {
        this.fetchFields()
    }
}
</script>