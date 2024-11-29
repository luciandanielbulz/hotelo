<x-layout>
    @if($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif

    <div class="container">
        <!-- Erfolgsnachricht -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('logos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Kundeninformationen -->
            <div class="form-row mt-4">
                <div class="form-group col-md-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="client">Client auswählen</label>
                    <select class="form-control" id="client_id" name="client_id" required>
                        <option value="">Bitte wählen</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->clientname }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="filename">Datei hochladen</label>
                    <input type="file" class="form-control" id="filename" name="file" required>
                    @error('file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit-Button -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Speichern</button>
            </div>
        </form>
        <hr>
    </div>
</x-layout>
