	$(document).ready(function(){

		// Add/Remove Attribute Script
		const maxField   = 10;
		const wrapper    = $('.field_wrapper');
		const addButton  = '.add_button';
		const removeTmpl = '<a href="javascript:void(0);" class="btn btn-sm btn-danger remove_button" title="Remove row"><i class="fas fa-minus"></i></a>';
		
		$(document).on('click', addButton, function (e) {
			e.preventDefault();
			if (wrapper.find('.attribute-row').length >= maxField) return;
			const row = $(this).closest('.attribute-row').clone();
			row.find('input').val('');               // clear values
			row.find(addButton).replaceWith(removeTmpl);
			wrapper.append(row);
		});
		
		wrapper.on('click', '.remove_button', function (e) {
			e.preventDefault();
			$(this).closest('.attribute-row').remove();
		});

	
		// ✅ Check Admin Password is correct or not
		$("#current_pwd").keyup(function(){
			var current_pwd = $("#current_pwd").val();
			$.ajax({
				headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
				type:'post',
				url: '/admin/verify-password',
				data:{current_pwd:current_pwd},
				success:function(resp){
					if(resp == "false"){
						$("#verifyPwd").html("<font color='red'>Current Password is incorrect</font>");
					} else if(resp == "true"){
						$("#verifyPwd").html("<font color='green'>Current Password is correct</font>");
					}
				},
				error:function(){
					alert("Error");
				}
			});
		});

		$(document).on('click','#deleteProfileImage',function(){
		    if (confirm('Are you sure you want to remove your Profile Image?')) {
		        var admin_id = $(this).data('admin-id');
		        $.ajax({
		            headers: {
		                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		            },
		            type:'post',
		            url:'delete-profile-image',
		            data:{admin_id:admin_id},
		            success:function(resp){
		                if(resp['status'] == true){
		                    alert(resp['message']);
		                    $('#profileImageBlock').remove();
		                }
		            },error:function(){
		                alert("Error occurred while deleting the image.");
		            }
		        });
		    }
		});

		// Update Subadmin Status
		$(document).on("click", ".updateSubadminStatus", function() {
		    var status = $(this).children("i").data("status");
		    var subadmin_id = $(this).data("subadmin_id");
				$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        type: 'post',
		        url: '/admin/update-subadmin-status',
		        data: { status: status, subadmin_id: subadmin_id },
		        success: function(resp) {
		            if (resp['status'] == 0) {
		                $("a[data-subadmin_id='" + subadmin_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
		            } else if (resp['status'] == 1) {
		                $("a[data-subadmin_id='" + subadmin_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
		            }
		        },
		        error: function() {
		            alert("Error");
		        }
		    });
		});

		// Update Category Status
		$(document).on("click", ".updateCategoryStatus", function () {
		    var status = $(this).find("i").data("status");
		    var category_id = $(this).data("category-id");
			$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        type: 'post',
		        url: '/admin/update-category-status',
		        data: { status: status, category_id: category_id },
		        success: function (resp) {
		            if (resp['status'] == 0) {
		                $("a[data-category-id='" + category_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
		            } else if (resp['status'] == 1) {
		                $("a[data-category-id='" + category_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
		            }
		        },
		        error: function () {
		            alert("Error");
		        }
		    });
		});

		// Update Product Status
		$(document).on("click", ".updateProductStatus", function () {
		    var status = $(this).find("i").data("status");
		    var product_id = $(this).data("product-id");
			$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        type: 'post',
		        url: '/admin/update-product-status',
		        data: { status: status, product_id: product_id },
		        success: function (resp) {
		            if (resp['status'] == 0) {
		                $("a[data-product-id='" + product_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
		            } else if (resp['status'] == 1) {
		                $("a[data-product-id='" + product_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
		            }
		        },
		        error: function () {
		            alert("Error");
		        }
		    });
		});

		// Update Attribute Status
		$(document).on("click", ".updateAttributeStatus", function () {
		    var status = $(this).find("i").data("status");
		    var attribute_id = $(this).data("attribute-id");
			$.ajax({
		        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		        type: 'post',
		        url: '/admin/update-attribute-status',
		        data: { status: status, attribute_id: attribute_id },
		        success: function (resp) {
		            if (resp['status'] == 0) {
		                $("a[data-attribute-id='" + attribute_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
		            } else if (resp['status'] == 1) {
		                $("a[data-attribute-id='" + attribute_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
		            }
		        },
		        error: function () {
		            alert("Error");
		        }
		    });
		});

		// Update Brand Status
		$(document).on("click", ".updateBrandStatus", function () {
			var status = $(this).find("i").data("status");
			var brand_id = $(this).data("brand-id");
				$.ajax({
			        headers: {
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			        },
			        type: 'post',
			        url: '/admin/update-brand-status',
			        data: { status: status, brand_id: brand_id },
			        success: function (resp) {
			            if (resp['status'] == 0) {
			                $("a[data-brand-id='" + brand_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
			            } else if (resp['status'] == 1) {
			                $("a[data-brand-id='" + brand_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
			            }
			        },
			        error: function () {
			            alert("Error");
			        }
			 });
		});



		$(document).on('click','#deleteCategoryImage',function(){
		    if (confirm('Are you sure you want to remove this Category Image?')) {
		        var category_id = $(this).data('category-id');
		        $.ajax({
		            headers: {
		                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		            },
		            type:'post',
		            url:'/admin/delete-category-image',
		            data:{category_id:category_id},
		            success:function(resp){
		                if(resp['status'] == true){
		                    alert(resp['message']);
		                    $('#categoryImageBlock').remove();
		                }
		            },error:function(){
		                alert("Error occurred while deleting the image.");
		            }
		        });
		    }
		});

		$(document).on('click','#deleteSizeChartImage',function(){
		    if (confirm('Are you sure you want to remove this Size Chart Image?')) {
		        var category_id = $(this).data('category-id');
		        $.ajax({
		            headers: {
		                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		            },
		            type:'post',
		            url:'/admin/delete-sizechart-image',
		            data:{category_id:category_id},
		            success:function(resp){
		                if(resp['status'] == true){
		                    alert(resp['message']);
		                    $('#sizechartImageBlock').remove();
		                }
		            },error:function(){
		                alert("Error occurred while deleting the image.");
		            }
		        });
		    }
		});

		/*$(".confirmDelete").click(function() {
		    var name = $(this).attr('name');
		    if (confirm('Are you sure to delete this ' + name + '?')) {
		        return true;
		    }
		    return false;
		});*/

		$(document).on("click", ".confirmDelete", function (e) {
		    e.preventDefault();
	
		    let button = $(this);
		    let module = button.data("module");
		    let moduleid = button.data("id");
		    let form = button.closest("form");
		    let redirectUrl = "/admin/delete-" + module + "/" + moduleid;
	
		    Swal.fire({
		        title: 'Are you sure?',
		        text: "You won't be able to revert this!",
		        icon: 'warning',
		        showCancelButton: true,
		        confirmButtonColor: '#3085d6',
		        cancelButtonColor: '#d33',
		        confirmButtonText: 'Yes, delete it!'
		    }).then((result) => {
		        if (result.isConfirmed) {
		            // Check if form exists AND has delete route
		            if (form.length > 0 && form.attr("action") && form.attr("method") === "POST") {
		                // Create and append hidden _method input if not present
		                if (form.find("input[name='_method']").length === 0) {
		                    form.append('<input type="hidden" name="_method" value="DELETE">');
		                }
		                form.submit();
		            } else {
		                // Use redirect if no delete form present
		                window.location.href = redirectUrl;
		            }
		        }
		    });
		});



	});
