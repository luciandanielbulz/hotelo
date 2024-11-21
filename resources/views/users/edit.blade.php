<x-layout>
    <div class="container">
        <div class="row">
            <div class="col p-3">
                <h2>Benutzer bearbeiten</h2>
            </div>
        </div>
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Dies wird benötigt, da Laravel PUT-Methoden für Updates verwendet -->

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <input type="hidden" id="selecteduserid" name="selecteduserid" value="{{ $user->id }}">
                        <label for="name">Vorname</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                    </div>
                    <input type="hidden" id="userid" name="userid" value="{{ $user->id }}">
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="lastname">Nachname</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $user->lastname }}" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="login">Loginname</label>
                        <input type="text" class="form-control" id="login" name="login" value="{{ $user->name }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="clientId">Client</label>
                        <select class="form-control" id="clientId" name="clientId" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $client->id == old('client_id', $user->client_id) ? 'selected' : '' }}>
                                    {{ $client->clientname }} - {{ $client->companyname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="isactive">Aktiv</label>
                        <select class="form-control" id="isactive" name="isactive" required>
                            <option value="1" {{ $user->isactive == 1 ? 'selected' : '' }}>Ja</option>
                            <option value="0" {{ $user->isactive == 0 ? 'selected' : '' }}>Nein</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="rightsId">Rolle</label>
                        <select class="form-control" id="role_id" name="role_id" required>
                            @foreach($rights as $right)
                                <option value="{{ $right->id }}" {{ $right->id == old('role_id', $user->role_id) ? 'selected' : '' }}>
                                    {{ $right->name }}
                                </option>
                            @endforeach
                        </select>
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
