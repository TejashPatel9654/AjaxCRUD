<!DOCTYPE html>
<html lang="en">

<head>
  <title>Users</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

</head>

<body>

  <div class="container">
    <div class="row" style="margin-top:20px;">
      <div class="col-md-6">
        <h2>User List</h2>
      </div>
      <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUser">
          Add User
        </button>
      </div>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>Profile</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="getData">

      </tbody>
    </table>
  </div>

  <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="exampleModalLabel">Add User</h2>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="post" id="submitForm" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" name="name" class="form-control clear-data" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" class="form-control clear-data" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="text" name="phone" class="form-control clear-data" placeholder="Enter Phone" required>
            </div>
            <div class="form-group">
              <label for="profile">Profile</label>
              <input type="file" name="profile">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="exampleModalLabel">Edit User</h2>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="post" id="editForm" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="user_id" id="user_id">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" name="name" id="name" class="form-control clear-data" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" class="form-control clear-data" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="text" name="phone" id="phone" class="form-control clear-data" placeholder="Enter Phone" required>
            </div>
            <div class="form-group">
              <label for="profile">Profile</label>
              <input type="file" name="profile">
              <input type="hidden" name="old_profile" id="old_profile">
            </div>
            <div id="old_profile_img">

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
    $('body').delegate(".deleteRecord", 'click', function() {
      let id = $(this).attr('id');
      if(confirm('Are you sure you want to delete this record?')){
        $.ajax({
        type: "POST",
        url: "user_action.php?type=delete",
        data: {
          id: id
        },
        dataType: "json",
        success: function(data) {
          alert(data.message);
          $("#row"+id).remove();
        },
        error: function(data) {

        }
      });
      }
      return false;
    });

    $('body').delegate("#editForm", "submit", function(e) {
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: "user_action.php?type=update",
        data: new FormData(this),
        processData:false,
        contentType:false,
        dataType: "json",
        success: function(data) {
          if (data.success == true) {
            alert(data.message);
            $("#editUser").modal('hide');
            getData();
          } else {
            alert(data.message);
          }
        },
        error: function(data) {

        }
      });
    });

    $('body').delegate(".editRecord", 'click', function() {
      let id = $(this).attr('id');
      $.ajax({
        type: "POST",
        url: "user_action.php?type=edit",
        data: {
          id: id
        },
        dataType: "json",
        success: function(data) {
          $("#editUser").modal('show');
          $("#user_id").val(data.id);
          $("#name").val(data.name);
          $("#email").val(data.email);
          $("#phone").val(data.phone);
          $("#old_profile").val(data.profile_pic);
          $("#old_profile_img").html(data.old_profile);
        },
        error: function(data) {

        }
      });
    });
    $('body').delegate("#submitForm", "submit", function(e) {
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: "user_action.php?type=add",
        data: new FormData(this),
        processData:false,
        contentType:false,
        dataType: "json",
        success: function(data) {
          if (data.success == true) {
            alert(data.message);
            $("#addUser").modal('hide');
            getData();
            $('.clear-data').val('');
          } else {
            alert(data.message);
          }
        },
        error: function(data) {

        }
      });
    });

    function getData() {
      $.ajax({
        type: "POST",
        url: "user_action.php?type=list",
        dataType: "json",
        success: function(data) {
          $("#getData").html(data.html);
        },
        error: function(data) {

        }
      });
    }
    getData();
  </script>
</body>

</html>