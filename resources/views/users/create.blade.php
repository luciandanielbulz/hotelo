<x-layout>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        <div class="row p-3">
            <div class="col">
                <h2>Benutzer erstellen</h2>
            </div>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf <!-- CSRF-Schutz für das Formular -->

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="lastname">Nachname</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="username">Login</label>
                        <input type="text" class="form-control" id="username" name="username" value="" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="client_id">Klient</label>
                        <select class="form-control" id="client_id" name="client_id" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->clientname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="isactive">Active</label>
                        <select class="form-control" id="isactive" name="isactive" required>
                            <option value="1" {{ old('isactive') == 1 ? 'selected' : '' }}>Ja</option>
                            <option value="0" {{ old('isactive') == 0 ? 'selected' : '' }}>Nein</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="role_id">Rolle</label>
                        <select class="form-control" id="role_id" name="role_id" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="password">Passwort</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
