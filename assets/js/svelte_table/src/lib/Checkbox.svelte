<script context="module">
    export const SIMPLE_INPUT = "simple";
</script>

<script>
    import { onMount } from "svelte";
    import pickedElementsStore from "./stores/pickedElementsStore.js";
    import { globalCheckState } from "./GlobalCheckBox.svelte";
    export let id = 0;

    let input;

    function clickHandler(e) {
        e.stopPropagation();
        if (input.checked) {
            pickedElementsStore.add(id);
        } else {
            pickedElementsStore.remove(id);
        }
    }

    onMount(() => {
        const unsubscribe = globalCheckState.subscribe((state) => {
            if (state) {
                input.checked = true;
                pickedElementsStore.add(id);
            } else if (state === false) {
                input.checked = false;
                pickedElementsStore.remove(id);
            }
        });

        return () => {
            unsubscribe();
        };
    });
</script>

<input
    type="checkbox"
    class="form-check-input"
    data-input={SIMPLE_INPUT}
    on:click={clickHandler}
    bind:this={input}
/>
