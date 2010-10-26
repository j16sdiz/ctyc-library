Ext.ns('Ext.CTYC')
Ext.CTYC.BookGrid = Ext.extend(Ext.grid.GridPanel, {
	trackMouseOver: false,
	loadMask: true,
	trackMouseOver: true,

	// grid columns
	columns: [{
		id: 'id',
		header: 'id',
		dataIndex: 'id',
		sortable: true,
		hidden: true,
		hideable: false	 // hack
	},
	{
		id: 'code',
		header: 'code',
		dataIndex: 'code',
		sortable: true,
		xtype: 'templatecolumn',
		tpl: '{cat}{code}-{copy}'
	},
	{
		id: 'title',
		header: 'title',
		dataIndex: 'title',
		sortable: true,
		editor: {
			xtype: 'textfield'
		}
	},
	{
		id: 'author',
		header: 'author',
		dataIndex: 'author',
		sortable: true,
		editor: {
			xtype: 'textfield'
		}
	},
	{
		id: 'publisher',
		header: 'publisher',
		dataIndex: 'publisher',
		sortable: true,
		editor: {
			xtype: 'textfield'
		}
	},
	{
		id: 'edition',
		header: 'edition',
		dataIndex: 'edition',
		sortable: true,
		editor: {
			xtype: 'textfield'
		}
	},
	{
		id: 'isbn',
		header: 'isbn',
		dataIndex: 'isbn',
		sortable: true,
		editor: {
			xtype: 'textfield'
		}
	},
	{
		id: 'status',
		header: 'status',
		dataIndex: 'status',
		sortable: true,
		editor: {
			xtype: 'textfield'
		}
	},
	{
		id: 'publishDate',
		header: 'publishDate',
		dataIndex: 'publishDate',
		sortable: true,
		renderer: Ext.util.Format.dateRenderer('Y-m-d'),
		editor: {
			xtype: 'datefield'
		}
	},
	{
		id: 'col-act',
		xtype: 'actioncolumn',
		items: [{
			iconCls: 'icon-book-edit',
			tooltip: 'Edit',
			handler:  function(grid, rowIndex, colIndex) {
				var rec = grid.getStore().getAt(rowIndex);
				console.log(rec);
				try {
					handleBookEdit(grid, rowIndex, colIndex);
				} catch (err) {
					console.warn(err);
				}
			}
		}],
		width: 28,
		fixed: true,
		menuDisabled: true,
		sortable: false,
		//hidden: true,
		hideable: false	 // hack
	}],

	viewConfig: {
		autoFill: true,
		forceFit: true
	}
});
Ext.reg('x-ctyc-bookgrid', Ext.CTYC.BookGrid);
