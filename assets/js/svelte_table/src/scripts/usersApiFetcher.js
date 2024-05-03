import { getData, sendData, deleteData } from "./fetcher.js";
import { changeVisibleDataSimple } from "../lib/stores/changeVisibleDataSimple.js";
import { deleteRowsStore } from "../lib/stores/deleteRowsStore.js";

const API_ROUTE = "/api/v1/users";

/**
 *
 * @returns {Promise <{ data: any[] }>}
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
    {
        let json = await makeJson(result);
        if (json.redirect) {
            window.location.href = "/";
        }

        if (json.status) {
            changeVisibleDataSimple.set({
                field: { value: "block", name: "status" },
                ids: data,
            });
        }
    }
}

/**
 *
 * @param {number[]} data
 */
export async function sendUNBlock(data) {
    const end = "/unblock";

    const result = await sendData(API_ROUTE + end, {
        body: JSON.stringify(data),
    });
    {
        let json = await makeJson(result);

        if (json.status) {
            changeVisibleDataSimple.set({
                field: { value: "active", name: "status" },
                ids: data,
            });
        }
    }
}

/**
 *
 * @param {number[]} data
 */
export async function sendDelete(data) {
    const end = "/delete";

    const result = await deleteData(API_ROUTE + end, {
        body: JSON.stringify(data),
    });
    {
        let json = await makeJson(result);

        if (json.status) {
            deleteRowsStore.set(data);
        }
    }
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
