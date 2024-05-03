var table = function() {
  "use strict";var __defProp = Object.defineProperty;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __publicField = (obj, key, value) => {
  __defNormalProp(obj, typeof key !== "symbol" ? key + "" : key, value);
  return value;
};

  function noop() {
  }
  function run(fn) {
    return fn();
  }
  function blank_object() {
    return /* @__PURE__ */ Object.create(null);
  }
  function run_all(fns) {
    fns.forEach(run);
  }
  function is_function(thing) {
    return typeof thing === "function";
  }
  function safe_not_equal(a, b) {
    return a != a ? b == b : a !== b || a && typeof a === "object" || typeof a === "function";
  }
  function is_empty(obj) {
    return Object.keys(obj).length === 0;
  }
  function append(target, node) {
    target.appendChild(node);
  }
  function insert(target, node, anchor) {
    target.insertBefore(node, anchor || null);
  }
  function detach(node) {
    if (node.parentNode) {
      node.parentNode.removeChild(node);
    }
  }
  function destroy_each(iterations, detaching) {
    for (let i = 0; i < iterations.length; i += 1) {
      if (iterations[i])
        iterations[i].d(detaching);
    }
  }
  function element(name) {
    return document.createElement(name);
  }
  function text(data) {
    return document.createTextNode(data);
  }
  function space() {
    return text(" ");
  }
  function empty() {
    return text("");
  }
  function listen(node, event, handler, options) {
    node.addEventListener(event, handler, options);
    return () => node.removeEventListener(event, handler, options);
  }
  function attr(node, attribute, value) {
    if (value == null)
      node.removeAttribute(attribute);
    else if (node.getAttribute(attribute) !== value)
      node.setAttribute(attribute, value);
  }
  function children(element2) {
    return Array.from(element2.childNodes);
  }
  let current_component;
  function set_current_component(component) {
    current_component = component;
  }
  function get_current_component() {
    if (!current_component)
      throw new Error("Function called outside component initialization");
    return current_component;
  }
  function onMount(fn) {
    get_current_component().$$.on_mount.push(fn);
  }
  const dirty_components = [];
  const binding_callbacks = [];
  let render_callbacks = [];
  const flush_callbacks = [];
  const resolved_promise = /* @__PURE__ */ Promise.resolve();
  let update_scheduled = false;
  function schedule_update() {
    if (!update_scheduled) {
      update_scheduled = true;
      resolved_promise.then(flush);
    }
  }
  function add_render_callback(fn) {
    render_callbacks.push(fn);
  }
  const seen_callbacks = /* @__PURE__ */ new Set();
  let flushidx = 0;
  function flush() {
    if (flushidx !== 0) {
      return;
    }
    const saved_component = current_component;
    do {
      try {
        while (flushidx < dirty_components.length) {
          const component = dirty_components[flushidx];
          flushidx++;
          set_current_component(component);
          update(component.$$);
        }
      } catch (e) {
        dirty_components.length = 0;
        flushidx = 0;
        throw e;
      }
      set_current_component(null);
      dirty_components.length = 0;
      flushidx = 0;
      while (binding_callbacks.length)
        binding_callbacks.pop()();
      for (let i = 0; i < render_callbacks.length; i += 1) {
        const callback = render_callbacks[i];
        if (!seen_callbacks.has(callback)) {
          seen_callbacks.add(callback);
          callback();
        }
      }
      render_callbacks.length = 0;
    } while (dirty_components.length);
    while (flush_callbacks.length) {
      flush_callbacks.pop()();
    }
    update_scheduled = false;
    seen_callbacks.clear();
    set_current_component(saved_component);
  }
  function update($$) {
    if ($$.fragment !== null) {
      $$.update();
      run_all($$.before_update);
      const dirty = $$.dirty;
      $$.dirty = [-1];
      $$.fragment && $$.fragment.p($$.ctx, dirty);
      $$.after_update.forEach(add_render_callback);
    }
  }
  function flush_render_callbacks(fns) {
    const filtered = [];
    const targets = [];
    render_callbacks.forEach((c) => fns.indexOf(c) === -1 ? filtered.push(c) : targets.push(c));
    targets.forEach((c) => c());
    render_callbacks = filtered;
  }
  const outroing = /* @__PURE__ */ new Set();
  let outros;
  function group_outros() {
    outros = {
      r: 0,
      c: [],
      p: outros
      // parent group
    };
  }
  function check_outros() {
    if (!outros.r) {
      run_all(outros.c);
    }
    outros = outros.p;
  }
  function transition_in(block, local) {
    if (block && block.i) {
      outroing.delete(block);
      block.i(local);
    }
  }
  function transition_out(block, local, detach2, callback) {
    if (block && block.o) {
      if (outroing.has(block))
        return;
      outroing.add(block);
      outros.c.push(() => {
        outroing.delete(block);
        if (callback) {
          if (detach2)
            block.d(1);
          callback();
        }
      });
      block.o(local);
    } else if (callback) {
      callback();
    }
  }
  function ensure_array_like(array_like_or_iterator) {
    return (array_like_or_iterator == null ? void 0 : array_like_or_iterator.length) !== void 0 ? array_like_or_iterator : Array.from(array_like_or_iterator);
  }
  function create_component(block) {
    block && block.c();
  }
  function mount_component(component, target, anchor) {
    const { fragment, after_update } = component.$$;
    fragment && fragment.m(target, anchor);
    add_render_callback(() => {
      const new_on_destroy = component.$$.on_mount.map(run).filter(is_function);
      if (component.$$.on_destroy) {
        component.$$.on_destroy.push(...new_on_destroy);
      } else {
        run_all(new_on_destroy);
      }
      component.$$.on_mount = [];
    });
    after_update.forEach(add_render_callback);
  }
  function destroy_component(component, detaching) {
    const $$ = component.$$;
    if ($$.fragment !== null) {
      flush_render_callbacks($$.after_update);
      run_all($$.on_destroy);
      $$.fragment && $$.fragment.d(detaching);
      $$.on_destroy = $$.fragment = null;
      $$.ctx = [];
    }
  }
  function make_dirty(component, i) {
    if (component.$$.dirty[0] === -1) {
      dirty_components.push(component);
      schedule_update();
      component.$$.dirty.fill(0);
    }
    component.$$.dirty[i / 31 | 0] |= 1 << i % 31;
  }
  function init(component, options, instance2, create_fragment2, not_equal, props, append_styles = null, dirty = [-1]) {
    const parent_component = current_component;
    set_current_component(component);
    const $$ = component.$$ = {
      fragment: null,
      ctx: [],
      // state
      props,
      update: noop,
      not_equal,
      bound: blank_object(),
      // lifecycle
      on_mount: [],
      on_destroy: [],
      on_disconnect: [],
      before_update: [],
      after_update: [],
      context: new Map(options.context || (parent_component ? parent_component.$$.context : [])),
      // everything else
      callbacks: blank_object(),
      dirty,
      skip_bound: false,
      root: options.target || parent_component.$$.root
    };
    append_styles && append_styles($$.root);
    let ready = false;
    $$.ctx = instance2 ? instance2(component, options.props || {}, (i, ret, ...rest) => {
      const value = rest.length ? rest[0] : ret;
      if ($$.ctx && not_equal($$.ctx[i], $$.ctx[i] = value)) {
        if (!$$.skip_bound && $$.bound[i])
          $$.bound[i](value);
        if (ready)
          make_dirty(component, i);
      }
      return ret;
    }) : [];
    $$.update();
    ready = true;
    run_all($$.before_update);
    $$.fragment = create_fragment2 ? create_fragment2($$.ctx) : false;
    if (options.target) {
      if (options.hydrate) {
        const nodes = children(options.target);
        $$.fragment && $$.fragment.l(nodes);
        nodes.forEach(detach);
      } else {
        $$.fragment && $$.fragment.c();
      }
      if (options.intro)
        transition_in(component.$$.fragment);
      mount_component(component, options.target, options.anchor);
      flush();
    }
    set_current_component(parent_component);
  }
  class SvelteComponent {
    constructor() {
      /**
       * ### PRIVATE API
       *
       * Do not use, may change at any time
       *
       * @type {any}
       */
      __publicField(this, "$$");
      /**
       * ### PRIVATE API
       *
       * Do not use, may change at any time
       *
       * @type {any}
       */
      __publicField(this, "$$set");
    }
    /** @returns {void} */
    $destroy() {
      destroy_component(this, 1);
      this.$destroy = noop;
    }
    /**
     * @template {Extract<keyof Events, string>} K
     * @param {K} type
     * @param {((e: Events[K]) => void) | null | undefined} callback
     * @returns {() => void}
     */
    $on(type, callback) {
      if (!is_function(callback)) {
        return noop;
      }
      const callbacks = this.$$.callbacks[type] || (this.$$.callbacks[type] = []);
      callbacks.push(callback);
      return () => {
        const index = callbacks.indexOf(callback);
        if (index !== -1)
          callbacks.splice(index, 1);
      };
    }
    /**
     * @param {Partial<Props>} props
     * @returns {void}
     */
    $set(props) {
      if (this.$$set && !is_empty(props)) {
        this.$$.skip_bound = true;
        this.$$set(props);
        this.$$.skip_bound = false;
      }
    }
  }
  const PUBLIC_VERSION = "4";
  if (typeof window !== "undefined")
    (window.__svelte || (window.__svelte = { v: /* @__PURE__ */ new Set() })).v.add(PUBLIC_VERSION);
  async function getData(root, options) {
    options = {
      method: "GET",
      ...options
    };
    return await fetch(root, options);
  }
  async function sendData(root, options) {
    options = {
      method: "POST",
      ...options
    };
    return await fetch(root, options);
  }
  async function deleteData(root, options) {
    options = {
      method: "DELETE",
      ...options
    };
    return await fetch(root, options);
  }
  const subscriber_queue = [];
  function writable(value, start = noop) {
    let stop;
    const subscribers = /* @__PURE__ */ new Set();
    function set(new_value) {
      if (safe_not_equal(value, new_value)) {
        value = new_value;
        if (stop) {
          const run_queue = !subscriber_queue.length;
          for (const subscriber of subscribers) {
            subscriber[1]();
            subscriber_queue.push(subscriber, value);
          }
          if (run_queue) {
            for (let i = 0; i < subscriber_queue.length; i += 2) {
              subscriber_queue[i][0](subscriber_queue[i + 1]);
            }
            subscriber_queue.length = 0;
          }
        }
      }
    }
    function update2(fn) {
      set(fn(value));
    }
    function subscribe(run2, invalidate = noop) {
      const subscriber = [run2, invalidate];
      subscribers.add(subscriber);
      if (subscribers.size === 1) {
        stop = start(set, update2) || noop;
      }
      run2(value);
      return () => {
        subscribers.delete(subscriber);
        if (subscribers.size === 0 && stop) {
          stop();
          stop = null;
        }
      };
    }
    return { set, update: update2, subscribe };
  }
  const changeVisibleDataSimple = writable({ field: null, ids: null });
  const deleteRowsStore = writable([]);
  const API_ROUTE = "/api/v1/users";
  async function getAllData() {
    const end = "/list";
    const result = await getData(API_ROUTE + end, {});
    return await makeJson(result);
  }
  async function sendBlock(data) {
    const end = "/block";
    const result = await sendData(API_ROUTE + end, {
      body: JSON.stringify(data)
    });
    {
      let json = await makeJson(result);
      if (json.redirect) {
        window.location.href = "/";
      }
      if (json.status) {
        changeVisibleDataSimple.set({
          field: { value: "block", name: "status" },
          ids: data
        });
      }
    }
  }
  async function sendUNBlock(data) {
    const end = "/unblock";
    const result = await sendData(API_ROUTE + end, {
      body: JSON.stringify(data)
    });
    {
      let json = await makeJson(result);
      if (json.status) {
        changeVisibleDataSimple.set({
          field: { value: "active", name: "status" },
          ids: data
        });
      }
    }
  }
  async function sendDelete(data) {
    const end = "/delete";
    const result = await deleteData(API_ROUTE + end, {
      body: JSON.stringify(data)
    });
    {
      let json = await makeJson(result);
      if (json.status) {
        deleteRowsStore.set(data);
      }
    }
  }
  async function makeJson(response) {
    if (response.ok) {
      return await response.json();
    } else {
      return false;
    }
  }
  function createStore() {
    const pickedId = {};
    return {
      add,
      remove,
      getPicked
    };
    function add(id) {
      pickedId[id] = true;
    }
    function remove(id) {
      if (pickedId[id])
        delete pickedId[id];
    }
    function getPicked() {
      const arr = [];
      for (let key of Object.keys(pickedId)) {
        arr.push(parseInt(key));
      }
      return arr;
    }
  }
  const pickedElementsStore = createStore();
  function create_fragment$3(ctx) {
    let input_1;
    let mounted;
    let dispose;
    return {
      c() {
        input_1 = element("input");
        attr(input_1, "type", "checkbox");
        attr(input_1, "class", "form-check-input");
      },
      m(target, anchor) {
        insert(target, input_1, anchor);
        ctx[2](input_1);
        if (!mounted) {
          dispose = listen(
            input_1,
            "click",
            /*clickHandler*/
            ctx[1]
          );
          mounted = true;
        }
      },
      p: noop,
      i: noop,
      o: noop,
      d(detaching) {
        if (detaching) {
          detach(input_1);
        }
        ctx[2](null);
        mounted = false;
        dispose();
      }
    };
  }
  const globalCheckState = writable(false);
  function instance$4($$self, $$props, $$invalidate) {
    let input;
    function clickHandler() {
      if (input.checked) {
        globalCheckState.set(true);
      } else {
        globalCheckState.set(false);
      }
    }
    function input_1_binding($$value) {
      binding_callbacks[$$value ? "unshift" : "push"](() => {
        input = $$value;
        $$invalidate(0, input);
      });
    }
    return [input, clickHandler, input_1_binding];
  }
  class GlobalCheckBox extends SvelteComponent {
    constructor(options) {
      super();
      init(this, options, instance$4, create_fragment$3, safe_not_equal, {});
    }
  }
  function create_fragment$2(ctx) {
    let input_1;
    let mounted;
    let dispose;
    return {
      c() {
        input_1 = element("input");
        attr(input_1, "type", "checkbox");
        attr(input_1, "class", "form-check-input");
        attr(input_1, "data-input", SIMPLE_INPUT);
      },
      m(target, anchor) {
        insert(target, input_1, anchor);
        ctx[3](input_1);
        if (!mounted) {
          dispose = listen(
            input_1,
            "click",
            /*clickHandler*/
            ctx[1]
          );
          mounted = true;
        }
      },
      p: noop,
      i: noop,
      o: noop,
      d(detaching) {
        if (detaching) {
          detach(input_1);
        }
        ctx[3](null);
        mounted = false;
        dispose();
      }
    };
  }
  const SIMPLE_INPUT = "simple";
  function instance$3($$self, $$props, $$invalidate) {
    let { id = 0 } = $$props;
    let input;
    function clickHandler(e) {
      e.stopPropagation();
      if (input.checked) {
        pickedElementsStore.add(id);
      } else {
        pickedElementsStore.remove(id);
      }
    }
    onMount(() => {
      const unsubscribe = globalCheckState.subscribe((state) => {
        if (state) {
          $$invalidate(0, input.checked = true, input);
          pickedElementsStore.add(id);
        } else if (state === false) {
          $$invalidate(0, input.checked = false, input);
          pickedElementsStore.remove(id);
        }
      });
      return () => {
        unsubscribe();
      };
    });
    function input_1_binding($$value) {
      binding_callbacks[$$value ? "unshift" : "push"](() => {
        input = $$value;
        $$invalidate(0, input);
      });
    }
    $$self.$$set = ($$props2) => {
      if ("id" in $$props2)
        $$invalidate(2, id = $$props2.id);
    };
    return [input, clickHandler, id, input_1_binding];
  }
  class Checkbox extends SvelteComponent {
    constructor(options) {
      super();
      init(this, options, instance$3, create_fragment$2, safe_not_equal, { id: 2 });
    }
  }
  function create_fragment$1(ctx) {
    let tr;
    let td0;
    let checkbox;
    let t0;
    let td1;
    let t2;
    let td2;
    let t4;
    let td3;
    let t6;
    let td4;
    let t8;
    let td5;
    let current;
    checkbox = new Checkbox({ props: { id: (
      /*id*/
      ctx[0]
    ) } });
    return {
      c() {
        tr = element("tr");
        td0 = element("td");
        create_component(checkbox.$$.fragment);
        t0 = space();
        td1 = element("td");
        td1.textContent = `${/*name*/
        ctx[1]}`;
        t2 = space();
        td2 = element("td");
        td2.textContent = `${/*email*/
        ctx[2]}`;
        t4 = space();
        td3 = element("td");
        td3.textContent = `${/*last_loggined_at*/
        ctx[3] ? (
          /*last_loggined_at*/
          ctx[3]
        ) : "none"}`;
        t6 = space();
        td4 = element("td");
        td4.textContent = `${/*status*/
        ctx[5]}`;
        t8 = space();
        td5 = element("td");
        td5.textContent = `${/*registrated_at*/
        ctx[4]}`;
        attr(td0, "class", "d-flex svelte-x2gqhf");
        attr(td1, "class", "svelte-x2gqhf");
        attr(td2, "class", "svelte-x2gqhf");
        attr(td3, "class", "svelte-x2gqhf");
        attr(td4, "class", "svelte-x2gqhf");
        attr(td5, "class", "svelte-x2gqhf");
        attr(tr, "class", "svelte-x2gqhf");
      },
      m(target, anchor) {
        insert(target, tr, anchor);
        append(tr, td0);
        mount_component(checkbox, td0, null);
        append(tr, t0);
        append(tr, td1);
        append(tr, t2);
        append(tr, td2);
        append(tr, t4);
        append(tr, td3);
        append(tr, t6);
        append(tr, td4);
        append(tr, t8);
        append(tr, td5);
        current = true;
      },
      p: noop,
      i(local) {
        if (current)
          return;
        transition_in(checkbox.$$.fragment, local);
        current = true;
      },
      o(local) {
        transition_out(checkbox.$$.fragment, local);
        current = false;
      },
      d(detaching) {
        if (detaching) {
          detach(tr);
        }
        destroy_component(checkbox);
      }
    };
  }
  function instance$2($$self, $$props, $$invalidate) {
    let { data } = $$props;
    let { id, name, email, last_loggined_at, registrated_at, status } = data;
    $$self.$$set = ($$props2) => {
      if ("data" in $$props2)
        $$invalidate(6, data = $$props2.data);
    };
    return [id, name, email, last_loggined_at, registrated_at, status, data];
  }
  class TableRow extends SvelteComponent {
    constructor(options) {
      super();
      init(this, options, instance$2, create_fragment$1, safe_not_equal, { data: 6 });
    }
  }
  function instance$1($$self) {
    const events = {
      "send-block": goBlock,
      "send-unblock": goUnblock,
      "send-delete": goDelete
    };
    onMount(() => {
      for (let [key, callback] of Object.entries(events)) {
        document.addEventListener(key, callback);
      }
    });
    async function goBlock() {
      const ids = getPicked();
      if (!ids || ids.length === 0)
        return;
      await sendBlock(ids);
    }
    async function goUnblock() {
      const ids = getPicked();
      if (!ids || ids.length === 0)
        return;
      await sendUNBlock(ids);
    }
    async function goDelete() {
      const ids = getPicked();
      if (!ids || ids.length === 0)
        return;
      await sendDelete(ids);
    }
    function getPicked() {
      return pickedElementsStore.getPicked();
    }
    return [];
  }
  class ButtonsApiController extends SvelteComponent {
    constructor(options) {
      super();
      init(this, options, instance$1, null, safe_not_equal, {});
    }
  }
  function get_each_context(ctx, list, i) {
    const child_ctx = ctx.slice();
    child_ctx[2] = list[i];
    return child_ctx;
  }
  function create_if_block(ctx) {
    let each_1_anchor;
    let current;
    let each_value = ensure_array_like(
      /*data*/
      ctx[0]
    );
    let each_blocks = [];
    for (let i = 0; i < each_value.length; i += 1) {
      each_blocks[i] = create_each_block(get_each_context(ctx, each_value, i));
    }
    const out = (i) => transition_out(each_blocks[i], 1, 1, () => {
      each_blocks[i] = null;
    });
    return {
      c() {
        for (let i = 0; i < each_blocks.length; i += 1) {
          each_blocks[i].c();
        }
        each_1_anchor = empty();
      },
      m(target, anchor) {
        for (let i = 0; i < each_blocks.length; i += 1) {
          if (each_blocks[i]) {
            each_blocks[i].m(target, anchor);
          }
        }
        insert(target, each_1_anchor, anchor);
        current = true;
      },
      p(ctx2, dirty) {
        if (dirty & /*data*/
        1) {
          each_value = ensure_array_like(
            /*data*/
            ctx2[0]
          );
          let i;
          for (i = 0; i < each_value.length; i += 1) {
            const child_ctx = get_each_context(ctx2, each_value, i);
            if (each_blocks[i]) {
              each_blocks[i].p(child_ctx, dirty);
              transition_in(each_blocks[i], 1);
            } else {
              each_blocks[i] = create_each_block(child_ctx);
              each_blocks[i].c();
              transition_in(each_blocks[i], 1);
              each_blocks[i].m(each_1_anchor.parentNode, each_1_anchor);
            }
          }
          group_outros();
          for (i = each_value.length; i < each_blocks.length; i += 1) {
            out(i);
          }
          check_outros();
        }
      },
      i(local) {
        if (current)
          return;
        for (let i = 0; i < each_value.length; i += 1) {
          transition_in(each_blocks[i]);
        }
        current = true;
      },
      o(local) {
        each_blocks = each_blocks.filter(Boolean);
        for (let i = 0; i < each_blocks.length; i += 1) {
          transition_out(each_blocks[i]);
        }
        current = false;
      },
      d(detaching) {
        if (detaching) {
          detach(each_1_anchor);
        }
        destroy_each(each_blocks, detaching);
      }
    };
  }
  function create_each_block(ctx) {
    let tablerow;
    let current;
    tablerow = new TableRow({ props: { data: { .../*user*/
    ctx[2] } } });
    return {
      c() {
        create_component(tablerow.$$.fragment);
      },
      m(target, anchor) {
        mount_component(tablerow, target, anchor);
        current = true;
      },
      p(ctx2, dirty) {
        const tablerow_changes = {};
        if (dirty & /*data*/
        1)
          tablerow_changes.data = { .../*user*/
          ctx2[2] };
        tablerow.$set(tablerow_changes);
      },
      i(local) {
        if (current)
          return;
        transition_in(tablerow.$$.fragment, local);
        current = true;
      },
      o(local) {
        transition_out(tablerow.$$.fragment, local);
        current = false;
      },
      d(detaching) {
        destroy_component(tablerow, detaching);
      }
    };
  }
  function create_key_block(ctx) {
    let if_block_anchor;
    let current;
    let if_block = (
      /*data*/
      ctx[0] && create_if_block(ctx)
    );
    return {
      c() {
        if (if_block)
          if_block.c();
        if_block_anchor = empty();
      },
      m(target, anchor) {
        if (if_block)
          if_block.m(target, anchor);
        insert(target, if_block_anchor, anchor);
        current = true;
      },
      p(ctx2, dirty) {
        if (
          /*data*/
          ctx2[0]
        ) {
          if (if_block) {
            if_block.p(ctx2, dirty);
            if (dirty & /*data*/
            1) {
              transition_in(if_block, 1);
            }
          } else {
            if_block = create_if_block(ctx2);
            if_block.c();
            transition_in(if_block, 1);
            if_block.m(if_block_anchor.parentNode, if_block_anchor);
          }
        } else if (if_block) {
          group_outros();
          transition_out(if_block, 1, 1, () => {
            if_block = null;
          });
          check_outros();
        }
      },
      i(local) {
        if (current)
          return;
        transition_in(if_block);
        current = true;
      },
      o(local) {
        transition_out(if_block);
        current = false;
      },
      d(detaching) {
        if (detaching) {
          detach(if_block_anchor);
        }
        if (if_block)
          if_block.d(detaching);
      }
    };
  }
  function create_fragment(ctx) {
    let div;
    let table2;
    let colgroup;
    let t5;
    let tr;
    let td;
    let globalcheckbox;
    let t6;
    let th0;
    let t8;
    let th1;
    let t10;
    let th2;
    let t12;
    let th3;
    let t14;
    let th4;
    let t16;
    let previous_key = (
      /*data*/
      ctx[0]
    );
    let t17;
    let apicontroller;
    let current;
    globalcheckbox = new GlobalCheckBox({});
    let key_block = create_key_block(ctx);
    apicontroller = new ButtonsApiController({});
    return {
      c() {
        div = element("div");
        table2 = element("table");
        colgroup = element("colgroup");
        colgroup.innerHTML = `<col span="1" class="column svelte-vxomek"/> <col span="1" class="column svelte-vxomek"/> <col span="1" class="column svelte-vxomek"/> <col span="1" class="column svelte-vxomek"/> <col span="1" class="column svelte-vxomek"/> <col span="1" class="column svelte-vxomek"/>`;
        t5 = space();
        tr = element("tr");
        td = element("td");
        create_component(globalcheckbox.$$.fragment);
        t6 = space();
        th0 = element("th");
        th0.textContent = "Name";
        t8 = space();
        th1 = element("th");
        th1.textContent = "e-Mail";
        t10 = space();
        th2 = element("th");
        th2.textContent = "Last Login";
        t12 = space();
        th3 = element("th");
        th3.textContent = "Status";
        t14 = space();
        th4 = element("th");
        th4.textContent = "Registrated At";
        t16 = space();
        key_block.c();
        t17 = space();
        create_component(apicontroller.$$.fragment);
        attr(td, "class", "d-flex svelte-vxomek");
        attr(th0, "scope", "col");
        attr(th0, "class", "svelte-vxomek");
        attr(th1, "scope", "col");
        attr(th1, "class", "svelte-vxomek");
        attr(th2, "scope", "col");
        attr(th2, "class", "svelte-vxomek");
        attr(th3, "scope", "col");
        attr(th3, "class", "svelte-vxomek");
        attr(th4, "scope", "col");
        attr(th4, "class", "svelte-vxomek");
        attr(tr, "class", "table__header svelte-vxomek");
        attr(table2, "class", "svelte-vxomek");
        attr(div, "class", "table__wrapper svelte-vxomek");
      },
      m(target, anchor) {
        insert(target, div, anchor);
        append(div, table2);
        append(table2, colgroup);
        append(table2, t5);
        append(table2, tr);
        append(tr, td);
        mount_component(globalcheckbox, td, null);
        append(tr, t6);
        append(tr, th0);
        append(tr, t8);
        append(tr, th1);
        append(tr, t10);
        append(tr, th2);
        append(tr, t12);
        append(tr, th3);
        append(tr, t14);
        append(tr, th4);
        append(table2, t16);
        key_block.m(table2, null);
        insert(target, t17, anchor);
        mount_component(apicontroller, target, anchor);
        current = true;
      },
      p(ctx2, [dirty]) {
        if (dirty & /*data*/
        1 && safe_not_equal(previous_key, previous_key = /*data*/
        ctx2[0])) {
          group_outros();
          transition_out(key_block, 1, 1, noop);
          check_outros();
          key_block = create_key_block(ctx2);
          key_block.c();
          transition_in(key_block, 1);
          key_block.m(table2, null);
        } else {
          key_block.p(ctx2, dirty);
        }
      },
      i(local) {
        if (current)
          return;
        transition_in(globalcheckbox.$$.fragment, local);
        transition_in(key_block);
        transition_in(apicontroller.$$.fragment, local);
        current = true;
      },
      o(local) {
        transition_out(globalcheckbox.$$.fragment, local);
        transition_out(key_block);
        transition_out(apicontroller.$$.fragment, local);
        current = false;
      },
      d(detaching) {
        if (detaching) {
          detach(div);
          detach(t17);
        }
        destroy_component(globalcheckbox);
        key_block.d(detaching);
        destroy_component(apicontroller, detaching);
      }
    };
  }
  function instance($$self, $$props, $$invalidate) {
    let data = null;
    onMount(() => {
      getUsers();
    });
    changeVisibleDataSimple.subscribe((changeData) => {
      let { field, ids } = changeData;
      if (!field || !ids || ids.length === 0)
        return;
      let { value, name } = field;
      if (!value || !name)
        return;
      let idSet = new Set(ids);
      data.forEach((obj) => {
        if (idSet.has(obj.id)) {
          obj[name] = value;
        }
      });
      $$invalidate(0, data);
    });
    deleteRowsStore.subscribe((ids) => {
      if (!ids || ids.length === 0)
        return;
      let idsSet = new Set(ids);
      $$invalidate(0, data = data.filter((obj) => {
        if (idsSet.has(obj.id)) {
          return false;
        }
        return true;
      }));
    });
    async function getUsers() {
      $$invalidate(0, data = await getAllData());
      $$invalidate(0, data = data.data);
    }
    return [data];
  }
  class App extends SvelteComponent {
    constructor(options) {
      super();
      init(this, options, instance, create_fragment, safe_not_equal, {});
    }
  }
  const app = new App({
    target: document.getElementById("table")
  });
  return app;
}();
