(function () {
    const blockedKeys = new Set(['u', 's']);

    document.addEventListener('contextmenu', function (event) {
        event.preventDefault();
    });

    document.addEventListener('keydown', function (event) {
        const key = String(event.key || '').toLowerCase();
        const blockedCtrlKey = (event.ctrlKey || event.metaKey) && blockedKeys.has(key);
        const blockedInspectKey = (event.ctrlKey || event.metaKey) && event.shiftKey && ['i', 'j', 'c'].includes(key);

        if (event.key === 'F12' || blockedCtrlKey || blockedInspectKey) {
            event.preventDefault();
            event.stopPropagation();
        }
    }, true);
})();
