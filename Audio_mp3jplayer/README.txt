README for Audio_MP3jplayer.module
2013-11-24

What it's for:
I made this to support pages representing CDs, LPs, etc.
For practical reasons the audio files used are mp3 files.
You drag and drop a group of mp3 files onto the 'audio' field this module creates.
It extracts data and images from id3 info in the mp3 files and puts them away for later use.
The jPlayer player used appears to work well on modern browsers.

What it does:
Creates an 'audio' field (like Audio_MP3 module by Christoph Thelen).
Extracts track info and images from ID3 tags.
Populates player's playlist.

Status:
Working well for me but as of this writing I've only used it in demos.
It's under-developed in terms of error handling and confguration options.
There are lots of diagnostic print statements you can turn on to get a better idea what's going on. Rip them out if the clutter offends you.

The ID3 parser:
It's available at http://getid3.sourceforge.net or http://www.getid3.org
Download ID3 files to /sites/templates/includes/getid3/

The player:
It's available at http://www.jplayer.org
Download jQuery jPlayer files to /sites/templates/jPlayer/
Look at the jPlayer examples and cook up some CSS to suit your design.
An example of player markup is provided to show how class names relate to user interface.

Dependency:
My FieldtypeFilePlusData module must be installed.

1. Installing the module will create a new 'audio' field. 
	Add a field called 'audio' of type 'audio' to your Template in Setup > Templates.
	Add a field caled 'images' of type 'image' to your Template in Setup > Templates. 
	
2. Your template file can then get track information using the id3 method.
	The first time you do this after dragging and dropping your mp3 files to the audio field...
		Extracted info such as track number and title are stored in the audio field's 'plus' subfield.
		Extracted images get added to the page's images field.
	The track information is returned as an array of arrays, one per track, organized by track number (usually 1,2,3...).
	
3. Your template file must add player markup. 

4. There's CSS and Javascript to deal with too. This sort of thing.

	$config->styles->add($config->urls->templates.'styles/playlist.css');
	$config->scripts->add($config->urls->templates.'jPlayer/jquery.jplayer.min.js');
	$config->scripts->add($config->urls->templates.'jPlayer/add-on/jplayer.playlist.min.js');
	
5. The module will make code to add to the javascript "ready" function. 
This Javascript populates the player's playlist with track information.
That's going to be different for each CD page so I keep it separate from my main JS file.
My template page wraps it up as function in script tags, like this:
	
	$js_readyCode = $audio->js_readyCode($trackInfo,$page);
	
	if (!empty($js_readyCode)) print '<script type="text/javascript">
	function extendedReadyFn(){
		'.$js_readyCode.'
	}
	</script>
	';
	
6. In my site's main Javascript file there's a ready function.
Along with any other code I might need there, I call extendedReadyFn() if it exists.
	
	$( document ).ready(function() {	

		if (typeof(extendedReadyFn) != 'undefined'){
			extendedReadyFn();	
		}

	}	

Additional info:
If you uncomment a line near the end of the id3 function (just after ksort) you can access an array of unique images recovered from the id3 this way: $audio->trackInfo['images'];
On an old version of XAMPP the volume control would break if Apache's mod_deflate.so was enabled.

2013-11-26 Updates
One tiny change to Audio_MP3jplayer.module so it works with both newly created data and some legacy data I have
Added a folder of things to help you setup a demo.

2013-12-01 Updates
1. Tested with jPlayer 2.5, was using 2.3 earlier. No change to the module file.
2. Removed things we were not using from player's css
3. Broke out some of the player's css to two files for conditional loading 
	In the demo template file we now conditionally load one of two small css files to either show or not show audio controls
	$config->styles->add($config->urls->templates.'styles/playlist.css');
	if (stripos($_SERVER['HTTP_USER_AGENT'], ' OS 7_0') === false) {
	    $config->styles->add($config->urls->templates.'styles/playlistVolume.css');	
	}else{
	    $config->styles->add($config->urls->templates.'styles/playlistNoVolume.css');	
	}	
Background for #3:
	Problems with volume control when using current iOS 7 devices - Volume control not shown and not working.
	Apple hath decreed: "On iOS devices, the audio level is always under the user’s physical control. The volume property is not settable in JavaScript."

	See the Developer Guide -> jPlayer Constructor -> Parameters -> noVolume (about 40 percent of the way down the page).
	http://www.jplayer.org/latest/developer-guide/#jPlayer-constructor
	By default volume controls are hidden for iOS devices but you can pass settings for this to the constructor.
	One can modify the defaults shown below replacing for example /ipad/ with something unlikely (e.g. /IGNOREipad/)
	This then gets added to the constructor call in the module's js_readyCode() function
	The default object is:
	    noVolume: {
	    ipad: /ipad/,
	    iphone: /iphone/,
	    ipod: /ipod/,
	    android_pad: /android(?!.*?mobile)/,
	    android_phone: /android.*?mobile/,
	    blackberry: /blackberry/,
	    windows_ce: /windows ce/,
	    iemobile: /iemobile/,
	    webos: /webos/,
	    playbook: /playbook/
	    }
	Doing that will allow the volume controls to be displayed, but they won't work! 
		
	From https://developer.apple.com/library/safari/documentation/AudioVideo/Conceptual/Using_HTML5_Audio_Video/Device-SpecificConsiderations/Device-SpecificConsiderations.html
		Volume Control in JavaScript

		On the desktop, you can set and read the volume property of an <audio> or <video> element. This allows you to set the element’s audio volume relative to the computer’s current volume setting. A value of 1 plays sound at the normal level. A value of 0 silences the audio. Values between 0 and 1 attenuate the audio.

		This volume adjustment can be useful, because it allows the user to mute a game, for example, while still listening to music on the computer.

		On iOS devices, the audio level is always under the user’s physical control. The volume property is not settable in JavaScript. Reading the volume property always returns 1.
	
	Also:
		https://developer.apple.com/library/safari/documentation/AudioVideo/Conceptual/Using_HTML5_Audio_Video/Device-SpecificConsiderations/Device-SpecificConsiderations.html
		https://developer.apple.com/library/ios/documentation/MediaPlayer/Reference/MPMusicPlayerController_ClassReference/DeprecationAppendix/AppendixADeprecatedAPI.html#//apple_ref/occ/instp/MPMusicPlayerController/volume

	There is a way to control volume from an iOS app, but not from a web page. 
		http://ios-blog.co.uk/tutorials/controlling-system-output-volume-with-the-mpvolumeview-class-part-one/

