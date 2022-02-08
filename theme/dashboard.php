<!-- Page title -->
<div class="page-header">
   <div class="row align-items-center">
      <div class="col-auto">
         <ol class="breadcrumb" aria-label="breadcrumbs">
            <li class="breadcrumb-item">
               <a href="<?=PROOT?>/dashboard">Dashboard
               </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
               <a href="javascript:void(0)">Overview
               </a>
            </li>
         </ol>
      </div>
      <div class="col-auto ml-auto d-print-none">
         <a href="<?=PROOT?>/links/new" class="btn btn-primary ml-3 d-none d-sm-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
               <path stroke="none" d="M0 0h24v24H0z">
               </path>
               <line x1="12" y1="5" x2="12" y2="19">
               </line>
               <line x1="5" y1="12" x2="19" y2="12">
               </line>
            </svg>
            Create new link
         </a>
      </div>
   </div>
</div>
<!-- Content here -->
<div class=" row row-deck row-cards">
   <div class="col-sm-6 col-lg-3">
      <div class="card">
         <div class="card-body py-4 px-2 text-center">
            <div class="h1 m-0">
               <?=$data['totalLinks']?>
            </div>
            <div class="text-muted ">Total Links
            </div>
         </div>
      </div>
   </div>
   <div class="col-sm-6 col-lg-3">
      <div class="card">
         <div class="card-body py-4 px-2 text-center">
            <div class="h1 m-0">
               <?=$data['totalViews']?>
            </div>
            <div class="text-muted ">Total Views
            </div>
         </div>
      </div>
   </div>
   <div class="col-sm-6 col-lg-3">
      <div class="card">
         <div class="card-body py-4 px-2 text-center">
            <div class="h1 m-0">
               <?=$data['totalServers']?>
            </div>
            <div class="text-muted ">Total Servers
            </div>
         </div>
      </div>
   </div>
   <div class="col-sm-6 col-lg-3">
      <div class="card">
         <div class="card-body py-4 px-2 text-center">
            <div class="h1 m-0 text-danger">
               <?=$data['rft']['broken']?>
            </div>
            <div class="text-muted ">Broken Links
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row row-cols-1 row-cols-md-5">
   <div class="col">
      <div class="card card-sm">
         <div class="card-body d-flex align-items-center">
            <span class="stamp mr-3">
            <img src="<?=Helper::getIcon('GDrive')?>" height="25" alt="gdrive-icon">
            </span>
            <div class="mr-3 lh-sm">
               <div class="strong">
                  Google Drive
               </div>
               <div class="text-muted">
                  <?=$data['dft']['GDrive']?> links
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card card-sm">
         <div class="card-body d-flex align-items-center">
            <span class="stamp mr-3">
            <img src="<?=Helper::getIcon('GPhoto')?>" height="28" alt="gphoto-icon">
            </span>
            <div class="mr-3 lh-sm">
               <div class="strong">
                  Google Photos
               </div>
               <div class="text-muted">
                  <?=$data['dft']['GPhoto']?> links
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card card-sm">
         <div class="card-body d-flex align-items-center">
            <span class="stamp mr-3">
            <img src="<?=Helper::getIcon('OneDrive')?>" height="25" alt="onedrive-icon">
            </span>
            <div class="mr-3 lh-sm">
               <div class="strong">
                  One Drive
               </div>
               <div class="text-muted">
                  <?=$data['dft']['OneDrive']?> links
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card card-sm">
         <div class="card-body d-flex align-items-center">
            <span class="stamp mr-3">
            <img src="<?=Helper::getIcon('Yandex')?>" height="25" alt="yandex-icon">
            </span>
            <div class="mr-3 lh-sm">
               <div class="strong">
                  Yandex
               </div>
               <div class="text-muted">
                  <?=$data['dft']['Yandex']?> links
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="card card-sm">
         <div class="card-body d-flex align-items-center">
            <span class="stamp mr-3">
            <img src="<?=Helper::getIcon('Direct')?>" height="22" alt="direct-icon">
            </span>
            <div class="mr-3 lh-sm">
               <div class="strong">
                  Direct
               </div>
               <div class="text-muted">
                  <?=$data['dft']['Direct']?> links
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row ">
   <div class="col-md-6 col-lg-8">
      <div class="card">
         <div class="card-header">
            <h4 class="card-title">Active Links
            </h4>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter">
               <thead>
                  <tr>
                     <th>Title
                     </th>
                     <th>Source
                     </th>
                     <th>Views
                     </th>
                     <th>Created at
                     </th>
                     <th>
                     </th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($data['maLinks'] as $link): ?>
                  <tr>
                     <td>
                        <a href="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" target="_blank" class="text-reset">
                        <?=$link['title']?>
                        </a>
                     </td>
                     <td> 
                        <img src="<?=Helper::getIcon($link['type'])?>" height="20" alt="source-icon"> 
                     </td>
                     <td>
                        <?=number_format($link['views'])?>
                     </td>
                     <td class="text-muted">
                        <?=Helper::formatDT($link['created_at'],false)?>
                     </td>
                     <td>
                        <div class="btn-list flex-nowrap     " style="justify-content: center;">
                           <a href="javascript:void(0)" class="text-dark copy-plyr-link" data-url="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" data-toggle="tooltip" data-placement="top" title="copy player link">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" style="font-size: 1.3rem;" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z">
                                 </path>
                                 <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5">
                                 </path>
                                 <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5">
                                 </path>
                              </svg>
                           </a>
                           <a href="javascript:void(0)" class="text-secondary ml-2 copy-embed-code" data-url="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" data-toggle="tooltip" data-placement="top" title="copy embed code">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" style="font-size: 1.3rem;" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z">
                                 </path>
                                 <polyline points="7 8 3 12 7 16">
                                 </polyline>
                                 <polyline points="17 8 21 12 17 16">
                                 </polyline>
                                 <line x1="14" y1="4" x2="10" y2="20">
                                 </line>
                              </svg>
                           </a>
                           <a href="<?=PROOT?>/links/edit/<?=$link['id']?>" class="text-info ml-2" data-toggle="tooltip" data-placement="top" title="edit">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" style="font-size: 1.3rem;" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z">
                                 </path>
                                 <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3">
                                 </path>
                                 <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3">
                                 </path>
                                 <line x1="16" y1="5" x2="19" y2="8">
                                 </line>
                              </svg>
                           </a>
                        </div>
                     </td>
                  </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
      <div class="card">
         <div class="card-header">
            <h4 class="card-title">Recently Links
            </h4>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter">
               <thead>
                  <tr>
                     <th>Title
                     </th>
                     <th>Source
                     </th>
                     <th>Views
                     </th>
                     <th>Created at
                     </th>
                     <th>
                     </th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($data['raLinks'] as $link): ?>
                  <tr>
                     <td>
                        <a href="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" target="_blank" class="text-reset">
                        <?=$link['title']?>
                        </a>
                     </td>
                     <td> 
                        <img src="<?=Helper::getIcon($link['type'])?>" height="20" alt="source-icon"> 
                     </td>
                     <td>
                        <?=number_format($link['views'])?>
                     </td>
                     <td class="text-muted">
                        <?=Helper::formatDT($link['created_at'],false)?>
                     </td>
                     <td>
                        <div class="btn-list flex-nowrap     " style="justify-content: center;">
                           <a href="javascript:void(0)" class="text-dark copy-plyr-link" data-url="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" data-toggle="tooltip" data-placement="top" title="copy player link">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" style="font-size: 1.3rem;" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z">
                                 </path>
                                 <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5">
                                 </path>
                                 <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5">
                                 </path>
                              </svg>
                           </a>
                           <a href="javascript:void(0)" class="text-secondary ml-2 copy-embed-code" data-url="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" data-toggle="tooltip" data-placement="top" title="copy embed code">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" style="font-size: 1.3rem;" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z">
                                 </path>
                                 <polyline points="7 8 3 12 7 16">
                                 </polyline>
                                 <polyline points="17 8 21 12 17 16">
                                 </polyline>
                                 <line x1="14" y1="4" x2="10" y2="20">
                                 </line>
                              </svg>
                           </a>
                           <a href="<?=PROOT?>/links/edit/<?=$link['id']?>" class="text-info ml-2" data-toggle="tooltip" data-placement="top" title="edit">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" style="font-size: 1.3rem;" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z">
                                 </path>
                                 <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3">
                                 </path>
                                 <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3">
                                 </path>
                                 <line x1="16" y1="5" x2="19" y2="8">
                                 </line>
                              </svg>
                           </a>
                        </div>
                     </td>
                  </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <div class="col-md-6 col-lg-4">
      <div class="card">
         <div class="card-body">
            <h3 class="card-title">Links Status
            </h3>
            <div id="links-status" >
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <h3 class="card-title">Servers Usage
            </h3>
            <div id="servers-usage" >
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <h3 class="card-title">Cache
            </h3>
            <div class="text-center">
               <div class="h1 m-0 text-warning" id="cache-size">
                  <?=Helper::formatSize($data['drSize'])?>
               </div>
               <div class="text-muted mb-2">Total Cache Size
               </div>
               <button type="button" class="btn btn-primary btn-sm   mb-3" id="clear-cache" 
                  <?=$data['drSize']==0?'disabled':''?> >clear cache
               </button>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div style="
               display: flex;
               justify-content: space-between;
               ">
               <h3 class="card-title">Proxy 
               </h3>
               <a href="<?=PROOT?>/settings/proxy">Go to proxy settings
               </a>
            </div>
            <div class="row">
               <div class="text-center col-6">
                  <div class="h1 m-0 text-success">
                     <?=$data['proxy'][0]?>
                  </div>
                  <div class="text-muted mb-2">Active Proxies
                  </div>
               </div>
               <div class="text-center col-6">
                  <div class="h1 m-0 text-danger" >
                     <?=$data['proxy'][1]?>
                  </div>
                  <div class="text-muted mb-2">Broken Proxies
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div style="
               display: flex;
               justify-content: space-between;
               ">
               <h3 class="card-title">GDrive Auths 
               </h3>
               <a href="<?=PROOT?>/settings/gauth">Go to gauths settings
               </a>
            </div>
            <div class="row">
               <div class="text-center col-6">
                  <div class="h1 m-0 text-success" >
                     <?=$data['gauths']['active']?>
                  </div>
                  <div class="text-muted mb-2">Active gauths
                  </div>
               </div>
               <div class="text-center col-6">
                  <div class="h1 m-0 text-danger" >
                     <?=$data['gauths']['broken']?>
                  </div>
                  <div class="text-muted mb-2">Broken gauths
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>