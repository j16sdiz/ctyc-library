<div id="loading-mask"></div>
<div id="loading">
  <div class="loading-indicator">
	Loading...
  </div>
</div>

<?php
	echo HTML::script("static/js/bookgrid.js", NULL, TRUE);
	echo HTML::script("static/extjs/latest/examples/ux/SearchField.js", NULL, TRUE);
?>

<script type="text/javascript">
Ext.CTYC.CatSelect = Ext.extend(Ext.form.ComboBox, {
	mode: 'local',
	editable: false,
	triggerAction: 'all',
	store: [['','-- All --'],['A','A - OT / NT'],['B','B - OT'],['C','C - NT']],
	value: '',
	listeners: { 
		select : function(cbx, rec, idx) {
			cbx.mainStore.proxy.api['read'].url = cbx.mainStore.proxy.url + rec.data.field1;
			cbx.mainStore.reload();
		}
	}
} );
Ext.reg('x-ctyc-catselect', Ext.CTYC.CatSelect);
	
Ext.onReady(function() {
	// create the Data Store
	var store = new Ext.data.JsonStore({
		root: 'books',
		totalProperty: 'totalBooks',
		idProperty: 'id',
		restful: true,
		remoteSort: true,

		fields: ['id', 'cat', 'code', 'copy', 'title', 'author', 'publisher', 'edition', 'isbn', 'status', {
			name: 'publishDate',
			type: 'date',
			dateFormat: 'Y-m-d'
		}],

		url: 'http://ctyc.dyndns.org/library/api/book/',
		writer: new Ext.data.JsonWriter()
	});
	store.setDefaultSort('code', 'asc');
	store.load({
		params: {
			start: 0,
			limit: 20
		}
	});

	var viewport = new Ext.Viewport({
		layout: 'border',
		items: [{
			region: 'north',
			html: "1234"
		},
		{
			id: 'bookpanel',
			margins: '0 5 5 5',
			region: 'center',
			store: store,
			xtype: 'x-ctyc-bookgrid',
			tbar: [
			'Cat:',
			{ xtype: 'x-ctyc-catselect', mainStore: store },
			'-',
			'Search:',
			new Ext.ux.form.SearchField({
				store: store,
				width: 300
			})],
			bbar: new Ext.PagingToolbar({
				displayInfo: true,
				displayMsg: 'Displaying topics {0} - {1} of {2}',
				emptyMsg: "No topics to display",
				pageSize: 20,
				store: store
			}),
			buttonAlign: 'left',
			fbar: [{
				text: 'fbar Left'
			},
			'->', {
				text: 'fbar Right'
			}]
		}]
	});

	setTimeout(function() {
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({
			remove: true
		});
	},
	250);
});
</script>
