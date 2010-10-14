<div id="addbookformwrap">
<div style="width:20em; height:30ex; border: 1px solid black; float: right;"></div>
<form id="addbookform" action="#">
<fieldset>
<legend>This is my form</legend>
<ul>
<li><label for="cat">Category</label> <select name="cat" id="cat">
  <option value="">Please Select...</option>
  <option value="A">A - Bible OT/NT</option>
  <option value="B">B - Bible OT</option>
  <option value="C">C - Bible NT</option>
</select>
<li><label for="isbn">ISBN</label> <input type="text" id="isbn" name="isbn" /></li>
<li><label for="title">Book Title</label> <input type="text" id="title" name="title" /></li>
<li><label for="author">Author</label> <input type="text" id="author" name="author" /></li>
<li><label for="publisher">Publisher</label> <input type="text" id="publisher" name="publisher" /></li>
<li><label for="publishDate">Publish Date</label> <input type="text" id="publishDate" name="publishDate" /></li>
<li><label for="edition">Edition</label> <input type="text" id="edition" name="edition" /></li>
<li><label for="quantity">Quantity</label> <input type="text" id="quantity" name="quantity" /></li>
</ul>
<fieldset>
</form>
</div>
<?php echo HTML::script("static/js/jquery.validate.js"); ?>
<script type="text/javascript" charset="utf-8"> 
var marc;

$(document).ready(function() {
	$( "#publishDate" ).datepicker({ dateFormat: 'yy-mm-dd', autoSize: true, showOn: 'button' });
	$.validator.addMethod("isbn", function (isbn, elem) {
		if (validateISBN(isbn, elem)) {
			marc = { title : [] };
			$.ajax({
				url: '<?php echo URL::base() ?>json/z3950/library.cuhk.edu.hk/' + $(elem).val(),
				dataType: 'json',
				success: function(data) {
					marc = data;
				}
			});
				
			return true;
		} else {
			return false;
		}
	}, "error");
	var validator = $("#addbookform").validate({
		onblur: true,
		onchange: true,
		rules: {
			cat: "required",
			isbn: "isbn",
			title: "required"
		},
		messages: {
			cat: { required: "meow" },
			isbn: { isbn: "Invalid ISBN" }
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent());
		},
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp; $$").addClass("checked");
		}
	});


	$('#title, #author, #publisher, #publishDate, #edition').autocomplete({
		source: function (search, callback) {
			callback(marc[this.element.attr('id')]);
		},
		minLength: 0,
		delay: 100
	});
});

// This functions takes a string containing
// an ISBN (ISBN-10 or ISBN-13) and returns
// true if it's valid or false if it's invalid.
function validateISBN(isbn, elem) {
	if(isbn.match(/[^0-9xX\.\-\s]/)) {
		return false;
	}

	isbn = isbn.replace(/[^0-9xX]/g,'');

	if(isbn.length != 10 && isbn.length != 13) {
		return false;
	}
	if (isbn.length == 0) return true;

	var ret;
	var checkDigit = 0;
	if(isbn.length == 10) {
		checkDigit = 11 - ( (
								 10 * isbn.charAt(0) +
								 9  * isbn.charAt(1) +
								 8  * isbn.charAt(2) +
								 7  * isbn.charAt(3) +
								 6  * isbn.charAt(4) +
								 5  * isbn.charAt(5) +
								 4  * isbn.charAt(6) +
								 3  * isbn.charAt(7) +
								 2  * isbn.charAt(8)
								) % 11);

		if(checkDigit == 10) {
			ret = (isbn.charAt(9) == 'x' || isbn.charAt(9) == 'X') ? true : false;
		} else {
			ret = (isbn.charAt(9) == checkDigit ? true : false);
		}
	} else {
		checkDigit = 10 -  ((
								 1 * isbn.charAt(0) +
								 3 * isbn.charAt(1) +
								 1 * isbn.charAt(2) +
								 3 * isbn.charAt(3) +
								 1 * isbn.charAt(4) +
								 3 * isbn.charAt(5) +
								 1 * isbn.charAt(6) +
								 3 * isbn.charAt(7) +
								 1 * isbn.charAt(8) +
								 3 * isbn.charAt(9) +
								 1 * isbn.charAt(10) +
								 3 * isbn.charAt(11)
								) % 10);

		if(checkDigit == 10) {
			ret = (isbn.charAt(12) == 0 ? true : false) ;
		} else {
			ret = (isbn.charAt(12) == checkDigit ? true : false);
		}
	}

	elem = $(elem);
	if (ret && isbn != elem.val() && elem.attr('id') != $(document.activeElement).attr('id'))
		elem.val(isbn);
	return ret;
}
</script>
