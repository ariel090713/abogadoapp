<?php

namespace App\Livewire\Admin;

use App\Models\Blog;
use App\Models\ContentCategory;
use App\Models\Downloadable;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\LegalGuide;
use App\Models\News;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ContentManagement extends Component
{
    use WithFileUploads, WithPagination;

    public $contentType = 'legal_guides'; // legal_guides, news, blogs, events, galleries, downloadables
    public $search = '';
    public $filterStatus = 'all';
    public $filterCategory = 'all';
    
    // Category Management
    public $showCategoryModal = false;
    public $categoryEditMode = false;
    public $selectedCategoryId = null;
    public $categoryName = '';
    public $categoryDescription = '';
    public $categoryOrder = 0;
    
    // Delete Confirmation
    public $showDeleteModal = false;
    public $deleteItemId = null;
    public $deleteItemType = 'content'; // 'content' or 'category'

    // Form fields
    public $showModal = false;
    public $editMode = false;
    public $selectedId = null;
    
    public $title = '';
    public $excerpt = '';
    public $content = '';
    public $description = '';
    public $category = '';
    public $featured_image;
    public $is_published = false;
    
    // Event specific
    public $event_type = '';
    public $event_date = '';
    public $location = '';
    public $meeting_link = '';
    public $max_participants = null;
    
    // Gallery specific
    public $gallery_type = 'photos';
    public $showAddItemModal = false;
    public $newItemTitle = '';
    public $newItemFile;
    public $newItemOrder = 1;
    
    // Downloadable specific
    public $file;
    public $file_category = '';

    protected $queryString = ['contentType', 'search', 'filterStatus', 'filterCategory'];

    public function updatedContentType()
    {
        $this->resetPage();
        $this->reset(['search', 'filterStatus', 'filterCategory']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
        $this->dispatch('modal-opened');
    }

    public function edit($id)
    {
        $this->editMode = true;
        $this->selectedId = $id;
        
        $item = $this->getModel()->find($id);
        
        $this->title = $item->title;
        $this->is_published = $item->is_published;
        
        if ($this->contentType === 'legal_guides' || $this->contentType === 'blogs') {
            $this->excerpt = $item->excerpt;
            $this->content = $item->content;
            $this->category = $item->category;
        } elseif ($this->contentType === 'news') {
            $this->excerpt = $item->excerpt;
            $this->content = $item->content;
        } elseif ($this->contentType === 'events') {
            $this->description = $item->description;
            $this->content = $item->content;
            $this->event_type = $item->event_type;
            $this->event_date = $item->event_date->format('Y-m-d\TH:i');
            $this->location = $item->location;
            $this->meeting_link = $item->meeting_link;
            $this->max_participants = $item->max_participants;
        } elseif ($this->contentType === 'galleries') {
            $this->description = $item->description;
            $this->gallery_type = $item->type;
        } elseif ($this->contentType === 'downloadables') {
            $this->description = $item->description;
            $this->file_category = $item->category;
        }
        
        $this->showModal = true;
        $this->dispatch('modal-opened');
    }

    public function save(FileUploadService $fileService)
    {
        $this->validate($this->getRules());

        try {
            $data = [
                'title' => $this->title,
                'is_published' => $this->is_published,
            ];

            // Handle file uploads
            if ($this->featured_image) {
                $fileData = $fileService->uploadPublic($this->featured_image, 'content-images');
                $data['featured_image'] = $fileData['url'];
            }

            if ($this->file && $this->contentType === 'downloadables') {
                $fileData = $fileService->uploadPublic($this->file, 'downloadables');
                $data['file_path'] = $fileData['url'];
                $data['file_type'] = $this->file->getClientOriginalExtension();
                $data['file_size'] = $this->file->getSize();
            }

            // Add content type specific fields
            if ($this->contentType === 'legal_guides' || $this->contentType === 'blogs') {
                $data['excerpt'] = $this->excerpt;
                $data['content'] = $this->content;
                $data['category'] = $this->category;
                $data['author_id'] = auth()->id();
            } elseif ($this->contentType === 'news') {
                $data['excerpt'] = $this->excerpt;
                $data['content'] = $this->content;
                $data['author_id'] = auth()->id();
            } elseif ($this->contentType === 'events') {
                $data['description'] = $this->description;
                $data['content'] = $this->content;
                $data['event_type'] = $this->event_type;
                $data['event_date'] = $this->event_date;
                $data['location'] = $this->location;
                $data['meeting_link'] = $this->meeting_link;
                $data['max_participants'] = $this->max_participants;
            } elseif ($this->contentType === 'galleries') {
                $data['description'] = $this->description;
                $data['type'] = $this->gallery_type;
            } elseif ($this->contentType === 'downloadables') {
                $data['description'] = $this->description;
                $data['category'] = $this->file_category;
            }

            if ($this->editMode) {
                $this->getModel()->find($this->selectedId)->update($data);
                session()->flash('success', 'Content updated successfully.');
            } else {
                $this->getModel()->create($data);
                session()->flash('success', 'Content created successfully.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->getModel()->find($id)->delete();
            session()->flash('success', 'Content deleted successfully.');
            $this->showDeleteModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function confirmDelete($id, $type = 'content')
    {
        $this->deleteItemId = $id;
        $this->deleteItemType = $type;
        $this->showDeleteModal = true;
    }
    
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteItemId = null;
        $this->deleteItemType = 'content';
    }
    
    public function executeDelete()
    {
        if ($this->deleteItemType === 'category') {
            $this->deleteCategory($this->deleteItemId);
        } elseif ($this->deleteItemType === 'gallery-item') {
            $this->deleteGalleryItem($this->deleteItemId);
        } elseif ($this->deleteItemType === 'downloadable-file') {
            $this->deleteDownloadableFile($this->deleteItemId);
        } else {
            $this->delete($this->deleteItemId);
        }
    }

    public function togglePublish($id)
    {
        $item = $this->getModel()->find($id);
        $item->update(['is_published' => !$item->is_published]);
        session()->flash('success', 'Status updated successfully.');
    }

    public function getModel()
    {
        return match($this->contentType) {
            'legal_guides' => new LegalGuide(),
            'news' => new News(),
            'blogs' => new Blog(),
            'events' => new Event(),
            'galleries' => new Gallery(),
            'downloadables' => new Downloadable(),
        };
    }

    public function getRules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'is_published' => 'boolean',
        ];

        if ($this->contentType === 'legal_guides' || $this->contentType === 'blogs') {
            $rules['excerpt'] = 'required|string';
            $rules['content'] = 'required|string';
            $rules['category'] = 'required|string';
        } elseif ($this->contentType === 'news') {
            $rules['excerpt'] = 'required|string';
            $rules['content'] = 'required|string';
        } elseif ($this->contentType === 'events') {
            $rules['description'] = 'required|string';
            $rules['content'] = 'required|string';
            $rules['event_type'] = 'required|string';
            $rules['event_date'] = 'required|date';
        } elseif ($this->contentType === 'galleries') {
            $rules['gallery_type'] = 'required|in:photos,videos';
        } elseif ($this->contentType === 'downloadables') {
            $rules['description'] = 'required|string';
            $rules['file_category'] = 'required|string';
            if (!$this->editMode) {
                $rules['file'] = 'required|file|max:10240';
            }
        }

        if ($this->featured_image) {
            $rules['featured_image'] = 'image|max:5120';
        }

        return $rules;
    }

    public function resetForm()
    {
        $this->reset([
            'title', 'excerpt', 'content', 'description', 'category',
            'featured_image', 'is_published', 'event_type', 'event_date',
            'location', 'meeting_link', 'max_participants', 'gallery_type',
            'file', 'file_category', 'selectedId'
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $query = $this->getModel()->query();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus !== 'all') {
            $query->where('is_published', $this->filterStatus === 'published');
        }

        if ($this->filterCategory !== 'all' && in_array($this->contentType, ['legal_guides', 'blogs', 'downloadables'])) {
            $query->where('category', $this->filterCategory);
        }

        $items = $query->latest()->paginate(15);
        $categories = $this->getCategories();

        return view('livewire.admin.content-management', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }

    public function addGalleryItem(FileUploadService $fileService)
    {
        $this->validate([
            'newItemTitle' => 'required|string|max:255',
            'newItemFile' => 'required|image|max:10240', // Only images for now
            'newItemOrder' => 'required|integer|min:1',
        ]);

        try {
            $gallery = Gallery::findOrFail($this->selectedId);
            
            // Actually upload the file to S3 public bucket
            $fileData = $fileService->uploadPublic(
                $this->newItemFile,
                'gallery-items'
            );
            
            $gallery->items()->create([
                'title' => $this->newItemTitle,
                'file_path' => $fileData['url'], // Use the actual S3 URL
                'order' => $this->newItemOrder,
            ]);

            $this->reset(['newItemTitle', 'newItemFile', 'newItemOrder', 'showAddItemModal']);
            $this->newItemOrder = 1;
            
            session()->flash('success', 'Gallery item added successfully');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add gallery item: ' . $e->getMessage());
        }
    }

    public function deleteGalleryItem($itemId)
    {
        try {
            $item = \App\Models\GalleryItem::findOrFail($itemId);
            $item->delete();
            
            session()->flash('success', 'Gallery item deleted successfully');
            $this->showDeleteModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete gallery item: ' . $e->getMessage());
        }
    }

    public function deleteDownloadableFile($downloadableId)
    {
        try {
            $downloadable = Downloadable::findOrFail($downloadableId);
            
            // Note: In production, you should also delete the file from S3
            // $fileService->deleteFile($downloadable->file_path);
            
            $downloadable->update([
                'file_path' => null,
                'file_type' => null,
                'file_size' => null,
            ]);
            
            session()->flash('success', 'File deleted successfully. Please upload a new file.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete file: ' . $e->getMessage());
        }
    }

    // Category Management Methods
    public function createCategory()
    {
        $this->resetCategoryForm();
        $this->showCategoryModal = true;
        $this->categoryEditMode = false;
    }

    public function editCategory($id)
    {
        $this->categoryEditMode = true;
        $this->selectedCategoryId = $id;
        
        $category = ContentCategory::findOrFail($id);
        $this->categoryName = $category->name;
        $this->categoryDescription = $category->description;
        $this->categoryOrder = $category->order;
        
        $this->showCategoryModal = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'categoryName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string',
            'categoryOrder' => 'required|integer|min:0',
        ]);

        try {
            $type = match($this->contentType) {
                'legal_guides' => 'legal_guide',
                'blogs' => 'blog',
                'downloadables' => 'downloadable',
                default => null,
            };

            if (!$type) {
                session()->flash('error', 'Categories not available for this content type.');
                return;
            }

            $data = [
                'name' => $this->categoryName,
                'type' => $type,
                'description' => $this->categoryDescription,
                'order' => $this->categoryOrder,
            ];

            if ($this->categoryEditMode) {
                ContentCategory::findOrFail($this->selectedCategoryId)->update($data);
                session()->flash('success', 'Category updated successfully.');
            } else {
                ContentCategory::create($data);
                session()->flash('success', 'Category created successfully.');
            }

            $this->closeCategoryModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function deleteCategory($id)
    {
        try {
            ContentCategory::findOrFail($id)->delete();
            session()->flash('success', 'Category deleted successfully.');
            $this->showDeleteModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function toggleCategoryStatus($id)
    {
        $category = ContentCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        session()->flash('success', 'Category status updated successfully.');
    }

    public function resetCategoryForm()
    {
        $this->reset(['categoryName', 'categoryDescription', 'categoryOrder', 'selectedCategoryId']);
        $this->categoryOrder = 0;
    }

    public function closeCategoryModal()
    {
        $this->showCategoryModal = false;
        $this->resetCategoryForm();
    }

    public function getCategories()
    {
        $type = match($this->contentType) {
            'legal_guides' => 'legal_guide',
            'blogs' => 'blog',
            'downloadables' => 'downloadable',
            default => null,
        };

        if (!$type) {
            return collect();
        }

        return ContentCategory::ofType($type)->ordered()->get();
    }
}
