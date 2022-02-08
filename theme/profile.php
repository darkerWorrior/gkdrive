<!-- Page title -->
<div class="page-header">
   <div class="row align-items-center">
      <div class="col-auto">
         <ol class="breadcrumb" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="<?=PROOT?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0)">profile</a></li>
         </ol>
      </div>
   </div>
</div>
<!-- Content here -->
<div class="row">
   <div class="col-md-6">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Profile</h3>
         </div>
         <div class="card-body">
            <?=$this->displayAlerts()?>
            <form action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data" >
               <input type="text" id="server-id" class="form-control" name="id" hidden placeholder="">
               <div class="form-group mb-3">
                  <label class="form-label">Username</label>
                  <input type="text"  class="form-control"  name="username" value="<?=$user['username']?>" placeholder="Enter username">
               </div>
               <div class="form-group mb-3">
                  <label class="form-label">New password</label>
                  <input type="password"  class="form-control" name="password" placeholder="Enter new password">
               </div>
               <div class="form-group mb-3">
                  <label class="form-label">Confirm password</label>
                  <input type="password"  class="form-control" name="confirm_passsword" placeholder="Confirm new password">
               </div>
               <div class="form-group mb-3">
                  <label class="form-label">Profile image</label>
                  <input type="file"  class="form-control" name="image">
                  <input type="text" name="image" value="<?=$user['img']?>" hidden >
                  <?php if(!empty($user['img'])): ?>
                  <img src="<?=PROOT?>/uploads/<?=$user['img']?>" height="50" alt="profile-image">
                  <?php endif; ?>
               </div>
               <div class="form-footer">
                  <button type="submit" class="btn btn-primary btn-block">Save</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>