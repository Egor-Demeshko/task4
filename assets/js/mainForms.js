import FormController from "./FormController.js";

const config = {
    registration: null,
    login: null,
};

const elements = {};
const controllers = {};

const hideClass = "hide";
const START_STATE = "translate(-50%, -50%)";
const PRE_END_STATE = "translate(-250%, -50%)";
const END_STATE = "translate(200%, -50%)";
const DURATION_TIME = 400;
let lock = false;

(function mainForms() {
    for (let key of Object.keys(config)) {
        let el = document.querySelector(`[data-form=${key}]`);
        if (!el || !el instanceof HTMLElement) return;

        if (key !== "login") {
            controllers[key] = new FormController(el);
        }

        elements[key] = el;
        el.querySelector("[data-goto]").addEventListener(
            "click",
            changeActiveElement
        );
    }

    positionElementOnHide(elements["registration"]);
    fadeIn(elements["login"]);
})();

function fadeOut(el) {
    let { finished } = el.animate(
        [
            { transform: START_STATE, opacity: 1 },
            { transform: PRE_END_STATE, opacity: 0 },
        ],
        { duration: DURATION_TIME, fill: "forwards" }
    );

    finished.then(() => {
        setTimeout(() => {
            el.style.transform = END_STATE;
            lock = false;
        }, 30);
    });
}

function fadeIn(el) {
    //el.classList.remove(hideClass);
    el.animate(
        [
            { transform: END_STATE, opacity: 0 },
            { transform: START_STATE, opacity: 1 },
        ],
        {
            duration: DURATION_TIME,
            fill: "forwards",
        }
    );
}

function changeActiveElement({ target }) {
    if (lock) return;
    if (target.tagName !== "SPAN") return;

    lock = true;

    let visibleForm = target.getAttribute("data-goto");

    for (let [key, el] of Object.entries(elements)) {
        if (key === visibleForm) {
            fadeOut(el);
        } else {
            fadeIn(el);
        }
    }
}

function positionElementOnHide(el) {
    el.style.transform = END_STATE;
}
