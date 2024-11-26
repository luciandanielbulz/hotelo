<x-layout>
    
    <div class="container">

        <div class="row pt-3">

                @foreach($tiles as $tile)
                    <div class='col-md-4 mb-4'>
                        <a href='{{ $tile["targetFile"] }}' class='text-decoration-none text-dark'>
                            <div class='tile p-4' style='background-color: {{ $tile["backgroundColor"] }}'>
                                <div class='row'>
                                    <div class='col-auto'>
                                        <i class='{{ $tile["icon"] }}'></i>
                                    </div>
                                    <div class='col'>
                                        <h5>{{ $tile["title"] }}</h5>
                                        <p>{{ $tile["text"] }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

        </div>

    </div>


</x-layout>
