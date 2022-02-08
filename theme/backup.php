
<!-- Page title -->
<div class="page-header">
<div class="row align-items-center">
    <div class="col-auto">
    <ol class="breadcrumb" aria-label="breadcrumbs">
                          <li class="breadcrumb-item"><a href="<?=PROOT?>/dashboard">Dashboard</a></li>
                          <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0)">Backup</a></li>
                        </ol>
    </div>
</div>
</div>
<!-- Content here -->


<div class="row">




<div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Database backup</h3>
            </div>
            <div class="card-body">

            <?=$this->displayAlerts()?>

            <div class="row align-items-center">
    <div class="col-auto">
    <p> <b>Last backup</b> : <br> <?=Helper::formatDT($this->config['last_backup'])?> </p>
    </div>


    <div class="col-auto ml-auto d-print-none">
                <!-- <span class="d-none d-sm-inline">
                  <a href="#" class="btn btn-secondary">
                    New view
                  </a>
                </span> -->
                <a href="<?=PROOT?>/settings/backup?i=1" class="btn btn-primary ml-3 d-none d-sm-inline-block">
                 
                  Get backup
                </a>
               
              </div>
</div>

            </div>
        </div>
    </div>






</div>