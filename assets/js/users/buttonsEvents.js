(function createBlock() {
    const button = document.querySelector('[data-control="block"]');

    if (!button) return;
    button.addEventListener("click", function (event) {
        document.dispatchEvent(
            new CustomEvent("send-block", { bubbles: true })
        );
    });
})();

(function createUnblock() {
    const button = document.querySelector('[data-control="unblock"]');

    if (!button) return;
    button.addEventListener("click", function (event) {
        document.dispatchEvent(
            new CustomEvent("send-unblock", { bubbles: true })
        );
    });
})();

(function createDelete() {
    const button = document.querySelector('[data-control="delete"]');

    if (!button) return;
    button.addEventListener("click", function (event) {
        document.dispatchEvent(
            new CustomEvent("send-delete", { bubbles: true })
        );
    });
})();
