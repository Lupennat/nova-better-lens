<template>
  <ResourceBetterLens
    :field="field"
    :resource-name="field.resourceName"
    :via-resource="resourceName"
    :via-resource-id="resourceId"
    :via-relationship="field.belongsToManyRelationship"
    :create-link-parameters="field.createLinkParameters"
    relationship-type="belongsToMany"
    @actionExecuted="actionExecuted"
    :load-cards="false"
    :initialPerPage="field.perPage || 5"
    :should-override-meta="false"
    :can-collapse="true"
    :lens="field.lensName"
    :is-authorized-to-create="field.isAuthorizedToCreate"
    :searchable="field.searchable"
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
