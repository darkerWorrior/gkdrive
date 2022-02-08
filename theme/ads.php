
<!-- Page title -->
<div class="page-header">
<div class="row align-items-center">
    <div class="col-auto">
    <ol class="breadcrumb" aria-label="breadcrumbs">
                          <li class="breadcrumb-item"><a href="<?=PROOT?>/dashboard">Dashboard</a></li>
                          <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0)">Advertisement</a></li>
                        </ol>
    </div>
</div>
</div>
<!-- Content here -->



<div class="row">




<div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vast Ad Tags</h3>
            </div>
            <div class="card-body">

         

            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Type</th>
                      <th>Offset</th>
                      <th class="w-1"></th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php foreach($ads['vast'] as $ad):
                    
                        $ac = json_decode($ad['code'], true);
                        $offset = $ac['offset'];
                        $skipOffSet = isset($ac['skipoffset']) ? $ac['skipoffset'] : '';
                        $type = isset($ac['type']) ? $ac['type'] : 'video';

                    ?>

                    <tr>

                    <td><?=$ad['title']?></td>
                    <td>
                        <?php if($type != 'video'): ?>
                            <span class="badge bg-cyan-lt">banner</span>
                        <?php else: ?>
                            <span class="badge bg-blue-lt">video</span>
                        <?php endif; ?>
                    </td>
                    <td><?=$offset?></td>

                    <td>
                        <div class="btn-list flex-nowrap">
                                 <a href="#" class="text-info edit-vast mr-3" 
                                    data-id="<?=$ad['id']?>"  
                                    data-title="<?=$ad['title']?>"
                                    data-offset="<?=$offset?>" 
                                    data-skipoffset="<?=$skipOffSet?>"
                                    data-type="<?=$type?>"
                                    data-file="<?=$ac['tag']?>"
                                 >Edit</a>
                                 <a href="<?=PROOT?>/ads/del/<?=$ad['id']?>" class="text-danger">Delete</a>

                              </td>
                        </div>
                    
                    </tr>

                    <?php endforeach; ?>
                 
                  </tbody>
                </table>
              </div>

















            </div>
        </div>



        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pop Ads</h3>
            </div>
            <div class="card-body">


            <form action="<?=PROOT.'/ads/save-popad'?>" method="post" id="">
            <div class="form-group mb-3">
                          <textarea class="form-control" name="popads"  placeholder="Enter pop ad code" rows="8" ><?=base64_decode($ads['popad'])?></textarea>
                        </div>

            <div class="form-footer">

          <button type="submit" class="btn btn-primary">Save popads</button>
        </div>
                    
            </form>


            </div>
        </div>

    </div>


<div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add/Edit Vast Ad</h3>
            </div>
            <div class="card-body">

            <?=$this->displayAlerts()?>

                    <form action="<?=PROOT.'/ads/save-vast'?>" method="post" id="form-ads">
                    <input type="text"  class="form-control" name="id" id="vast-id" hidden placeholder="">
                        <div class="form-group mb-3">
                            <label class="form-label">Title</label>
                            <input type="text"  class="form-control" id="vast-title"  name="title" placeholder="Vast ad 1">
                        </div>

                        <div class="mb-3">
                            <div class="form-label">Ad Type:</div>
                            <select name="type" class="form-select" id="vast-type">
                              <option value="nonlinear">Banner</option>
                              <option value="video">Video</option>
                            </select>
                          </div>

                        <div class="form-group mb-3">
                            <label class="form-label">XML File</label>
                            <input type="url" id="vast-file" class="form-control" name="xml" placeholder="https://mydomain.com/ad-data/vast.xml" required>
                        </div>


                        <div class="form-group row showcase_row_area">
            <div class="col-md-6">
               <label for="form-label">Start Offset: </label>
               <input type="text" name="offset" class="form-control" id="vast-offset" placeholder="" required="">
            </div>
            <div class="col-md-6 skipoff-input d-none ">
               <label for="form-label">Skip Offset: </label>
               <input type="text" name="skip-offset" class="form-control" value="5" id="vast-skipoffset" placeholder="">
            </div>
         </div>



                        <div class="form-footer">
                        <button type="reset" class="btn btn-secondary  btn-block">Reset</button>

          <button type="submit" class="btn btn-primary btn-block">Save</button>
        </div>
                    
                    
                    </form>


            </div>
        </div>







    </div>









</div>
