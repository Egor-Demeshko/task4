import { getData, sendData } from "./fetcher.js";

const API_ROUTE = "/api/v1/users";

/**
 *
 * @returns {Promise <string | false>}
 */
export async function getAllData() {
    const end = "/list";

    /**
     * @type {Response}
     */
    const result = await getData(API_ROUTE + end, {});
    return await makeJson(result);
}

/**
 *
 * @param { number[] } data
 * @returns
 */
export async function sendBlock(data) {
    const end = "/block";

    const result = await sendData(API_ROUTE + end, {
        body: JSON.stringify(data),
    });
    return await makeJson(result);
}

/**
 *
 * @param {Response} response
 * @returns
 */
async function makeJson(response) {
    if (response.ok) {
        return await response.json();
    } else {
        return false;
    }
}
