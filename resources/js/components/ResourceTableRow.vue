<script>
    import ResourceTableRow from '@/components/ResourceTableRow';

    export default {
        extends: ResourceTableRow,
        methods: {
            navigateToDetailView(e) {
                if (!this.resource.authorizedToView) {
                    return;
                }
                this.commandPressed ? window.open(this.viewURL, '_blank') : this.$inertia.visit(this.viewURL);
            },

            navigateToEditView(e) {
                if (!this.resource.authorizedToUpdate) {
                    return;
                }
                this.commandPressed ? window.open(this.updateURL, '_blank') : this.$inertia.visit(this.updateURL);
            }
        },
        computed: {
            resourceLinkParameters() {
                return this.resource.resourceLinkParameters &&
                    typeof this.resource.resourceLinkParameters === 'object' &&
                    !Array.isArray(this.resource.resourceLinkParameters)
                    ? this.resource.resourceLinkParameters
                    : {};
            },

            updateURL() {
                if (this.viaManyToMany) {
                    return this.$url(
                        `/resources/${this.viaResource}/${this.viaResourceId}/edit-attached/${this.resourceName}/${this.resource.id.value}`,
                        Object.assign(
                            {
                                viaRelationship: this.viaRelationship,
                                viaPivotId: this.resource.id.pivotValue
                            },
                            this.resourceLinkParameters
                        )
                    );
                }

                return this.$url(
                    `/resources/${this.resourceName}/${this.resource.id.value}/edit`,
                    Object.assign(
                        {
                            viaResource: this.viaResource,
                            viaResourceId: this.viaResourceId,
                            viaRelationship: this.viaRelationship
                        },
                        this.resourceLinkParameters
                    )
                );
            },

            viewURL() {
                return this.$url(
                    `/resources/${this.resourceName}/${this.resource.id.value}`,
                    this.resourceLinkParameters
                );
            }
        }
    };
</script>
