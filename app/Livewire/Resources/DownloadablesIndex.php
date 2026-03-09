<?php

namespace App\Livewire\Resources;

use App\Models\Downloadable;
use Livewire\Component;
use Livewire\WithPagination;

class DownloadablesIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function download($id)
    {
        $downloadable = Downloadable::findOrFail($id);
        $downloadable->incrementDownloads();
        
        return redirect($downloadable->file_path);
    }

    public function render()
    {
        $query = Downloadable::where('is_published', true);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        $downloadables = $query->latest()->paginate(12);

        return view('livewire.resources.downloadables-index', [
            'downloadables' => $downloadables,
        ])->layout('layouts.guest');
    }
}
