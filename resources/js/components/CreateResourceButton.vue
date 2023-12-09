<template>
    <div v-if="shouldShowButtons">
        <!-- Attach Related Models -->
        <ButtonInertiaLink class="shrink-0" v-if="shouldShowAttachButton" dusk="attach-button" :href="attachUrl">
            <slot>
                <span class="hidden md:inline-block">
                    {{ __('Attach :resource', { resource: singularName }) }}
                </span>
                <span class="inline-block md:hidden">
                    {{ __('Attach') }}
                </span>
            </slot>
        </ButtonInertiaLink>

        <!-- Create Related Models -->
        <ButtonInertiaLink
            v-else-if="shouldShowCreateButton"
            class="shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center font-bold"
            dusk="create-button"
            :href="createUrl"
        >
            <span class="hidden md:inline-block">
                {{ label }}
            </span>
            <span class="inline-block md:hidden">
                {{ __('Create') }}
            </span>
        </ButtonInertiaLink>
    </div>
</template>
<script>
    import CreateResourceButton from '@/components/CreateResourceButton';

    export default {
        name: 'CreateResourceButton',
        extends: CreateResourceButton,
        props: {
            linkParameters: {
                required: true,
            },
        },
        computed: {
            shouldShowAttachButton() {
                return (
                    (this.relationshipType === 'belongsToMany' || this.relationshipType === 'morphToMany') &&
                    this.authorizedToRelate
                );
            },

            shouldShowCreateButton() {
                return this.authorizedToCreate && this.authorizedToRelate && !this.alreadyFilled;
            },

            shouldShowButtons() {
                return this.shouldShowAttachButton || this.shouldShowCreateButton;
            },
            attachUrl() {
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
            },
            createUrl() {
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
            },
        },
    };
</script>
