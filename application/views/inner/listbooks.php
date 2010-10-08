<script type="text/javascript" charset="utf-8"> 
var oTable;

function select(item) {
	alert(item.name);
}
$(document).ready(function() {
	$('#booksTable').dataTable( {
		"aaSorting": [ [1, 'asc'] ],
		"sDom": '<"H"lrf>t<"F"ip>',
		"fnRowCallback":
			function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) 
			{
				$('input.selbox', nRow).change(function(obj)
					{
						if (obj.target.checked)
							$(nRow).addClass('ui-state-highlight');
						else
							$(nRow).removeClass('ui-state-highlight');
					}
				);
				return nRow;
			},
		"aoColumnDefs": [ 
			{ 
				"fnRender": function(id) {
					return "<input name='s[]' value='"+id.aData[0]+"' type='checkbox' class='selbox' />";
				}, 
				"bSortable" : false,
				"bSearchable" : false,
				"aTargets": [ 0 ] 
			}, {
				"sClass": "center",
				"aTargets": [ 0, 5 ]
			}
		],
		"bAutoWidth": true,
		"bInfo": true,
		"bJQueryUI": true,
		"bProcessing": true,
		"bServerSide": true,
		"sPaginationType": "full_numbers",
		"sAjaxSource": '<?php echo URL::base() ?>json/list',
	} );
	oTable = $('#booksTable').dataTable();
} );
</script>
<div id="dynamic"> 
<table class="display" id="booksTable"> 
<thead> 
<tr> 
<th width="0"></th>
<th width="9%">Code</th> 
<th width="45%">Name</th> 
<th width="20%">Author</th> 
<th width="15%">Publisher</th> 
<th width="11%">PubDate</th> 
<th>S</th>
</tr> 
</thead> 
<tbody> 
<tr></tr>
</tbody> 
</table> 
</div> 
<div class="spacer"></div> 
