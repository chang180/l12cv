<?php

namespace App\Livewire\Resume;

use App\Helpers\MarkdownHelper;
use Livewire\Component;

class MarkdownPreview extends Component
{
    public $markdown = '';

    public $html = '';

    public function mount($markdown = '')
    {
        $this->markdown = $markdown;
        $this->updatePreview();
    }

    public function updatedMarkdown()
    {
        $this->updatePreview();
    }

    public function updatePreview()
    {
        if (empty(trim($this->markdown))) {
            $this->html = '<div class="text-slate-500 dark:text-slate-400 italic text-sm">開始輸入 Markdown 內容...</div>';

            return;
        }

        $this->html = MarkdownHelper::getPreviewHtml($this->markdown);
    }

    public function render()
    {
        return view('livewire.resume.markdown-preview');
    }
}
