export default class ErrorMessage {
    /**
     *
     * @param {HTMLElement} element
     * @param {[{field: string, value: string, message: string}]} errors
     */
    static createErrorMessage(element, errors) {
        const ul = document.createElement("ul");
        this.#addListClasses(ul);
        this.#addErrorClasses(element);

        //go through errors
        for (const error of errors) {
            const li = document.createElement("li");
            li.innerText = error.message;
            this.#addErrorClasses(li);
            ul.appendChild(li);
        }

        const parent = element.closest("div");
        if (parent) {
            parent.prepend(ul);
        }
    }

    /**
     *
     * @param {HTMLElement} element
     */
    static #addErrorClasses(element) {
        //we should find input if out element is not UL or LI
        //
    }

    static #addListClasses(ul) {
        ul.classList.add("error__list");
    }

    /**
     *
     * @param {HTMLElement} el
     */
    static setInputInvalid(el) {
        el.classList.add("is-invalid");
    }
}
