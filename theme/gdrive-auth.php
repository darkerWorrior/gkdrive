<!-- Page title -->
<div class="page-header">
   <div class="row align-items-center">
      <div class="col-auto">
         <ol class="breadcrumb" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="<?=PROOT?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?=PROOT?>/settings/general">Settings</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0)">GDrive Auth</a></li>
         </ol>
      </div>
   </div>
</div>
<!-- Content here -->
<div class="row">
   <div class="col-md-8">
      <div class="card">
         <div class="card-header" style="justify-content: space-between;">
            <h3 class="card-title">GDrive auths</h3>
            <div class="">
               <a href="<?=PROOT?>/settings/gauth/new" class="btn btn-primary">Add account</a>
            </div>
         </div>
         <div class="card-body">
            <div id="alert-wrap"></div>
            <div class="table-responsive">
               <table class="table table-vcenter card-table">
                  <thead>
                     <tr>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Last updated at</th>
                        <th class="w-1"></th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach($auths as $auth): ?>
                     <tr>
                        <td><?=$auth['email']?></td>
                        <td>
                           <?php if($auth['status'] == 0): ?>
                           <span class="badge bg-green-lt sd-<?=$auth['id']?>">Active</span>
                           <?php else: ?>
                           <span class="badge bg-red-lt sd-<?=$auth['id']?>">Broken</span>
                           <?php endif; ?>
                        </td>
                        <td><?=Helper::formatDT($auth['updated_at'])?></td>
                        <td>
                           <div class="btn-list flex-nowrap">
                              <a href="<?=PROOT?>/settings/gauth/edit/<?=$auth['id']?>" class="text-secondary ml-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                 <svg class="icon" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" style="font-size:1.3rem" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M13.293 3.293a1 1 0 011.414 0l2 2a1 1 0 010 1.414l-9 9a1 1 0 01-.39.242l-3 1a1 1 0 01-1.266-1.265l1-3a1 1 0 01.242-.391l9-9zM14 4l2 2-9 9-3 1 1-3 9-9z" clip-rule="evenodd"></path>
                                    <path fill-rule="evenodd" d="M14.146 8.354l-2.5-2.5.708-.708 2.5 2.5-.708.708zM5 12v.5a.5.5 0 00.5.5H6v.5a.5.5 0 00.5.5H7v.5a.5.5 0 00.5.5H8v-1.5a.5.5 0 00-.5-.5H7v-.5a.5.5 0 00-.5-.5H5z" clip-rule="evenodd"></path>
                                 </svg>
                              </a>
                              <a href="javascript:void(0)" class="text-info ml-2 refresh-gauth"  data-toggle="tooltip" data-placement="top" title="" data-id="<?=$auth['id']?>" data-original-title="Refresh">
                                 <svg class="icon " width="1em" style="font-size:1.3rem" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4 9.5a.5.5 0 00-.5.5 6.5 6.5 0 0012.13 3.25.5.5 0 00-.866-.5A5.5 5.5 0 014.5 10a.5.5 0 00-.5-.5z" clip-rule="evenodd"></path>
                                    <path fill-rule="evenodd" d="M4.354 9.146a.5.5 0 00-.708 0l-2 2a.5.5 0 00.708.708L4 10.207l1.646 1.647a.5.5 0 00.708-.708l-2-2zM15.947 10.5a.5.5 0 00.5-.5 6.5 6.5 0 00-12.13-3.25.5.5 0 10.866.5A5.5 5.5 0 0115.448 10a.5.5 0 00.5.5z" clip-rule="evenodd"></path>
                                    <path fill-rule="evenodd" d="M18.354 8.146a.5.5 0 00-.708 0L16 9.793l-1.646-1.647a.5.5 0 00-.708.708l2 2a.5.5 0 00.708 0l2-2a.5.5 0 000-.708z" clip-rule="evenodd"></path>
                                 </svg>
                              </a>
                              <a href="javascript:void(0)" class="text-danger del-gauth ml-2" data-url="<?=PROOT?>/settings/gauth/del/<?=$auth['id']?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                 <svg class="icon " width="1em" style="font-size:1.3rem" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.5 7.5A.5.5 0 018 8v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V8z"></path>
                                    <path fill-rule="evenodd" d="M16.5 5a1 1 0 01-1 1H15v9a2 2 0 01-2 2H7a2 2 0 01-2-2V6h-.5a1 1 0 01-1-1V4a1 1 0 011-1H8a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM6.118 6L6 6.059V15a1 1 0 001 1h6a1 1 0 001-1V6.059L13.882 6H6.118zM4.5 5V4h11v1h-11z" clip-rule="evenodd"></path>
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
</div>