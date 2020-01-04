<template>
    <!-- Create Field Modal -->
    <modal name="modal-create-field" :resizable="true" :height="'auto'">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Create Field
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closemodal('modal-create-field')">&times;</button>
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

                    <!-- Create Field Form -->
                    <form class="form-horizontal" role="form">
                        <!-- Email -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Title</label>

                            <div class="col-md-7">
                                <input id="create-field-title" type="text" class="form-control"
                                                            @keyup.enter="store" v-model="field.title">
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Type</label>

                            <div class="col-md-7">
                                <select v-model="field.type" class="custom-select">
                                    <option value="S">String</option>
                                    <option value="N">Number</option>
                                    <option value="B">Boolean</option>
                                    <option value="D">Date</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" @click="closemodal('modal-create-field')">Close</button>

                    <button type="button" class="btn btn-primary" @click="store" :disabled="loading">
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
            field: {
                title: '',
                type: ''
            }
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
         * Create a new Field.
         */
        store(){
            this.loading = true
            this.errors = [];

            axios.post('/api/fields', this.field)
                .then(response => {
                    this.loading = false
                    this.field = {
                        title: '',
                        type: ''
                    }
                    this.$emit('update')
                    this.$modal.hide('modal-create-field')
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
        }
    }
}
</script>