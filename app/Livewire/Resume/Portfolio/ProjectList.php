<?php

namespace App\Livewire\Resume\Portfolio;

use Livewire\Component;
use App\Models\Project;

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

    public function render()
    {
        return view('livewire.resume.portfolio.project-list');
    }
}
