@extends('layout')

@section('login-display')
<div class="mb-12 border border-warning">
    <div class="form-group row m-2">
        <div class="m-3">
            <p class="mb-3 text-center">Login</p>
            <form class="m-3" action="" method="post">
                @csrf
                @include('partials.create-user-display-partials')
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-warning" id="button1" name="submit1">SUBMIT</button>
                    @if($errors->has('fail'))
                        <div class="alert alert-warning mb-3" role="alert">
                            {{ $errors->get('fail')[0] }}
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
