import { writable } from "svelte/store";

export const changeVisibleDataSimple = writable({ field: null, ids: null });
