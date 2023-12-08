<template>
  <ResourceBetterLens
    :resourceName="resourceName"
    :lens="lens"
    :searchable="searchable"
    :shouldOverrideMeta="true"
    :is-authorized-to-create="false"
    :via-resource="viaResource"
    :via-resource-id="viaResourceId"
  />
</template>

<script>
import { mapProps } from "@/mixins";

export default {
  name: "Lens",

  props: {
    lens: {
      type: String,
      required: true,
    },

    searchable: {
      type: Boolean,
      default: false,
    },

    ...mapProps(["resourceName", "viaResource", "viaResourceId"]),
  },
};
</script>
