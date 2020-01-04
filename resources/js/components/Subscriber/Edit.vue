<template>
    <!-- Edit Subscribe Modal -->
    <modal name="modal-edit-subscriber" :resizable="true" :height="'auto'" :scrollable="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Edit Subscriber #{{subscriber.id}}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closemodal('modal-edit-subscriber')">&times;</button>
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

                    <!-- Create Subscribe Form -->
                    <form class="form-horizontal" role="form" v-if="compiled">
                        <!-- Email -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email</label>

                            <div class="col-md-7">
                                <input id="create-subscribe-email" type="email" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.email">
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Address</label>

                            <div class="col-md-7">
                                <input id="create-subscribe-address" type="text" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.address">
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Name</label>

                            <div class="col-md-7">
                                <input id="create-subscribe-name" type="text" class="form-control"
                                                            @keyup.enter="store" v-model="subscriber.name">
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">State</label>

                            <div class="col-md-7">
                                <select id="create-subscribe-state" v-model="subscriber.state" class="custom-select">
                                    <option value="A">active</option>
                                    <option value="U">unsubscribed</option>
                                    <option value="J">junk</option>
                                    <option value="B">bounced</option>
                                    <option value="N">unconfirmed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Additional fields -->
                        <div class="form-group" v-for="(field, index) in fields" :key="index">
                            <label class="col-md-3 control-label" v-if="field.type != 'B'">{{field.title}}</label>

                            <div class="col-md-7">
                                <input :id="'create-subscriber-field-'+field.id" type="text" class="form-control"
                                                            @keyup.enter="store" v-model="fields_input[field.id].value" v-if="field.type == 'S'">
                                
                                <input :id="'create-subscriber-field-'+field.id" type="date" class="form-control"
                                                            @keyup.enter="store" v-model="fields_input[field.id].value" v-else-if="field.type == 'D'">

                                <input :id="'create-subscriber-field-'+field.id" type="number" class="form-control"
                                                            @keyup.enter="store" v-model="fields_input[field.id].value" v-else-if="field.type == 'N'">
                                
                                <div class="form-check" v-if="field.type == 'B'">
                                    <input class="form-check-input" type="checkbox" :id="'field_checkbox_'+field.id" v-model="fields_input[field.id].value">
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
                    <button type="button" class="btn btn-default" @click="closemodal('modal-edit-subscriber')">Close</button>

                    <button type="button" class="btn btn-primary" @click="store" :disabled="loading && !compiled">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading"></span>
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
export default {
    props: ['subscriber', 'title'],
    data() {
        return {
            errors: [],
            fields: [],
            fields_input: {},
            loading: false,
            compiled: false
        };
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
            this.$set(this.subscriber, 'filled', this.fields_input)
            axios.put('/api/subscribers/'+this.subscriber.id, this.subscriber)
                .then(response => {
                    this.loading = false
                    this.$emit('update')
                    this.$modal.hide('modal-edit-subscriber')
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
            this.compiled = false
            this.fields = []
            var urlGet = '/api/fields?all=yes'
            axios.get(urlGet)
                .then(response => {
                    for (let index = 0; index < response.data.length; index++) {
                        this.fields_input[response.data[index].id] = {value: this.getPivotValue(this.subscriber.additional_fields, response.data[index])}
                    }
                    this.fields = response.data
                    this.compiled = true
                })
                .catch(error => {
                    console.log(error)
                });
        },
        getPivotValue(fields, field)
        {
            var id = field.id
            if (fields[id] && fields[id].value)
            {
                if (field.type == 'B' && fields[id].value === '0')
                {
                    return false
                }
                return fields[id].value
            }
            if (field.type == 'B')
            {
                return false
            }
            return ''
        }
    }
}
</script>