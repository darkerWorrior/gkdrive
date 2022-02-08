<?php defined("APP") or die(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <title><?=$data['title']?></title>
      <meta name="author" content="CodySeller" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link rel="icon" href="<?=PROOT?>/uploads/<?=$this->config['favicon']?>" type="image/x-icon"/>
      <link rel="shortcut icon" href="<?=PROOT?>/uploads/<?=$this->config['favicon']?>" type="image/x-icon"/>
      <link rel="stylesheet" href="<?=getThemeURI()?>/assets/libs/plyr/style.min.css">
      <!-- Preload -->
      <link
         rel="preload"
         as="font"
         crossorigin
         type="font/woff2"
         href="https://cdn.plyr.io/static/fonts/gordita-medium.woff2"
         />
      <link
         rel="preload"
         as="font"
         crossorigin
         type="font/woff2"
         href="https://cdn.plyr.io/static/fonts/gordita-bold.woff2"
         />
      <script src="https://cdn.jsdelivr.net/npm/hls.js"></script>
      <link href="<?=getThemeURI()?>/assets/css/player.css?v=<?=time()?>" rel="stylesheet"/>
      <?php if($data['type'] == 'GPhoto' ): ?>
      <meta name="referrer" content="never" />
      <meta name="referrer" content="no-referrer" />
      <link rel='dns-prefetch' href='//lh3.googleusercontent.com' />
      <?php endif; ?>
      <style>

.menu-btn
{
   background-image:url(<?=getThemeURI()?>/static/icons/menu.png)
};

</style>
   </head>
   <body>
      <?php if(!empty($servers) && $this->config['showServers']): ?>
      <div id="server-list">
         <div class="menu-btn"  onclick="toggle_visibility()"></div>
         <ul id="servers" >
            <?=helper::getServerList($servers)?>
         </ul>
      </div>
      <?php endif; ?>
      <?php if($this->isAdblockEnabled()): ?>
      <div class="__000ab d-none" id="__000ab">
         <div class="__000ab-content">
            <div class="top">
               <img src="<?=Helper::getIcon('stop')?>" height="80" alt="">
               <h2 class="my-3">Adblock Detected</h2>
               <p class="mb-3 "><b>We have detected that you are using as adblock browser plugin <br> to disable advertising from loading on our website.</b> </p>
            </div>
            <div class="bottom">
               <p class="mb-3">
                  The revenue earned from advertising enables us to provide the quality content <br>
                  you're trying to reach on this website. In order to view this page, we request <br>
                  that you disable adblock in plugin settings
               </p>
               <a href="<?=$_SERVER['REQUEST_URI']?>" class="btn btn-block btn-danger">I Have Disabled Adblock for This Site</a>
            </div>
         </div>
      </div>
      <?php endif; ?>
      <?php if($this->isPreloaderEnabled()): ?>
      <div id="loader-wrapper">
         <div id="loader"></div>
      </div>
      <?php endif; ?>
      <video
         controls
         playsinline
         data-poster="<?=$data['poster']?>"
         id="player"
         crossorigin
         >
         <?php if($data['type'] == 'Yandex'): ?>
         <source  type="application/x-mpegURL"   src="<?=$data['sources']?>" >
         <?=$data['subs']?>
         <?php endif; ?>
      </video>
      </video>
      <script src="<?=getThemeURI()?>/assets/libs/plyr/script.min.js"></script>
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	        <script>
      const controls = [
             'play-large', // The large play button in the center
             'restart', // Restart playback
             'rewind', // Rewind by the seek time (default 10 seconds)
             'play', // Play/pause playback
             'fast-forward', // Fast forward by the seek time (default 10 seconds)
             'progress', // The progress bar and scrubber for playback and buffering
             'current-time', // The current time of playback
             'duration', // The full duration of the media
             'mute', // Toggle mute
             'volume', // Volume control
             'captions', // Toggle captions
             'settings', // Settings menu
             'pip', // Picture-in-picture (currently Safari only)
             'airplay', // Airplay (currently Safari only)
             'fullscreen' // Toggle fullscreen
         ];
      </script>
      <?php if($data['type'] == 'Yandex'): ?>
      <script>


         
             document.addEventListener("DOMContentLoaded", () => {
                 const video = document.querySelector("video");
                 const source = video.getElementsByTagName("source")[0].src;
                 
                 const defaultOptions = {controls};
         
                 if (Hls.isSupported()) {
                     const hls = new Hls();
                     hls.loadSource(source);
         
                     hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
         
                     const availableQualities = hls.levels.map((l) => l.height)
         
                     defaultOptions.quality = {
                         default: availableQualities[0],
                         options: availableQualities,
                         forced: true,        
                         onChange: (e) => updateQuality(e),
                     }
         
                     const player = new Plyr('#player', defaultOptions);
         
                    
         
                     });
                     hls.attachMedia(video);
                     window.hls = hls;
                 } else {
                     const player = new Plyr(video, defaultOptions);
                    
         
                 }
             });
         
             player.source = {
                             title: '<?=$data['title']?>',
                             poster: '<?=$data['poster']?>'
              };
         
         
         
             
      </script>
      <?php else: ?>
      <script>
      
         
         
         
                  const player = new Plyr('#player', { controls });
                  
         
                  player.source = {
                    type: 'video',
                    title: '<?=$data['title']?>',
                    sources: <?=$data['sources']?>,
                    poster: '<?=$data['poster']?>',
                    tracks: <?=$data['subs']?>
                    
                    
                  };
                
         
               
                  
               
      </script>
      <?php endif; ?>
      <?php
         $script = '';
        
         
         $preloader = 'setTimeout(function(){
          $("#loader").delay(1000).fadeOut("slow");
          $("#loader-wrapper").delay(1500).fadeOut("slow");
         }, 2000);';
         
         $adblockDetecter = ' var adBlockEnabled = false;
         var testAd = document.createElement("div");
         testAd.innerHTML = "&nbsp;";
         testAd.className = "adsbox";
         document.body.appendChild(testAd);
         if (testAd.offsetHeight === 0) {
           adBlockEnabled = true;
           testAd.remove();
           var __000ab = document.getElementById("__000ab");
           var jwplayer1 = document.getElementById("jw_player");
           __000ab.classList.remove("d-none");
           jwplayer1.remove();
         console.log("AdBlock Enabled?", adBlockEnabled)
         }';
         
         if($this->isPreloaderEnabled()) $script .= $preloader;
         if($this->isAdblockEnabled()) $script .= $adblockDetecter;
         
         $script = ' $(document).ready(function() {'.$script.'});';
         
         
         ?>
      <script>
         function updateQuality(newQuality) {
                 window.hls.levels.forEach((level, levelIndex) => {
                 if (level.height === newQuality) {
                     console.log("Found quality match with " + newQuality);
                     window.hls.currentLevel = levelIndex;
                 }
                 });
             }
         
         
      </script>
      <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
      <script>
         <?php
            error_reporting(E_ALL);
            $packer = new JSPacker($script, 'Normal', true, false, true);
            $packed_js = $packer->pack();
            echo $packed_js; ?>
            function toggle_visibility() 
           {
               var e = document.getElementById("servers");
               if ( e.style.display == "block" )
                   e.style.display ="none";
               else
                   e.style.display = "block";
           }
      </script>
       <?=$popads?>
   </body>
</html>