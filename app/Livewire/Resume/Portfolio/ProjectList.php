<?php

namespace App\Livewire\Resume\Portfolio;

use App\Models\Project;
use Livewire\Component;

class ProjectList extends Component
{
    public $resumeId;

    public $projects = [];

    protected $listeners = ['projectSaved' => 'refreshProjects', 'projectDeleted' => 'refreshProjects'];

    public function mount($resumeId)
    {
        $this->resumeId = $resumeId;
        $this->refreshProjects();
    }

    public function refreshProjects()
    {
        $this->projects = auth()->user()->projects()
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createProject()
    {
        $this->dispatch('openProjectForm');
    }

    public function editProject($projectId)
    {
        $this->dispatch('openProjectForm', ['projectId' => $projectId]);
    }

    public function confirmDelete($projectId)
    {
        $this->dispatch('openDeleteModal', ['projectId' => $projectId]);
    }

    public function reorderProjects(array $projectIds): void
    {
        $ownedProjectIds = auth()->user()->projects()
            ->whereIn('id', $projectIds)
            ->pluck('id')
            ->all();

        if (count($ownedProjectIds) !== count($projectIds)) {
            return;
        }

        foreach (array_values($projectIds) as $index => $projectId) {
            Project::where('user_id', auth()->id())
                ->whereKey($projectId)
                ->update(['order' => $index + 1]);
        }

        $this->refreshProjects();
        $this->dispatch('projectOrderSaved');
    }

    public function render()
    {
        return view('livewire.resume.portfolio.project-list');
    }
}
