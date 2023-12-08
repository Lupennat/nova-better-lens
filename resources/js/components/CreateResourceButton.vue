<template>
  <div v-if="shouldShowButtons">
    <!-- Attach Related Models -->
    <component
      :is="component"
      class="flex-shrink-0"
      v-if="shouldShowAttachButton"
      dusk="attach-button"
      :href="attachUrl"
    >
      <slot>
        <span class="hidden md:inline-block">
          {{ __("Attach :resource", { resource: singularName }) }}
        </span>
        <span class="inline-block md:hidden">
          {{ __("Attach") }}
        </span>
      </slot>
    </component>

    <!-- Create Related Models -->
    <component
      :is="component"
      class="flex-shrink-0"
      v-else-if="shouldShowCreateButton"
      dusk="create-button"
      :href="createUrl"
    >
      <span class="hidden md:inline-block">
        {{ label }}
      </span>
      <span class="inline-block md:hidden">
        {{ __("Create") }}
      </span>
    </component>
  </div>
</template>
<script>
import CreateResourceButton from "@/components/CreateResourceButton";

export default {
  extends: CreateResourceButton,
  props: {
    linkParameters: {
      required: true,
    },
  },
  computed: {
    parameters() {
      return this.linkParameters &&
        typeof this.linkParameters === "object" &&
        !Array.isArray(this.linkParameters)
        ? this.linkParameters
        : {};
    },
    attachUrl() {
      return this.$url(
        `/resources/${this.viaResource}/${this.viaResourceId}/attach/${this.resourceName}`,
        Object.assign(this.parameters, {
          viaRelationship: this.viaRelationship,
          polymorphic: this.relationshipType == "morphToMany" ? "1" : "0",
        }),
      );
    },
    createUrl() {
      return this.$url(
        `/resources/${this.resourceName}/new`,
        Object.assign(this.parameters, {
          viaResource: this.viaResource,
          viaResourceId: this.viaResourceId,
          viaRelationship: this.viaRelationship,
          relationshipType: this.relationshipType,
        }),
      );
    },
  },
};
</script>
