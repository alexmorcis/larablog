
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=devise-width, initial-sacle=1.0">
        <title>Mi primera vista</title>
    </head>

        <body>
            <h1>Mundo Laravel - {!! "Hola mundo $nombre $apellido "!!}</h1>
            <ul>
                @isset($posts2)
                    isset
                @endisset
                @empty($post3)
                    vacio
                @endempty
             
                @forelse ($posts as $post)
                {{--  <?php dd($loop)?>  --}}
                <li>
                    @if ($loop->first)
                    Primero:   
                   
                    @elseif ($loop->last)
                    Ultimo:  
                    @else
                    Medio: 
                    @endif
                    {{ $post }}
                </li>    
                @empty
                <li>vacio</li>
                    
                @endforelse
            
            </ul>
        </body>
  
    

</html>    