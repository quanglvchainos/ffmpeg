<!doctype html>
<html>
<body>
<form action="{{route('postThum')}}" method="post"   enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <label for="file">Filename:</label>
    <div class="form-group">
        <label for="video" class="">Video</label>
        <input type="file" class="form-control" id="video" name="video" accept="video/*" placeholder="">
    </div>>

    <input type="submit" name="submit" value="Submit" />
</form>
</body>
</html>