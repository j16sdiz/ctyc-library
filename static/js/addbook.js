var marc;
var libraries = ['library.cuhk.edu.hk', // CU
'158.182.30.1', // BU
'edlis.ied.edu.hk', // IEd
'lnclib.ln.edu.hk', // LU
'library.hku.hk', // HKU
'lib.cityu.edu.hk', // City
'library.polyu.edu.hk', // PolyU
// DEAD	'nbinet.ncl.edu.tw',	// Taiwan Cent Lib
'ustlib.ust.hk', // UST
'library.dts.edu', // Dallas Theological Seminary (2200/unicon)
'fulleripac.fuller.edu', // Fuller Theological Seminary (210/Horizon)
'library.ggbts.edu', // Golden Gate Baptist Theological Seminary (7090/Voyager)
'library.bu.edu' // Boston U
];
var ajaxReq = [];
$(document).ready(function() {
	$("#publishDate").datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		autoSize: true,
		showOn: 'button'
	});
	$.validator.addMethod("isbn", function(isbn, elem) {
		if (validateISBN(isbn, elem)) {
			$.each(ajaxReq, function() {
				this.abort();
			});
			ajaxReq = [];
			marc = {
				"publishDate": [],
				"edition": [],
				"author": [],
				"title": [],
				"publisher": []
			};
			$.each(libraries, function(idx, val) {
				var req =
				$.ajax({
					url: '/library/json/z3950/' + val + '/' + $(elem).val(),
					dataType: 'json',
					success: function(data) {
						if (data != null) {
							$.each(data, function(idx2, val2) {
								$.each(val2, function(idx3, val3) {
									marc[idx2].push(val3);
								});
								marc[idx2].sort();
								marc[idx2] = $.unique(marc[idx2]);
							});
						}
					},
					error: function() {}
				});
				ajaxReq.push(req);
			});

			return true;
		} else {
			return false;
		}
	},
	"error");
	var validator = $("#addbookform").validate({
		onblur: true,
		onchange: true,
		rules: {
			cat: "required",
			isbn: "isbn",
			title: "required"
		},
		messages: {
			cat: {
				required: "meow"
			},
			isbn: {
				isbn: "Invalid ISBN"
			}
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
		source: function(search, callback) {
			if (marc && marc[this.element.attr('id')]) callback(marc[this.element.attr('id')]);
		},
		minLength: 0,
		delay: 100
	});
});

// This functions takes a string containing
// an ISBN (ISBN-10 or ISBN-13) and returns
// true if it's valid or false if it's invalid.
function validateISBN(isbn, elem) {
	if (isbn.match(/[^0-9xX\.\-\s]/)) {
		return false;
	}

	isbn = isbn.replace(/[^0-9xX]/g, '');

	if (isbn.length != 10 && isbn.length != 13) {
		return false;
	}
	if (isbn.length == 0) return true;

	var ret;
	var checkDigit = 0;
	if (isbn.length == 10) {
		checkDigit = 11 - ((
		10 * isbn.charAt(0) + 9 * isbn.charAt(1) + 8 * isbn.charAt(2) + 7 * isbn.charAt(3) + 6 * isbn.charAt(4) + 5 * isbn.charAt(5) + 4 * isbn.charAt(6) + 3 * isbn.charAt(7) + 2 * isbn.charAt(8)) % 11);

		if (checkDigit == 10) {
			ret = (isbn.charAt(9) == 'x' || isbn.charAt(9) == 'X') ? true : false;
		} else {
			ret = (isbn.charAt(9) == checkDigit ? true : false);
		}
	} else {
		checkDigit = 10 - ((
		1 * isbn.charAt(0) + 3 * isbn.charAt(1) + 1 * isbn.charAt(2) + 3 * isbn.charAt(3) + 1 * isbn.charAt(4) + 3 * isbn.charAt(5) + 1 * isbn.charAt(6) + 3 * isbn.charAt(7) + 1 * isbn.charAt(8) + 3 * isbn.charAt(9) + 1 * isbn.charAt(10) + 3 * isbn.charAt(11)) % 10);

		if (checkDigit == 10) {
			ret = (isbn.charAt(12) == 0 ? true : false);
		} else {
			ret = (isbn.charAt(12) == checkDigit ? true : false);
		}
	}

	elem = $(elem);
	if (ret && isbn != elem.val() && elem.attr('id') != $(document.activeElement).attr('id')) elem.val(isbn);
	return ret;
}