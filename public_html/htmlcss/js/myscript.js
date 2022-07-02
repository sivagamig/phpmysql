$(document).ready(function(){
	$('#contact_submit').click(function(e){
		$contact_name = $('#contact_name').val();
		$contact_mail = $("#contact_mail").val();
		$contact_comment = $("#contact_comment").val();
		console.log($contact_name);
		console.log($contact_mail);
		console.log($contact_comment);
		if($contact_name=='' && $contact_mail=='' && $contact_comment=='')
		{
			alert("Please fill all fields");
		}
		else{
		 $.ajax({
                        url: 'sendemail.php',
                        type: 'post',
                        data: {
                            "contact_name": contact_name,
							"contact_mail" : contact_mail,
							"contact_comment" : contact_comment
                            
                        },
                        success: function(response) {                           
                            $("#result").html(response);
                            $("#result").fadeOut(3000);
                        }
               });
		}
		
	});
	e.preventDefault();
	 
	
})