'use strict';

;( function (document, window, index) {
	var inputs = document.querySelectorAll( '.choose_file_btn ' );
	Array.prototype.forEach.call(inputs, function(input)
	{
		var label = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener('change', function(e)
		{
			var fileName = '';
			if(this.files && this.files.length > 1)
				fileName = ( this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if(fileName)
				label.querySelector('span').innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener('focus', function() {
			input.classList.add('has-focus'); 
		});
		input.addEventListener('blur', function() {
			input.classList.remove('has-focus'); 
		});
	});
}(document, window, 0));

// var fileobj;
// function upload_file(e) {
//     e.preventDefault();
//     fileobj = e.dataTransfer.files[0];
//     ajax_file_upload(fileobj);
// }
 
// function file_explorer() {
//     document.getElementById('selectfile').click();
//     document.getElementById('selectfile').onchange = function() {
//         fileobj = document.getElementById('selectfile').files[0];
//         ajax_file_upload(fileobj);
//     };
// }
 
// function ajax_file_upload(file_obj) {
//     if(file_obj != undefined) {
//         var form_data = new FormData();                  
//         form_data.append('file', file_obj);
//         $.ajax({
//             type: 'POST',
//             url: '../php/second_hand.php',
//             contentType: false,
//             processData: false,
//             data: form_data,
//             success:function(response) {
//                 // alert(response);
//                 // $('#selectfile').val('');
//             }
//         });
//     }
// }