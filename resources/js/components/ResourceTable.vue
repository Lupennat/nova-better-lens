<script>
    import ResourceTable from '@/components/ResourceTable';
    import ResourceTableHeader from '@/components/ResourceTableHeader';
    import ResourceTableRow from './ResourceTableRow';

    export default {
        extends: ResourceTable,
        components: {
            ResourceTableHeader,
            ResourceTableRow
        }
    };
</script>
