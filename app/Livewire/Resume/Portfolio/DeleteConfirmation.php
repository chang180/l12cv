<?php

namespace App\Livewire\Resume\Portfolio;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class DeleteConfirmation extends Component
{
    public $projectId = null;
    public $isVisible = false;

    protected $listeners = ['openDeleteModal', 'closeDeleteModal'];

    public function openDeleteModal($params = [])
    {
        if (isset($params['projectId'])) {
            $this->projectId = $params['projectId'];
            $this->isVisible = true;
        }
    }

    public function closeDeleteModal()
    {
        $this->isVisible = false;
        $this->projectId = null;
    }

    public function deleteProject()
    {
        $project = Project::find($this->projectId);

        if ($project && $project->user_id === auth()->id()) {
            // 刪除縮略圖
            if ($project->thumbnail) {
                Storage::delete($project->thumbnail);
            }

            $project->delete();

            $this->dispatch('notify', [
                'message' => '項目已成功刪除',
                'type' => 'success',
            ]);
            
            $this->dispatch('projectDeleted');
        }

        $this->closeDeleteModal();
    }

    public function render()
    {
        return view('livewire.resume.portfolio.delete-confirmation');
    }
}