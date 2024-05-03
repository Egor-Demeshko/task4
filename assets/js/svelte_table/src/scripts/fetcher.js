/**
 *
 * @param {string} root
 * @param {*} options
 * @returns {Promise<Response>}
 */
export async function getData(root, options) {
    options = {
        method: "GET",
        ...options,
    };
    return await fetch(root, options);
}

/**
 * @param {string} root
 * @param {*} options
 * @returns {Promise<Response>}
 */
export async function sendData(root, options) {
    options = {
        method: "POST",
        ...options,
    };
    return await fetch(root, options);
}
