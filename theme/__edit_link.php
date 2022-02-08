<!-- Page title -->
<div class="page-header">
   <div class="row align-items-center">
      <div class="col-auto">
         <ol class="breadcrumb" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="<?=PROOT?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?=PROOT?>/links/all">Links</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0)">Edit Link</a></li>
         </ol>
      </div>
   </div>
</div>
<!-- Content here -->
<form action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data" >
   <div class="row">
      <div class="col-md-8">
         <div class="card">
            <div class="card-header">
               <h3 class="card-title">Edit link</h3>
            </div>
            <div class="card-body">
               <?php $this->displayAlerts(); ?>
               <div class="form-group mb-3 ">
                  <label class="form-label">File Title</label>
                  <div>
                     <input type="text" class="form-control" name="title" value="<?=$link['title']?>"  placeholder="Enter file name">
                  </div>
               </div>
               <div class="form-group mb-3 ">
                  <label class="form-label">Main Link*</label>
                  <div>
                     <div class="input-group mb-2">
                        <button class="btn  btn-secondary"  type="button"  data-toggle="tooltip" data-placement="top" title="<?=$link['type']?>">
                        <img src="<?=Helper::getIcon($link['type'])?>" height="15" alt="">
                        </button>
                        <input type="text" class="form-control" name="main_link" value="<?=$link['main_link']?>" placeholder="Enter your main link" required>
                     </div>
                     <small class="form-hint">Supported sources: google drive, google photos, one drive, yadisk, direct </small>
                  </div>
               </div>
               <div class="form-group mb-3 ">
                  <label class="form-label">Alternative  Link/s</label>
                  <input type="text" class="form-control" name="alt_link" value="<?=$link['alt_link']?>" placeholder="Enter your alternative link">
                  <small class="form-hint">Supported sources: google drive, google photos, one drive, yadisk, direct </small>
               </div>
               <div class="form-group mb-3 ">
                  <label class="form-label">Subtitles</label>
                  <div class="" id="sub-list">
                     <?php foreach($link['subtitles'] as $k => $sub): ?>
                     <div class="row row-sm mb-2" id="<?=$k==0?'add-sub-dumy':''?>">
                        <div class="col-auto">
                           <select class="form-select" name="sub[<?=$k+1?>][label]" style="min-width: 175px;">
                              <?php    
                                 $subLables = json_decode($this->config['sublist'], true);
                                 foreach($subLables as $sublbl):
                                   $selected = $sub['label'] == $sublbl ? 'selected' : '';
                                 ?>
                              <option value="<?=$sublbl?>" <?=$selected?>><?=ucwords($sublbl)?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                        <div class="col">
                           <input type="file" name="sub[<?=$k+1?>][file]"  placeholder="Search for…">
                           <input type="text" name="sub[<?=$k+1?>][file]" class="sub-file" hidden value="<?=$sub['file']?>">
                           <input type="text" name="sub[<?=$k+1?>][is_remove]" class="is_remove_sub" hidden value="0">
                        </div>
                        <div class="col-auto align-self-center">
                           <a href="javascript:void(0)" class="link-secondary add-sub" title="" data-toggle="tooltip" data-original-title="add new" style="vertical-align: middle;">
                              <svg class="icon icon-md" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                 <path fill-rule="evenodd" d="M10 5.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H6a.5.5 0 010-1h3.5V6a.5.5 0 01.5-.5z" clip-rule="evenodd"></path>
                                 <path fill-rule="evenodd" d="M9.5 10a.5.5 0 01.5-.5h4a.5.5 0 010 1h-3.5V14a.5.5 0 01-1 0v-4z" clip-rule="evenodd"></path>
                              </svg>
                           </a>
                           <?php if(!empty($sub['file'])): ?>
                           <a href="<?=Helper::getSubD($sub['file'])?>"  class="link-secondary download" title="" data-toggle="tooltip" data-original-title="download" style="vertical-align: middle;    ">
                              <svg class="icon" width="1em" height="1em" viewBox="0 0 20 20" style="font-size: 1.2rem;" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                 <path fill-rule="evenodd" d="M6.646 11.646a.5.5 0 01.708 0L10 14.293l2.646-2.647a.5.5 0 01.708.708l-3 3a.5.5 0 01-.708 0l-3-3a.5.5 0 010-.708z" clip-rule="evenodd"></path>
                                 <path fill-rule="evenodd" d="M10 4.5a.5.5 0 01.5.5v9a.5.5 0 01-1 0V5a.5.5 0 01.5-.5z" clip-rule="evenodd"></path>
                              </svg>
                           </a>
                           <a href="javascript:void(0)" class="link-danger remove-sub" title="" data-toggle="tooltip" data-original-title="remove">
                              <svg class="icon " width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M7.5 7.5A.5.5 0 018 8v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V8z"></path>
                                 <path fill-rule="evenodd" d="M16.5 5a1 1 0 01-1 1H15v9a2 2 0 01-2 2H7a2 2 0 01-2-2V6h-.5a1 1 0 01-1-1V4a1 1 0 011-1H8a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM6.118 6L6 6.059V15a1 1 0 001 1h6a1 1 0 001-1V6.059L13.882 6H6.118zM4.5 5V4h11v1h-11z" clip-rule="evenodd"></path>
                              </svg>
                           </a>
                           <?php endif; ?>
                           <?php if(empty($sub['file']) && $k ==0): ?>
                           <a href="javascript:void(0)" class="link-secondary remove-sub d-none" title="" data-toggle="tooltip" data-original-title="remove">
                              <svg class="icon " width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M7.5 7.5A.5.5 0 018 8v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V8z"></path>
                                 <path fill-rule="evenodd" d="M16.5 5a1 1 0 01-1 1H15v9a2 2 0 01-2 2H7a2 2 0 01-2-2V6h-.5a1 1 0 01-1-1V4a1 1 0 011-1H8a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM6.118 6L6 6.059V15a1 1 0 001 1h6a1 1 0 001-1V6.059L13.882 6H6.118zM4.5 5V4h11v1h-11z" clip-rule="evenodd"></path>
                              </svg>
                           </a>
                           <?php endif; ?>
                           <a href="javascript:void(0)" class="link-secondary ml-2 move" title="" data-toggle="tooltip" data-original-title="move">
                              <svg class="icon icon-sm" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                 <path fill-rule="evenodd" d="M4 11.5a.5.5 0 01.5.5v3.5H8a.5.5 0 010 1H4a.5.5 0 01-.5-.5v-4a.5.5 0 01.5-.5z" clip-rule="evenodd"></path>
                                 <path fill-rule="evenodd" d="M8.854 11.11a.5.5 0 010 .708l-4.5 4.5a.5.5 0 11-.708-.707l4.5-4.5a.5.5 0 01.708 0zm7.464-7.464a.5.5 0 010 .708l-4.5 4.5a.5.5 0 11-.707-.708l4.5-4.5a.5.5 0 01.707 0z" clip-rule="evenodd"></path>
                                 <path fill-rule="evenodd" d="M11.5 4a.5.5 0 01.5-.5h4a.5.5 0 01.5.5v4a.5.5 0 01-1 0V4.5H12a.5.5 0 01-.5-.5zm4.5 7.5a.5.5 0 00-.5.5v3.5H12a.5.5 0 000 1h4a.5.5 0 00.5-.5v-4a.5.5 0 00-.5-.5z" clip-rule="evenodd"></path>
                                 <path fill-rule="evenodd" d="M11.146 11.11a.5.5 0 000 .708l4.5 4.5a.5.5 0 00.708-.707l-4.5-4.5a.5.5 0 00-.708 0zM3.682 3.646a.5.5 0 000 .708l4.5 4.5a.5.5 0 10.707-.708l-4.5-4.5a.5.5 0 00-.707 0z" clip-rule="evenodd"></path>
                                 <path fill-rule="evenodd" d="M8.5 4a.5.5 0 00-.5-.5H4a.5.5 0 00-.5.5v4a.5.5 0 001 0V4.5H8a.5.5 0 00.5-.5z" clip-rule="evenodd"></path>
                              </svg>
                           </a>
                        </div>
                        <?php if(!empty($sub['file'])): ?>
                        <div class="col-12 sub-label">
                           <span class="badge bg-blue-lt"><?=substr($sub['file'], 0, 100)?></span>
                        </div>
                        <?php endif; ?>
                     </div>
                     <!-- ./ row -->
                     <?php endforeach; ?>
                  </div>
                  <small class="form-hint">Supported formats : .srt, .vtt, .dfxp, .ttml, .xml</small>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="card">
            <div class="card-body">
               <div class="form-group mb-3 ">
                  <label class="form-label">Preview image</label>
                  <div>
                     <input type="file" name="preview_image" >
                     <?php if(!empty($link['preview_img'])): ?>
                     <div class="preview-img-wrap mt-2">
                        <input type="text" name="preview_image" value="<?=$link['preview_img']?>" hidden>
                        <img src="<?=Helper::getBanner($link['preview_img'])?>" class="w-100" alt="preview_image">
                        <a href="javascript:void(0)" class="text-danger remove-preview-img">remove</a>
                     </div>
                     <?php endif; ?>
                  </div>
               </div>
               <div class="form-group mb-3 ">
                  <label class="form-label">Custom slug</label>
                  <div>
                     <input type="text" class="form-control" name="slug" value="<?=$link['slug']?>" placeholder="Enter custom video slug" required>
                  </div>
               </div>
               <div class="form-group mb-3 ">
                  <label class="form-label">Link Status</label>
                  <select class="form-select" name="status">
                     <option value="active" <?=$link['status'] == 0 ? 'selected' : ''?> >Active</option>
                     <option value="inactive" <?=$link['status'] == 1 ? 'selected' : ''?> >Draft</option>
                  </select>
               </div>
               <div class="mb-3">
                  <ul>
                     <li> <b>Created At</b> : <i><?=Helper::formatDT($link['created_at'])?></i> </li>
                     <li> <b>Last Updated At</b> : <i><?=Helper::formatDT($link['updated_at'])?></i> </li>
                  </ul>
               </div>
               <div class="form-footer">
                  <button type="submit" class="btn btn-block btn-primary">Save link</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>
<div class="row">
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Direct Stream Link</h3>
         </div>
         <div class="card-body">
            <?php if($link['type'] == 'GDrive'): ?>
            <div class="position-relative">
               <textarea class="form-control"  readonly id="streamLink" rows='3'><?=Helper::getStreamLink($link['slug'])?></textarea>
               <button type="button" class="btn btn-sm btn-success position-absolute" id="copyStreamLink" style="bottom: 8px;right:8px;">copy</button>
            </div>
            <small>Available Qulities: <?=implode(', ',Helper::getQulities($link['data']))?></small>
            <?php else: ?>
            <small>Not Available !</small>
            <?php endif; ?>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Player Link</h3>
         </div>
         <div class="card-body">
            <div class="position-relative">
               <textarea class="form-control"  readonly id="plyrLink" rows='4'><?=Helper::getPlyrLink($this->config['playerSlug'], $link['slug'])?></textarea>
               <button type="button" class="btn btn-sm btn-success position-absolute" id="copyPlyrLink" style="bottom: 8px;right:8px;">copy</button>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Embed Code</h3>
         </div>
         <div class="card-body">
            <div class="position-relative">
               <textarea class="form-control" id="embedCode" readonly rows='4'><?=Helper::getEmbedCode(Helper::getPlyrLink($this->config['playerSlug'], $link['slug']))?></textarea>
               <button type="button" class="btn btn-sm btn-success position-absolute" id="copyEmbedCode" style="bottom: 8px;right:8px;">copy</button>
            </div>
         </div>
      </div>
   </div>
</div>