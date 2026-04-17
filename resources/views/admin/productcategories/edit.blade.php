@extends('admin/layouts.app')

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header">Product Categories - Edit <a href="{{ route('productcategories.index') }}" class="btn btn-light float-right btn-sm "><i class="fas fa-chevron-left"></i> Go Back</a></div>

    <div class="card-body">
        <form method="post" action="{{ route('productcategories.update', $category->id) }}" enctype="multipart/form-data" novalidate>
            @csrf
            {{ method_field('PUT') }}
            <div class="row">
                <div class="col-md-5">

                    <div class="form-group">
                        <label for="categories">Product Type <span class="required">*</span></label>
                        <select class="form-control" id="type" name="type" required >
                            <option value="0" <?php if($category->type==0){ echo 'selected="selected"'; } ?>>General</option>
                            <option value="1" <?php if($category->type==1){ echo 'selected="selected"'; } ?>>By Trade Name</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="name">Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="Write category name" maxlength="150" required>
                    </div>

                     <div class="form-group">
                        <label for="name">Display Order <span class="required">*</span></label>
                        <input type="number" class="form-control" id="display_order" name="display_order" value="{{ old('name', $category->display_order) }}" placeholder="Display Order" maxlength="5" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="6" required>{{!! old('description', $category->description) !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="is_active">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $category->meta_title) }}" placeholder="Meta title" onkeyup="countChar(this, 'charNumTitlemeta', 125)" maxlength="125">
                        <span id="charNumTitlemeta" class="text-info">125 Characters Left</span>
                    </div>

                    <div class="form-group">
                        <label for="is_active">Meta Keywords</label>
                        <textarea name="meta_keywords" id="meta_keywords" class="form-control" rows="3" maxlength="280" placeholder="Meta Keywords" onkeyup="countChar(this, 'charNumExcerptKey', 280)">{{ old('meta_keywords', $category->meta_keywords) }}</textarea>
                        <span id="charNumExcerptKey" class="text-info">280 Characters Left</span>
                    </div>

                    <div class="form-group">
                        <label for="is_active">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" class="form-control" rows="3" maxlength="280" placeholder="Meta Description" onkeyup="countChar(this, 'charNumExcerptDescription', 280)">{{ old('meta_description', $category->meta_description) }}</textarea>
                        <span id="charNumExcerptDescription" class="text-info">280 Characters Left</span>
                    </div>

                    <div class="form-group">
                        <label for="image">Featured Image <span class="required">*</span></label>
                        <img class="img-fluid" src="{{asset('storage/'.$category->image.'')}}" alt="{{ $category->title }}">
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="is_active">Publish <span class="required">*</span></label>
                        <select class="form-control" id="is_active" name="is_active" required>
                            <option value="1" @if(old('is_active', $category->is_active) == 1) selected @endif>Yes</option>
                            <option value="0" @if(old('is_active', $category->is_active) == 0) selected @endif>No</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                </div>
            </div>
        </form>
    </div>

</div>
<script type="text/javascript">
    // Count Char Helper
function countChar(val, id, limit) {
    leftChar = limit - val.value.length;
    $('#'+id).text( leftChar + " Characters Left");
}
</script>
@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>
<script>
// Integrate TinyMCE Editor
// Make Config Settings
var editor_config = {
    path_absolute : base_url,
    selector:'#description',
    height: 450,
    plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
  toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat',
    image_advtab: true,
    relative_urls: false,
    file_browser_callback : function(field_name, url, type, win) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = editor_config.path_absolute + '/tinymce/filemanager?field_name=' + field_name;
      if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no"
      });
    }
  };
// Init TinyMCE
tinymce.init(editor_config);
</script>
@endsection
