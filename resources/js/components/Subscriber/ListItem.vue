<template>
    <tr>
        <td>{{subscriber.id}}</td>
        <td>{{subscriber.email}}</td>
        <td>{{subscriber.address}}</td>
        <td>{{subscriber.name}}</td>
        <td>{{subscriber.show_state}}</td>
        <td class="text-nowrap">
            <a class="btn btn-info" href="#" role="button" @click="showEditForm">Edit</a>
            <a href="#" class="btn btn-danger" @click="deleteSubscriber(subscriber)">Delete</a>
        </td>
    </tr>
</template>
<script>
export default {
    name: 'SubscriberListItem',
    props: ['subscriber'],
    data() {
        return {

        }
    },
    methods: {
        showEditForm() {
            this.$emit('show:edit', this.subscriber)
        },
        deleteSubscriber(subscriber)
        {
            if(confirm('Confirm delete '+this.subscriber.email))
            {
                axios.delete('/api/subscribers/'+this.subscriber.id)
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