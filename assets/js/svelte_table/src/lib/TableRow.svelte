<script lang="ts">
    import Checkbox from "./Checkbox.svelte";
    /**
     * @type {{name: string, email: string, last_login: string, registrated_at: string, status: string}}
     */
    export let data;
    let { id, name, email, last_loggined_at, registrated_at, status } = data;
</script>

<tr>
    <td class="d-flex">
        <Checkbox {id} />
    </td>
    <td>{name}</td>
    <td>{email}</td>
    <td>{last_loggined_at ? last_loggined_at : "none"}</td>
    <td>{status}</td>
    <td>{registrated_at}</td>
</tr>

<style>
    tr {
        border-top: 2px solid var(--bs-secondary);
        border-bottom: 2px solid var(--bs-secondary);
    }

    td {
        align-items: center;
        justify-content: center;
        padding: 0.6rem;
    }
</style>
