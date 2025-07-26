<?php

namespace App\Livewire\Resume\Portfolio;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ProjectForm extends Component
{
    use WithFileUploads;

    public $resumeId;
    public $projectId = null;
    public $title = '';
    public $description = '';
    public $thumbnail = null;
    public $url = '';
    public $githubUrl = '';
    public $technologies = '';
    public $completionDate = '';
    public $isFeatured = false;
    public $order = 0;
    public $thumbnailPreview = null;
    public $existingThumbnail = null;
    public $removeThumbnail = false;
    public $isVisible = false;

    protected $listeners = ['openProjectForm', 'closeProjectForm'];

    protected $rules = [
        'title' => 'required|max:255',
        'description' => 'nullable',
        'thumbnail' => 'nullable|image|max:1024',
        'url' => 'nullable|url|max:255',
        'githubUrl' => 'nullable|url|max:255',
        'technologies' => 'nullable',
        'completionDate' => 'nullable|date',
        'isFeatured' => 'boolean',
        'order' => 'integer|min:0',
    ];

    public function mount($resumeId)
    {
        $this->resumeId = $resumeId;
    }

    public function openProjectForm($params = [])
    {
        $this->resetForm();
        
        if (isset($params['projectId'])) {
            $this->loadProject($params['projectId']);
        }
        
        $this->isVisible = true;
    }

    public function closeProjectForm()
    {
        $this->isVisible = false;
        $this->resetForm();
    }

    public function loadProject($projectId)
    {
        $project = Project::find($projectId);
        
        if ($project && $project->user_id === auth()->id()) {
            $this->projectId = $project->id;
            $this->title = $project->title;
            $this->description = $project->description;
            $this->url = $project->url;
            $this->githubUrl = $project->github_url;
            $this->technologies = $project->technologies ? implode(', ', $project->technologies) : '';
            $this->completionDate = $project->completion_date ? $project->completion_date->format('Y-m-d') : '';
            $this->isFeatured = $project->is_featured;
            $this->order = $project->order;
            $this->existingThumbnail = $project->thumbnail ? Storage::url($project->thumbnail) : null;
        }
    }

    public function resetForm()
    {
        $this->projectId = null;
        $this->title = '';
        $this->description = '';
        $this->thumbnail = null;
        $this->url = '';
        $this->githubUrl = '';
        $this->technologies = '';
        $this->completionDate = '';
        $this->isFeatured = false;
        $this->order = 0;
        $this->thumbnailPreview = null;
        $this->existingThumbnail = null;
        $this->removeThumbnail = false;
    }

    public function updatedThumbnail()
    {
        $this->validate([
            'thumbnail' => 'image|max:1024',
        ]);

        $this->thumbnailPreview = $this->thumbnail->temporaryUrl();
        $this->removeThumbnail = false;
    }

    public function removeThumbnail()
    {
        $this->thumbnail = null;
        $this->thumbnailPreview = null;
        $this->removeThumbnail = true;
    }

    public function saveProject()
    {
        $this->validate();

        $technologies = $this->technologies ? explode(',', $this->technologies) : [];
        $technologies = array_map('trim', $technologies);

        $projectData = [
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'github_url' => $this->githubUrl,
            'technologies' => $technologies,
            'completion_date' => $this->completionDate ?: null,
            'is_featured' => $this->isFeatured,
            'order' => $this->order,
        ];

        if ($this->projectId) {
            // 更新現有項目
            $project = Project::find($this->projectId);

            if ($project && $project->user_id === auth()->id()) {
                // 處理縮略圖
                if ($this->thumbnail) {
                    // 刪除舊縮略圖
                    if ($project->thumbnail) {
                        Storage::delete($project->thumbnail);
                    }

                    // 上傳新縮略圖
                    $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
                    $projectData['thumbnail'] = $thumbnailPath;
                } elseif ($this->removeThumbnail && $project->thumbnail) {
                    // 如果選擇移除縮略圖
                    Storage::delete($project->thumbnail);
                    $projectData['thumbnail'] = null;
                }

                $project->update($projectData);

                $this->dispatch('notify', [
                    'message' => '項目已成功更新',
                    'type' => 'success',
                ]);
            }
        } else {
            // 創建新項目
            if ($this->thumbnail) {
                $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
                $projectData['thumbnail'] = $thumbnailPath;
            }

            Project::create($projectData);

            $this->dispatch('notify', [
                'message' => '項目已成功創建',
                'type' => 'success',
            ]);
        }

        $this->closeProjectForm();
        $this->dispatch('projectSaved');
    }

    public function render()
    {
        return view('livewire.resume.portfolio.project-form');
    }
}