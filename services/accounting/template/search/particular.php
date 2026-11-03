 
<div id="modal_add_category" class="modal " tabindex="-1" data-bs-toggle="modal" data-bs-target="#modal_add_category">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            
          </div>

          <div class="modal-body">
            
<div id="particular_load"></div>
		  </div>
          
		
        </div>
      </div><!-- modal-dialog -->
    </div>
 <button class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#modal_add_category" ><i class="bx bx-plus"></i> Add Category</button><br>
  <table id="particular_detail" class="table">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">Name</th>
                      <th class="wd-20p">Category</th>                     
					 <th class="wd-20p">Account</th>
					 <th class="wd-20p">Edit</th>
					 <th class="wd-20p">Delete</th>
                  
	
                     
                  
                  </tr>
              </thead>
              
            </table>
 <script>
	$(document).ready(function(){
		my_id="";
	var table = $('#particular_detail').DataTable({
				'stripeClasses':[],
                'processing': true,
				 'columnDefs': [{ width: '30%', targets: 0 }],
				   "language": {
	     searchPlaceholder: "Search....",
        search: "",
     processing: ''
},
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch/fetch_particular_detail.php',
								"data": {
        "my_id":my_id
       
    }
                },
				


				'columns': [
                    
				
					                 

                      { data: 'name' },
                      { data: 'category' },
					{ data: 'account' },
					{ data: 'edit' },
					{ data: 'delete' },
				
                   
					  
					
				
					  
					
					
              
                ],
				"lengthMenu": [[20, 350, 450, -1], [300, 325, 450, "All"]],
				dom: 'frtip'
			
            });
						Table.buttons('.export').remove();

            });
	</script>