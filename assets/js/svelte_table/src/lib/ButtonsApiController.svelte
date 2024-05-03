<script>
    import { onMount } from "svelte";
    import pickedElementsStore from "./stores/pickedElementsStore.js";
    import { sendBlock } from "../scripts/usersApiFetcher.js";

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
        await sendBlock(ids);
    }

    function goUnblock() {
        const ids = getPicked();
        if (!ids || ids.length === 0) return;
    }

    function goDelete() {
        const ids = getPicked();
        if (!ids || ids.length === 0) return;
    }

    function getPicked() {
        return pickedElementsStore.getPicked();
    }
</script>
