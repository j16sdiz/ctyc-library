<div id="addbookformwrap">
	<form id="addbookform" action="#">
		<fieldset>
			<legend>This is my form</legend>
			<ul>
				<li><label for="cat">Category</label> <select name="cat" id="cat">
					<option value="">Please Select...</option>
					<option value="A">A - Bible OT/NT</option>
					<option value="B">B - Bible OT</option>
					<option value="C">C - Bible NT</option>
				</select>
				<li><label for="isbn">ISBN</label> <input type="text" id="isbn" name="isbn" /></li>
				<li><label for="title">Book Title</label> <input type="text" id="title" name="title" /></li>
				<li><label for="author">Author</label> <input type="text" id="author" name="author" /></li>
				<li><label for="publisher">Publisher</label> <input type="text" id="publisher" name="publisher" /></li>
				<li><label for="publishDate">Publish Date</label> <input type="text" id="publishDate" name="publishDate" /></li>
				<li><label for="edition">Edition</label> <input type="text" id="edition" name="edition" /></li>
				<li><label for="quantity">Quantity</label> <input type="text" id="quantity" name="quantity" /></li>
			</ul>
			<fieldset>
			</form>
		</div>
		<?php echo HTML::script("static/js/jquery.validate.js"); ?>
		<?php echo HTML::script("static/js/jquery.dump.js"); ?>
		<?php echo HTML::script("static/js/addbook.js"); ?>
