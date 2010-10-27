function handleBookEdit(grid, rowIndex, colIndex) {
	var sm = grid.getSelectionModel();
	sm.selectRow(rowIndex);

	var row = grid.getView().getRow(rowIndex);
	var store = grid.getStore();
	var rec = store.getAt(rowIndex);

	var form = new Ext.form.FormPanel(
	{
		baseCls: 'x-plain',
		labelWidth: 55,
		defaultType: 'textfield',
		items: [
			{ fieldLabel: 'Label 1', name: 'title', anchor:'100%'},
			{ fieldLabel: 'Label 2', name: 'author', anchor:'100%'}
		]
	});
	form.getForm().loadRecord(rec);

	var win = new Ext.Window({
		plain: true,
		bodyStyle: 'padding:5px',
		animateTarget: row,
		modal: true,
		layout: "fit",
		width: 500,
		height: 400,
		minWidth: 300,
		minHeight: 200,
		title: "Title - " + rec.data.title,
		items: form,
buttons: [  { text: 'Save', iconCls: 'icon-save', handler: function() {
form.getForm().updateRecord(rec);
form.ownerCt.close();
} } ]
	});
	win.show();
};
