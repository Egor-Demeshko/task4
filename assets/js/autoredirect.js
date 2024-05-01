export default class AutoRedirect {
    #element = null;
    constructor(elQuery, time, redirectTo) {
        this.#element = document.querySelector(elQuery);
        if (!this.#element) return null;

        this.startCountdown(time, redirectTo);
    }

    startCountdown(time, redirectTo) {
        let counterId = setInterval(() => {
            time--;
            this.#element.textContent = time;

            if (time <= 0) {
                window.location.href = redirectTo;
                clearInterval(counterId);
            }
        }, 1000);
    }
}
