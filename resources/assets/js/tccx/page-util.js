export function scrollToElement(element) {
    return () => {
        if (Boolean(element))
            window.location.href = "#" + element;
    };
}

/**
 * Mount a component if given element exists
 * @param element element selector
 * @param instance Vue instance
 * @param predicate additional boolean predicate before mounting component
 */
export function mountVueApp(element, instance, predicate = true) {
    if ($(element).length && (predicate instanceof Function ? predicate() : predicate))
        instance.$mount(element);
}

/**
 * show confirmation dialog before user leave the page
 */
export function beforeLeave() {
    window.onbeforeunload = function () {
        return 'Are you sure you want to leave?';
    };
}