<?php exit; ?>{comments_admin}
{news_posts}
<!-- if(notices) -->
      {news_notices}
<!-- endif(notices) -->
<center>{news_pagination}</center>
<!-- if(admin) -->
{news_admin}
<script type="text/javascript">
function styleopen(yx)
{
document.getElementById(yx).style.display='block';
}
</script>
<div id="adminpost" style="display:none">
{news_admin_form}
</div>
<!-- endif(admin) -->
