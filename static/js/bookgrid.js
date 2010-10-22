Ext.ns('Ext.CTYC')
Ext.CTYC.BookGrid = Ext.extend(Ext.grid.GridPanel,{
	trackMouseOver:false,
	loadMask: true,

	// grid columns
	columns:[
		{ id: 'id', header: 'id', dataIndex: 'id', sortable: true },
		{ id: 'code', header: 'code', dataIndex: 'code', sortable: true, xtype:'templatecolumn', tpl: '{cat}{code}-{copy}' },
		{ id: 'title', header: 'title', dataIndex: 'title', sortable: true, editor: {xtype: 'textfield'} },
		{ id: 'author', header: 'author', dataIndex: 'author', sortable: true, editor: {xtype: 'textfield'} },
		{ id: 'publisher', header: 'publisher', dataIndex: 'publisher', sortable: true, editor: {xtype: 'textfield'}  },
		{ id: 'edition', header: 'edition', dataIndex: 'edition', sortable: true, editor: {xtype: 'textfield'} },
		{ id: 'isbn', header: 'isbn', dataIndex: 'isbn', sortable: true, editor: {xtype: 'textfield'}  },
		{ id: 'status', header: 'status', dataIndex: 'status', sortable: true, editor: {xtype: 'textfield'}  },
		{ id: 'publishDate', header: 'publishDate', dataIndex: 'publishDate', sortable: true, editor: {xtype: 'datefield'} }
		],

		viewConfig: {
			autoFill: true,
			forceFit: true
		},
});
Ext.reg('x-ctyc-bookgrid', Ext.CTYC.BookGrid);
