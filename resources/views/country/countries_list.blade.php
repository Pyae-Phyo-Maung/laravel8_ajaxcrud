<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Country List</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.5/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
</head>
<body>
<div class="container">
    <div class="row" style="margin-top: 45px;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Countries List
                </div>
                <div class="card-body">
                    
                    <table class="table table-hover" id="countries_table">
                        <thead>
                            <th>No</th>
                            <th>Country Name</th>
                            <th>Capital Name</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                   
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Create Country
                </div>
                <div class="card-body">
                    <form action="{{route('country.create')}}" method="post" id="country_form">
                       @csrf
                        <div class="form-group">
                            <label for="country_name">Country Name</label>
                            <input 
                            type="text" 
                            class="form-control"
                            id="country_name"
                            name="country_name"
                            placeholder="Enter country name"
                            autofocus>
                            <span class="text-danger text-error country_name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="capital_city">Capital City</label>
                            <input
                            type="text"
                            class="form-control"
                            id="capital_city"
                            name="capital_city"
                            placeholder="Enter capital city"
                            autofocus>
                            <span class="text-danger error-text capital_city_error"></span>
                        </div>
                        <button 
                        type="submit"
                        class="form-control btn btn-success"
                        id="country_btn"
                        name="country_btn">
                        Create
                        </button>

                    </form>
                </div>
            </div>
        </div>
        @include('country.edit_country_modal')
    </div>
</div>

<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.slim.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.5/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script type="text/javascript">
   toastr.options.preventDuplicates=true;
    
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    
    $(function(){
        //Create Country
        $('#country_form').on('submit',function(e){
            e.preventDefault();
           $.ajax({
               url:$('#country_form').attr('action'),
               method:$('#country_form').attr('method'),
               data:new FormData(this),
               processData:false,
               contentType: false,
               dataType:"json",
               beforeSend:function(){
                   $('#country_form').find('span.error-text').text('');
               },
               success:function(data){
                   if(data.code == 0){
                       $.each(data.error,function(prefix,val){
                        $('#country_form').find('span.'+prefix+'_error').text(val[0]);
                       });
                      
                   }else {
                       $('#country_form')[0].reset();
                       $('#countries_table').DataTable().ajax.reload(null,true);
                       toastr.success(data.msg);
                   }
               }
            });
        });

        //Get Country List
        $('#countries_table').DataTable({
            processing : true,
            info : true,
            ajax : "{{route('getcountries.list')}}",
            pageLength : 5,
            aLengthMenu : [[5,10,25,50,-1],[5,10,25,50,"All"]],
            columns : [
                // {data:'id',name:'id'},
                {data:'DT_RowIndex',name:'DT_RowIndex'},
                {data:'country_name',name:'country_name'},
                {data:'capital_city',name:'capital_city'},
                {data:'actions',name:'actions',orderable: false, searchable: false},
            ]
        });

        //Get Country Detail
        $(document).on('click','#editCountryBtn',function(){
                var country_id=$(this).data('id');
                $('.edit_country').find('form')[0].reset();
                $('.edit_country').find('span.error-text').text('');
                $.post("<?= route('get.country.detail') ?>",{country_id:country_id},function(data){
                        $('.edit_country').find('input[name="cid"]').val(data.details.id);
                        $('.edit_country').find('input[name="country_name"]').val(data.details.country_name);
                        $('.edit_country').find('input[name="capital_city"]').val(data.details.capital_city);
                        $('.edit_country').modal('show');
                },'json');
        });

        //Update Country Detail
        $('#updateCountry').on('submit',function(e){
            e.preventDefault();
           var form=this;
            $.ajax({
                url : $(form).attr('action'),
                type : 'post',
                data : new FormData(form),
                processData : false,
                dataType : "json",
                contentType : false,
                beforeSend : function(){
                    $(form).find('span.error-text').text('');
                },
                success : function(data){
               // console.log(data);
                    if(data.code == 0){
                        $.each(data.error,function(prefix,val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                    }else {
                        $('.edit_country').modal('hide');
                        $('.edit_country').find('form')[0].reset();
                       $('#countries_table').DataTable().ajax.reload(null,true);
                       toastr.success(data.msg);
                    }
                }
            });
        });

        //Delete Country Detail
        $(document).on('click','#deleteCountryBtn',function(){
            var country_id = $(this).data('id');
            var url = "<?= route('delete.country.detail') ?>";

            Swal.fire({
                title : "Are you sure?",
                html : "You want to <b>delete</b> this country",
                showCancelButton: true,
                showCloseButton : true,
                cancelButtonText : 'Cancel',
                confirmButtonText : 'Yes,Delete',
                cancelButtonColor : '#d33',
                confirmButtonColor : '#556ee6',
                width : 300,
                allowOutsideClick : false
            }).then(function(result){
                if(result.value){
                    $.post(url,{country_id:country_id},function(data){
                        if(data.code = 1){
                            $('#countries_table').DataTable().ajax.reload(null,true);
                            toastr.success(data.msg);
                        }else {
                            toastr.error(data.msg);
                        }
                    },'json');
                }
            })
        });
    });
</script>
</body>
</html>
