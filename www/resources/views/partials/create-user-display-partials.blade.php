<div class="form-group row">
      <label for="name" class="mb-4 col-sm-4 col-form-label">Username</label>
      <div class="col-sm-8">
          <input value="{{ old('name', null) }}" type="text" class="form-control" name="name" id="name" placeholder="Username">
      </div>
</div>
@if($errors->has('name'))
    <div class="alert alert-danger mb-3" role="alert">
        {{ $errors->get('name')[0] }}
    </div>
@endif
<div class="form-group row">
    <label for="password" class="mb-4 col-sm-4 col-form-label">Password</label>
    <div class="col-sm-8">
        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
  </div>
</div>
@if($errors->has('password'))
    <div class="alert alert-danger mb-3" role="alert">
        {{ $errors->get('password')[0] }}
    </div>
@endif
<input type="hidden" name="email" id="email" value="{{ rand(1, 50000) }}new_user@mail.com">
