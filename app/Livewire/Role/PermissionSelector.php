<?php

namespace App\Livewire\Role;

use Livewire\Component;
use App\Models\Permission;
use App\Models\Role;

class PermissionSelector extends Component
{
    public $roleId;
    public $selectedPermissions = [];
    public $search = '';
    public $showOnlySelected = false;
    public $expandedCategories = [];

    protected $listeners = ['refreshPermissions' => '$refresh'];

    public function mount($roleId = null)
    {
        $this->roleId = $roleId;
        
        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $this->selectedPermissions = $role->permissions()->pluck('permissions.id')->toArray();
            }
        }

        // Standardmäßig alle Kategorien aufklappen
        $this->expandedCategories = Permission::getCategories()->toArray();
    }

    public function updatedSearch()
    {
        // Reaktiv beim Suchen
    }

    public function toggleCategory($category)
    {
        if (in_array($category, $this->expandedCategories)) {
            $this->expandedCategories = array_diff($this->expandedCategories, [$category]);
        } else {
            $this->expandedCategories[] = $category;
        }
    }

    public function expandAllCategories()
    {
        $this->expandedCategories = Permission::getCategories()->toArray();
    }

    public function collapseAllCategories()
    {
        $this->expandedCategories = [];
    }

    public function togglePermission($permissionId)
    {
        if (in_array($permissionId, $this->selectedPermissions)) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, [$permissionId]);
        } else {
            $this->selectedPermissions[] = $permissionId;
        }
    }

    public function selectAll()
    {
        $permissions = $this->getFilteredPermissions();
        foreach ($permissions as $permission) {
            if (!in_array($permission->id, $this->selectedPermissions)) {
                $this->selectedPermissions[] = $permission->id;
            }
        }
    }

    public function deselectAll()
    {
        $permissions = $this->getFilteredPermissions();
        foreach ($permissions as $permission) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, [$permission->id]);
        }
    }

    public function clearAll()
    {
        $this->selectedPermissions = [];
    }

    public function selectAllInCategory($category)
    {
        $permissions = Permission::where('category', $category)->get();
        foreach ($permissions as $permission) {
            if (!in_array($permission->id, $this->selectedPermissions)) {
                $this->selectedPermissions[] = $permission->id;
            }
        }
    }

    public function deselectAllInCategory($category)
    {
        $permissions = Permission::where('category', $category)->get();
        foreach ($permissions as $permission) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, [$permission->id]);
        }
    }

    private function getFilteredPermissions()
    {
        $query = Permission::query();
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->showOnlySelected) {
            $query->whereIn('id', $this->selectedPermissions);
        }

        return $query->orderBy('category')->orderBy('description')->orderBy('name')->get();
    }

    private function getGroupedPermissions()
    {
        $permissions = $this->getFilteredPermissions();
        return $permissions->groupBy('category');
    }

    public function render()
    {
        $groupedPermissions = $this->getGroupedPermissions();
        $selectedCount = count($this->selectedPermissions);
        $totalCount = Permission::count();

        return view('livewire.role.permission-selector', [
            'groupedPermissions' => $groupedPermissions,
            'selectedCount' => $selectedCount,
            'totalCount' => $totalCount,
        ]);
    }
} 