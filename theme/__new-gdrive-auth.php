<!-- Page title -->
<div class="page-header">
   <div class="row align-items-center">
      <div class="col-auto">
         <h2 class="page-title">
            New GDrive Auth
         </h2>
      </div>
   </div>
</div>
<!-- Content here -->
<div class="row">
   <div class="col-md-10">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Add/Edit GDrive Auth</h3>
         </div>
         <div class="card-body">
            <?=$this->displayAlerts()?>
            <form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
               <input type="text" id="server-id" class="form-control" name="id" hidden placeholder="">
               <div class="form-group mb-3">
                  <label class="form-label">Email</label>
                  <input type="text"  class="form-control"  name="email" value="<?=$auth['email']?>" placeholder="info@example.com" required>
               </div>
               <div class="form-group mb-3">
                  <label class="form-label">Client ID</label>
                  <input type="text"  class="form-control" name="client_id" value="<?=$auth['client_id']?>" placeholder="1078811469304-7t0qobs4o5n93vfq9eod7r74l2sn2f3h.apps.googleusercontent.com" required>
               </div>
               <div class="form-group mb-3">
                  <label class="form-label">Client Secret</label>
                  <input type="text"  class="form-control" name="client_secret" value="<?=$auth['client_secret']?>" placeholder="1078811469304-7t0qobs4o5n93vfq9eod7r74l2sn2f3h.apps.googleusercontent.com" required>
               </div>
               <div class="form-group mb-3">
                  <label class="form-label">Refresh Token</label>
                  <input type="text"  class="form-control" name="refresh_token" value="<?=$auth['refresh_token']?>" placeholder="1078811469304-7t0qobs4o5n93vfq9eod7r74l2sn2f3h.apps.googleusercontent.com" required>
               </div>
               <div class="form-footer text-right">
                  <button type="submit" class="btn btn-primary">Save</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>