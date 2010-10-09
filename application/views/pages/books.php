<script type="text/javascript" charset="utf-8"> 
var oTable;

function fillDetails( nTr, aData ) {
	nTr.addClass('details');
	var cell = $('td', nTr);
	
	$.ajax({
		url: '<?php echo URL::base() ?>json/getdetail/' + aData[1],
		success: function(data) {
			cell.append(
				'<table class="detailsTable" style="border: 1px solid black">' +
				'<tr>' +
				'<td class="col-id"></td>' +
				'<td class="col-code">A0001-1</td>' +
				'<td class="col-name">Name</td>' +
				'<td class="col-author">author</td>' + 
				'<td class="col-publisher">Publisher</td>' +
				'<td class="col-btn"></td>' +
				'</tr></table>');
				cell.text(data);
		},
		error: function(req) {
			cell.text("Error!");
			cell.addClass('ui-state-error');
			cell.addClass('center');
		}
	});
}

$.fn.dataTableExt.oApi.fnSetFilteringDelay = function ( oSettings, iDelay ) {
	/*
	 * Type:        Plugin for DataTables (www.datatables.net) JQuery plugin.
	 * Name:        dataTableExt.oApi.fnSetFilteringDelay
	 * Version:     2.2.1
	 * Description: Enables filtration delay for keeping the browser more
	 *              responsive while searching for a longer keyword.
	 * Inputs:      object:oSettings - dataTables settings object
	 *              integer:iDelay - delay in miliseconds
	 * Returns:     JQuery
	 * Usage:       $('#example').dataTable().fnSetFilteringDelay(250);
	 * Requires:	  DataTables 1.6.0+
	 *
	 * Author:      Zygimantas Berziunas (www.zygimantas.com) and Allan Jardine (v2)
	 * Created:     7/3/2009
	 * Language:    Javascript
	 * License:     GPL v2 or BSD 3 point style
	 * Contact:     zygimantas.berziunas /AT\ hotmail.com
	 */
	var
		_that = this,
		iDelay = (typeof iDelay == 'undefined') ? 250 : iDelay;
	
	this.each( function ( i ) {
		$.fn.dataTableExt.iApiIndex = i;
		var
			$this = this, 
			oTimerId = null, 
			sPreviousSearch = null,
			anControl = $( 'input', _that.fnSettings().aanFeatures.f );
		
			anControl.unbind( 'keyup' ).bind( 'keyup', function() {
			var $$this = $this;

			if (sPreviousSearch === null || sPreviousSearch != anControl.val()) {
				window.clearTimeout(oTimerId);
				sPreviousSearch = anControl.val();	
				oTimerId = window.setTimeout(function() {
					$.fn.dataTableExt.iApiIndex = i;
					_that.fnFilter( anControl.val() );
				}, iDelay);
			}
		});
		
		return this;
	} );
	return this;
}

$(document).ready(function() {
	$('#booksTable').dataTable( {
		"aaSorting": [ [1, 'asc'] ],
		"sDom": '<"H"lrf>t<"F"ip>',
		"fnRowCallback":
			function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) 
			{
				$('.btn-exp', nRow).click(function(evt) {
					var src = $(evt.srcElement);
					if (src.hasClass('ui-icon-circlesmall-plus')) {
						src.addClass('ui-icon-circlesmall-minus');
						src.removeClass('ui-icon-circlesmall-plus');
						
						var aData = oTable.fnGetData( nRow );
						var newRow = $(oTable.fnOpen( nRow, "", ""));
						fillDetails(newRow, aData);
					} else {
						src.addClass('ui-icon-circlesmall-plus');
						src.removeClass('ui-icon-circlesmall-minus');
						oTable.fnClose( nRow );
					}
				});
				return nRow;
			},
		"aoColumnDefs": [ 
			{ 
				"fnRender": function(id) {
					return "<span class='ui-icon ui-icon-circlesmall-plus btn-exp'>[+]</span>";
				}, 
				"bSortable" : false,
				"bSearchable" : false,
				"aTargets": [ 0 ] 
			}, {
				"sClass": "center",
				"aTargets": [ 0, 5 ]
			}, {
				"fnRender": function(id) {
					return "<span class='ui-icon ui-icon-pencil btn-edit'>Edit</span>";
				}, 
				"bSortable" : false,
				"bSearchable" : false,
				"aTargets": [ 5 ] 
			}
		],
		"bInfo": true,
		"bJQueryUI": true,
		"bProcessing": true,
		"bServerSide": true,
		"sPaginationType": "full_numbers",
		"sAjaxSource": '<?php echo URL::base() ?>json/list'
	} );
	oTable = $('#booksTable').dataTable();
	oTable.fnSetFilteringDelay(500);
} );
</script>
<div id="dynamic"> 
<table class="display" id="booksTable"> 
<thead> 
<tr> 
<th class="col-id"></th>
<th class="col-code">Code</th> 
<th class="col-name">Name</th> 
<th class="col-author">author</th> 
<th class="col-publisher">Publisher</th> 
<th class="col-btn"></th>
</tr> 
</thead> 
<tbody> 
<tr></tr>
</tbody> 
</table> 
</div> 
