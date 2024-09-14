<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('author')->paginate(10);
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $authors = Author::all();
        return view('books.create', compact('authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:books,serial_number',
            'published_at' => 'required|date',
            'author_id' => 'required|exists:authors,id',
        ]);

        Book::create($request->only(['title', 'serial_number', 'published_at', 'author_id']));

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        return view('books.edit', compact('book', 'authors'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:books,serial_number,' . $book->id,
            'published_at' => 'required|date',
            'author_id' => 'required|exists:authors,id',
        ]);

        try {
            $book->update($request->only(['title', 'serial_number', 'published_at', 'author_id']));
            return redirect()->route('books.index')->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function destroy(Book $book)
    {
        try {
            $book->delete();
            return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }
}
