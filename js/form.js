// When #contactForm is submitted when send a post request and #contactNoti is displayed in contact.php
$(document).ready(function(){
	placeHolder();
	autoComplete();
	validateForms();
});

function validateForms(){
	$("#authorForm").on('submit', function(){
		var errorMsg  	= "";
		var formInfo  	= $(this).serialize();
		var name  		= $("[name='name']").val();
		var category_id = $("[name='category_id']").val();
		var title_id 	= $("[name='title_id']").val();
		// var img_url		= $("[name='image url']").val().replace(/\s/g,"");
		var img_url		= '';
		var biography  	= $("[name='biography']").val();
		var info      	= {"name":name, "image url":img_url, "biography":biography, "category_id":category_id, "title_id":title_id };
		var url 		= "http://www.bennettl.com/inspyr/author/";
		// If validateFields has a return value, then there is an errorMsg
		if (validateAuthor(info)){
			errorMsg = validateAuthor(info);
			$("#authorForm .errorMsg").text(errorMsg).css('color','red').show();
		} else{
			$.post(url,formInfo);
			$("[name='name']").val('Name').addClass('inactive');
			$("[name='image url']").val('Image url').addClass('inactive');
			$("[name='biography']").val('Biography').addClass('inactive');
			$("#authorForm .errorMsg").text("Author Submitted!").css('color','#4086a3').show();
		} 
		return false;
	});

	$("#quoteForm").on('submit', function(){
		var errorMsg  = "";
		var formInfo  = $(this).serialize();
		var author    = $("[name='author']").val();
		var quote  	  = $("[name='quote']").val();
		var info      = {"name":name, "author":author, "quote":quote};
		var url 	  = "http://www.bennettl.com/inspyr/quote/";
		// If validateFields has a return value, then there is an errorMsg
		if (validateQuote(info)){
			errorMsg = validateQuote(info);
			$("#quoteForm .errorMsg").text(errorMsg).css('color','red').show();
		} else{
			$.post(url,formInfo);
			$("[name='author']").val('Author').addClass('inactive');
			$("[name='quote']").val('Quote').addClass('inactive');
			$("#quoteForm .errorMsg").text("Quote Submitted!").css('color','#4086a3').show();
		} 
		return false;
	});
}
	
function autoComplete(){
	$("[name='author']").autocomplete({
		source: function ( request, response ) {
       		$.ajax({
				// get the source asynchronously
	            url: getAutocompleteUrl(),
	            dataType: "json",
	            data: {
	            	//term: request.term,
	            },
	            success: function(data){
	                response( $.map( data.authors, function ( item ) {
	                    return {
	                        label: item.author_name,
	                        value: item.author_id
	                    };
	                }));
	            }
        	});
    	},
    	select: function (event, ui) {
    		// When user clicks
            var v = ui.item.value;
           // $("[name='authorHidden']").val(v);
            this.value = ui.item.label;            // update what is displayed in the textbox
            return false;
        },
        focus: function (event, ui) {
        	// When user hovers
            var v = ui.item.value;
         //   $("[name='authorHidden']").val(v);
           // this.value = ui.item.label;            // update what is displayed in the textbox
            return false;
        },
    	minLength: 2, // user types twice
  		open: function() {
  			// readjust positioning
	        var position = $(".ui-autocomplete").position(),
	          top = position.top;
	        $(".ui-autocomplete").css('top', '-=10px');

        }
	}).keyup(function (e) {
        if(e.which === 13) {
            $(".ui-menu-item").css('display','none');
        }            
    });
}
// Returns the autcomeplete url
function getAutocompleteUrl(){
	var baseUrl   = "http://www.bennettl.com/inspyr/author/autocomplete/";
	var searchStr = $("[name='author']").val();
	return baseUrl + searchStr;
}
// Handles placeholder text
function placeHolder(){
	$(".inputText, textarea").on('focus',function(){
		var $this = $(this);
		var value = $(this).val();
		var name = $(this).attr('name').capitalize();

		// If value is equal to name
	 	if (value == name){ 
			$(this).removeClass('inactive');
	 		$this.val('');
	 	};
	});

	$(".inputText, textarea").on('blur',function(){
		var $this = $(this);
		var value = $(this).val();
		var name = $(this).attr('name').capitalize();
		// If user didnt enter anything on blue
	 	if (value == '') { 
			$(this).addClass('inactive');
	 		$this.val(name);
	 	};
	});
}
// Returns errorMsg if not valid
function validateAuthor(info){
	errorMsg = '';

	// Max length validation
	if (info['name'].toLowerCase() == 'name'){
		errorMsg = "Please enter a name";
	}
	if (info['category_id'] == '-1'){
		errorMsg = "Please select a category id";
	}
	// if (info['image url'].toLowerCase() == 'imageurl'){
	// 	errorMsg = "Please enter an image url";
	// }
	if (info['biography'].toLowerCase() == 'biography'){
		errorMsg = "Please enter a biography";
	}

	// Regex validation for names
	var reg = /^[a-zA-z ]{1,60}$/
	if (reg.test(info['name']) == false){
		errorMsg = "Name must container only letters A-Z.";
	}

	// If there are no error message, return false, else return true
	return (errorMsg == '') ? false : errorMsg;
}

// Returns errorMsg if not valid
function validateQuote(info){
	errorMsg = '';

	// Max length validation
	if (info['author'].toLowerCase() == 'author'){
		errorMsg = "Please enter an author";
	}
	if (info['quote'].toLowerCase() == 'quote'){
		errorMsg = "Please enter a quote";
	}

	// Regex validation for names
	var reg = /^[a-zA-z ]{1,60}$/
	if (reg.test(info['author']) == false){
		errorMsg = "Author Name must container only letters A-Z.";
	}

	// If there are no error message, return false, else return true
	return (errorMsg == '') ? false : errorMsg;
}
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

