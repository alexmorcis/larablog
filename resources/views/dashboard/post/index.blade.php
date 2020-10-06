@extends('dashboard.master')
@section('content')
<div class="container">
    <a class='btn btn-success mt-3 mb-3 ' href="{{ route('post.create') }}">
        crear
    </a>

    <table class="table">
        <thead>

            <tr>
                <td>
                    Id
                </td>
                <td>
                    Título
                </td>
                <td>
                    Posteado
                </td>
                <td>
                    Creación
                </td>

                <td>
                    Actualizacion

                </td>
                <td>
                    Acciones

                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <td>
                    {{ $post->id }}
                </td>
                <td>
                    {{ $post->title }}
                </td>
                <td>
                    {{ $post->posted }}
                </td>
                <td>
                    {{ $post->created_at}}
                </td>

                <td>
                    {{ $post->updated_at }}

                </td>
                <td>

                </td>
                </tr>
            @endforeach

        </tbody>

    </table>
   
    {!! $posts->appends(['sort' => 'department'])->links() !!}
@endsection
