<!DOCTYPE html>
<html>
<head>
    <title>Image Upload & Display</title>
</head>
<body>
    <h1>Upload Image</h1>
    <form action="/upload" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <select name="type" required>
            <option value="products">Products</option>
            <option value="users">Users</option>
            <option value="banners">Banners</option>
        </select>
        <button type="submit">Upload</button>
    </form>

    <h2>Display Image</h2>
    <form action="/show" method="GET">
        <select name="type" required>
            <option value="products">Products</option>
            <option value="users">Users</option>
            <option value="banners">Banners</option>
        </select>
        <input type="text" name="filename" placeholder="filename.jpg" required>
        <button type="submit">Show Image</button>
    </form>
</body>
</html>