<?php exit; ?>

<div class="c_butt">
 <ul>
 <li id="facebook"><a href="http://www.facebook.com/profile.php?id=100001662225297" target="_blank"></a></li>
 <li id="twitter"><a href="http://twitter.com/OfficalBRG" target="_blank"></a></li>
 <li id="youtube"><a href="http://www.youtube.com/user/jeutie" target="_blank"></a></li>
 </ul>
</div>

<div class="side-r">
<!-- if(sidebar_guest) -->
<div class="membersip_b">
<div class="mems-b-head">Профиль</div>
  <div class="mem-b-cont">
  <form action="quest.php?name=login" method="post" name="login_form">
  <input type="text" id="username" maxlength="20" name="username" class="loginbox_username" />
  <input type="password" id="password" maxlength="20" name="password" class="loginbox_password"  />
  <input type="hidden" name="action" value="Login" />
  <div class="line1"></div>
  <table cellpadding="2" cellspacing="2"><tr>
  <td valign="top" style=" text-align:right">
  <div id="log-b"><input type="submit" value="Войти" /></div>
  </td>
  <td valign="top" style="padding: 5px 0px 0px 0px;">
  <a href="quest.php?name=gimmepass">Забыли пароль?</a>
  <a href="quest.php?name=register">Создать аккаунт</a>
  </td>
  </tr></table>
 </form>
 </div>
 <div class="mems-b-down"></div>
</div>
<!-- else(sidebar_guest) -->	
<!-- endif(sidebar_guest) -->

<!-- if(sidebar_loggedin) -->
<div class="membersip_b">
<div class="mems-b-head">Профиль</div>
  <div class="mem-b-cont">
  Логин: <strong><font color="#696969"> {username} </font></strong><br /><br />
  Донат бонусы {gm}<br />
  Бонусы голосования: {vp}<br />
  Активность: {banned}<br />
  <div class="acc-p-b"><a href="quest.php?name=account">&raquo; Личный кабинет</a></div>		
  </div>
<div class="mems-b-down"></div>
</div>
<!-- else(sidebar_loggedin) -->	
<!-- endif(sidebar_loggedin) -->

<div class="realms-status">
<!--<strong>[<a href="./quest.php?name=status" title="">Игроки онлайн</a>]</strong>-->
    
<div class="realm-st">
  <div class="r-st-up"> {s1name} {online1} </div>
  <div class="r-st-d">  
    <div class="idk"> Онлайн: <font color="#262626">{totcharacters}</font> </div> 
    Аптайм: <font color="#262626">{uptime}</font> 
  </div>
</div>
	
<!-- if(server2and3) -->
<div class="realm-st">
  <div class="r-st-up"> {s2name} {online2} </div>
  <div class="r-st-d">  
    <div class="idk"> Онлайн: <font color="#262626">{totcharacters2}</font> </div> 
    Аптайм: <font color="#262626">{uptime2}</font> 
  </div>
</div>     
<!-- else(server2and3) -->	
<!-- endif(server2and3) -->
 <center><div class="realmlist">
  <center> Set realmlist  <font color="#43170e">wow-paradise.ru</font> </center>
 </div></center>
</div>

<!-- Donation GOAL -->
<!--   <div class="donation_goal">    -->
<!--   <div class="d_goal_title">DONATION GOAL</div>    -->
<!--   <div class="d_goal_content">    -->
          <!--Put SCALE here-->
<!--   </div>    -->
<!--   </div>    -->
<!--Donation GOAL-END-->

<div class="shout_box">
  {shoutbox}
   <div class="content">	
   <ul>
   </ul>
   </div>
</div>



</div>
























