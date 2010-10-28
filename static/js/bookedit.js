function handleBookEdit(grid, rowIndex, colIndex) {
	var sm = grid.getSelectionModel();
	sm.selectRow(rowIndex);

	var row = grid.getView().getRow(rowIndex);
	var store = grid.getStore();
	var rec = store.getAt(rowIndex);

	var formPanel = new Ext.form.FormPanel(
	{
		baseCls: 'x-plain',
		labelWidth: 85,
		defaultType: 'textfield',
		items: [
			{ fieldLabel: 'Code', name: 'code', anchor:'100%', xtype: 'displayfield'},
			{ fieldLabel: 'Title', name: 'title', anchor:'100%'},
			{ fieldLabel: 'Author', name: 'author', anchor:'100%'},
			{ fieldLabel: 'Publisher', name: 'publisher', anchor:'100%'},
			{ fieldLabel: 'Publish Date', name: 'publishDate', xtype: 'datefield', format: 'Y-m-d'},
			{ fieldLabel: 'Edition', name: 'edition', anchor:'100%'},
			{ fieldLabel: 'ISBN', name: 'isbn', anchor:'100%'}
		]
	});
	var form = formPanel.getForm();
	form.loadRecord(rec);
	form.findField('code').setValue(rec.data['cat'] + rec.data['code'] + '-' + rec.data['copy']);

	var win = new Ext.Window({
		plain: true,
		bodyStyle: 'padding:5px',
		animateTarget: row,
		modal: true,
		layout: "fit",
		width: 500,
		height: 350,
		minWidth: 300,
		minHeight: 350,
		title: "Title - " + rec.data.title,
		items: formPanel,
buttons: [  { text: 'Save', iconCls: 'icon-save', handler: function() {
form.updateRecord(rec);
formPanel.ownerCt.close();
} } ]
	});
	win.show();
};
