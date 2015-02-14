<?php
//you looking at comments
//FOR SIMPLE BOX
$db->select_db($db_name);//making sure right db is selected

//
$box_simple_short = new Template("styles/".$style."/box_simple_short.php");
$box_short = new Template("styles/".$style."/box_short.php");
$box_short->setVar("imagepath", 'styles/'.$style.'/images/');
$box_simple_short->setVar("imagepath", 'styles/'.$style.'/images/');
//NEWS RELATED TEMPLATE:
$tpl_news = new Template("styles/".$style."/news.php");

//viewing comments
if(isset($_GET['news']) && isset($_GET['page'])&& !isset($_GET['delete'])) 
 {
   $page=pun_htmlspecialchars(preg_replace( "/[^a-zA-Z0-9'.!?:]/", "", $_GET['page'] ));
   $news=pun_htmlspecialchars(preg_replace( "/[^a-zA-Z0-9'.!?:]/", "", $_GET['news'] ));
   $post=pun_htmlspecialchars(preg_replace( "/[^a-zA-Z0-9'.!?:]/", "", $_GET['post'] ));
   $deletecomment=pun_htmlspecialchars($_GET['deletecomment']);
   $limit=10;
   $selection="SELECT * from news where id='".$news."'";
   $selection2=mysql_query($selection);
  
     
		
		 $cont1= '<a href="./">&laquo; Go Back</a>  ';
		 if (!$a_user['is_guest'] && ($a_user[$db_translation['gm']]==$db_translation['a'] or $a_user[$db_translation['gm']]==$db_translation['az']))
		 $cont1.= '&nbsp;| GM & admin tools: <a href="./?news='.$news.'&page=0&deletecomments">[Prune all comments]</a>';
		$box_simple_short->setVar("content", $cont1);
    	$comments_admin= $box_simple_short->toString();
  //POST comment
	if (!$a_user['is_guest'])
	{
		if ($_POST['comm2'])
		{		
			$t1=time();
			$t2=date('F j, Y');
			$message=pun_linebreaks(pun_trim(parse_message($_POST['comm1'])));
			$_POST['comm3']=preg_replace( "/[^0-9]/", "",$_POST['comm3'] ); 
			$db->query("INSERT INTO comments (poster,content,newsid,timepost,datepost) VALUES ('".$a_user[$db_translation['login']]."','".$db->escape($message)."','".$_POST['comm3']."','".$t1."','".$t2."')") or error('Unable to add new comment. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Re-importing sql database you got in part 1 of site download will probably fix problem', __FILE__, __LINE__);
			$box_simple_short->setVar("content", 'Thank you, your comment has been posted.');
    		$news_content1= $box_simple_short->toString(); echo '<div style="height:4px"></div>';
		}
		elseif (isset($_GET['deletecomments']))
		{
			if (!$a_user['is_guest'] && ($a_user[$db_translation['gm']]==$db_translation['a'] or $a_user[$db_translation['gm']]==$db_translation['az']))
			{
			$db->query("DELETE FROM comments WHERE newsid='".$news."'") or error('Unable to delete the comment. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please notify an Administrator whom will fix this issue.', __FILE__, __LINE__);
			$box_simple_short->setVar("content", 'All comments for this news announcement have been deleted.');
    		$news_content1= $box_simple_short->toString(); echo '<div style="height:4px"></div>';
			}
		}
		
	}    

   $getforuminfo="SELECT * from news where id='".$news."' limit 1";
   $getforuminfo2=mysql_query($getforuminfo) or die("Could not get forum info");
   $getforuminfo3=mysql_fetch_array($getforuminfo2);
   
 
  
   while($selection3=mysql_fetch_array($selection2))
  {
	 $getforuminfo3['content']=pun_linebreaks2($getforuminfo3['content']);
	 $cont4_title.= $getforuminfo3['title'];
	 $cont4.= $getforuminfo3['content']."<strong>Posted by $getforuminfo3[author] &nbsp;&nbsp; $getforuminfo3[datepost]&nbsp;</strong><br><br>";
	 $getcomm="SELECT * from comments where newsid='".$news."' order by timepost ASC limit ".$page.",".$limit;
	 $getcomm2=mysql_query($getcomm) or error('Unable to select comments. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please notify an Administrator whom will fix this issue.', __FILE__, __LINE__);
	 while($getcomm3=mysql_fetch_array($getcomm2))
	  {
	   		if ($deletecomment==$getcomm3['id'])
			{
				if (!$a_user['is_guest'] && ($a_user[$db_translation['gm']]==$db_translation['a'] or $a_user[$db_translation['gm']]==$db_translation['az']))
				{
				$db->query("DELETE FROM comments WHERE id='".$deletecomment."' LIMIT 1") or error('Unable to delete the comment. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please notify an Administrator whom will fix this issue.', __FILE__, __LINE__);
				$cont4.= '<i>This comment is now deleted.</i><br><br>';
				}
			}
			else
			{
				$cont4.= "<div style=' font-size: 11px; color:#ffffff'>";
				if (!$a_user['is_guest'] && ($a_user[$db_translation['gm']]==$db_translation['a'] or $a_user[$db_translation['gm']]==$db_translation['az']))
				{
					$cont4.= '<span style="float:right"><a href="./?news='.$news.'&page='.$page.'&deletecomment='.$getcomm3['id'].'">[x]</a></span>';
				}
				$cont4.="<em><strong>".$getcomm3['poster']."</strong> @ $getcomm3[datepost] says:</em>";
				
				$cont4.= "</div>";
				$cont4.= "<div style='font-size: 9px; margin-left: 8px; color:#00FFFF'>".$getcomm3['content']."</div>"; 
			}
	 }
//if logged in
//	 if (!$a_user['is_guest'])
//   {
//				 $cont4.='<br /><br />
//				 <form action="?news='. $news .'&page=0" method="post">
//				 <textarea name="comm1" cols="82" rows="5"></textarea><br/>
//				 <input name="comm3" type="hidden" value="'. $news .'" />
//				 <div id="log-b3"><input name="comm2" type="submit" value="Comment" /></div><br/><br/><br/>
//				 </form>';
//	 }

	 
	 	$box_short->setVar("content_title", $cont4_title);
		$box_short->setVar("content", $cont4);
		$news_content1.= $box_short->toString();$cont4_title='';$cont4='';
	 
	 
	 
  }

  $order="SELECT COUNT(*) from comments where newsid=$news order by timepost DESC";
  $order2=mysql_query($order);
  $d=0;
  $f=0;
  $g=1;
  $order3=mysql_result($order2,0);
  $start=$_GET['page'];
  $prev=$start-$limit;
  $next=$start+$limit;
  if($start>=$limit)
  { $news_pagination ='<a href="?news='.$news.'&page=0" >First</a>&nbsp&nbsp;&nbsp; 
     <a href="?news='.$news.'&page='.$prev.'" >&laquo;</a>&nbsp;
	';
  }
  while($f<$order3)
   {
      if($f%$limit==0)
       {
        if($f>=$start-3*$limit&&$f<=$start+7*$limit)
         { $news_pagination.='
		   <a href="?news='.$news .'&page='.$d.'" >
          '.$g.'</a> ';
           $g++;
         }
       }
     $d=$d+1;
     $f++;
   }
  if($start<=$order3-$limit)
  { $news_pagination.='
    <a href="?news='. $news.'&page='.$next.'" >
    &raquo;</a>&nbsp;&nbsp;&nbsp; ';
    $last=$order3-$limit;
	$news_pagination.='
    <a href="?news='.$news.'&page='.$last.'" >
    Last</a>&nbsp;&nbsp;&nbsp; ';
  }  

}

else if(!isset($_GET['news']) && !isset($_GET['page'])&& isset($_GET['delete']))
{	if (isset($_GET['delete']) && isset($_GET['confirm'])) 
	{
		//delete specific news
		$delid=pun_htmlspecialchars($_GET['delete']);
		if (!$a_user['is_guest'] &&  $a_user[$db_translation['gm']]==$db_translation['az'])
		{
			$db->query("DELETE FROM news WHERE id='".$delid."'") or error('Unable to delete the selected news. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Re-importing sql database will probably fix the problem', __FILE__, __LINE__);
			$box_simple_short->setVar('content',"News Deleted, redirecting...<meta http-equiv='refresh' content='2;url=./'>");
			$tpl_news->setVar("notices.news_notices", $box_simple_short->toString());
		}
	}
	else
	{
		$delid=pun_htmlspecialchars($_GET['delete']);
		$box_simple_short->setVar('content',"<center>Are you sure you want to delete the selected news?<br><br><a href='?delete=".$delid."&confirm=yes'>Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='./'>No</a>");
		
		 $tpl_news->setVar("notices.news_notices", $box_simple_short->toString());
	}
}
else //looking at main index
{
	//edit post
	if (isset($_POST['editnews']))
	{
		if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]==$db_translation['az'])
			$db->query("UPDATE news SET title='".$db->escape($_POST['edittitle'])."',content='".parse_message(pun_linebreaks($db->escape($_POST['newsedit'])))."' WHERE id='".$db->escape($_POST['id'])."'") or error('Unable to edit the selected news. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Re-importing sql database will probably fix the problem', __FILE__, __LINE__);
		
	}
	//edit post end
      $forumselect1="SELECT * from news order by timepost DESC limit 10";
      $forumselect2=mysql_query($forumselect1) or error('Unable select news from database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Re-importing sql database you got in part 1 of site download will probably fix problem', __FILE__, __LINE__);
   
        while($forumselect3=mysql_fetch_array($forumselect2))
        {
		    $ico=$forumselect3['iconid'];
			if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]==$db_translation['az'])
				  {
				  $content1.= "
				  <span style='float:right;font-weight:normal'>
				   <a href='./?edit=$forumselect3[id]'>[edit]</a>&nbsp;&nbsp;
				   <a href='./?delete=$forumselect3[id]'>[x]</a>
				   &nbsp;&nbsp;
				  </span>
				  "; 
				  }
				  
			$content1.= '<a href="?news='. $forumselect3['id'] .'&page=0">'. $forumselect3['title']."</a>";
				 
				  
			      $forumselect3['content']=pun_linebreaks2($forumselect3['content']);
				  if (!isset($_GET['edit']) or $_GET['edit']<>$forumselect3['id'])
			      	$content2.= $forumselect3['content'];
				  else
				  {
				  	if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]==$db_translation['az'])
				  	{
					$content2.= '<form action="index.php" method="post"> Editing news with BB-code as format:<br/>
					<input name="edittitle" type="text" style="width:95%" value="'.pun_htmlspecialchars($forumselect3['title']).'" /><br><br><textarea name="newsedit" cols="" rows="15" style="width:95%">'.pun_htmlspecialchars($forumselect3['content']).'</textarea><br> <input name="id" type="hidden" value="'.$_GET['edit'].'"/>
					<div id="log-b3"><input name="editnews" type="submit" value="Edit" /></div><br/><br/>
					</form>';
					}
					else
						$content2.= $forumselect3['content'];
				}
				  $ccount1 = "SELECT COUNT(*) FROM comments where newsid='".$forumselect3['id']."'";
				  $ccount2=mysql_query($ccount1) or error('Unable select the number of comments from the database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Re-importing sql database will probably fix the problem', __FILE__, __LINE__);
				  $ccount= mysql_result($ccount2, 0); 
				  $content2.= "<strong>Posted by $forumselect3[author]&nbsp;&nbsp; $forumselect3[datepost]</strong>";
				  
				  //$content2.= "<strong>Posted by $forumselect3[author]&nbsp;&nbsp; $forumselect3[datepost]</strong>&nbsp;&nbsp;&nbsp;&nbsp;
				  //<a href='?news=$forumselect3[id]&page=0'>$ccount comments</a>";
			
            
			$box_short->setVar("content_title", $content1);
			$box_short->setVar("content", $content2);
			$news_content1 .= $box_short->toString();
			$content1='';$content2='';
          
        }
		
        if (mysql_num_rows($forumselect2) > 0)
        {
         	mysql_data_seek($forumselect2,0);
        }
		
		
		//if logged in
		
		 if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]==$db_translation['az'])
		 {
			 if ($_POST['comm5'] )
			 {		
			 		$t1=time();
					$t2=date('F j, Y');
					$title=pun_htmlspecialchars($_POST['title']);
			 		$message=parse_message(pun_linebreaks($_POST['comm4']));
					$author=$_POST['name'];
					$db->query("INSERT INTO news (title,content,iconid,timepost,datepost,author) VALUES ('".$db->escape($title)."','".$db->escape($message)."','0','".$t1."','".$t2."','".$db->escape($author)."')") or error('Unable to add news. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Re-importing the sql database will probably fix the problem', __FILE__, __LINE__);
					$box_simple_short->setVar("content", "News Posted, redirecting back to the home page.<meta http-equiv='refresh' content='1;url=./'>");
					$news_content2= $box_simple_short->toString();
			 }
			 else //admin area
			 {
			 		$box_simple_short->setVar("content", 'Admin Tool: <a style="cursor:pointer" onclick="javascript:styleopen(\'adminpost\')">[Post news]</a>');
					$admin_box.= $box_simple_short->toString();
					
					$box_short->setVar("content_title", "Post News - only Admin accounts can post or edit news.");
					$box_short->setVar("content", '
					 <form action="" method="post">
					 <input name="name" type="hidden" value="'.$a_user[$db_translation['login']].'" />
					 Title: <br /><input name="title" type="text" style="width:97%" /><div class="spacer"></div>
					 News body: (BBCode and Smilies On)<br /><textarea name="comm4" style="width:518px;" rows="15"></textarea>
					 <div id="log-b3"><input name="comm5" type="submit" value="Post" /></div><br/><br/><br/>
					 </form>
					');
					$admin_box_form = $box_short->toString();
					
					
					
					
					 
					
			 }
		 }
		
		
		
}
$tpl_news->setVar("news_posts", $news_content1);
$tpl_news->setVar("comments_admin", $comments_admin);
$tpl_news->setVar("news_pagination", $news_pagination);
if ($admin_box<>'')
	$tpl_news->setVar("admin.news_admin", $admin_box);
if ($admin_box_form<>'')
	$tpl_news->setVar("admin.news_admin_form", $admin_box_form);
if ($news_content2<>'')
	$tpl_news->setVar("notices.news_notices", $news_content2);



//finally for all elements:
 //news posts boxes:
	$news_content.= $tpl_news->toString();
 //

?>