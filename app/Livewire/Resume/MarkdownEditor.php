<?php

namespace App\Livewire\Resume;

use Livewire\Component;

class MarkdownEditor extends Component
{
    public $content = '';

    public $editorId;

    public $height = '300px';

    public $placeholder = '開始撰寫您的履歷簡介...';

    public string $parentEvent = 'update-parent-summary';

    public mixed $context = null;

    public function mount(
        $content = '',
        $height = '300px',
        $placeholder = '開始撰寫您的履歷簡介...',
        string $parentEvent = 'update-parent-summary',
        mixed $context = null,
    ): void {
        $this->content = $content;
        $this->height = $height;
        $this->placeholder = $placeholder;
        $this->parentEvent = $parentEvent;
        $this->context = $context;
        $this->editorId = 'markdown-editor-'.uniqid();
    }

    public function updatedContent($value)
    {
        // 當內容更新時，發送事件給父組件
        $this->dispatch('markdown-content-updated', $value);
    }

    public function updateContent(string $content): void
    {
        $this->content = $content;
        $this->dispatch($this->parentEvent, content: $content, context: $this->context);
    }

    public function render()
    {
        return view('livewire.resume.markdown-editor');
    }
}
