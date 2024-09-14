@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between">
        <h2>Authors List</h2>
        <a href="{{ route('authors.create') }}" class="btn btn-primary">Add New Author</a>
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
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($authors as $author)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $author->name }}</td>
                <td>{{ $author->email }}</td>
                <td>
                    <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('authors.destroy', $author->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No Authors Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $authors->links() }}
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).on('click', '.delete-author', function() {
        var authorId = $(this).data('id');
        if (confirm('Are you sure you want to delete this author?')) {
            $.ajax({
                url: '/authors/' + authorId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    $('#author-' + authorId).remove();
                    alert('Author deleted successfully!');
                }
            });
        }
    });
</script>
@endsection
