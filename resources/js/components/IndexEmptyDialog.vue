<script>
    import IndexEmptyDialog from '@/components/IndexEmptyDialog';

    export default {
        extends: IndexEmptyDialog,
        computed: {
            buttonURL() {
                if (this.shouldShowAttachButton) {
                    return this.$url(
                        `/resources/${this.viaResource}/${this.viaResourceId}/attach/${this.resourceName}`,
                        Object.assign(
                            {
                                viaRelationship: this.viaRelationship,
                                polymorphic: this.relationshipType == 'morphToMany' ? '1' : '0',
                            },
                            this.linkParameters,
                        ),
                    );
                } else if (this.shouldShowCreateButton) {
                    return this.$url(
                        `/resources/${this.resourceName}/new`,
                        Object.assign(
                            {
                                viaResource: this.viaResource,
                                viaResourceId: this.viaResourceId,
                                viaRelationship: this.viaRelationship,
                                relationshipType: this.relationshipType,
                            },
                            this.linkParameters,
                        ),
                    );
                }
            },
        },
    };
</script>
