<?php

$page_title = "Reporter Profile";
include('includes/header_profile.php');

// Update reporter profile start
if (isset($_POST['btn_submit'])) {

	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['class'] = "warn";
		$_SESSION['msg'] = "invalid_email_format";
	} else {

		$email = cleanInput($_POST['email']);

		$sql = "SELECT * FROM tbl_users WHERE `email` = '$email' AND `id` <> '" . $reporter_id . "'";

		$res = mysqli_query($mysqli, $sql);

		if (mysqli_num_rows($res) == 0) {
			$data = array(
				'name'  =>  cleanInput($_POST['name']),
				'email'  =>  cleanInput($_POST['email']),
				'phone'  =>  cleanInput($_POST['phone'])
			);

			if ($_POST['password'] != "") {

				$password = md5(trim($_POST['password']));

				$data = array_merge($data, array("password" => $password));
			}

			if ($_FILES['profile_img']['name'] != "") {

				if ($user_row['user_profile'] != "" or !file_exists('images/' . $user_row['user_profile'])) {
					unlink('images/' . $user_row['user_profile']);
				}

				$ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);

				$profile_img = rand(0, 99999) . '_' . date('dmYhis') . "_user." . $ext;

				//Main Image
				$tpath1 = 'images/' . $profile_img;

				if ($ext != 'png') {
					$pic1 = compress_image($_FILES["profile_img"]["tmp_name"], $tpath1, 80);
				} else {
					$tmp = $_FILES['profile_img']['tmp_name'];
					move_uploaded_file($tmp, $tpath1);
				}

				$data = array_merge($data, array("user_profile" => $profile_img));
			}

			$user_edit = Update('tbl_users', $data, "WHERE id = '" . $reporter_id . "'");

			$_SESSION['class'] = "success";

			$_SESSION['msg'] = "11";
		} else {
			$_SESSION['class'] = "warn";
			$_SESSION['msg'] = "email_exist";
		}
	}

	header("Location:reporter_profile.php?reporter_id=" . $reporter_id);
	exit;
}
?>

<div class="card-body no-padding tab-content">
	<div role="tabpanel" class="tab-pane active">
		<div class="row">
			<div class="col-md-12">
				<form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
					<div class="section">
						<div class="section-body">
							<div class="form-group">
								<label class="col-md-3 control-label">Name :-</label>
								<div class="col-md-6">
									<input type="text" name="name" id="name" value="<?= $user_row['name'] ?>" class="form-control" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Email :-</label>
								<div class="col-md-6">
									<input type="email" name="email" id="email" value="<?= $user_row['email'] ?>" class="form-control" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Password :-</label>
								<div class="col-md-6">
									<input type="password" name="password" id="password" value="" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Phone :-</label>
								<div class="col-md-6">
									<input type="text" name="phone" id="phone" value="<?= $user_row['phone'] ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Profile Image :-
									<p class="control-label-help">(Recommended resolution: 100x100, 200x200) OR Squre image</p>
								</label>
								<div class="col-md-6">
									<div class="fileupload_block">
										<input type="file" name="profile_img" value="fileupload" accept=".png, .jpg, .jpeg, .svg, .gif" <?php echo (!isset($_GET['reporter_id'])) ? 'required="require"' : '' ?> id="fileupload">
										<div class="fileupload_img">
											<?php
											$img_src = "";
											if (!isset($_GET['reporter_id']) || $user_row['user_profile'] == '') {
												$img_src = 'assets/images/landscape.jpg';
											} else {
												$img_src = 'images/' . $user_row['user_profile'];
											}
											?>
											<img type="image" src="<?= $img_src ?>" alt="image" style="width: 86px;height: 86px" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-9 col-md-offset-3">
									<button type="submit" name="btn_submit" class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- End profile header div -->
</div>
</div>
</div>

<?php
include('includes/footer.php');
?>

<script type="text/javascript">
// Profile image
$("input[name='profile_img']").change(function() {
	var file = $(this);

if (file[0].files.length != 0) {
	if (isImage($(this).val())) {
		render_upload_image(this, $(this).next('.fileupload_img').find("img"));
	} else {
		$(this).val('');
		$('.notifyjs-corner').empty();
		$.notify(
			'Only jpg/jpeg, png, gif and svg files are allowed!', {
				position: "top center",
				className: 'error'
			}
		);
	}
  }
});
</script>