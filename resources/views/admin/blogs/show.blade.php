@extends('admin/layouts.app')

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header">Blogs - View <a href="{{ route('blogs.index') }}" class="btn btn-light float-right btn-sm "><i class="fas fa-chevron-left"></i> Go Back</a></div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <p><b>ID:</b> {{ $blog->id }}, <b>Created at</b>: {{ $blog->created_at }}, <b>Updated At</b> {{ $blog->updated_at }}</p>
                <div class="form-group">
                    <label for="title"><b>Title:</b></label>
                    <p>{{ $blog->title }}</p>
                    <p><b>Slug</b>: {{ $blog->slug }}</p>
                </div>
                <div class="form-group">
                    <label for="excerpt"><b>Excerpt/Summary: </b></label>
                    {{ $blog->excerpt }}
                </div>
                <div class="form-group">
                    <label for="description"><b>Description:</b></label>
                    {!! $blog->description !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="image"><b>Featured Image:</b></label>
                    @if($blog->image)
                        <img class="img-fluid" src="{{ safe_storage_url($blog->image) }}" alt="{{ $blog->title }}">
                    @else
                        <p class="text-muted">No image available</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="categories"><b>Categories:</b></label>
                    @if($blog->categories && $blog->categories->count() > 0)
                        <ul>
                        @foreach($blog->categories as $category)
                            <li>{{ $category->name }}</li>
                        @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No categories</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="user_id"><b>Author:</b></label>
                    {{ $blog->user ? $blog->user->name : 'Unknown' }}
                </div>
                <div class="form-group">
                    <label for="is_active"><b>Publish:</b></label>
                    {{ $blog->is_active == 1 ? 'Yes' : 'No' }}
                </div>
                <div class="form-group">
                    <label for="allow_comments"><b>Allow Comments:</b></label>
                    {{ $blog->allow_comments == 1 ? 'Yes' : 'No' }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('custom_js')
@endsection
