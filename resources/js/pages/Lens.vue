<template>
    <ResourceBetterLens
        :resourceName="resourceName"
        :lens="lens"
        :searchable="searchable"
        :shouldOverrideMeta="true"
        :is-authorized-to-create="isAuthorizedToCreate"
        :create-link-parameters="createLinkParameters"
        :via-resource="viaResource"
        :via-resource-id="viaResourceId"
    />
</template>

<script>
    import { mapProps } from '@/mixins';

    export default {
        name: 'Lens',

        props: {
            lens: {
                type: String,
                required: true,
            },

            searchable: {
                type: Boolean,
                default: false,
            },

            isAuthorizedToCreate: {
                type: Boolean,
                default: false,
            },

            createLinkParameters: {
                default() {
                    return {};
                },
            },

            ...mapProps(['resourceName', 'viaResource', 'viaResourceId']),
        },
    };
</script>
