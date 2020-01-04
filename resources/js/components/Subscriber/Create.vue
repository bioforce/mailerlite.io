<template>
    <!-- Create Subscribe Modal -->
    <modal name="modal-create-subscriber" :resizable="true" :height="'auto'" :scrollable="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Create Subscriber
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closemodal('modal-create-subscriber')">&times;</button>
                </div>

                <div class="modal-body">
                    <!-- Form Errors -->
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <p><strong>Whoops!</strong> Something went wrong!</p>
                        <br>
                        <ul>
                            <li v-for="(error, index) in errors" :key="index">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-center" v-if="!compiled">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <!-- Create Client Form -->
                    <form class="form-horizontal" role="form" v-if="compiled">
                        <!-- Email -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email</label>

                            <div class="col-md-7">
                                <input id="create-subscriber-email" type="email" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.email">
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Address</label>

                            <div class="col-md-7">
                                <input id="create-subscriber-address" type="text" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.address">
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Name</label>

                            <div class="col-md-7">
                                <input id="create-subscriber-name" type="text" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.name">
                            </div>
                        </div>

                        <!-- Additional fields -->
                        <div class="form-group" v-for="(field, index) in fields" :key="index">
                            <label class="col-md-3 control-label" v-if="field.type != 'B'">{{field.title}}</label>

                            <div class="col-md-7">
                                <input :id="'create-subscriber-field-'+field.id" type="text" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.fields[field.id].value" v-if="field.type == 'S'">
                                
                                <input :id="'create-subscriber-field-'+field.id" type="date" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.fields[field.id].value" v-else-if="field.type == 'D'">

                                <input :id="'create-subscriber-field-'+field.id" type="number" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.fields[field.id].value" v-else-if="field.type == 'N'">
                                
                                <div class="form-check" v-if="field.type == 'B'">
                                    <input class="form-check-input" type="checkbox" :id="'field_checkbox_'+field.id" v-model="subscriber.fields[field.id].value">
                                    <label class="form-check-label" :for="'field_checkbox_'+field.id">
                                        {{field.title}}
                                    </label>
                                </div>
                                
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" @click="closemodal('modal-create-subscriber')">Close</button>

                    <button type="button" class="btn btn-primary" @click="store" :disabled="loading || !compiled">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading"></span>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            errors: [],
            subscriber: {
                name: '',
                email: '',
                address: '',
                fields: {}
            },
            fields: {},
            compiled: false
        }
    },
    methods: {
        /**
         * Close modal
         */
        closemodal(name) {
            this.$modal.hide(name)
        },
        /**
         * Create a new Subscriber.
         */
        store(){
            this.loading = true
            this.errors = [];

            axios.post('/api/subscribers', this.subscriber)
                .then(response => {
                    this.loading = false
                    this.$emit('update')
                    this.$modal.hide('modal-create-subscriber')
                })
                .catch(error => {
                    this.loading = false
                    if (typeof error.response.data === 'object') {
                        this.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        this.errors = ['Something went wrong. Please try again.'];
                    }
                });
            return true
        },
        updateFields() {
            this.fields = []
            this.subscriber.fields = {}
            var urlGet = '/api/fields?all=yes'
            axios.get(urlGet)
                .then(response => {                    
                    for (let index = 0; index < response.data.length; index++) {
                        this.subscriber.fields[response.data[index].id] = {value: ''}
                    }
                    this.fields = response.data
                    this.compiled = true
                })
                .catch(error => {
                    console.log(error.response.data)
                });
        }
    }
}
</script>