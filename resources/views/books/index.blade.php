<form method="GET" style="margin-bottom: 20px;">
    <label>
        List shown :
        <select name="per_page">
            @foreach($perPageOptions as $option)
                <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
    </label>
    <br>
    <label>
        Search :
        <input type="text" name="search" value="{{ $search }}">
    </label>
    <br>
    <button type="submit" style="background: #4285f4; color: white; padding: 8px 24px; border: none; margin-top: 10px;">SUBMIT</button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Book Name</th>
            <th>Category Name</th>
            <th>Author Name</th>
            <th>Average Rating</th>
            <th>Voter</th>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $i => $book)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->bookCategory->name }}</td>
                <td>{{ $book->author->name }}</td>
                <td>{{ number_format($book->ratings_avg_rating, 2) }}</td>
                <td>{{ $book->ratings_count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>