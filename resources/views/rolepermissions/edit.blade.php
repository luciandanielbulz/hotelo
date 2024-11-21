<x-layout>
    <div class="container p-3">
        <h3>Berechtigungen verwalten</h3>
    </div>
    <div class="container">

        <div class="row">
            <form action="{{ route('rolepermissions.update', $role->id) }}" method="POST">
                @csrf
                @foreach ($raw_permissions as $raw_permission)
                    <div class="row pb-2">
                        <input
                            type="checkbox"
                            id="permission_{{ $raw_permission->id }}"
                            name="permissions[]"
                            value="{{ $raw_permission->id }}"
                            {{ $permissions->contains('id', $raw_permission->id) ? 'checked' : '' }}
                        >
                        <label class="pl-2" for="permission_{{ $raw_permission->id }}">
                            {{ $raw_permission->description }}
                        </label>
                    </div>
                @endforeach
                    <div class="row">
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </div>
            </form>

        </div>
    </div>
</x-layout>
