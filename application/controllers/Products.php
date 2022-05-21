<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {
	       
          
			public function index()
			{
				$data['LISTDATA']=$this->product->get_data('products');
				$this->load->view('product/product_list',$data);
			}
			public  function delete()
			{
			  $id = $this->input->post('id');
			  $this->product->delete('products',$id);
	   
			  $response['status']=2;
			  $response['message']="<div class='alert alert-success'>Record has been deleted succesfully</div>";
	          echo json_encode($response);
			}

			public function showEditForm()
			{
				$id = $this->input->post('id');
				$result = $this->product->fetchdatabyid($id,'products');
				echo json_encode($result);
			}

			public function addProduct()
			{
				$id = $this->input->post('product_id');
				$this->load->library('form_validation');
				$this->form_validation->set_rules('product_name', 'Product Name', 'required');
				$this->form_validation->set_rules('product_price', 'Product Price', 'required');
				$this->form_validation->set_rules('product_description', 'Product Description', 'required');
			   //$this->form_validation->set_rules('product_image', 'Product Image', 'required');
			
					if ($this->form_validation->run() == True)
					{
						  $data = [];
			 
				  $count = count($_FILES['product_image']['name']);
 
				  for($i=0;$i<$count;$i++){

					if(!empty($_FILES['product_image']['name'][$i])){
 
					  $_FILES['file']['name'] = $_FILES['product_image']['name'][$i];
					  $_FILES['file']['type'] = $_FILES['product_image']['type'][$i];
					  $_FILES['file']['tmp_name'] = $_FILES['product_image']['tmp_name'][$i];
					  $_FILES['file']['error'] = $_FILES['product_image']['error'][$i];
					  $_FILES['file']['size'] = $_FILES['product_image']['size'][$i];

					  $config['upload_path'] = 'uploads/'; 
					  $config['allowed_types'] = 'jpg|jpeg|png|gif';
					  $config['max_size'] = '5000'; // max_size in kb
					  $config['file_name'] = $_FILES['product_image']['name'][$i];
 		
					  $this->load->library('upload',$config); 
 
					  if($this->upload->do_upload('file')){
						$uploadData = $this->upload->data();
			
						$filename = $uploadData['file_name'];
			
					  $product_image[] = $filename;
					  }
					}
 
				  }
	   
						$data['product_image']= implode(',', $product_image);
			
						$data['product_name']=$this->input->post('product_name');
						$data['product_price']=$this->input->post('product_price');
						$data['product_description']=$this->input->post('product_description');
						if($id !=''){
							$this->product->update('products',$id,$data);
							$response['status']=1;
							$response['message']="<div class='alert alert-success'>Record has been updated succesfully</div>";
						}else{

			
						$this->product->insert('products',$data);
						$response['status']=1;
						$response['message']="<div class='alert alert-success'>Record has been added succesfully</div>";
						}
		
			
					}
					else
					{
						$response['status']=0;
						$response['product_name']=strip_tags(form_error('product_name'));
						$response['product_price']=strip_tags(form_error('product_price'));
						$response['product_description']=strip_tags(form_error('product_description'));
						$response['product_image']=strip_tags(form_error('product_image'));
		    
					} 
					echo json_encode($response);
				}
			}

  ?>

