<h1 style="text-align:center; margin-bottom: 30px;">Top 10 Most Famous Author</h1>
<table border="1" cellpadding="8" cellspacing="0" style="margin: 0 auto;">
    <thead>
        <tr>
            <th>No</th>
            <th>Author Name</th>
            <th>Voter</th>
        </tr>
    </thead>
    <tbody>
        @foreach($authors as $i => $author)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $author->name }}</td>
                <td>{{ $author->voter_count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>