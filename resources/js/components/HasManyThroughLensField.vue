<template>
    <ResourceLens
        :field="field"
        :resource-name="field.resourceName"
        :via-resource="resourceName"
        :via-resource-id="resourceId"
        :via-relationship="field.hasManyThroughRelationship"
        relationship-type="hasManyThrough"
        @actionExecuted="actionExecuted"
        :load-cards="false"
        :initialPerPage="field.perPage || 5"
        :should-override-meta="false"
        :can-collapse="true"
        :lens="field.lensName"
        :searchable="field.searchable"
        :is-authorized-to-create="field.isAuthorizedToCreate"
        :show-pagination="field.showPagination"
    />
</template>

<script>
    import ResourceLens from '../views/Lens';

    export default {
        emits: ['actionExecuted'],

        components: { ResourceLens },

        props: ['resourceName', 'resourceId', 'resource', 'field', 'lens'],

        methods: {
            /**
             * Handle the actionExecuted event and pass it up the chain.
             */
            actionExecuted() {
                this.$emit('actionExecuted');
            }
        }
    };
</script>
