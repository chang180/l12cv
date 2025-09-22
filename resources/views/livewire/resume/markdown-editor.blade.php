<div wire:ignore>
    <div id="{{ $editorId }}" style="height: {{ $height }};"></div>

    <script>
        (function() {
            const editorId = '{{ $editorId }}';
            const content = @json($content);
            const placeholder = @json($placeholder);
            let editor = null;
            let debounceTimer = null;
            let isInitialized = false;
            let initAttempts = 0;
            const maxAttempts = 10;

            function initEditor() {
                initAttempts++;
                console.log(`Editor init attempt ${initAttempts}/${maxAttempts} for ${editorId}`);

                // 檢查Toast UI Editor是否已載入
                if (typeof toastui === 'undefined') {
                    console.log('Toast UI Editor not loaded yet, retrying...');
                    if (initAttempts < maxAttempts) {
                        setTimeout(initEditor, 500);
                    }
                    return;
                }

                const { Editor } = toastui;
                const element = document.querySelector('#' + editorId);

                if (!element) {
                    console.log('Editor element not found, retrying...');
                    if (initAttempts < maxAttempts) {
                        setTimeout(initEditor, 500);
                    }
                    return;
                }

                // 檢查元素是否可見
                if (element.offsetParent === null) {
                    console.log('Editor element not visible, retrying...');
                    if (initAttempts < maxAttempts) {
                        setTimeout(initEditor, 500);
                    }
                    return;
                }

                // 如果已經初始化過且編輯器存在，不重複初始化
                if (isInitialized && editor) {
                    console.log('Editor already initialized');
                    return;
                }

                // 如果編輯器已存在，先清理
                if (editor) {
                    try {
                        editor.destroy();
                    } catch (e) {
                        console.warn('Error destroying previous editor:', e);
                    }
                    editor = null;
                }

                try {
                    console.log('Creating new Toast UI Editor...');
                    editor = new Editor({
                        el: element,
                        height: '{{ $height }}',
                        initialEditType: 'markdown',
                        previewStyle: 'vertical',
                        placeholder: placeholder,
                        initialValue: content || '',
                        usageStatistics: false,
                        hideModeSwitch: false,
                        toolbarItems: [
                            ['heading', 'bold', 'italic', 'strike'],
                            ['hr', 'quote'],
                            ['ul', 'ol', 'task', 'indent', 'outdent'],
                            ['table', 'image', 'link'],
                            ['code', 'codeblock']
                        ],
                        events: {
                            change: function() {
                                console.log('🔥 Markdown editor change event triggered!');
                                // 使用防抖來避免頻繁更新
                                clearTimeout(debounceTimer);
                                debounceTimer = setTimeout(() => {
                                    try {
                                        const markdown = editor.getMarkdown();
                                        console.log('🔥 Markdown content:', markdown.substring(0, 50) + '...');
                                        if (typeof Livewire !== 'undefined' && window.Livewire) {
                                            console.log('🔥 Calling updateContent with Livewire...');
                                            @this.call('updateContent', markdown);
                                        } else {
                                            console.warn('🔥 Livewire not available!');
                                        }
                                    } catch (e) {
                                        console.warn('Error getting markdown content:', e);
                                    }
                                }, 1000); // 1秒防抖
                            }
                        }
                    });

                    isInitialized = true;
                    console.log('✅ Markdown editor initialized successfully!');

                } catch (error) {
                    console.error('❌ Error initializing markdown editor:', error);
                    isInitialized = false;
                    // 重試一次
                    if (initAttempts < maxAttempts) {
                        setTimeout(initEditor, 1000);
                    }
                }
            }

            // 多種初始化時機
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initEditor);
            } else {
                initEditor();
            }

            // Livewire 初始化時機
            document.addEventListener('livewire:init', function() {
                console.log('Livewire initialized, trying to init editor...');
                setTimeout(initEditor, 100);
            });

            // 頁面完全載入後再試一次
            window.addEventListener('load', function() {
                console.log('Window loaded, trying to init editor...');
                setTimeout(initEditor, 200);
            });

            // 定時檢查（作為最後的保險）
            const visibilityChecker = setInterval(() => {
                if (!isInitialized || !editor) {
                    const element = document.querySelector('#' + editorId);
                    if (element && element.offsetParent !== null) {
                        console.log('Visibility checker: trying to init editor...');
                        initEditor();
                    }
                } else {
                    // 編輯器已初始化，停止檢查
                    clearInterval(visibilityChecker);
                }
            }, 1000);

            // 清理函數
            window.addEventListener('beforeunload', () => {
                clearInterval(visibilityChecker);
                if (editor) {
                    try {
                        editor.destroy();
                    } catch (e) {
                        console.warn('Error destroying editor on unload:', e);
                    }
                }
            });
        })();
    </script>
</div>
