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
    }

    public function updatedSearch()
    {
        // Reaktiv beim Suchen
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

    private function getFilteredPermissions()
    {
        $query = Permission::query();
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->showOnlySelected) {
            $query->whereIn('id', $this->selectedPermissions);
        }

        return $query->orderBy('description')->orderBy('name')->get();
    }

    public function render()
    {
        $permissions = $this->getFilteredPermissions();
        $selectedCount = count($this->selectedPermissions);
        $totalCount = Permission::count();

        return view('livewire.role.permission-selector', [
            'permissions' => $permissions,
            'selectedCount' => $selectedCount,
            'totalCount' => $totalCount,
        ]);
    }
} 