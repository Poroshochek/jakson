    @if ($errors->any())
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err  }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
