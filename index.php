<?php
function humanTiming ($time)
{
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	$time = strtotime($time);
    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'año',
        2592000 => 'mes',
        604800 => 'semana',
        86400 => 'día',
        3600 => 'hora',
        60 => 'minuto',
        1 => 'segundo'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}
function cleanInput($input){

	$input=htmlentities($input);
	$input=nl2br($input);

	return $input;
}
if(isset($_GET['u'])){

$userProfile= @file_get_contents("http://api.taringa.net/user/nick/view/".$_GET['u']);


$uProfile=json_decode($userProfile,true);
if(isset($uProfile['code'])){
	die('<h1>El usuario no existe.</h1>');
}

$userStats= file_get_contents("http://api.taringa.net/user/stats/view/".$uProfile['id']);
$uStats= json_decode($userStats,true);

$userShouts= file_get_contents("http://api.taringa.net/shout/user/view/".$uProfile['id']);
$uShouts=json_decode($userShouts,true);

?>
<html>
<head>
	<title>Perfil de @<?php echo $uProfile['nick']; ?> en Taringa!</title>
	<meta charset="UTF-8">
	<meta name="robots" content="index,nofollow">
	<meta property="og:site_name" content="Taringa! Perfiles">
	<meta property="og:type" content="website">
	<meta property="og:url" content="http://perfiles.klep.xyz/<?php echo $uProfile['nick']; ?>">
	<meta property="og:description" content="<?php echo $uProfile['range']['name']; ?> - <?php echo $uProfile['message']; ?>">
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<style type="text/css">
		
	</style>
	<link rel="stylesheet" type="text/css" href="style.css?0.1">
	<link rel="shortcut icon" href="<?php echo $uProfile['avatar']['tiny']; ?>">
</head>
<body>
	<header>
	  <img src="http://o1.t26.net/img/v6/media/img/logo-taringa.png" alt="" class="logo" /><span class="desc">Perfiles</span>
	   <div class="searchbox"><form action="/index.php" method="get"><input type="text" name="u" placeholder="Buscar usuario"/></form></div>
	</header>
	<main>
	<?php
	if($uProfile['profile_active']!=true){
		?>
	  <div class="descr">El usuario desactivó la cuenta.</div>
	  <?php
	}
	if($uProfile['status']!==10){
		?>
	  <div class="descr banned">El usuario se encuentra suspendido.</div>
	  <?php
	}
	?>

	  <div class="overcard">
	    <img src="<?php echo $uProfile['avatar']['big']; ?>" alt="" class="avatar" />
	    <div class="user-info">
	      <ul class="info-general">
	      	<li class="username"><?php echo $uProfile['name']; ?> <?php echo $uProfile['last_name']; ?></li>
	        <li class="nick">@<a href="<?php echo $uProfile['canonical']; ?>" target="_blank"><?php echo $uProfile['nick']; ?></a></li>
	         <!--<li class="country">Suiza</li>-->
	        <li class="rank"><b><?php echo $uProfile['range']['name']; ?></b></li>       
	        <li class="bio"><?php echo $uProfile['message']; ?></li>
	        <li class="web"><a href="<?php echo $uProfile['site']; ?>" target="_blank"><?php echo $uProfile['site']; ?></a></li>
	        <li></li>
	      </ul>
	      
	    </div>
	    <ul class="stats">
	        <li><span><?php echo $uStats['points']; ?></span><span>Puntos</span></li>
	        <li><span><?php echo $uStats['posts']; ?></span><span>Posts</span></li>
	        <li><span><?php echo $uStats['comments']; ?></span><span>Comentarios</span></li>
	        <li><span><?php echo $uStats['threads']; ?></span><span>Temas</span></li>
	        <li><span><?php echo $uStats['followers']; ?></span><span>Seguidores</span></li>
	      <li><span><?php echo $uStats['followings']; ?></span><span>Siguiendo</span></li>
	      <li><span><?php echo $uStats['shouts']; ?></span><span>Shouts</span></li>
	      </ul>
	  </div>
	  <div class="last-activity">
	  	

	    <div class="feed-container">
	    	<?php 
	  	foreach ($uShouts as $uShout){
		?>
	      <div class="feed-border clearfix">
	   
	    <div class="feed-body clearfix">
	      <div class="feed-avatar">
	        <img src="<?php echo $uShout['owner']['avatar']['medium']; ?>" alt="">
	      </div>
	      <div class="feed-content">
	        <div class="username">@<a href="<?php echo $uShout['owner']['canonical']; ?>"><?php echo $uShout['owner']['nick']; ?></a></div>
	        <p><?php echo cleanInput($uShout['body']); ?></p>
	        <?php
	        if($uShout['attachment']!=null){
	        	if($uShout['attachment']['type'] == "video"){
	        		parse_str( parse_url( $uShout['attachment']['url'], PHP_URL_QUERY ), $varss );
	        		?>
					<iframe style="width:100%" height="390" src="https://www.youtube.com/embed/<?php echo $varss['v'];?>" frameborder="0" allowfullscreen></iframe>
					<?php

	        	}
	        	elseif ($uShout['attachment']['type'] == "image") {
	        		?>
	        		<a target="_blank" href="https://archive.anpep.xyz/id/<?php echo $uShout['id'];?>"><img style="max-width:100%" src="<?php echo $uShout['attachment']['url'];?>"></a>
	        		<?php

	        	}
	        	elseif($uShout['attachment']['type'] == "link"){
					?>
	        		<div class="attach-link"><a target="_blank" href="<?php echo $uShout['attachment']['url']; ?>"><?php echo $uShout['attachment']['url']; ?></a></div>
	        		<?php	        		
	        	}
	        }
	        ?>
	      </div>
	    </div>
	    <div class="feed-footer clearfix">
	      <div class="footer-left">
	        <span class="footer-time"><a target="_blank" href="https://archive.anpep.xyz/id/<?php echo $uShout['id'];?>" target="_blank"><?php echo "Hace ".humanTiming($uShout['created']);?></a></span>
	      </div>
	      <div class="footer-right">
	        <span class="comment"><a target="_blank" href="https://archive.anpep.xyz/id/<?php echo $uShout['id'];?>" title="Comentarios"><i class="fa fa-comments"></i> <?php echo $uShout['replies'];?></a></span>
	        <span class="divisor"></span>
	        <span class="favs"><a target="_blank" href="https://archive.anpep.xyz/id/<?php echo $uShout['id'];?>" title="Favoritos"><i class="fa fa-star-o"></i> <?php echo $uShout['favorites'];?></a></span>
	        <span class="divisor"></span>
	         <span class="plusOne"><a target="_blank" href="https://archive.anpep.xyz/id/<?php echo $uShout['id'];?>" title="Me gusta"><i class="fa fa-heart"></i> <?php echo $uShout['likes'];?></a></span>
	        <span class="divisor"></span>
	        <span class="share"><a target="_blank" href="https://archive.anpep.xyz/id/<?php echo $uShout['id'];?>" title="Reshouts"><i class="fa fa-retweet"></i> <?php echo $uShout['forwards'];?></a></span>
	      </div>     
	      </div>
	  </div>
	  <?php
		}
	  ?>
	    </div>
	  </div>
	</main>
	<footer>
	  <ul>
	    <li>Fabi hizo esto con mucho ♥</li>
	  </ul>
	</footer>
	<div class="scrollup"></div>
	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<script type="text/javascript">
		$(document).on('scroll', function(e){
		  /* apears to 30% of total page ;) */
		  if(($(document).scrollTop() * 100 / $(document).height()) > 20){
		    $(".scrollup").show();
		  }
		  else{
		    $(".scrollup").hide();
		  }
		});
		$(".scrollup").click(function(){
			var body = $("html, body");
			body.animate({scrollTop:0}, '500', 'swing');
		});
	</script>
</body>
</html>

<?php 
}
else{
header("Location: Naoko-");
}
?>
