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
        e.preventDefault();

        let response = await fetch("/", {
            method: "POST",
            body: new FormData(this.#form),
        });
        if (response.ok) {
            let errorObj = await response.json();
            if (errorObj.status === STATUS_FALSE) {
                this.#processError(errorObj?.data);
            }
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
