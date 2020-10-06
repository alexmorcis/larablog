@extends('dashboard.master')
@section('content')
<div class="container">
  @include('dashboard.partials.validation-error')
<form action="{{ route("post.store") }}" method="POST">
 @csrf
    <div class="form-group">
        <label for="title">Título</label>
        <input  class= "form-control" type="text" name="title" id="title" >
    </div>
    @error('title')
    <div class="alert alert-danger" role="alert">
        {{ $message }}
  </div>
    @enderror
    
    <div class="form-group">
        <label for="url_clean">Url limpia</label>
        <input  class= "form-control" type="text" name="url_clean" is="url_clean">
    </div>
    <div class="form-group">
        <label for="content">Contenido</label>
        <textarea class= "form-control" name="content" id="content"  rows="3"></textarea>
    </div>
    <input type="submit" value="Enviar" class="btn btn-primary">
</form>
</div>
@endsection
