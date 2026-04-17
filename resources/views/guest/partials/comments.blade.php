@if($blog->allow_comments == 1)
<!-- Comments List Box -->
 <div class="comment-section">
    <h3>Comments <small class="float-right">{{ $total_comments }} Comments</small></h3>
		@if(count($comments) < 1)
		<h4>No comments yet! Be the first to comment</h4>
		@else
		  
				@foreach($comments as $comment)
					<div class="comment">
						<p class="comment-author">{{ $comment->name }} - <small>{{ $comment->created_at->diffForHumans() }}</small></p>
						<p class="comment-text">{{ $comment->body }}</p>
					</div>
				@endforeach
			
		@endif
</div>

@auth
<form action="#" method="post" id="deletCommentForm" display: none;>
    @csrf
    {{ method_field('DELETE') }}
</form>

@section('custom_js')
<script>
function callDeletItem(id, model) {
    if (confirm('Are you sure?')) {
        $("#deletCommentForm").attr('action', base_url + '/admin/'+ model + '/' + id);
        $("#deletCommentForm").submit();
    }
}
</script>
@endsection
@endauth

@endif