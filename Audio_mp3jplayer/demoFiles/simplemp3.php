<?php	//simple template file for mp3 demo

//special for cd pages
$audMod = $modules->get('Audio_MP3jplayer');	

$a = $audMod->id3($page);	//Audio_MP3jplayer caches organized id3 data in audio field's FieldtypeFilePlusData plus subfield
$trackInfo = $a['tracks'];

//This is JS code to put inside the jQuery ready function
$js_readyCode = $audMod->js_readyCode($trackInfo, $page);
if(!empty($js_readyCode)){
	$extendedReadyFn = 'function extendedReadyFn(){
'.$js_readyCode.'
}
';
}

//special for cd pages
//user agent: "AppleCoreMedia/1.0.0.11B554a (iPad; U; CPU OS 7_0_4 like Mac OS X; en_us)"
//playlistB.css hides audio controls. Use for iOS7, which blocks JS audio controls.
$config->styles->add($config->urls->templates.'styles/playlist.css');
if (stripos($_SERVER['HTTP_USER_AGENT'], ' OS 7_0') === false) {
    $config->styles->add($config->urls->templates.'styles/playlistVolume.css');	
}else{
    $config->styles->add($config->urls->templates.'styles/playlistNoVolume.css');	
}
//$config->styles->add($config->urls->templates.'jPlayer/blue.monday/jplayer.blue.monday.css');
$config->scripts->add($config->urls->templates.'jPlayer/jquery.jplayer.min.js');
$config->scripts->add($config->urls->templates.'jPlayer/add-on/jplayer.playlist.min.js');
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $page->get("headline|title"); ?></title>
	<meta name="generator" content="ProcessWire <?php echo $config->version; ?>" />

    <!-- Don't forget the viewport meta tag for responsive sites! -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" />	<!-- Icons -->

<?php foreach ($config->styles->unique() as $style) echo "	<link rel='stylesheet' type='text/css' href='$style' />".PHP_EOL;?>

</head>
<body>
<h1>Audio_MP3jplayer Module</h1>
<?php echo $page->body.PHP_EOL; ?>

<?php if(!empty($trackInfo)){ ?>
<!-- [PLAYER -->
<div id="jquery_jplayer_1" class="jp-jplayer"></div>

<div id="jp_container_1" class="jp-audio">
	<div class="jp-type-playlist">
		<div class="jp-gui jp-interface">
			<ul class="jp-controls">
				<li><a href="javascript:;" class="jp-play" tabindex="1"><i class="fa fa-play"></i></a></li>
				<li><a href="javascript:;" class="jp-pause" tabindex="1"><i class="fa fa-pause"></i></a></li>
				<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li -->
				<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute"><i class="fa fa-volume-up"></i></a></li>
				<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute"><i class="fa fa-volume-off"></i></a></li>
				<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
			</ul>

			<div class="jp-progress">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>
				</div>
			</div>

			<div class="jp-volume-bar">
				<div class="jp-volume-bar-value"></div>
			</div>
			<div class="jp-time-holder">
				<div class="jp-current-time"></div>
				<div class="jp-duration"></div>
			</div>
		</div>
		<div class="jp-playlist">
			<ul>
				<li></li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
</div>
<!-- PLAYER] -->
<div>
<br />Here are the non-redundant images found in the mp3 files. Usually all tracks from one album have the same image.
<?php foreach($page->images as $image) echo '<br /><img src="'.$image->url.'" alt="'.$image->description.'"/>'.PHP_EOL;?>		
</div>

<?php 
	echo'<br />Here is the track data:<pre>'.PHP_EOL;var_dump($trackInfo);echo'</pre>';
}else{ ?>
<p>There is no track information yet. </p>
<?php 
	$ct = $page->audio->count();
	echo '<p>$page->audio->count() = '.$ct.'<br />'; 
	echo ($ct == 0) ? 'That probably means no mp3 files have been added to the page yet.' : 'That indicates a problem.';
}?>


<div class="bottom">
	Audio_MP3jplayer &nbsp;by <a href="http://www.sb3d.com">SB3D</a><br/>
	Powered&nbsp;by&nbsp;<a href='http://processwire.com'>ProcessWire</a> Open&nbsp;Source&nbsp;CMS/CMF
</div>
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<?php	if(!empty($extendedReadyFn))print '<script type="text/javascript">
'.$extendedReadyFn.'
</script>
';
?>	
<?php 
foreach ($config->scripts->unique() as $script) echo '	<script type="text/javascript" src="'.$script.'"></script>'.PHP_EOL;
?>	
<script type="text/javascript">
//<![CDATA[
	
$( document ).ready(function() {	
	if (typeof(extendedReadyFn) != 'undefined'){
		extendedReadyFn();
	}
	$("div.jp-type-playlist jp-no-solution").fadeIn('slow');
});

//]]>	
</script>
</body>
</html>
