<!-- resources/views/posts/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
        <table class="table-striped">
            <thead>
                <tr>
                    <th width="30%">Title</th>
                    <th width="25%">Author</th>
                    <th width="20%">Created At</th>
                    <th width="25%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through all the posts -->
                @foreach ($posts as $post)
                    <tr>
                        <td style="padding: 10px;">
                            <div style="display: flex; align-items: center;">
                                @if(isset($imageMap[$post->title]))
                                        <img src="{{ asset($imageMap[$post->title]) }}" alt="Image" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                                    @endif
                                    <span>{{ $post->title }}</span>
                            </div>
                        </td>
                        <td style="padding: 10px;">{{ $post->title }}</td>
                        <td style="padding: 10px;">{{ $post->name }}</td>
                        <td style="padding: 10px;">{{ $post->created_at->format('M d, Y H:i:s') }}</td>
                        <td>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary mr-2 btn-sm">View</a>

                            @if (Auth::user()->can('edit posts'))
                                <a href="{{ route('posts.edit', $post->id) }}"
                                    class="btn btn-secondary mr-2 btn-sm">Edit</a>
                            @endif

                            @if (Auth::user()->can('delete posts'))
                                <form action="{{ route('posts.delete', $post->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger mr-2 btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
