<div id="content">
    <h1><?=$title_info;?></h1>
	
	<?php echo validation_errors(); ?>
	<div id="content_padded">

		<?php echo form_open('user/newuser'); ?>

			<table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
				<tbody>
					<tr>
						<td class="fieldlabel">User Name</td>
						<td class="fieldarea">
							<input type="text" size="40" name="username" class="input-text" value="<?php echo set_value("username") ?>" />
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">First Name</td>
						<td class="fieldarea">
							<input type="text" size="40" name="firstname" class="input-text" value="" />
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Last Name</td>
						<td class="fieldarea">
							<input type="text" size="40" name="lastname" class="input-text" value="" />
						</td>
					</tr>
					<tr>
						<td width="15%" class="fieldlabel">Email Address</td>
						<td class="fieldarea">
							<input type="text" size="40" name="useremail" class="input-text" value="" />
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Password</td>
						<td class="fieldarea">
							<input type="password" size="40" name="password" class="input-text" value="" />
						</td>
					</tr>
					<tr>
						<td width="15%" class="fieldlabel">Confirm Password</td>
						<td class="fieldarea">
							<input type="password" size="40" name="confirmpassword" class="input-text" value="" />
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Location list</td>
						<td class="fieldarea">
							<table width="100%">
								
								<?=$locationlist;?>
								
							<tbody>
						</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Permission list</td>
						<td class="fieldarea">
							<table width="100%">
							<tbody>
								<tr><td width="25%">
									<input type="checkbox" name="category[]" value="cid_117" id="cid_117">
									<label for="cid_117">Create user</label>
								</td><td width="25%">
									<input type="checkbox" name="category[]" value="cid_118" id="cid_118">
									<label for="cid_118">Edit User</label>
								</td><td width="25%">
									<input type="checkbox" name="category[]" value="cid_119" id="cid_119">
									<label for="cid_119">Delete User</label>
								</td>
								</tr>
								<tr>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_132" id="cid_132">
									<label for="cid_132">Create Category</label>
								</td>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_142" id="cid_142">
									<label for="cid_142">Edit Category</label>
								</td>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_123" id="cid_123">
									<label for="cid_123">Delete Category</label>
								</td>
								</tr>
								<tr>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_132" id="cid_132">
									<label for="cid_132">Create Model</label>
								</td>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_142" id="cid_142">
									<label for="cid_142">Edit Model</label>
								</td>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_123" id="cid_123">
									<label for="cid_123">Delete Model</label>
								</td>
								</tr>
								<tr>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_132" id="cid_132">
									<label for="cid_132">Create Inventory</label>
								</td>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_142" id="cid_142">
									<label for="cid_142">Edit Inventory</label>
								</td>
								<td width="25%">
									<input type="checkbox" name="category[]" value="cid_123" id="cid_123">
									<label for="cid_123">Delete Inventory</label>
								</td>
								</tr>
						</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td width="15%" class="fieldlabel">Active</td>
						<td class="fieldarea">
							<input type="checkbox" id="useractive" name="useractive">
						</td>
					</tr>
				</tbody>
			</table>

		<p align="center"><input type="submit" class="button" value="Submit"></p>

		</form>

	</div>
</div>