function handleBookEdit(grid, rowIndex, colIndex) {
	var sm = grid.getSelectionModel();
	sm.selectRow(rowIndex);

	var row = grid.getView().getRow(rowIndex);
	var store = grid.getStore();
	var rec = store.getAt(rowIndex);

	var form = new Ext.form.FormPanel(
	{
		items: [
			{ xtype: 'textfield', fieldLabel: 'Label 1', name: 'title'},
			{ xtype: 'textfield', fieldLabel: 'Label 2', name: 'author'}
		] ,
buttons: [  { text: 'Save', iconCls: 'icon-save', handler: function() {
form.getForm().updateRecord(rec);
form.ownerCt.close();
} } ]
	});
	form.getForm().loadRecord(rec);

	var win = new Ext.Window({
		animateTarget: row,
		modal: true,
		layout: "fit",
		title: "Title - " + rec.data.title,
		items: [ form ]
	});
	win.show();
};
