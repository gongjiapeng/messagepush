jQuery(function($) {
	$('table th input:checkbox').on('click' , function(){
		var that = this;
		$(this).closest('table').find('tr > td:first-child input:checkbox')
		.each(function(){
			this.checked = that.checked;
			$(this).closest('tr').toggleClass('selected');
		});	
	});

	$(".save-btn").click(function(){  
		$.ajax({  
		   type: "POST",  
		   url: $('#ajaxform').attr('action'),  
		   data: $('#ajaxform').serializeArray(),  
		   dataType: 'json',  
		   success: function(res){  
		   	console.log(res);
			   	if(res.code==0){
			   		alert(res.message);
			   		location.href=res.url;
			   	}else{
			   		alert(res.message);
			   	}
		   	} 
		}); 
	}); 
	$(".remove-tmp").click(function(argument) {
		var id = $(this).attr('data-value');
		var url = $(this).attr('data-href');
		var _token = $(this).attr('data-tocken');
		//alert(url);
		if(confirm('确认要删除吗？')){
			$.ajax({  
			   	type: "POST",  
			   	url: url,  
			   	data: 'id='+id+'&_token='+_token,
			   	dataType: 'json',  
			   	success: function(res){  
			   	console.log(res);
				   	if(res.code==0){
				   		alert(res.message);
				   		location.href=res.url;
				   	}else{
				   		alert(res.message);
				   	}
			   	} 
			}); 
		}else{
			return false;
		}
	});
})