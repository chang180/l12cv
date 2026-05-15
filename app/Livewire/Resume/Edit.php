<?php

namespace App\Livewire\Resume;

use App\Models\Resume;
use App\Support\ResumeTemplates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public $resumeId;

    public $resume;

    public $title;

    public $summary;

    public $template = ResumeTemplates::DEFAULT;

    public $templateOptions = [];

    public $skills = [];

    public $languages = [];

    public $certifications = [];

    public $education = [];

    public $experience = [];

    public $currentTab = 'basic';

    protected $listeners = ['update-parent-summary' => 'handleParentSummaryUpdate'];

    public function mount($resumeId = null)
    {
        // 如果沒有提供 resumeId，則獲取當前用戶的履歷
        if (! $resumeId) {
            $user = Auth::user();
            if (! $user) {
                abort(401);
            }
            $this->resume = $user->resume;
            if (! $this->resume) {
                return redirect()->route('resume.dashboard')
                    ->with('error', '您還沒有建立履歷');
            }
            $this->resumeId = $this->resume->id;
        } else {
            $this->resumeId = $resumeId;
            $this->resume = Resume::findOrFail($resumeId);

            // 確保當前用戶有權編輯此履歷
            $currentUserId = Auth::id();
            if ($this->resume->user_id !== $currentUserId) {
                abort(403);
            }
        }

        // 初始化表單數據
        $this->title = $this->resume->title;
        $this->summary = $this->resume->summary;
        $this->template = ResumeTemplates::resolve($this->resume->template ?? null)['key'];
        $this->templateOptions = ResumeTemplates::all();
        $this->skills = $this->resume->skills ?? [];
        $this->languages = $this->resume->languages ?? [];
        $this->certifications = $this->resume->certifications ?? [];
        $this->education = $this->resume->education ?? [];
        $this->experience = $this->resume->experience ?? [];
    }

    public function handleParentSummaryUpdate($content)
    {
        $this->summary = $content;

        if ($this->resume) {
            $this->resume->update(['summary' => $this->summary]);
            $this->resume->recordVersion('summary.autosaved');
            $this->dispatch('auto-saved');
        }
    }

    public function updateBasicInfo()
    {
        $this->validate([
            'template' => ['required', Rule::in(ResumeTemplates::keys())],
        ]);

        $this->resume->update([
            'title' => $this->title,
            'summary' => $this->summary,
            'template' => $this->template,
        ]);
        $this->resume->recordVersion('basic.updated');

        session()->flash('status', '✅ 基本資料已更新');

        // 滾動到頁面頂部
        $this->dispatch('scroll-to-top');
    }

    public function autoSaveBasicInfo(): void
    {
        $this->validate([
            'template' => ['required', Rule::in(ResumeTemplates::keys())],
        ]);

        $this->resume->update([
            'title' => $this->title,
            'summary' => $this->summary,
            'template' => $this->template,
        ]);
        $this->resume->recordVersion('basic.autosaved');

        $this->dispatch('auto-saved');
    }

    public function addSkill()
    {
        $this->skills[] = '';
    }

    public function removeSkill($index)
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function updateSkills()
    {
        $skills = collect($this->skills)
            ->map(fn ($skill) => trim((string) $skill))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->skills = $skills;

        $this->resume->update([
            'skills' => $skills,
        ]);
        $this->resume->recordVersion('skills.updated');

        $this->dispatch('notify', [
            'message' => '技能標籤已更新',
            'type' => 'success',
        ]);
    }

    public function addLanguage()
    {
        $this->languages[] = [
            'name' => '',
            'level' => '基礎',
        ];
    }

    public function removeLanguage($index)
    {
        unset($this->languages[$index]);
        $this->languages = array_values($this->languages);
    }

    public function updateLanguages()
    {
        $languages = collect($this->languages)
            ->map(fn ($language) => [
                'name' => trim((string) ($language['name'] ?? '')),
                'level' => trim((string) ($language['level'] ?? '')),
            ])
            ->filter(fn ($language) => $language['name'] !== '')
            ->map(fn ($language) => [
                'name' => $language['name'],
                'level' => $language['level'] !== '' ? $language['level'] : '基礎',
            ])
            ->values()
            ->all();

        $this->languages = $languages;

        $this->resume->update([
            'languages' => $languages,
        ]);
        $this->resume->recordVersion('languages.updated');

        $this->dispatch('notify', [
            'message' => '語言能力已更新',
            'type' => 'success',
        ]);
    }

    public function addCertification()
    {
        $this->certifications[] = [
            'name' => '',
            'issuer' => '',
            'issued_at' => '',
            'url' => '',
        ];
    }

    public function removeCertification($index)
    {
        unset($this->certifications[$index]);
        $this->certifications = array_values($this->certifications);
    }

    public function updateCertifications()
    {
        $certifications = collect($this->certifications)
            ->map(fn ($certification) => [
                'name' => trim((string) ($certification['name'] ?? '')),
                'issuer' => trim((string) ($certification['issuer'] ?? '')),
                'issued_at' => trim((string) ($certification['issued_at'] ?? '')),
                'url' => trim((string) ($certification['url'] ?? '')),
            ])
            ->filter(fn ($certification) => $certification['name'] !== '')
            ->values()
            ->all();

        $this->certifications = $certifications;

        $this->resume->update([
            'certifications' => $certifications,
        ]);
        $this->resume->recordVersion('certifications.updated');

        $this->dispatch('notify', [
            'message' => '證照和認證已更新',
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
        $this->resume->recordVersion('education.updated');

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
        $this->resume->recordVersion('experience.updated');

        $this->dispatch('notify', [
            'message' => '工作經驗已更新',
            'type' => 'success',
        ]);
    }

    /**
     * 檢查是否應該顯示「目前在職中」選項
     * 只有在開始日期在最後一個工作之後時才顯示
     */
    public function shouldShowCurrentOption($index)
    {
        // 如果只有一個工作經驗，總是顯示
        if (count($this->experience) <= 1) {
            return true;
        }

        // 獲取當前工作經驗的開始日期
        $currentStartDate = $this->experience[$index]['start_date'] ?? null;
        if (! $currentStartDate) {
            return true; // 如果沒有開始日期，顯示選項讓用戶填寫
        }

        // 找到其他工作經驗中最晚的結束日期
        $latestEndDate = null;
        foreach ($this->experience as $i => $exp) {
            if ($i === $index) {
                continue;
            } // 跳過當前工作經驗

            $endDate = $exp['end_date'] ?? null;
            if ($endDate && (! $latestEndDate || $endDate > $latestEndDate)) {
                $latestEndDate = $endDate;
            }
        }

        // 如果沒有其他工作經驗的結束日期，顯示選項
        if (! $latestEndDate) {
            return true;
        }

        // 如果當前開始日期在最後一個工作之後，顯示選項
        return $currentStartDate > $latestEndDate;
    }

    /**
     * 當工作經驗欄位更新時的處理邏輯
     */
    public function updatedExperience($value, $key)
    {
        // 檢查是否是 current 欄位被更新
        if (str_ends_with($key, '.current')) {
            // 提取索引
            $index = (int) str_replace('.current', '', $key);

            if ($value) {
                // 勾選「目前在職中」時，清空結束日期
                $this->experience[$index]['end_date'] = '';
            } else {
                // 取消勾選「目前在職中」時，如果結束日期為空，提供一個合理的預設值
                if (empty($this->experience[$index]['end_date'])) {
                    $startDate = $this->experience[$index]['start_date'] ?? null;
                    if ($startDate) {
                        // 設定結束日期為開始日期後 1 年（用戶可以自行修改）
                        $endDate = date('Y-m-d', strtotime($startDate.' +1 year'));
                        $this->experience[$index]['end_date'] = $endDate;
                    }
                }
            }
        }

        // 檢查是否是 start_date 欄位被更新
        if (str_ends_with($key, '.start_date')) {
            // 提取索引
            $index = (int) str_replace('.start_date', '', $key);

            // 如果開始日期改變後，不應該顯示「目前在職中」選項，
            // 但用戶已經勾選了，則自動取消勾選
            if (! $this->shouldShowCurrentOption($index) && ($this->experience[$index]['current'] ?? false)) {
                $this->experience[$index]['current'] = false;
                // 取消勾選時，不清空結束日期，讓用戶可以重新填寫
            }
        }
    }

    public function render()
    {
        return view('livewire.resume.edit');
    }
}
