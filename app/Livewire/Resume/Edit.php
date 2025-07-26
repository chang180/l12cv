<?php

namespace App\Livewire\Resume;

use Livewire\Component;
use App\Models\Resume;
use App\Models\Project;

class Edit extends Component
{
    public $resumeId;
    public $resume;
    public $title;
    public $summary;
    public $education = [];
    public $experience = [];
    public $currentTab = 'basic';

    public function mount($resumeId = null)
    {
        // 如果沒有提供 resumeId，則獲取當前用戶的履歷
        if (!$resumeId) {
            $this->resume = auth()->user()->resume;
            if (!$this->resume) {
                return redirect()->route('resume.dashboard')
                    ->with('error', '您還沒有建立履歷');
            }
            $this->resumeId = $this->resume->id;
        } else {
            $this->resumeId = $resumeId;
            $this->resume = Resume::findOrFail($resumeId);

        // 確保當前用戶有權編輯此履歷
        if ($this->resume->user_id !== auth()->id()) {
            abort(403);
        }
        }

        // 初始化表單數據
        $this->title = $this->resume->title;
        $this->summary = $this->resume->summary;
        $this->education = $this->resume->education ?? [];
        $this->experience = $this->resume->experience ?? [];
    }

    public function updateBasicInfo()
    {
        $this->resume->update([
            'title' => $this->title,
            'summary' => $this->summary,
        ]);

        $this->dispatch('notify', [
            'message' => '基本資料已更新',
            'type' => 'success',
        ]);
    }

    public function addEducation()
    {
        $this->education[] = [
            'school' => '',
            'degree' => '',
            'field' => '',
            'start_date' => '',
            'end_date' => '',
            'description' => '',
        ];
    }

    public function removeEducation($index)
    {
        unset($this->education[$index]);
        $this->education = array_values($this->education);
    }

    public function updateEducation()
    {
        $this->resume->update([
            'education' => $this->education,
        ]);

        $this->dispatch('notify', [
            'message' => '學歷資料已更新',
            'type' => 'success',
        ]);
    }

    public function addExperience()
    {
        $this->experience[] = [
            'company' => '',
            'position' => '',
            'start_date' => '',
            'end_date' => '',
            'current' => false,
            'description' => '',
        ];
    }

    public function removeExperience($index)
    {
        unset($this->experience[$index]);
        $this->experience = array_values($this->experience);
    }

    public function updateExperience()
    {
        $this->resume->update([
            'experience' => $this->experience,
        ]);

        $this->dispatch('notify', [
            'message' => '工作經驗已更新',
            'type' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.resume.edit');
    }
}
