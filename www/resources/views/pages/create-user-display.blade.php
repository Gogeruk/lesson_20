@extends('layout')

@section('create-user-display')
<div class="mb-12 border border-warning">
    <div class="form-group row m-2">
        <div class="m-3">
            <p class="mb-3 text-center">Create a new user</p>
            <form class="m-3" action="" method="post">
                @csrf
                <div class="form-group row">
                      <label for="email" class="mb-4 col-sm-4 col-form-label">Email</label>
                      <div class="col-sm-8">
                          <input value="{{ old('email', null) }}" type="text" class="form-control" name="email" id="email" placeholder="Email">
                      </div>
                </div>
                @if($errors->has('email'))
                    <div class="alert alert-danger mb-3" role="alert">
                        {{ $errors->get('email')[0] }}
                    </div>
                @endif
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
