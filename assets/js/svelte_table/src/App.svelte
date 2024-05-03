<script lang="ts">
    import { onMount } from "svelte";
    import { getAllData } from "./scripts/usersApiFetcher.js";
    import TableRow from "./lib/TableRow.svelte";
    import GlobalCheckBox from "./lib/GlobalCheckBox.svelte";
    import ApiController from "./lib/ButtonsApiController.svelte";

    /**
     * @var {[]|null}
     */
    let data = null!;

    onMount(() => {
        getUsers();
    });

    async function getUsers() {
        data = await getAllData();
        data = data.data;
    }
</script>

<div class="table__wrapper">
    <table>
        <colgroup>
            <col span="1" class="column" />
            <col span="1" class="column" />
            <col span="1" class="column" />
            <col span="1" class="column" />
            <col span="1" class="column" />
            <col span="1" class="column" />
        </colgroup>
        <tr class="table__header">
            <td class="d-flex">
                <GlobalCheckBox />
            </td>
            <th scope="col">Name</th>
            <th scope="col">e-Mail</th>
            <th scope="col">Last Login</th>
            <th scope="col">Status</th>
            <th scope="col">Registrated At</th>
        </tr>

        {#if data}
            {#each data as user}
                <TableRow data={user} />
            {/each}
        {/if}
    </table>
</div>
<ApiController />

<style>
    .table__wrapper {
        padding: 2.5rem 1.125rem 0;
        overflow: scroll;
    }

    table {
        width: 100%;
        overflow-x: scroll;
    }

    th {
        padding: 0.8rem;
    }

    td {
        align-items: center;
        justify-content: center;
        padding: 0.6rem;
    }

    .column {
        border: 3px solid var(--bs-black);
    }

    .table__header {
        background-color: var(--bs-secondary);
        color: var(--bs-light);
    }
</style>
