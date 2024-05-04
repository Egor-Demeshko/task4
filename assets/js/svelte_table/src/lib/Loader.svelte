<script>
    import { fade } from "svelte/transition";
    export let is_modal = false;
</script>

<div class="loader_wr" transition:fade class:is_modal>
    <div class="loader">
        <div class="loader__inner one"></div>
        <div class="loader__inner two"></div>
        <div class="loader__inner three"></div>
    </div>
</div>

<style>
    .loader_wr {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        background-color: #10120f42;
        border-radius: 25px 25px 0 0;
    }
    .loader_wr.is_modal {
        border-radius: 25px;
    }
    .loader {
        position: absolute;
        top: calc(50% - 32px);
        left: calc(50% - 32px);
        width: 64px;
        height: 64px;
        border-radius: 50%;
        perspective: 800px;
    }

    .loader__inner {
        position: absolute;
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }

    .loader__inner.one {
        left: 0%;
        top: 0%;
        animation: rotate-one 1s linear infinite;
        border-bottom: 3px solid #10120f;
    }

    .loader__inner.two {
        right: 0%;
        top: 0%;
        animation: rotate-two 1s linear infinite;
        border-right: 3px solid #10120f;
    }

    .loader__inner.three {
        right: 0%;
        bottom: 0%;
        animation: rotate-three 1s linear infinite;
        border-top: 3px solid #10120f;
    }

    @keyframes rotate-one {
        0% {
            transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);
        }
        100% {
            transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg);
        }
    }

    @keyframes rotate-two {
        0% {
            transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg);
        }
        100% {
            transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg);
        }
    }

    @keyframes rotate-three {
        0% {
            transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg);
        }
        100% {
            transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg);
        }
    }
</style>
