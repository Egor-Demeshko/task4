<script>
    import { onMount } from "svelte";
    import pickedElementsStore from "./stores/pickedElementsStore.js";
    import {
        sendBlock,
        sendUNBlock,
        sendDelete,
    } from "../scripts/usersApiFetcher.js";
    import { tableBlocked } from "../lib/stores/tableBlocked.js";

    const events = {
        "send-block": goBlock,
        "send-unblock": goUnblock,
        "send-delete": goDelete,
    };

    onMount(() => {
        for (let [key, callback] of Object.entries(events)) {
            document.addEventListener(key, callback);
        }
    });

    async function goBlock() {
        const ids = getPicked();
        if (!ids || ids.length === 0) return;
        blockInterection();
        await sendBlock(ids);
        unblockInterection();
    }

    async function goUnblock() {
        const ids = getPicked();
        if (!ids || ids.length === 0) return;
        blockInterection();
        await sendUNBlock(ids);
        unblockInterection();
    }

    async function goDelete() {
        const ids = getPicked();
        if (!ids || ids.length === 0) return;
        blockInterection();
        await sendDelete(ids);
        unblockInterection();
    }

    function getPicked() {
        return pickedElementsStore.getPicked();
    }

    function blockInterection() {
        document.dispatchEvent(new CustomEvent("block_buttons"));
        tableBlocked.set(true);
    }

    function unblockInterection() {
        document.dispatchEvent(new CustomEvent("unblock_buttons"));
        tableBlocked.set(false);
    }
</script>
