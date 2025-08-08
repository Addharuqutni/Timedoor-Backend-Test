<h1 style="text-align:center; margin-bottom: 30px;">Insert Rating</h1>
<form method="POST" action="{{ route('ratings.store') }}" style="width: 500px; margin: 0 auto;">
    @csrf

    <div style="margin-bottom: 20px;">
        <label>Book Author :</label>
        <select name="author_id" id="author_id" required style="width: 100%;">
            <option value="">-- Select Author --</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}">{{ $author->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom: 20px;">
        <label>Book Name :</label>
        <select name="book_id" id="book_id" required style="width: 100%;">
            <option value="">-- Select Book --</option>
            {{-- Book options will be filled by JS --}}
        </select>
    </div>

    <div style="margin-bottom: 20px;">
        <label>Rating :</label>
        <select name="rating" required style="width: 100%;">
            @for($i=1; $i<=10; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div style="text-align:center;">
        <button type="submit" style="background: #4285f4; color: white; padding: 8px 24px; border: none;">SUBMIT</button>
    </div>
</form>

<script>
    // Prepare books data for JS
    const booksByAuthor = @json($authors->mapWithKeys(fn($a) => [$a->id => $a->books->map(fn($b) => ['id' => $b->id, 'title' => $b->title])]));

    document.getElementById('author_id').addEventListener('change', function() {
        const authorId = this.value;
        const bookSelect = document.getElementById('book_id');
        bookSelect.innerHTML = '<option value="">-- Select Book --</option>';
        if (booksByAuthor[authorId]) {
            booksByAuthor[authorId].forEach(function(book) {
                const opt = document.createElement('option');
                opt.value = book.id;
                opt.textContent = book.title;
                bookSelect.appendChild(opt);
            });
        }
    });
</script>