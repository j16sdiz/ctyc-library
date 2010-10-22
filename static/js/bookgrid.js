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
