<!doctype html>
<!--
   * Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
   * @version 1.0.0-alpha.5
   * @link https://github.com/tabler/tabler
   * Copyright 2018-2019 The Tabler Authors
   * Copyright 2018-2019 codecalm.net Paweł Kuna
   * Licensed under MIT (https://tabler.io/license)
   -->
<html lang="en">
   <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
      <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
      <title>Admin Login - GDplyr</title>
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
      <meta name="msapplication-TileColor" content="#206bc4"/>
      <meta name="theme-color" content="#206bc4"/>
      <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
      <meta name="apple-mobile-web-app-capable" content="yes"/>
      <meta name="mobile-web-app-capable" content="yes"/>
      <meta name="HandheldFriendly" content="True"/>
      <meta name="MobileOptimized" content="320"/>
      <meta name="robots" content="noindex,nofollow,noarchive"/>
      <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
      <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon"/>
      <!-- Tabler Core -->
      <link href="<?=getThemeURI()?>/assets/css/tabler.min.css" rel="stylesheet"/>
      <!-- Tabler Plugins -->
      <link href="<?=getThemeURI()?>/assets/css/tabler-flags.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/tabler-payments.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/tabler-buttons.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/demo.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/custom.css?v=<?=time()?>" rel="stylesheet"/>
      <style>
         body {
         display: none;
         }
      </style>
   </head>
   <body class="antialiased border-top-wide border-primary d-flex flex-column">
      <div class="flex-fill d-flex flex-column justify-content-center">
         <div class="container-tight py-6">
            <div class="text-center mb-4">
               <img src="./static/logo.svg" height="36" alt="">
            </div>
            <form class="card card-md" action="<?=$_SERVER['REQUEST_URI']?>" method="post">
               <div class="card-header bg-primary text-light">
                  <h3 class="mb-0 d-block text-center w-100">Admin Login</h3>
               </div>
               <div class="card-body">
                  <div class="logo text-center mb-3">
                     <a href="<?=PROOT?>/login"><img src="<?=PROOT?>/uploads/<?=$this->config['logo']?>" height="50" alt="logo"></a>
                  </div>
                  <?php $this->displayAlerts(); ?>
                  <div class="mb-3"> 
                     <input type="text" class="form-control" name="username" placeholder="Enter username" autocomplete="off" required>
                  </div>
                  <div class="mb-2">
                     <div class="input-group input-group-flat">
                        <input type="password" class="form-control" name="password"  placeholder="Enter password" required>
                        <span class="input-group-text">
                           <a href="#" class="link-secondary" title="Show password" data-toggle="tooltip">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"/>
                                 <circle cx="12" cy="12" r="2" />
                                 <path d="M2 12l1.5 2a11 11 0 0 0 17 0l1.5 -2" />
                                 <path d="M2 12l1.5 -2a11 11 0 0 1 17 0l1.5 2" />
                              </svg>
                           </a>
                        </span>
                     </div>
                  </div>
                  <div class="form-footer">
                     <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                  </div>
               </div>
            </form>
            <div class="text-center text-muted">
               Develop by  <a href="https://www.codester.com/codyseller" tabindex="-1">CodySeller</a> &nbsp;|&nbsp;&copy;2021
            </div>
         </div>
      </div>
      <script>
         document.body.style.display = "block"
      </script>
   </body>
</html>