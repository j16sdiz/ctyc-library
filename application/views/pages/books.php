<script type="text/javascript" charset="utf-8"> 
var oTable;

function fillDetails( nTr, aData ) {
	nTr.addClass('details');
	var cell = $('td', nTr);
	$("<td></td>").insertBefore(cell);
	cell.attr('colspan', cell.attr('colspan') - 1);
	cell.append("<div class='center ui-state-active' style='width:100%'> Loading ... </div>");
	
	$.ajax({
		url: '<?php echo URL::base() ?>json/getdetail/' + aData[1],
		dataType: 'json',
		success: function(data) {
			cell.empty();
			for (row in data) {
				var detailRow = $('<div class="ui-state-active detail-box"></div>');

				var detailCode = $('<span class="detail-code"></span>');
				detailCode.text(data[row].code + "-" + data[row].copy);
				detailRow.append(detailCode);

				var detailTitle = $('<span class="detail-title"></span>');
				detailTitle.text(data[row].name );
				detailRow.append(detailTitle);

				var detailEdition = $('<span class="detail-edition"></span>');
				detailEdition.text(data[row].version);
				detailRow.append(detailEdition);

				var detailAuthor = $('<span class="detail-author"></span>');
				detailAuthor.text(data[row].author );
				detailRow.append(detailAuthor);

				var detailPublisher = $('<span class="detail-publisher"></span>');
				detailPublisher.text(data[row].publisher);
				detailRow.append(detailPublisher);

				cell.append(detailRow);
			}
		},
		error: function(req) {
			cell.empty();
			cell.append("<div class='center ui-state-error' style='width:100%'> Error! </div>");
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
		var
			$this = this, 
			oTimerId = null, 
			sPreviousSearch = null,
			sRealSearch = null;
			anControl = $( 'input', _that.fnSettings().aanFeatures.f );
		
			anControl.unbind( 'keyup' ).bind( 'keyup', function(evt) {
			var $$this = $this;

			if (sPreviousSearch === null ||
				sPreviousSearch != anControl.val() ||
				evt.keyCode == 13 ||
			    anControl.val() == '') {
				window.clearTimeout(oTimerId);
				sPreviousSearch = anControl.val();	
				oTimerId = window.setTimeout(function() {
					if (sRealSearch == null || sRealSearch != anControl.val()) {
						sRealSearch = anControl.val();
						_that.fnFilter( sRealSearch );
					}
				}, (evt.keyCode == 13 || anControl.val() == '') ? 0 : iDelay);
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
		"bAutoWidth" : true,
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
			}, {
				"sClass" : "col-code",
				"aTargets" : [ 1 ]
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
	oTable.fnSetFilteringDelay(300);
} );
</script>
<table id="booksTable"> 
<thead> 
<tr> 
<th class="col-hdr-id"></th>
<th class="col-hdr-code">Code</th> 
<th class="col-hdr-name">Name</th> 
<th class="col-hdr-author">author</th> 
<th class="col-hdr-publisher">Publisher</th> 
<th class="col-hdr-btn"></th>
</tr> 
</thead> 
<tbody> 
<tr></tr>
</tbody> 
</table> 
