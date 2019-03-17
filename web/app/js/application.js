function submitForm(form){
	var submit = form.find(":submit");
	var previousValue = submit.html();
	submit.attr("disabled","true");
	submit.html("Working <i class='fa fa-cog fa-spin'></i>");
	$.ajax({
		type: 'POST',
		url: form.attr("action"),
		data: form.serialize(),
		dataType: 'json',
		success: function(data) {
			if (data.success) {
				if (data.goURL) {
					location.replace(data.goURL);
				}
				if (data.reload) {
					location.reload(true);
				}
				if (typeof data.showClass != 'undefined') {
					$('.' + data.showClass).removeClass('hidden');
					$('.' + data.showClass).show();
				}
				if (data.message) {
                    form.find('#message').html("<div class='message message-success'>" + data.message + "</div>");
					submit.html(previousValue);
					submit.removeAttr("disabled");
					if (!data.noReset) {
						form[0].reset();
					}
                    
				}
			} else {
			    if (data.goURL) {
					location.replace(data.goURL);
				}
				submit.html(previousValue);
				submit.removeAttr("disabled");
				form.find('#message').hide();
				form.find('#message').html("<div class=\" message message-error\">"+data.message+"</div>");
				form.find('#message').fadeIn();
			}
		},
		error: function() {
			submit.html(previousValue);
			submit.removeAttr("disabled");
		}
	});
	return false;
}