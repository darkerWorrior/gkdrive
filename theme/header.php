<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
      <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
      <title><?=$this->getTitle()?></title>
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
      <meta name="msapplication-TileColor" content="#206bc4"/>
      <meta name="theme-color" content="#206bc4"/>
      <meta name="robots" content="noindex,nofollow,noarchive"/>
      <link rel="icon" href="<?=PROOT?>/uploads/<?=$this->config['favicon']?>" type="image/x-icon"/>
      <link rel="shortcut icon" href="<?=PROOT?>/uploads/<?=$this->config['favicon']?>" type="image/x-icon"/>
      <!-- Libs CSS -->
      <link href="<?=getThemeURI()?>/assets/libs/selectize/assets/css/selectize.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/flatpickr/assets/flatpickr.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/nouislider/assetsribute/nouislider.min.css" rel="stylesheet"/>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css"/>
      <!-- Tabler Core -->
      <link href="<?=getThemeURI()?>/assets/css/tabler.min.css" rel="stylesheet"/>
      <!-- Tabler Plugins -->
      <link href="<?=getThemeURI()?>/assets/css/tabler-flags.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/tabler-payments.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/tabler-buttons.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/demo.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/custom.css?v=2.2" rel="stylesheet"/>
      <style>
         body {
         display: none;
         }
      </style>
   </head>
   <body class="antialiased ">
      <div class="page">
      <header class="navbar navbar-expand-md navbar-dark">
         <div class="container-xl">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
            </button>
            <a href="./" class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pr-0 pr-md-3">
            <img src="<?=PROOT?>/uploads/<?=$this->config['logo']?>" height="40" alt="gdplyr" class="navbar-brand-image">
            </a>
            <div class="navbar-nav flex-row order-md-last">
               <div class="nav-item dropdown">
                  <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-toggle="dropdown">
                     <span class="avatar" style="background-image: url(<?=PROOT?>/uploads/<?=$this->userImg?>)"></span>
                     <div class="d-none d-xl-block pl-2">
                        <div><?=$this->getUsername();?></div>
                        <div class="mt-1 small text-muted">Administrator</div>
                     </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                     <a class="dropdown-item" href="<?=PROOT?>/profile">
                        <svg class="icon icon-md" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                           <path fill-rule="evenodd" d="M15 16s1 0 1-1-1-4-6-4-6 3-6 4 1 1 1 1h10zm-9.995-.944v-.002zM5.022 15h9.956a.274.274 0 00.014-.002l.008-.002c-.001-.246-.154-.986-.832-1.664C13.516 12.68 12.289 12 10 12c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664a1.05 1.05 0 00.022.004zm9.974.056v-.002zM10 9a2 2 0 100-4 2 2 0 000 4zm3-2a3 3 0 11-6 0 3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        &nbsp;
                        Profile
                     </a>
                     <div class="dropdown-divider"></div>
                     <a class="dropdown-item" href="<?=PROOT?>/logout">
                        <svg class="icon icon-md" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                           <path fill-rule="evenodd" d="M6.354 13.354a.5.5 0 000-.708L3.707 10l2.647-2.646a.5.5 0 10-.708-.708l-3 3a.5.5 0 000 .708l3 3a.5.5 0 00.708 0z" clip-rule="evenodd"></path>
                           <path fill-rule="evenodd" d="M13.5 10a.5.5 0 00-.5-.5H4a.5.5 0 000 1h9a.5.5 0 00.5-.5z" clip-rule="evenodd"></path>
                           <path fill-rule="evenodd" d="M16 15.5a1.5 1.5 0 001.5-1.5V6A1.5 1.5 0 0016 4.5H9A1.5 1.5 0 007.5 6v1.5a.5.5 0 001 0V6a.5.5 0 01.5-.5h7a.5.5 0 01.5.5v8a.5.5 0 01-.5.5H9a.5.5 0 01-.5-.5v-1.5a.5.5 0 00-1 0V14A1.5 1.5 0 009 15.5h7z" clip-rule="evenodd"></path>
                        </svg>
                        &nbsp;
                        Logout
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </header>
      <div class="navbar-expand-md">
         <div class="navbar collapse navbar-collapse navbar-light" id="navbar-menu">
            <div class="container-xl">
               <ul class="navbar-nav">
                  <li class="nav-item <?=$this->getAT('dashboard')?>"  >
                     <a class="nav-link" href="<?=PROOT?>/dashboard"  >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"/>
                              <polyline points="5 12 3 12 12 3 21 12 19 12" />
                              <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                              <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Dashboard
                        </span>
                     </a>
                  </li>
                  <li class="nav-item <?=$this->getAT('links')?>">
                     <a class="nav-link" href="<?=PROOT?>/links/all" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                              <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Links
                        </span>
                     </a>
                  </li>
                  <li class="nav-item <?=$this->getAT('servers')?>">
                     <a class="nav-link" href="<?=PROOT?>/servers" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <line x1="3" y1="21" x2="21" y2="21"></line>
                              <line x1="9" y1="8" x2="10" y2="8"></line>
                              <line x1="9" y1="12" x2="10" y2="12"></line>
                              <line x1="9" y1="16" x2="10" y2="16"></line>
                              <line x1="14" y1="8" x2="15" y2="8"></line>
                              <line x1="14" y1="12" x2="15" y2="12"></line>
                              <line x1="14" y1="16" x2="15" y2="16"></line>
                              <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"></path>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Servers
                        </span>
                     </a>
                  </li>
                  <li class="nav-item <?=$this->getAT('bulk')?>">
                     <a class="nav-link" href="<?=PROOT?>/bulk" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                              <rect x="14" y="4" width="6" height="6" rx="1"></rect>
                              <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                              <rect x="14" y="14" width="6" height="6" rx="1"></rect>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Bulk Import
                        </span>
                     </a>
                  </li>
                  <li class="nav-item <?=$this->getAT('ads')?>">
                     <a class="nav-link" href="<?=PROOT?>/ads" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                              <path d="M7 15v-4a2 2 0 0 1 4 0v4"></path>
                              <line x1="7" y1="13" x2="11" y2="13"></line>
                              <path d="M17 9v6h-1.5a1.5 1.5 0 1 1 1.5 -1.5"></path>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Advertisement
                        </span>
                     </a>
                  </li>
                  <li class="nav-item dropdown <?=$this->getAT('settings')?>">
                     <a class="nav-link dropdown-toggle" href="#navbar-docs" data-toggle="dropdown" role="button" aria-expanded="false" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                              <circle cx="12" cy="12" r="3"></circle>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Settings
                        </span>
                     </a>
                     <ul class="dropdown-menu ">
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/settings/general" >
                           General
                           </a>
                        </li>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/settings/gauth" >
                           GDrive Auth
                           </a>
                        </li>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/settings/proxy" >
                           Proxy
                           </a>
                        </li>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/settings/backup" >
                           Backup
                           </a>
                        </li>
                     </ul>
                  </li>
               </ul>
               <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                  <form action="." method="get">
                     <div class="input-icon">
                        <span class="input-icon-addon">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"/>
                              <circle cx="10" cy="10" r="7" />
                              <line x1="21" y1="21" x2="15" y2="15" />
                           </svg>
                        </span>
                        <input type="text" class="form-control" placeholder="Search…">
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <div class="content">
      <div class="container-xl">