<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Api DataTables AJAX</title>

    <!-- Bootstrap CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <style>
        body {
            padding-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <h2 style ="text-align:center; color:blue;">Blog</h2>
                <a style="float:right;" href="{{route('user.logout')}}">Logout admin</a>
                <button type="button" class="btn btn-xs btn-primary float-right add" id="create-new-blog">Add Blog</button>
                
                <hr>
                @foreach ($array as $value)
                    <div>{{$value->title}}</div>
                    <div>{{$value->bank_id}}
                       
                    </div>
                    </br> 
                @endforeach
                <table id="blogs-table" class="table table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Desc</th>
                            <th width="70">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


                <!-- Dialog -->
                <div class="modal" tabindex="-1" role="dialog" id="ajax-modal">
                    <div class="modal-dialog" role="document">
                        <form class="form" action="" method="POST" id="formModal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="blog_id" name="id" >

                                    <div class="form-group">
                                        <label for="name">Title</label>
                                        <input type="text" id="title" name="title" class="form-control input-sm">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Desc</label>
                                        <input type="text" id="desc" name="des" class="form-control input-sm">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="btn-save" class="btn btn-primary btn-save">Save</button>
                                    <button type="button" class="btn btn-primary btn-update">Update</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--  -->
                
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <!-- App scripts -->
    <!--js code here-->
    <script>
        $(document).ready(function(){
            $('#blogs-table').DataTable({
                ajax: {url:'/api/v1/exams'},
                dataType:'json',
                type:"GET",
                columns: [
                    {data:'stt'},
                    {data:'title'},
                    {data:'desc'},
                    {data: 'actions'},
                ]
            });
        });

        if($("#formModal").length >0){
            $("#formModal").validate({
                submitHandler: function(form) {
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');
                    $('#btn-save').on('click', function () {
                    $.ajax({
                        url: 'api/v1/exams',
                        type: 'POST',
                        dataType: 'json',
                        success: function(data){
                            if(data.status == true){
                                alert(1);
                                $('#ajax-modal').modal('hide');
                            }else{
                                alert('Error');
                            }
                            
                        },
                    });
                });
                }
            });
        }
        

        //click add user
        $('#create-new-blog').click(function () {
            // $('#btn-save').val("create-product");
            // $('#productForm').trigger("reset");
            $('#blog_id').val('');
            $('.modal-title').html("Add New Blog");
            $('.btn-update').hide();
            $('.btn-save').show();
            $('#ajax-modal').modal('show');
        });

        //edit
        $('body').on('click', '#edit-blog', function () {
            var blog_id = $(this).data("id_edit"); 
            $.get('/api/v1/exams/' + blog_id + '/edit', function (data) {
                $('.modal-title').html("Edit Blog");
                $('#ajax-modal').modal('show');
                $('.btn-save').hide();
                $('.btn-update').show();
                $('#id').val(data.id);
                $('#title').val(data.data.title);
                $('#desc').val(data.data.des);
            });
        });

        $('body').on('click', '#delete-blog', function () {
            var blog_id = $(this).data("id");    
            if(confirm("Are You sure want to delete !")){
                $.ajax({
                    type: "DELETE",
                    url: "/api/v1/exams/"+blog_id,
                    dataType: 'json',
                    success: function(data){
                        if(data.status == true){
                           setTimeout(location.reload(),1000);
                        }else{
                            alert(data.message);
                        }
                    }
                });
            }
        });
       
    </script>
</body>
</html>


