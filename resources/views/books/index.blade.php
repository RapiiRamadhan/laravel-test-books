@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Books Section -->
    <div class="d-flex justify-content-between">
        <h2>Books List</h2>
        <a href="{{ route('books.create') }}" class="btn btn-primary">Add New Book</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Serial Number</th>
                <th>Published At</th>
                <th>Author</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
            <tr id="book-{{ $book->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->serial_number }}</td>
                <td>{{ $book->published_at }}</td>
                <td>{{ $book->author->name }}</td>
                <td>
                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No Books Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $books->links() }}
</div>

<script>
    $(document).on('click', '.delete-book', function() {
        var bookId = $(this).data('id');
        if (confirm('Are you sure you want to delete this book?')) {
            $.ajax({
                url: '/books/' + bookId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    $('#book-' + bookId).remove();  
                    alert('Book deleted successfully!');
                }
            });
        }
    });
</script>
@endsection
