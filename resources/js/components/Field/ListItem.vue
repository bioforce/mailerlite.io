<template>
    <tr>
        <td>{{field.id}}</td>
        <td>{{field.title}}</td>
        <td>{{field.show_type}}</td>
        <td class="text-nowrap">
            <a href="#" class="btn btn-danger" @click="deleteSubscriber(field)">Delete</a>
        </td>
    </tr>
</template>
<script>
export default {
    name: 'FieldListItem',
    props: ['field'],
    data() {
        return {

        }
    },
    methods: {
        showEditForm() {
            this.$emit('show:edit', this.field)
        },
        deleteSubscriber(field)
        {
            if(confirm('Confirm delete '+this.field.title))
            {
                axios.delete('/api/fields/'+this.field.id)
                .then(response => {
                    this.$emit('update')
                })
                .catch(error => {
                    if (typeof error.response.data === 'object') {
                        this.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        this.errors = ['Something went wrong. Please try again.'];
                    }
                });
            }
        }
    }
}
</script>