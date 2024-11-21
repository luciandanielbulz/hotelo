<x-layout>
    <div class="container">
        <h3 class="mb-4">Rolle anlegen</h3>


        <form action="{{ route('roles.store') }}" method="POST" class = "customer">
            @csrf
            <!-- Anrede und Titel -->
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="name">Rolenname</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group col-md-4">
                    <label for="description">Beschreibung</label>
                    <input type="text" class="form-control" id="description" name="description">
                </div>
            </div>


            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Speichern</button>
            </div>
        </form>


    </div>
</x-layout>
