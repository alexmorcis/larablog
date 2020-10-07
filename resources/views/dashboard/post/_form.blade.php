
    @csrf
    <div class="form-group">
        <label for="title">Título</label>
        <input class="form-control" type="text" name="title" id="title" value={{ old('title',$post->title) }}>
    </div>
    @error('title')
    <div class="alert alert-danger" role="alert">
        {{ $message }}
    </div>
    @enderror

    <div class="form-group">
        <label for="url_clean">Url limpia</label>
        <input class="form-control" type="text" name="url_clean" id="url_clean" value={{ old('url_clean' ,$post->url_clean) }}>
    </div>
    <div class="form-group">
        <label for="content">Contenido</label>
        <textarea class="form-control" name="content" id="content" rows="3">{{ old('content',$post->content), }} </textarea>
    </div>
    <input type="submit" value="Enviar" class="btn btn-primary">

