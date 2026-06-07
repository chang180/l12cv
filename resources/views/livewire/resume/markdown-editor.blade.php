<div wire:ignore>
    <div id="{{ $editorId }}" style="height: {{ $height }};" spellcheck="false" data-markdown-editor-root></div>
</div>

@script
<script>
    const editorId = @js($editorId);
    const initialContent = @js($content);
    const placeholder = @js($placeholder);
    const editorHeight = @js($height);

    let editor = null;
    let debounceTimer = null;
    let themeDebounceTimer = null;
    let retryTimer = null;
    let initialized = false;
    let currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    let visibilityObserver = null;
    let themeObserver = null;

    window.toastuiEditors = window.toastuiEditors || {};

    function getElement() {
        return document.getElementById(editorId);
    }

    function isElementVisible(element) {
        if (! element) {
            return false;
        }

        const rect = element.getBoundingClientRect();

        if (rect.width <= 0 || rect.height <= 0) {
            return false;
        }

        if (typeof element.checkVisibility === 'function') {
            return element.checkVisibility({
                checkOpacity: true,
                checkVisibilityCSS: true,
            });
        }

        return true;
    }

    function syncContent() {
        if (! editor || typeof editor.getMarkdown !== 'function') {
            return Promise.resolve();
        }

        try {
            const markdown = editor.getMarkdown();

            $wire.dispatch('update-parent-summary', { content: markdown });

            return $wire.call('updateContent', markdown);
        } catch (e) {
            return Promise.resolve();
        }
    }

    function scheduleSync() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            syncContent();
        }, 500);
    }

    function flushContent() {
        clearTimeout(debounceTimer);

        return syncContent();
    }

    function destroyEditor() {
        if (! editor) {
            return;
        }

        try {
            if (typeof editor.destroy === 'function') {
                editor.destroy();
            }
        } catch (e) {
            // 靜默處理錯誤
        }

        editor = null;
        initialized = false;

        if (window.toastuiEditors[editorId]) {
            delete window.toastuiEditors[editorId];
        }
    }

    function createEditor(element, value) {
        if (initialized || typeof toastui === 'undefined' || ! isElementVisible(element)) {
            return;
        }

        destroyEditor();

        editor = new toastui.Editor({
            el: element,
            height: editorHeight,
            initialEditType: 'markdown',
            previewStyle: 'vertical',
            placeholder: placeholder,
            initialValue: value || '',
            usageStatistics: false,
            hideModeSwitch: false,
            theme: currentTheme,
            toolbarItems: [
                ['heading', 'bold', 'italic', 'strike'],
                ['hr', 'quote'],
                ['ul', 'ol', 'task', 'indent', 'outdent'],
                ['table', 'image', 'link'],
                ['code', 'codeblock'],
            ],
            events: {
                change: scheduleSync,
            },
        });

        initialized = true;
        window.toastuiEditors[editorId] = editor;
    }

    function tryInitialize() {
        const element = getElement();

        if (! element || initialized) {
            return;
        }

        if (typeof toastui === 'undefined') {
            window.setTimeout(tryInitialize, 100);

            return;
        }

        if (! isElementVisible(element)) {
            return;
        }

        createEditor(element, initialContent);
    }

    function scheduleRetryInit() {
        clearInterval(retryTimer);

        let attempts = 0;

        retryTimer = window.setInterval(() => {
            if (initialized || attempts >= 40) {
                clearInterval(retryTimer);
                retryTimer = null;

                return;
            }

            attempts++;
            tryInitialize();
        }, 150);
    }

    function reinitializeForTheme() {
        const element = getElement();

        if (! element || ! initialized) {
            return;
        }

        let markdown = initialContent;

        try {
            markdown = editor?.getMarkdown?.() ?? initialContent;
        } catch (e) {
            markdown = initialContent;
        }

        destroyEditor();
        createEditor(element, markdown);
    }

    function handleThemeChange() {
        clearTimeout(themeDebounceTimer);

        themeDebounceTimer = window.setTimeout(() => {
            const nextTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';

            if (nextTheme === currentTheme) {
                return;
            }

            currentTheme = nextTheme;
            reinitializeForTheme();
        }, 250);
    }

    function setupObservers() {
        const element = getElement();

        if (! element || visibilityObserver) {
            return;
        }

        visibilityObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    tryInitialize();
                }
            });
        }, { threshold: 0.01 });

        visibilityObserver.observe(element);

        themeObserver = new MutationObserver((mutations) => {
            const classChanged = mutations.some(
                (mutation) => mutation.type === 'attributes' && mutation.attributeName === 'class',
            );

            if (classChanged) {
                handleThemeChange();
            }
        });

        themeObserver.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class'],
        });
    }

    window.markdownEditorFlushers = window.markdownEditorFlushers || {};
    window.markdownEditorFlushers[editorId] = flushContent;

    window.flushMarkdownEditors = function () {
        return Promise.all(
            Object.values(window.markdownEditorFlushers || {}).map((flusher) => flusher()),
        );
    };

    function cleanup() {
        clearTimeout(debounceTimer);
        clearTimeout(themeDebounceTimer);
        clearInterval(retryTimer);
        visibilityObserver?.disconnect();
        themeObserver?.disconnect();
        visibilityObserver = null;
        themeObserver = null;

        if (window.markdownEditorFlushers?.[editorId]) {
            delete window.markdownEditorFlushers[editorId];
        }

        destroyEditor();
    }

    if (window.toastuiEditors[editorId]) {
        editor = window.toastuiEditors[editorId];
        initialized = true;
    } else {
        setupObservers();
        requestAnimationFrame(tryInitialize);
        scheduleRetryInit();
    }

    document.addEventListener('livewire:navigating', cleanup, { once: true });
</script>
@endscript
