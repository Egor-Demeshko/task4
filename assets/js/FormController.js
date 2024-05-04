import ErrorMessage from "./ErrorMessage.js";
const STATUS_FALSE = false;

export default class FormController {
    #formWrapper = null;
    #form = null;
    constructor(formElement) {
        this.#formWrapper = formElement;
        this.#createListeners();
    }

    #createListeners() {
        this.#form = this.#formWrapper.querySelector("form");

        if (!this.#form) return;

        this.#form.addEventListener("submit", (e) => this.#submitHandler(e));
    }

    async #submitHandler(e) {
        e.stopPropagation();
        e.preventDefault();
        let submitRoute = "";

        if (this.#form.getAttribute("action") === "login") {
            submitRoute = "login";
        } else {
            submitRoute = "/";
        }

        let response = await fetch(submitRoute, {
            method: "POST",
            body: new FormData(this.#form),
        });

        let errorObj = await response.json();
        if (errorObj.status === STATUS_FALSE) {
            this.#processError(errorObj?.data);
        } else if (errorObj.status && errorObj.redirect) {
            let link = errorObj.redirect;
            let params = errorObj.params;

            if (params) {
                link += "?";
                for (let [key, value] of Object.entries(params)) {
                    link += `${key}=${value}&`;
                }

                if (link[link.length - 1] === "&") link = link.slice(0, -1);
            }
            window.location.href = link;
        }
    }

    #processError(data) {
        if (!data) return;
        const form = this.#form;
        /**
         * @param {{field: string, value: string, message: string}} errorData
         * @param {string} key - name of the field where error was encountered
         */
        for (let [key, errorData] of Object.entries(data)) {
            // at first we try to get element with exact field type, if not, then
            let element = form.querySelector(`[type=${key}]`);
            if (element) {
                ErrorMessage.setInputInvalid(element);
            }

            // field with exact name
            if (!element) {
                element = form.querySelector(`[name=${key}]`);
            }

            // filed with contain name
            if (!element) {
                element = form.querySelector(`[name*=${key}]`);
            }

            if (element) {
                ErrorMessage.createErrorMessage(element, errorData);
            }
        }
    }
}
