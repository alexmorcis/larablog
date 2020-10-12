@extends('dashboard.master')
@section('content')

    <div class="form-group">
        <label for="title">Título</label>
        <input  readonly class= "form-control" type="text" name="title" id="title"  value ={{ $post->title }}>
    </div>

    
    <div class="form-group">
        <label for="url_clean">Url limpia</label>
        <input readonly class= "form-control" type="text" name="url_clean" id="url_clean" value ={{ $post->url_clean }}>
    </div>
    <div class="form-group">
        <label for="content">Contenido</label>
        <textarea readonly class= "form-control" name="content" id="content"   rows="3">{{$post->content }} </textarea>
    </div>
    <input type="submit" value="Enviar" class="btn btn-primary">
</form>

@endsection
