<!-- Page title -->
<div class="page-header">
   <div class="row align-items-center">
      <div class="col-auto">
         <ol class="breadcrumb" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="<?=PROOT?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?=PROOT?>/links/all">Links</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0)">All</a></li>
         </ol>
      </div>
   </div>
</div>
<!-- Content here -->
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-header" style="    justify-content: space-between;">
            <h3 class="card-title">All Links</h3>
            <div class="">
               <a href="#" class="btn btn-danger mr-2 delete-selecetd-items d-none">Delete selected links &nbsp;(<b>0</b>) </a>
               <a href="<?=PROOT?>/links/new" class="btn btn-primary">Add link</a>
            </div>
         </div>
         <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
               <thead>
                  <tr>
                     <th class="w-1 no-sort"><input class="form-check-input m-0 align-middle " id="select_all" type="checkbox"></th>
                     <th class="w-1">#id</th>
                     <th>Title</th>
                     <th>Source</th>
                     <th>Quality</th>
                     <th>Views</th>
                     <th>Status</th>
                     <th>Last updated at</th>
                     <th class="w-1"></th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($links as $link):
                     $isDrive = $link['type'] == 'GDrive' ? true : false;
                     $id= $link['id'];  ?>
                  <tr id="link-<?=$id?>" data-id="<?=$id?>">
                     <td class="no-sort "><input class="form-check-input m-0 align-middle delete-item" type="checkbox" aria-label="Select invoice"></td>
                     <td><?=$link['id']?></td>
                     <td > <a href="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" target="_blank" class="text-reset"><?=$link['title']?></a> </td>
                     <td> <img src="<?=Helper::getIcon($link['type'])?>" height="20" alt="source-icon"> </td>
                     <td>
                        <?php 
                           $qualities = '--';
                             if($isDrive && !empty($link['data'])){
                                $qt = Helper::getQulities($link['data']);
                                $qualities = implode(', ', $qt);
                             }
                           ?>
                        <span class="badge bg-blue-lt"><?=$qualities?></span>
                     </td>
                     <td><?=number_format($link['views'])?></td>
                     <td> <?=Helper::getStatus($link['status'])?></td>
                     <td><?=Helper::formatDT($link['updated_at'])?></td>
                     <td>
                        <div class="btn-list flex-nowrap">
                           <a href="javascript:void(0)" class="text-dark copy-plyr-link" data-url="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" data-toggle="tooltip" data-placement="top" title="copy player link">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" style="font-size: 1.3rem;" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"></path>
                                 <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                                 <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                              </svg>
                           </a>
                           <a href="javascript:void(0)" class="text-secondary ml-2 copy-embed-code" data-url="<?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?>" data-toggle="tooltip" data-placement="top" title="copy embed code">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" style="font-size: 1.3rem;" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"></path>
                                 <polyline points="7 8 3 12 7 16"></polyline>
                                 <polyline points="17 8 21 12 17 16"></polyline>
                                 <line x1="14" y1="4" x2="10" y2="20"></line>
                              </svg>
                           </a>
                           <a href="<?=PROOT?>/links/edit/<?=$link['id']?>" class="text-info ml-2" data-toggle="tooltip" data-placement="top" title="edit">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" style="font-size: 1.3rem;" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"></path>
                                 <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                 <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                 <line x1="16" y1="5" x2="19" y2="8"></line>
                              </svg>
                           </a>
                           <a href="javascript:void(0)" class="text-danger ml-2 del-link" data-toggle="tooltip" data-id="<?=$id?>" data-placement="top" title="delete">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" style="font-size: 1.3rem;" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"></path>
                                 <line x1="4" y1="7" x2="20" y2="7"></line>
                                 <line x1="10" y1="11" x2="10" y2="17"></line>
                                 <line x1="14" y1="11" x2="14" y2="17"></line>
                                 <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                 <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
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
</div>