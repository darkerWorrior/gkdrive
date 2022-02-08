<?php defined("APP") or die(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <title><?=$data['title']?></title>
      <meta charset="utf-8">
      <meta name="author" content="CodySeller" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link rel="icon" href="<?=PROOT?>/uploads/<?=$this->config['favicon']?>" type="image/x-icon"/>
      <link rel="shortcut icon" href="<?=PROOT?>/uploads/<?=$this->config['favicon']?>" type="image/x-icon"/>
      <script src="<?=$this->getJWLicense()?>"></script>
      <?php if($this->videoType == 'GPhoto'): ?>
      <meta name="referrer" content="never" />
      <meta name="referrer" content="no-referrer" />
      <link rel='dns-prefetch' href='//lh3.googleusercontent.com' />
      <?php endif; ?>
      <link href="<?=getThemeURI()?>/assets/css/player.css?v=<?=time()?>" rel="stylesheet"/>

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


      <div id="jw_player"></div>



      <?php
         $script = 'const playerInstance = jwplayer("jw_player").setup({
             playlist: [{
                 title: "'.$data['title'].'",
                 sources: '.$data['sources'].',
                 "image": "'.$data['poster'].'",
                 "tracks": '.$data['subs'].'
             }],
             
           "logo": {
              "file": "'.$logo.'",
              "link": "#",
              "hide": "false",
              "position": "top-right"
           },
           "advertising": {
              "client": "vast",
              "schedule": ['.$ads.']
            }
           });
           ';
         
         
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