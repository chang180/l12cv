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
            
            // 檢查是否已經有編輯器實例
            if (window.toastuiEditors && window.toastuiEditors[editorId]) {
                editor = window.toastuiEditors[editorId];
                isInitialized = true;
                return;
            }

            function initEditor() {
                initAttempts++;

                // 檢查Toast UI Editor是否已載入
                if (typeof toastui === 'undefined') {
                    if (initAttempts < maxAttempts) {
                        setTimeout(initEditor, 500);
                    }
                    return;
                }

                const { Editor } = toastui;
                const element = document.querySelector('#' + editorId);

                if (!element) {
                    if (initAttempts < maxAttempts) {
                        setTimeout(initEditor, 500);
                    }
                    return;
                }

                // 檢查元素是否可見
                if (element.offsetParent === null) {
                    if (initAttempts < maxAttempts) {
                        setTimeout(initEditor, 500);
                    }
                    return;
                }

                // 如果已經初始化過且編輯器存在，不重複初始化
                if (isInitialized && editor) {
                    return;
                }

                // 如果編輯器已存在，先清理
                if (editor) {
                    try {
                        // 檢查編輯器是否仍然有效
                        if (editor.getMarkdown) {
                            editor.destroy();
                        }
                    } catch (e) {
                        // 靜默處理錯誤
                    }
                    editor = null;
                }

                try {
                    // 檢查是否為 dark mode
                    const isDark = document.documentElement.classList.contains('dark');
                    
                    editor = new Editor({
                        el: element,
                        height: '{{ $height }}',
                        initialEditType: 'markdown',
                        previewStyle: 'vertical',
                        placeholder: placeholder,
                        initialValue: content || '',
                        usageStatistics: false,
                        hideModeSwitch: false,
                        theme: isDark ? 'dark' : 'light', // 使用內建主題
                        toolbarItems: [
                            ['heading', 'bold', 'italic', 'strike'],
                            ['hr', 'quote'],
                            ['ul', 'ol', 'task', 'indent', 'outdent'],
                            ['table', 'image', 'link'],
                            ['code', 'codeblock']
                        ],
                        events: {
                            change: function() {
                                // 使用防抖來避免頻繁更新
                                clearTimeout(debounceTimer);
                                debounceTimer = setTimeout(() => {
                                    try {
                                        const markdown = editor.getMarkdown();
                                        if (typeof Livewire !== 'undefined' && window.Livewire) {
                                            @this.call('updateContent', markdown);
                                        }
                                    } catch (e) {
                                        // 靜默處理錯誤
                                    }
                                }, 1000); // 1秒防抖
                            }
                        }
                    });

                    isInitialized = true;
                    
                    // 將編輯器實例存儲到全域變數中
                    if (!window.toastuiEditors) {
                        window.toastuiEditors = {};
                    }
                    window.toastuiEditors[editorId] = editor;

                } catch (error) {
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
                setTimeout(initEditor, 100);
            });

            // 頁面完全載入後再試一次
            window.addEventListener('load', function() {
                setTimeout(initEditor, 200);
            });

            // 定時檢查（作為最後的保險）
            const visibilityChecker = setInterval(() => {
                if (!isInitialized || !editor) {
                    const element = document.querySelector('#' + editorId);
                    if (element && element.offsetParent !== null) {
                        initEditor();
                    }
                } else {
                    // 編輯器已初始化，停止檢查
                    clearInterval(visibilityChecker);
                }
            }, 1000);

            // 監聽 dark mode 變化
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        if (editor && isInitialized) {
                            // 重新初始化編輯器以應用新主題
                            setTimeout(() => {
                                try {
                                    let currentContent = '';
                                    
                                    // 安全地獲取當前內容
                                    if (editor && editor.getMarkdown) {
                                        currentContent = editor.getMarkdown();
                                    }
                                    
                                    // 安全地銷毀編輯器
                                    if (editor && editor.destroy) {
                                        try {
                                            editor.destroy();
                                        } catch (destroyError) {
                                            // 靜默處理銷毀錯誤
                                        }
                                    }
                                    
                                    editor = null;
                                    isInitialized = false;
                                    
                                    // 清除全域變數中的編輯器實例
                                    if (window.toastuiEditors && window.toastuiEditors[editorId]) {
                                        delete window.toastuiEditors[editorId];
                                    }
                                    
                                    // 重新初始化編輯器
                                    initEditor();
                                } catch (e) {
                                    // 靜默處理錯誤
                                }
                            }, 100);
                        }
                    }
                });
            });

            // 開始監聽 document 的 class 變化
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });

            // 清理函數
            window.addEventListener('beforeunload', () => {
                clearInterval(visibilityChecker);
                observer.disconnect();
                if (editor) {
                    try {
                        if (editor.destroy) {
                            editor.destroy();
                        }
                    } catch (e) {
                        // 靜默處理錯誤
                    }
                }
                
                // 清理全域變數
                if (window.toastuiEditors && window.toastuiEditors[editorId]) {
                    delete window.toastuiEditors[editorId];
                }
            });
        })();
    </script>
</div>
