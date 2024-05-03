<script context="module">
    export const globalCheckState = writable(false);
</script>

<script>
    import { writable } from "svelte/store";

    let input;

    function clickHandler() {
        if (input.checked) {
            globalCheckState.set(true);
        } else {
            globalCheckState.set(false);
        }
    }
</script>

<input
    type="checkbox"
    class="form-check-input"
    on:click={clickHandler}
    bind:this={input}
/>
