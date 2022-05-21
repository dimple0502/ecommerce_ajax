<!DOCTYPE html>
<html>
<head>
<title> Product List</title>
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header">
	<div class="container">
		<h3 class="heading">Ecommerce Application</h3>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h4>Product List</h4>
		</div>
		<div class="col-md-6 text-right">
			<a href="javascript:void(0)" onclick="showModel()" class="btn btn-primary">Create</a>
		</div>
		<div class="col-md-12">
			<table class="table table-striped mt-2" id="myTable">	
				<tr> 
				 <th>Sr No.</th> 
				 <th>Product Name</th> 
				 <th>Product Desccription</th> 
				 <th>Edit</th> 
				 <th>Delete</th> 
			   </tr> 
			        
         <?php foreach($LISTDATA as $data) 
             {?> 
       <tr> 
         <td><?php echo $data->product_name;?></td> 
         <td><?php echo $data->product_price;?></td> 
         <td><?php echo $data->product_description;?></td> 
         <td><a onClick="showEditForm(<?php echo $data->product_id;?>)" class="btn btn-primary">Edit</a></td> 
         <td><a  onClick="deleteProduct(<?php echo $data->product_id;?>)" class="btn btn-danger">Delete</a></td> 
       </tr> 
             <?php } ?>
			</table>
	
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="createProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	   <form method="post" enctype='multipart/form-data' id="addProductModel" class="addProductModel">
    
 <div class="modal-body">
 <input type="hidden"   name="product_id" id="product_id">
       <div class="form-group">
			<label for="email">Product Name:</label>
		    <input type="text" class="form-control"  placeholder="Enter Product Name" name="product_name" id="product_name">
          <p class="nameError"></p>
	   </div>
	   <div class="form-group">
		  <label for="pwd">Product Price</label>
		  <input type="number" class="form-control"  placeholder="Enter Product Price" name="product_price" id="product_price">
		 <p class="priceError"></p>
		</div>
	
	   <div class="form-group">
			<label for="pwd">Product Description:</label>
			<input type="text" class="form-control"  placeholder="Enter Product Desccription"  name="product_description" id="product_description">
		 <p class="descError"></p>
	  </div>
	  <div class="form-group">
		 <label for="pwd">Product Image:</label>
		 <input type='file' id="product_image" name='product_image[]' multiple="">
		 <p class="imgError"></p>
	   </div>
	   <div id="imgDiv"></div>
  </div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  <input type="submit" value="Submit"  class="btn btn-primary">
<!--<button type="button" id="submit" class="btn btn-primary">Save changes</button>-->
</div>
</form>

	   <!--<div id="response">
	   </div>-->
     
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="ajaxResponseModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	 
 <div class="modal-body" id="modal-body">
      
  </div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
 </div>


	   <!--<div id="response">
	   </div>-->
     
    </div>
  </div>
</div>
<script type="text/javascript">
function showModel(){
	$('#addProductModel #product_id').val();
	$('#addProductModel')[0].reset();
	document.getElementById("product_id").value='';

	$("#createProduct").modal("show");
	
}
$("form#addProductModel").submit(function(e) {
    e.preventDefault();
	
	 $.ajax({
            type: 'POST',
            url:'<?php echo base_url() ?>index.php/products/addProduct', 
			
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,

			success: function (response) {
				console.log(response.product_name);
                  if(response.status==0){
					  if(response.product_name !=''){
						  $('.nameError').html(response.product_name).addClass('invalid-feedback d-block');
					  }
					  if(response.product_price !=''){
						  $('.priceError').html(response.product_price).addClass('invalid-feedback d-block');
					  }
					  if(response.product_description !=''){
						  $('.descError').html(response.product_description).addClass('invalid-feedback d-block');
					  }

					 
				  }
				  else{
						$("#createProduct").modal("hide");
						$("#modal-body").html(response.message);
						$("#ajaxResponseModel").modal("show");
						setInterval('location.reload()', 2000);
				  }
                }
	 });
});
function showEditForm(id){
	$("#createProduct").modal("show");
	 $.ajax({
			url:'<?php echo base_url() ?>index.php/products/showEditForm', 
			type: "POST",
			data:{id:id},
			dataType:'json',
			success: function (response) {
				$("#product_name").val(response[0]['product_name']);
				$("#product_price").val(response[0]['product_price']);
				$("#product_description").val(response[0]['product_description']);
				$("#product_id").val(response[0]['product_id']);
				product_image = response[0]['product_image']
				if(product_image !=''){
					var imgSrcArr = product_image.split(',');
					var arrayLength = imgSrcArr.length;
					for (var i = 0; i < arrayLength; i++) {
						 imgpp = imgSrcArr[i];
						  var crate_img = document.createElement("IMG");
        crate_img.setAttribute("src", "<?php echo base_url();?>uploads/"+imgpp);
		 crate_img.setAttribute("style", "height:40px;width:40px;margin-right:10px;");
        document.getElementById("imgDiv").appendChild(crate_img);
						//var producPic ='<img src='"<?php echo base_url();?>uploads/"+imgpp>'/';
						//$('#imgDiv').append(producPic); 
					}
					
                }
				},
			error: function (error) {
				console.log(`Error ${error}`);
			}
	 });
}

function deleteProduct(id){
	$.ajax({
			url:'<?php echo base_url() ?>index.php/products/delete', 
			type: "POST",
			data:{id:id},
			dataType:'json',
			success: function (response) {
				console.log(response);
                  if(response.status==2){
					    $("#modal-body").html(response.message);
						$("#ajaxResponseModel").modal("show");
						setInterval('location.reload()', 2000);
				  }
                }
	 });
	

}
</script>
</body>
</html>
