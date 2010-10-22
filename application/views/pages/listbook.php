<div id="loading-mask"></div>
<div id="loading">
  <div class="loading-indicator">
    Loading...
  </div>
</div>

<script type="text/javascript">
Ext.ns('Ext.CTYC')
Ext.CTYC.BookGrid = Ext.extend(Ext.grid.GridPanel,{
			trackMouseOver:false,
			loadMask: true,

			// grid columns
			columns:[
			{ id: 'id', header: 'id', dataIndex: 'id', sortable: true },
			{ id: 'code', header: 'code', dataIndex: 'code', sortable: true, xtype:'templatecolumn', tpl: '{cat}{code}-{copy}' },
				{ id: 'title', header: 'title', dataIndex: 'title', sortable: true },
				{ id: 'author', header: 'author', dataIndex: 'author', sortable: true },
				{ id: 'publisher', header: 'publisher', dataIndex: 'publisher', sortable: true },
				{ id: 'edition', header: 'edition', dataIndex: 'edition', sortable: true },
				{ id: 'isbn', header: 'isbn', dataIndex: 'isbn', sortable: true },
				{ id: 'status', header: 'status', dataIndex: 'status', sortable: true },
				{ id: 'publishDate', header: 'publishDate', dataIndex: 'publishDate', sortable: true }
				],

				viewConfig: {
					autoFill: true,
					forceFit: true
				},
	});
Ext.reg('x-ctyc-bookgrid', Ext.CTYC.BookGrid);

Ext.onReady(function(){
	// create the Data Store
	var store = new Ext.data.JsonStore({
		root: 'books',
			totalProperty: 'totalBooks',
			idProperty: 'id',
			restful: true,
			remoteSort: true,

			fields: [
			'id', 'cat', 'code', 'copy', 'title', 'author', 'publisher', 'edition', 'isbn', 'status',
			{name: 'publishDate', type: 'date', dateFormat: 'Y-m-d' }
			],

			url: 'http://ctyc.dyndns.org/library/api/book/'
	});
	store.setDefaultSort('code', 'asc');
	store.load({params:{start:0, limit:20}});

	var viewport = new Ext.Viewport({
		layout: 'border',
		items: [
			{ region:'north', html: "1234" },
			{
				id: 'bookpanel',
				margins:'0 5 5 5',
				region:'center',
				store: store,
				xtype: 'x-ctyc-bookgrid',
				tbar: [
					'Search:',
					new Ext.form.TextField({
						width: 300,
						emptyText:'Search...',
						enableKeyEvents: true,
						listeners: {
							keypress: {
								scope: this,
								fn: function (o,e) {
									if (e.keyCode == 13) {
										alert(o.getValue());
									}
								}
							},
							scope: this
						}
					})
				],
				bbar: new Ext.PagingToolbar({
					displayInfo: true,
					displayMsg: 'Displaying topics {0} - {1} of {2}',
					emptyMsg: "No topics to display",
					pageSize: 20,
					store: store
				}),
				buttonAlign: 'left',
				fbar: [
				{ text: 'fbar Left' }, '->', { text: 'fbar Right' }
				]
			}
		]
	});

	setTimeout(function(){
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({remove:true});
	}, 250);
});
</script>
