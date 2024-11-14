<x-layout>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>Benutzer erstellen</h2>
            </div>
            <div class="col">
                <a href="{{ reoute('users.index') }}" class="btn btn-transparent float-right">Zurück</a>
            </div>
        </div>
        <form action="/users" method="POST">
            <div class = "row">
                <div class = "col">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class = "col">
                    <div class="form-group">
                        <label for="lastname">Nachname</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                </div>
                <div class = "col">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                </div>
            </div>

            <div class = "row">
                <div class = "col">
                    <div class="form-group">
                        <label for="clientId">Rights ID</label>
                        <select class="form-control" id="clientId" name="clientId" required>
                            <?php
                                //client_dropdown_show($pdo);
                            ?>
                        </select>
                    </div>

                </div>
                <div class = "col">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
            </div>
            <div class = "row">
                <div class = "col">
                    <div class="form-group">
                        <label for="active">Active</label>
                        <select class="form-control" id="active" name="active" required>
                            <option value="1">Ja</option>
                            <option value="0">Nein</option>
                        </select>
                    </div>
                </div>
                <div class = "col">
                    <div class="form-group">
                        <label for="rightsId">Rights ID</label>
                        <select class="form-control" id="rightsId" name="rightsId" required>
                            <?php
                                //rights_dropdown_show($pdo);
                            ?>
                        </select>
                    </div>
                </div>
                <div class = "col">
                    <div class="form-group">
                        <label for="password">Passwort</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
            </div>
            <div class = "row">
                <div class = "col">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </div>

        </form>
    </div>
</div>
</x-layout>
