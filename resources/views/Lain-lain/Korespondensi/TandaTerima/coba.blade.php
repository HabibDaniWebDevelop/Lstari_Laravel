
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @forelse ($data as $item)
    <img src="http://192.168.1.100/image/{{$item}}" alt="Trulli" width="500" height="333">
    @empty
    @endforelse

    
</body>
</html>