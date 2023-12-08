<template>
  <ResourceBetterLens
    :field="field"
    :resource-name="field.resourceName"
    :via-resource="resourceName"
    :via-resource-id="resourceId"
    :create-via-resource="field.createViaResource || resourceName"
    :create-via-resource-id="field.createViaResourceId || resourceId"
    :create-via-relationship="field.createViaRelationship || ''"
    :create-relationship-type="field.createRelationshipType || ''"
    :create-link-parameters="field.createLinkParameters"
    @actionExecuted="actionExecuted"
    :load-cards="false"
    :initialPerPage="field.perPage || 5"
    :should-override-meta="false"
    :can-collapse="true"
    :lens="field.lensName"
    :searchable="field.searchable"
    :is-authorized-to-create="field.isAuthorizedToCreate"
    :is-standalone-field="true"
    :show-pagination="field.showPagination"
  />
</template>

<script>
export default {
  emits: ["actionExecuted"],

  props: ["resourceName", "resourceId", "field"],

  methods: {
    /**
     * Handle the actionExecuted event and pass it up the chain.
     */
    actionExecuted() {
      this.$emit("actionExecuted");
    },
  },
};
</script>
