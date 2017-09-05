<html>
<head>
    <title>文件上传</title>
</head>
<body>
<h1>七牛文件上传</h1>
<form action="{{url('/api/cloud/qiniu/upload')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="image" >
    <input type="hidden" name="group_id" value="1" >
    <input type="submit" name="dosubmit" value="上传">
</form>
</body>
</html>
