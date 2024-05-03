function createStore() {
    /**
     * @type {{[key: string]: boolean}}
     */
    const pickedId = {};

    return {
        add,
        remove,
        getPicked,
    };

    /**
     *
     * @param {number} id
     */
    function add(id) {
        pickedId[id] = true;
        console.log(pickedId);
    }

    /**
     *
     * @param {number} id
     */
    function remove(id) {
        if (pickedId[id]) delete pickedId[id];
    }

    /**
     *
     * @returns {number[]}
     */
    function getPicked() {
        const arr = [];
        for (let key of Object.keys(pickedId)) {
            arr.push(parseInt(key));
        }
        return arr;
    }
}

const pickedElementsStore = createStore();

export default pickedElementsStore;
