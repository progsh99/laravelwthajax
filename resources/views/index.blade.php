<html>
      <head>
       <title> Application </title>  
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
       <style>

            .myare {

               display: none;
            }


.container {
    margin-top : 40px ;

  
}
.panel-body {
    height :140px ;
    width :98% ;
    overflow: scroll;
    text-align : center ;
    font-size :24px ;

}
.pid:hover {

    background-color : #337ab7 ;
}
.panel-body2 {
    height :400px ;
}
</style>
      </head>
    <body>
        <div id="mnw">
        </div>
    <div class="container" >
    <div class="panel panel-primary">
     <div class="panel-heading">Recipe Box
     <button id="btn_add" name="btn_add" class="btn btn-default pull-right">Add New Product</button>
        </div>
          <div class="panel-body" id="mainname"> 
        @foreach ($products as $product)
            
                 <p id="product{{$product->id}}" onclick="showproduct({{$product->id}})" class="pid">{{$product->name}}</p>
       
                @endforeach
          
            
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                 <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Product</h4>
                </div>
                <div class="modal-body">
                <form id="frmProducts" name="frmProducts" class="form-horizontal" novalidate="">
                    <div class="form-group error">
                     <label for="inputName" class="col-sm-3 control-label">Name</label>
                       <div class="col-sm-9">
                        <input type="text" class="form-control has-error" id="name" name="name" placeholder="Product Name" value="">
                       </div>
                       </div>
                     <div class="form-group">
                     <label for="inputDetail" class="col-sm-3 control-label">ingredients</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="ingredients" name="ingredients" placeholder="details" value="">
                        </div>
                    </div>


                    <div class="form-group">
                     <label for="inputDetail" class="col-sm-3 control-label">directions</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="directions" name="directions" placeholder="details" value="">
                        </div>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                <input type="hidden" id="product_id" name="product_id" value="0">
                </div>
            </div>
          </div>
      </div>

      
    </div>

   
    <div class="panel-heading">Details :

</div>
<div class="panel-body panel-body2" id="details"> 
        @foreach ($products as $product2)

        <div class="myare" id="{{$product2->id}}">
        <button class="btn btn-warning btn-detail open_modal" value="{{$product2->id}}">Edit</button>
          <button class="btn btn-danger btn-delete delete-product" value="{{$product2->id}}">Delete</button>
</br>

        Ingredients:
         <p>{{$product2->ingredients}}</p>
         Directions:
         <p>{{$product2->directions}}</p>
         

</div>
     
        @endforeach
      
        </div>

        
        <meta name="_token" content="{!! csrf_token() !!}" />
        <script src='https://cdnjs.cloudflare.com/ajax/libs/react/0.14.3/react.min.js'></script>
               
      
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script>
function showproduct(idm) {
 const boxes = document.getElementsByClassName('myare');

for (const box of boxes) {

  box.style.display = 'none';

}
document.getElementById(idm).style.display = 'block'; 

}
var url = "https://laraveltask0.herokuapp.com/productajaxCRUD";
    //display modal form for product editing
    $(document).on('click','.open_modal',function(){
        var product_id = $(this).val();
       
        $.get(url + '/' + product_id, function (data) {
            //success data
            console.log(data);
            $('#product_id').val(data.id);
            $('#name').val(data.name);
            $('#ingredients').val(data.ingredients);
            $('#directions').val(data.directions);
            $('#btn-save').val("update");
            $('#myModal').modal('show');
        }) 
    });
    //display modal form for creating new product
    $('#btn_add').click(function(){
        $('#btn-save').val("add");
        $('#frmProducts').trigger("reset");
        $('#myModal').modal('show');
    });
    //delete product and remove it from list
    $(document).on('click','.delete-product',function(){
        var product_id = $(this).val();
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        $.ajax({
            type: "DELETE",
            url: url + '/' + product_id,
            success: function (data) {
                console.log(data);
                $("#product" + product_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
    //create new product / update existing product
    $("#btn-save").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        e.preventDefault(); 
        var formData = {
            name: $('#name').val(),
            ingredients: $('#ingredients').val(),
            directions: $('#directions').val(),
        }
        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btn-save').val();
        var type = "POST"; //for creating new resource
        var product_id = $('#product_id').val();;
        var my_url = url;
        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + product_id;
        }
        console.log(formData);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                
                var product = " <p onclick='showproduct("+data.id+")' class='pid'>"+data.name +"</p>";
                var product2 = "  <div class='myare' id='"+data.id+"'>";
                 product2 +=  "<button class='btn btn-warning btn-detail open_modal' value='"+data.id+"'>Edit</button>  ";
                 product2 += " <button class='btn btn-danger btn-delete delete-product' value='"+data.id+"'>Delete</button> ";
                  product2 += "</br> " ;

                  product2 += " Ingredients: ";
                  product2 += "  <p>"+data.ingredients+"</p> ";
                  product2 += "  Directions: ";
                  product2 += " <p>"+data.directions+"</p> ";
         

                  product2 += "</div>" ;

                if (state == "add"){ //if user added a new record
                    $('#mainname').append(product);
                    $('#details').append(product2);
                }else{ //if user updated an existing record
                   $("#product" + product_id).replaceWith( product );
                   $("#" + product_id).replaceWith( product2 );
                }
                $('#frmProducts').trigger("reset");
                $('#myModal').modal('hide')
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
        </script>



    </body>
    </html>