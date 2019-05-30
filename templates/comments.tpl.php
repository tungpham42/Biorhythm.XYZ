<form method="POST" id="comment_form" class="w-100">
  <div class="form-group">
    <input type="text" name="comment_name" id="comment_name" class="form-control" placeholder="Enter Name" />
  </div>
  <div class="form-group">
    <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Enter Comment" rows="5"></textarea>
  </div>
  <div class="form-group">
    <input type="hidden" name="comment_id" id="comment_id" value="0" />
    <input type="submit" name="submit" id="submit" class="btn btn-warning" value="Submit" />
  </div>
</form>
<span id="comment_message" class="w-100"></span>
<br />
<div id="display_comment" class="w-100"></div>
<script type="text/javascript">
$(document).ready(function(){
 
  $('#comment_form').on('submit', function(event){
    event.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
      url:"/templates/comments/add_comment.php",
      method:"POST",
      data:form_data,
      dataType:"JSON",
      success:function(data)
      {
        if(data.error != '')
        {
          $('#comment_form')[0].reset();
          $('#comment_message').html(data.error);
          $('#comment_id').val('0');
          load_comment();
        }
      }
    })
  });

  load_comment();

  function load_comment()
  {
    $.ajax({
      url:"/templates/comments/fetch_comment.php",
      method:"POST",
      success:function(data)
      {
        $('#display_comment').html(data);
      }
    })
  }

  $(document).on('click', '.reply', function(){
    var comment_id = $(this).attr("id");
    $('#comment_id').val(comment_id);
    $('#comment_name').focus();
  });
 
});
</script>