<?php

namespace App\Livewire\Resume;

use Livewire\Component;

class MarkdownEditor extends Component
{
    public $content = '';

    public $editorId;

    public $height = '300px';

    public $placeholder = '開始撰寫您的履歷簡介...';

    public function mount($content = '', $height = '300px', $placeholder = '開始撰寫您的履歷簡介...')
    {
        $this->content = $content;
        $this->height = $height;
        $this->placeholder = $placeholder;
        $this->editorId = 'markdown-editor-'.uniqid();
    }

    public function updatedContent($value)
    {
        // 當內容更新時，發送事件給父組件
        $this->dispatch('markdown-content-updated', $value);
    }

    public function updateContent($content)
    {
        $this->content = $content;
        // 發送事件給父組件
        $this->dispatch('markdown-content-updated', $content);
    }

    public function render()
    {
        return view('livewire.resume.markdown-editor');
    }
}
