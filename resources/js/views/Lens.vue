<template>
    <LoadingView :loading="initialLoading" :dusk="lens + '-lens-component'">
        <template v-if="shouldOverrideMeta && resourceInformation">
            <Head :title="lensName" />
        </template>

        <Cards v-if="shouldShowCards" :cards="cards" :resource-name="resourceName" :lens="lens" />

        <Heading :level="1" class="mb-3 flex items-center" :class="{ 'mt-6': shouldShowCards && cards.length > 0 }">
            <span v-html="headingTitle" />
            <button
                v-if="!loading && canCollapse"
                @click="handleCollapsableChange"
                class="rounded border border-transparent h-6 w-6 ml-1 inline-flex items-center justify-center focus:outline-none focus:ring ring-primary-200"
                :aria-label="__('Toggle Collapsed')"
                :aria-expanded="shouldBeCollapsed === false ? 'true' : 'false'"
            >
                <CollapseButton :collapsed="shouldBeCollapsed" />
            </button>
        </Heading>

        <template v-if="!shouldBeCollapsed">
            <div class="flex">
                <IndexSearchInput
                    :class="{ 'mb-6': !viaResource }"
                    v-if="searchable"
                    :searchable="searchable"
                    v-model:keyword="search"
                    @update:keyword="search = $event"
                />

                <div class="w-full flex items-center" :class="{ 'mb-6': !viaResource }">
                    <!-- Create / Attach Button -->
                    <CreateResourceButton
                        :label="createButtonLabel"
                        :singular-name="singularName"
                        :resource-name="resourceName"
                        :via-resource="viaResource"
                        :via-resource-id="viaResourceId"
                        :via-relationship="viaRelationship"
                        :relationship-type="relationshipType"
                        :authorized-to-create="authorizedToCreate && !resourceIsFull"
                        :authorized-to-relate="authorizedToRelate"
                        class="flex-shrink-0 ml-auto"
                        :class="{ 'mb-6': viaResource }"
                    />
                </div>
            </div>

            <div class="flex"></div>

            <Card>
                <ResourceTableToolbar
                    :actions-endpoint="lensActionEndpoint"
                    :action-query-string="actionQueryString"
                    :all-matching-resource-count="allMatchingResourceCount"
                    :authorized-to-delete-any-resources="authorizedToDeleteAnyResources"
                    :authorized-to-delete-selected-resources="authorizedToDeleteSelectedResources"
                    :authorized-to-force-delete-any-resources="authorizedToForceDeleteAnyResources"
                    :authorized-to-force-delete-selected-resources="authorizedToForceDeleteSelectedResources"
                    :authorized-to-restore-any-resources="authorizedToRestoreAnyResources"
                    :authorized-to-restore-selected-resources="authorizedToRestoreSelectedResources"
                    :available-actions="availableActions"
                    :clear-selected-filters="clearSelectedFilters"
                    :close-delete-modal="closeDeleteModal"
                    :currently-polling="currentlyPolling"
                    :delete-all-matching-resources="deleteAllMatchingResources"
                    :delete-selected-resources="deleteSelectedResources"
                    :filter-changed="filterChanged"
                    :force-delete-all-matching-resources="forceDeleteAllMatchingResources"
                    :force-delete-selected-resources="forceDeleteSelectedResources"
                    :get-resources="getResources"
                    :has-filters="hasFilters"
                    :have-standalone-actions="haveStandaloneActions"
                    :lens="lens"
                    :is-lens-view="isLensView"
                    :per-page-options="perPageOptions"
                    :per-page="perPage"
                    :pivot-actions="pivotActions"
                    :pivot-name="pivotName"
                    :resources="resources"
                    :resource-information="resourceInformation"
                    :resource-name="resourceName"
                    :restore-all-matching-resources="restoreAllMatchingResources"
                    :restore-selected-resources="restoreSelectedResources"
                    :current-page-count="resources.length"
                    :select-all-checked="selectAllChecked"
                    :select-all-matching-checked="selectAllMatchingResources"
                    @deselect="clearResourceSelections"
                    :selected-resources="selectedResources"
                    :selected-resources-for-action-selector="selectedResourcesForActionSelector"
                    :should-show-action-selector="shouldShowActionSelector"
                    :should-show-check-boxes="shouldShowCheckBoxes"
                    :should-show-delete-menu="shouldShowDeleteMenu"
                    :should-show-polling-toggle="shouldShowPollingToggle"
                    :soft-deletes="softDeletes"
                    @start-polling="startPolling"
                    @stop-polling="stopPolling"
                    :toggle-select-all-matching="toggleSelectAllMatching"
                    :toggle-select-all="toggleSelectAll"
                    :trashed-changed="trashedChanged"
                    :trashed-parameter="trashedParameter"
                    :trashed="trashed"
                    :update-per-page-changed="updatePerPageChanged"
                    :via-has-one="viaHasOne"
                    :via-many-to-many="viaManyToMany"
                    :via-resource="viaResourceForPagination"
                />

                <LoadingView :loading="loading">
                    <IndexErrorDialog
                        v-if="resourceResponseError != null"
                        :resource="resourceInformation"
                        @click="getResources"
                    />

                    <template v-else>
                        <IndexEmptyDialog
                            v-if="!resources.length"
                            :create-button-label="createButtonLabel"
                            :singular-name="singularName"
                            :resource-name="resourceName"
                            :via-resource="viaResource"
                            :via-resource-id="viaResourceId"
                            :via-relationship="viaRelationship"
                            :relationship-type="relationshipType"
                            :authorized-to-create="authorizedToCreate && !resourceIsFull"
                            :authorized-to-relate="authorizedToRelate"
                        />

                        <ResourceTable
                            :authorized-to-relate="authorizedToRelate"
                            :resource-name="resourceName"
                            :resources="resources"
                            :singular-name="singularName"
                            :relationship-type="relationshipType"
                            :selected-resources="selectedResources"
                            :selected-resource-ids="selectedResourceIds"
                            :actions-are-available="allActions.length > 0"
                            :actions-endpoint="lensActionEndpoint"
                            :should-show-checkboxes="shouldShowCheckBoxes"
                            :via-resource="viaResource"
                            :via-resource-id="viaResourceId"
                            :via-relationship="viaRelationship"
                            :update-selection-status="updateSelectionStatus"
                            :sortable="true"
                            @order="orderByField"
                            @reset-order-by="resetOrderBy"
                            @delete="deleteResources"
                            @restore="restoreResources"
                            @actionExecuted="getResources"
                            ref="resourceTable"
                        />

                        <ResourcePagination
                            :pagination-component="paginationComponent"
                            :should-show-pagination="shouldShowPagination"
                            :has-next-page="hasNextPage"
                            :has-previous-page="hasPreviousPage"
                            :load-more="loadMore"
                            :select-page="selectPage"
                            :total-pages="totalPages"
                            :current-page="currentPage"
                            :per-page="perPage"
                            :resource-count-label="resourceCountLabel"
                            :current-resource-count="currentResourceCount"
                            :all-matching-resource-count="allMatchingResourceCount"
                        />
                    </template>
                </LoadingView>
            </Card>
        </template>
    </LoadingView>
</template>

<script>
    import {
        HasCards,
        Paginatable,
        PerPageable,
        Deletable,
        Collapsable,
        IndexConcerns,
        InteractsWithResourceInformation,
        SupportsPolling
    } from '@/mixins';
    import { CancelToken, isCancel } from 'axios';
    import { minimum } from '@/util';

    import ResourceTable from '../components/ResourceTable';

    let compiledSearchParams = null;

    export default {
        mixins: [
            Collapsable,
            Deletable,
            HasCards,
            Paginatable,
            PerPageable,
            IndexConcerns,
            InteractsWithResourceInformation,
            SupportsPolling
        ],

        name: 'Lens',
        components: { ResourceTable },

        props: {
            shouldOverrideMeta: {
                type: Boolean,
                default: false
            },

            canCollapse: {
                type: Boolean,
                default: false
            },

            lens: {
                type: String,
                required: true
            },

            searchable: {
                type: Boolean,
                required: true
            },

            isStandaloneField: {
                type: Boolean,
                default: false
            },

            isAuthorizedToCreate: {
                type: Boolean,
                default: false
            },

            showPagination: {
                type: Boolean,
                default: false
            }
        },

        data: () => ({
            actionCanceller: null,
            hasId: false
        }),

        /**
         * Mount the component and retrieve its initial data.
         */
        async created() {
            if (!this.resourceInformation) {
                return;
            }

            await this.getAuthorizationToRelate();

            Nova.$on('refresh-resources', this.getResources);
            Nova.$on('query-string-changed', searchParams => {
                compiledSearchParams = searchParams.toString();
            });

            if (this.actionCanceller !== null) this.actionCanceller();
        },

        beforeUnmount() {
            Nova.$off('refresh-resources', this.getResources);

            if (this.actionCanceller !== null) this.actionCanceller();
        },

        methods: {
            /**
             * Get the resources based on the current page, search, filters, etc.
             */
            getResources() {
                if (this.shouldBeCollapsed) {
                    this.loading = false;
                    return;
                }

                this.loading = true;
                this.resourceResponseError = null;

                this.$nextTick(() => {
                    this.clearResourceSelections();

                    return minimum(
                        Nova.request().get('/nova-vendor/better-lens/' + this.resourceName + '/lens/' + this.lens, {
                            params: this.resourceRequestQueryString,
                            cancelToken: new CancelToken(canceller => {
                                this.canceller = canceller;
                            })
                        }),
                        300
                    )
                        .then(({ data }) => {
                            this.resources = [];

                            this.resourceResponse = data;
                            this.resources = data.resources;
                            this.softDeletes = data.softDeletes;
                            this.perPage = data.per_page;
                            this.hasId = data.hasId;

                            this.handleResourcesLoaded();
                        })
                        .catch(e => {
                            if (isCancel(e)) {
                                return;
                            }

                            this.loading = false;
                            this.resourceResponseError = e;

                            throw e;
                        });
                });
            },

            /**
             * Get the actions available for the current resource.
             */
            getActions() {
                if (this.actionCanceller !== null) this.actionCanceller();

                this.actions = [];
                this.pivotActions = null;

                if (this.shouldBeCollapsed) {
                    return;
                }

                Nova.request()
                    .get(`/nova-api/${this.resourceName}/lens/${this.lens}/actions`, {
                        params: {
                            viaResource: this.viaResource,
                            viaResourceId: this.viaResourceId,
                            viaRelationship: this.viaRelationship,
                            relationshipType: this.relationshipType,
                            display: 'index',
                            resources: this.selectedResourcesForActionSelector
                        },
                        cancelToken: new CancelToken(canceller => {
                            this.actionCanceller = canceller;
                        })
                    })
                    .then(response => {
                        this.actions = response.data.actions;
                        this.pivotActions = response.data.pivotActions;
                        this.resourceHasActions = response.data.counts.resource > 0;
                    })
                    .catch(e => {
                        if (isCancel(e)) {
                            return;
                        }

                        throw e;
                    });
            },

            /**
             * Get the relatable authorization status for the resource.
             */
            getAuthorizationToRelate() {
                if (
                    this.shouldBeCollapsed ||
                    (!this.authorizedToCreate &&
                        this.relationshipType != 'belongsToMany' &&
                        this.relationshipType != 'morphToMany')
                ) {
                    return;
                }

                if (!this.viaResource) {
                    return (this.authorizedToRelate = true);
                }

                return Nova.request()
                    .get(
                        '/nova-api/' +
                            this.resourceName +
                            '/relate-authorization' +
                            '?viaResource=' +
                            this.viaResource +
                            '&viaResourceId=' +
                            this.viaResourceId +
                            '&viaRelationship=' +
                            this.viaRelationship +
                            '&relationshipType=' +
                            this.relationshipType
                    )
                    .then(response => {
                        this.authorizedToRelate = response.data.authorized;
                    });
            },

            /**
             * Get the count of all of the matching resources.
             */
            getAllMatchingResourceCount() {
                Nova.request()
                    .get('/nova-api/' + this.resourceName + '/lens/' + this.lens + '/count', {
                        params: this.resourceRequestQueryString
                    })
                    .then(response => {
                        this.allMatchingResourceCount = response.data.count;
                    });
            },

            /**
             * Load more resources.
             */
            loadMore() {
                if (this.currentPageLoadMore === null) {
                    this.currentPageLoadMore = this.currentPage;
                }

                this.currentPageLoadMore = this.currentPageLoadMore + 1;

                return minimum(
                    Nova.request().get('/nova-vendor/better-lens/' + this.resourceName + '/lens/' + this.lens, {
                        params: {
                            ...this.resourceRequestQueryString,
                            page: this.currentPageLoadMore // We do this to override whatever page number is in the URL
                        }
                    }),
                    300
                ).then(({ data }) => {
                    this.resourceResponse = data;
                    this.resources = [...this.resources, ...data.resources];

                    this.getAllMatchingResourceCount();

                    Nova.$emit('resources-loaded', {
                        resourceName: this.resourceName,
                        lens: this.lens,
                        mode: 'lens'
                    });
                });
            },

            async handleCollapsableChange() {
                this.loading = true;

                this.toggleCollapse();

                if (!this.collapsed) {
                    if (!this.filterHasLoaded) {
                        await this.initializeFilters(this.lens);
                        if (!this.hasFilters) {
                            await this.getResources();
                        }
                    } else {
                        await this.getResources();
                    }

                    await this.getAuthorizationToRelate();
                    await this.getActions();
                    this.restartPolling();
                } else {
                    this.loading = false;
                }
            },

            /**
             * Update the given query string values.
             */
            updateQueryString(value) {
                let searchParams = new URLSearchParams(window.location.search);
                let page = this.$inertia.page;

                _.forEach(value, (v, i) => {
                    searchParams.set(i, v || '');
                });

                if (compiledSearchParams !== searchParams.toString()) {
                    if (page.url !== `${window.location.pathname}?${searchParams}`) {
                        page.url = `${window.location.pathname}?${searchParams}`;

                        window.history.pushState(page, '', `${window.location.pathname}?${searchParams}`);
                    }

                    compiledSearchParams = searchParams.toString();
                }

                Nova.$emit('query-string-changed', searchParams);
            }
        },

        computed: {
            actionQueryString() {
                return {
                    currentSearch: this.currentSearch,
                    encodedFilters: this.encodedFilters,
                    currentTrashed: this.currentTrashed,
                    viaResource: this.viaResource,
                    viaResourceId: this.viaResourceId,
                    viaRelationship: this.viaRelationship
                };
            },

            /**
             * Get the endpoint for this resource's actions.
             */
            lensActionEndpoint() {
                return `/nova-api/${this.resourceName}/lens/${this.lens}/action`;
            },

            /**
             * Get the endpoint for this resource's metrics.
             */
            cardsEndpoint() {
                return `/nova-api/${this.resourceName}/lens/${this.lens}/cards`;
            },

            /**
             * Determine whether the user is authorized to perform actions on the delete menu
             */
            canShowDeleteMenu() {
                return (
                    this.hasId &&
                    Boolean(
                        this.authorizedToDeleteSelectedResources ||
                            this.authorizedToForceDeleteSelectedResources ||
                            this.authorizedToDeleteAnyResources ||
                            this.authorizedToForceDeleteAnyResources ||
                            this.authorizedToRestoreSelectedResources ||
                            this.authorizedToRestoreAnyResources
                    )
                );
            },

            /**
             * The Lens name.
             */
            lensName() {
                if ((this.isRelation || this.isStandaloneField) && this.field) {
                    return this.field.name;
                } else {
                    if (this.resourceResponse !== null) {
                        return this.resourceResponse.name;
                    }
                }
            },

            /**
             * Return the heading for the view
             */
            headingTitle() {
                if (this.initialLoading) {
                    return '&nbsp;';
                } else {
                    return this.lensName;
                }
            },

            /**
             * Determine if the index view should be collapsed.
             */
            shouldBeCollapsed() {
                return this.collapsed && this.canCollapse;
            },

            collapsedByDefault() {
                return this.field?.collapsedByDefault ?? false;
            },

            localStorageKey() {
                let name = `${this.resourceName}.${this.lensName}`;

                if (this.viaRelationship) {
                    name = `${name}.${this.viaRelationship}`;
                }

                return `nova.resources.${name}.collapsed`;
            },

            viaResourceForPagination() {
                return this.showPagination ? '' : this.viaResource;
            },

            /**
             * Get the current per page value from the query string.
             */
            currentPerPage() {
                return this.perPage;
            },

            /**
             * Determine if the user is authorized to create the current resource.
             */
            authorizedToCreate() {
                if (['hasOneThrough', 'hasManyThrough'].indexOf(this.relationshipType) >= 0) {
                    return false;
                }

                return this.isAuthorizedToCreate || false;
            }
        }
    };
</script>
