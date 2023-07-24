<template>
    <ResourceLens
        :resourceName="resourceName"
        :lens="lens"
        :searchable="searchable"
        :shouldOverrideMeta="true"
        :is-authorized-to-create="false"
    />
</template>

<script>
    import { mapProps } from '@/mixins';
    import ResourceLens from '../views/Lens';

    export default {
        name: 'Lens',

        components: {
            ResourceLens
        },

        props: {
            lens: {
                type: String,
                required: true
            },

            searchable: {
                type: Boolean,
                default: false
            },

            ...mapProps(['resourceName'])
        }
    };
</script>
