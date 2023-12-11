<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table, td, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<div>
    <table>
        <thead>
        <tr>
            <td>ID</td>
            <td>Ten huyet</td>
            <td>Phoi huyet</td>
            <td>Nhan huyet</td>
        </tr>
        </thead>
       <tbody>
            @foreach($arrNhanHuyet as $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{!!  $value->tenhuyet !!}</td>
                    <td>{!!  $value->phoihuyet !!}</td>
                    <td>{!!  $value->nhanhuyet !!}</td>
                </tr>
            @endforeach
       </tbody>
    </table>

</div>
</body>
</html>
