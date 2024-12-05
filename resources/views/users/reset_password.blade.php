<form action="{{ route('admin.reset-password', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="password">Neues Passwort</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div>
        <label for="password_confirmation">Passwort bestätigen</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>
    </div>

    <button type="submit">Passwort zurücksetzen</button>
</form>
