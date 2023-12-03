<form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".csv"> <!-- Change the name to "file" -->
    <button type="submit">Upload CSV</button>
</form>
