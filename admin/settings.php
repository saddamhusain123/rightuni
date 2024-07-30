<?php 
$page_title="Settings";

include("includes/header.php");
require("includes/function.php");
require("language/language.php");

require("includes/check_availability.php");

// Get settings
$qry="SELECT * FROM tbl_settings WHERE `id`='1'";
$result=mysqli_query($mysqli,$qry);
$settings_row=mysqli_fetch_assoc($result);

// Update app settings
if(isset($_POST['submit']))
{

  if($_FILES['app_logo']['name']!="")
  {        

    unlink('images/'.$settings_row['app_logo']);   

    $app_logo=$_FILES['app_logo']['name'];
    $pic1=$_FILES['app_logo']['tmp_name'];

    $tpath1='images/'.$app_logo;      
    copy($pic1,$tpath1);


    $data = array(
      'email_from'  =>  '-',       
      'app_name'  =>  cleanInput($_POST['app_name']),
      'app_logo'  =>  $app_logo,  
      'app_description'  => addslashes($_POST['app_description']),
      'app_version'  =>  cleanInput($_POST['app_version']),
      'app_author'  =>  cleanInput($_POST['app_author']),
      'app_contact'  =>  cleanInput($_POST['app_contact']),
      'app_email'  =>  cleanInput($_POST['app_email']), 
      'app_website'  =>  cleanInput($_POST['app_website']),
      'app_developed_by'  =>  cleanInput($_POST['app_developed_by'])
    );

  }
  else
  {

    $data = array(   
      'email_from'  =>  '-',  
      'app_name'  =>  cleanInput($_POST['app_name']),
      'app_description'  => addslashes($_POST['app_description']),
      'app_version'  =>  cleanInput($_POST['app_version']),
      'app_author'  =>  cleanInput($_POST['app_author']),
      'app_contact'  =>  cleanInput($_POST['app_contact']),
      'app_email'  =>  cleanInput($_POST['app_email']), 
      'app_website'  =>  cleanInput($_POST['app_website']),
      'app_developed_by'  =>  cleanInput($_POST['app_developed_by'])
    );

  } 

  $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

  $_SESSION['msg']="11";
  header( "Location:settings.php");
  exit;

}
// Update admob submit
else if(isset($_POST['admob_submit']))
{

  $data = array(
    'publisher_id'  =>  cleanInput($_POST['publisher_id']),
    'interstital_ad'  =>  ($_POST['interstital_ad']) ? 'true' : 'false',
    'interstital_ad_type'  =>  cleanInput($_POST['ad_type']),
    'interstital_ad_id'  =>  cleanInput($_POST['interstital_ad_id']),
    'interstital_facebook_id'  =>  cleanInput($_POST['interstital_facebook_id']),
    'interstitial_applovin_id'  =>  cleanInput($_POST['interstitial_applovin_id']),
    'interstital_ad_click'  =>  cleanInput($_POST['interstital_ad_click']),

    'banner_ad'  =>  ($_POST['banner_ad']) ? 'true' : 'false',
    'banner_ad_type'  =>  cleanInput($_POST['ad_type']),
    'banner_ad_id'  =>  cleanInput($_POST['banner_ad_id']),
    'banner_facebook_id'  =>  cleanInput($_POST['banner_facebook_id']),
    'banner_applovin_id'  =>  cleanInput($_POST['banner_applovin_id']),

    'start_ads_id' => cleanInput($_POST['start_ads_id']),

    'native_ad'  =>  ($_POST['native_ad']) ? 'true' : 'false',
    'native_ad_type'  => (strcmp($_POST['ad_type'], 'admob') == 0 or strcmp($_POST['ad_type'], 'startapp') == 0 or strcmp($_POST['ad_type'], 'facebook') == 0 or strcmp($_POST['ad_type'], 'applovins' ) == 0 or strcmp($_POST['ad_type'], 'wortise' ) == 0) ? cleanInput($_POST['ad_type']) : '',
    'native_ad_id'  =>  cleanInput($_POST['native_ad_id']),
    'native_facebook_id'  =>  cleanInput($_POST['native_facebook_id']),
    'native_position'  =>  cleanInput($_POST['native_position']),
    'native_applovin_id'  =>  cleanInput($_POST['native_applovin_id']),
    'android_ad_network' => cleanInput($_POST['ad_type']),

    'banner_wortise_id' => cleanInput($_POST['banner_wortise_id']),
    'interstitial_wortise_id' => cleanInput($_POST['interstitial_wortise_id']),
    'native_wortise_id' => cleanInput($_POST['native_wortise_id']),
    'wortise_app_id' => cleanInput($_POST['wortise_app_id'])
  );
  
  $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
  
  $_SESSION['msg']="11";
  header( "Location:settings.php");
  exit;


}

// Update api submit
else if(isset($_POST['api_submit']))
{

  $data = array(
    'api_home_limit'  =>  cleanInput($_POST['api_home_limit']),
    'api_latest_limit'  =>  cleanInput($_POST['api_latest_limit']),
    'api_cat_order_by'  =>  cleanInput($_POST['api_cat_order_by']),
    'api_cat_post_order_by'  =>  cleanInput($_POST['api_cat_post_order_by']) 
  );


  $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

  $_SESSION['msg']="11";
  header( "Location:settings.php");
  exit;

}

// Update app privacy policy
else if(isset($_POST['app_pri_poly']))
{

  $data = array(
    'app_privacy_policy'  =>  trim($_POST['app_privacy_policy'])
  );

  $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

  $_SESSION['msg']="11";
  header( "Location:settings.php");
  exit;
}

// Update account delete
else if(isset($_POST['account_delete']))
{

  $data = array(
    'account_delete_intruction'  =>  trim($_POST['account_delete_intruction'])
  );

  $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

  $_SESSION['msg']="11";
  header( "Location:settings.php");
  exit;
}
// Update app update popup
else if(isset($_POST['app_update_popup']))
{

  $data = array(
    'app_update_status'  =>  ($_POST['app_update_status']) ? 'true' : 'false',
    'app_new_version'  =>  trim($_POST['app_new_version']),
    'app_update_desc'  =>  addslashes(trim($_POST['app_update_desc'])),
    'app_redirect_url'  =>  trim($_POST['app_redirect_url']),
    'cancel_update_status'  =>  ($_POST['cancel_update_status']) ? 'true' : 'false',
  );

  $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

  $_SESSION['msg']="11";
  header("Location:settings.php");
  exit;

}
// Update app terms & conditions
else if(isset($_POST['app_terms_con'])){

	$data = array(
		'app_terms_conditions'  =>  trim($_POST['app_terms_conditions'])
	);

	$settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

	$_SESSION['msg']="11";
	header( "Location:settings.php");
	exit;
}
?>

<style type="text/css">
  .field_lable {
    margin-bottom: 10px;
    margin-top: 10px;
    color: #666;
    padding-left: 15px;
    font-size: 14px;
    line-height: 24px;
  }
  .banner_ads_block .toggle_btn, .interstital_ad_item .toggle_btn{
    margin-top: 6px;
  }
</style>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title"><?=$page_title?></div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="card-body mrg_bottom" style="padding: 0px">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#app_settings" aria-controls="app_settings" role="tab" data-toggle="tab">App Settings</a></li>
          <li role="presentation"><a href="#admob_settings" aria-controls="admob_settings" role="tab" data-toggle="tab">Ads Settings</a></li>
          <li role="presentation"><a href="#api_settings" aria-controls="api_settings" role="tab" data-toggle="tab">API Settings</a></li>
          <li role="presentation"><a href="#api_privacy_policy" aria-controls="api_privacy_policy" role="tab" data-toggle="tab">App Privacy Policy</a></li>
          <li role="presentation"><a href="#app_terms_cond" aria-controls="app_terms_cond" role="tab" data-toggle="tab">Terms & Condition</a></li>
          <li role="presentation"><a href="#account_delete" aria-controls="account_delete" role="tab" data-toggle="tab">Delete Account Instructions</a></li>
          <li role="presentation"><a href="#app_update_popup" aria-controls="app_update_popup" role="tab" data-toggle="tab">App Update Popup</a></li>
        </ul>
        
        <div class="tab-content">
          <!-- app settings -->
          <div role="tabpanel" class="tab-pane active" id="app_settings">
            <div class="container-fluid">
              <div class="rows">
                <div class="col-md-12">
                  <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <div class="section">
                      <div class="section-body">
                        <div class="form-group">
                          <label class="col-md-4 control-label">App Name :-</label>
                          <div class="col-md-6">
                            <input type="text" name="app_name" id="app_name" value="<?php echo $settings_row['app_name'];?>" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">App Logo :- <p class="control-label-help">(Recommended resolution: 80X80, 90x90)</p></label>
                          <div class="col-md-6">
                            <div class="fileupload_block">
                              <input type="file" name="app_logo" id="fileupload">

                              <?php if($settings_row['app_logo']!="") {?>
                                <div class="fileupload_img"><img type="image" src="images/<?php echo $settings_row['app_logo'];?>" alt="image" style="width: 90px;height: 90px"/></div>
                              <?php } else {?>
                                <div class="fileupload_img"><img type="image" src="assets/images/square.jpg" alt="image" style="width: 90px;height: 90px"/></div>
                              <?php }?>

                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">App Description :-</label>
                          <div class="col-md-6">
                            <textarea name="app_description" id="app_description" class="form-control"><?php echo stripslashes($settings_row['app_description']);?></textarea>
                            
                            <script>
                              CKEDITOR.replace( 'app_description' ,{
                                filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                                filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                                filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                              });
                            </script>
                          </div>
                        </div>
                        <div class="form-group">&nbsp;</div>                 
                        <div class="form-group">
                          <label class="col-md-4 control-label">App Version :-</label>
                          <div class="col-md-6">
                            <input type="text" name="app_version" id="app_version" value="<?php echo $settings_row['app_version'];?>" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">Author :-</label>
                          <div class="col-md-6">
                            <input type="text" name="app_author" id="app_author" value="<?php echo $settings_row['app_author'];?>" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">Contact :-</label>
                          <div class="col-md-6">
                            <input type="text" name="app_contact" id="app_contact" value="<?php echo $settings_row['app_contact'];?>" class="form-control">
                          </div>
                        </div>     
                        <div class="form-group">
                          <label class="col-md-4 control-label">Email :-</label>
                          <div class="col-md-6">
                            <input type="text" name="app_email" id="app_email" value="<?php echo $settings_row['app_email'];?>" class="form-control">
                          </div>
                        </div>                 
                        <div class="form-group">
                          <label class="col-md-4 control-label">Website :-</label>
                          <div class="col-md-6">
                            <input type="text" name="app_website" id="app_website" value="<?php echo $settings_row['app_website'];?>" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">Developed By :-</label>
                          <div class="col-md-6">
                            <input type="text" name="app_developed_by" id="app_developed_by" value="<?php echo $settings_row['app_developed_by'];?>" class="form-control">
                          </div>
                        </div> 
                        <div class="form-group">
                          <div class="col-md-8 col-md-offset-4">
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          
          <!-- ads settings -->
          <div role="tabpanel" class="tab-pane" id="admob_settings">
            <div class="container-fluid">
              <div class="rows">
                <div class="col-md-12">
                  <form action="" name="admob_settings" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <div class="section">
                      <div class="section-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-4 control-label">Ad Network:-</label>
                                  <div class="col-md-8">
                                    <select name="ad_type" id="ad_type" class="select2">
                                      <option value="admob" <?php if ($settings_row['android_ad_network'] == 'admob') {
                                        echo 'selected="selected"';
                                      } ?>>Admob</option>
                                      <option value="facebook" <?php if ($settings_row['android_ad_network'] == 'facebook') {
                                        echo 'selected="selected"';
                                      } ?>>Facebook</option>
                                      <option value="startapp" <?php if ($settings_row['android_ad_network'] == 'startapp') {
                                        echo 'selected="selected"';
                                      } ?>>StartApp</option>
                                      <option value="applovins" <?php if ($settings_row['android_ad_network'] == 'applovins') {
                                        echo 'selected="selected"';
                                      } ?>>AppLovin's MAX</option>
                                      <option value="wortise" <?php if ($settings_row['android_ad_network'] == 'wortise') {
                                                                echo 'selected="selected"';
                                                              } ?>>Wortise</option> 
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group publisher_id">
                                  <label class="col-md-4 control-label">Publisher ID :-</label>
                                  <div class="col-md-8">
                                    <input type="text" name="publisher_id" id="publisher_id" value="<?php echo $settings_row['publisher_id']; ?>" class="form-control">
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-4 control-label start_ads_id" style="display: none">StartApp App ID:-</label>
                                  <div class="col-md-8 start_ads_id" style="display: none">
                                    <input type="text" name="start_ads_id" id="start_ads_id" value="<?php echo $settings_row['start_ads_id']; ?>" class="form-control">
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                              <div class="form-group">
                                <label class="col-md-4 control-label wortise_app_id" style="display: none">Wortise App ID:-</label>
                                <div class="col-md-8 wortise_app_id" style="display: none">
                                  <input type="text" name="wortise_app_id" id="wortise_app_id" value="<?php echo $settings_row['wortise_app_id']; ?>" class="form-control">
                                </div>
                              </div>
                            </div>

                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                <div class="banner_ads_block">
                                  <div class="banner_ad_item">
                                    <label class="control-label" >Banner Ads :-</label>
                                    <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="chk_banner" name="banner_ad" value="true" class="cbx hidden" <?= ($settings_row['banner_ad'] == 'true') ? 'checked=""' : '' ?>>
                                      <label for="chk_banner" class="lbl"></label>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <p class="field_lable labels" style="padding-left:15px;">Banner ID :-</p>
                                      <div class="col-md-12 banner_ad_id" style="display: none">
                                        <input type="text" name="banner_ad_id" id="banner_ad_id" value="<?php echo $settings_row['banner_ad_id']; ?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 banner_facebook_id" style="display: none">
                                        <input type="text" name="banner_facebook_id" id="banner_facebook_id" value="<?php echo $settings_row['banner_facebook_id']; ?>" class="form-control">
                                      </div>

                                      <div class="col-md-12 banner_applovin_id" style="display: none">
                                        <input type="text" name="banner_applovin_id" id="banner_applovin_id" value="<?php echo $settings_row['banner_applovin_id']; ?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 banner_wortise_id" style="display: none">
                                      <input type="text" name="banner_wortise_id" id="banner_wortise_id" value="<?php echo $settings_row['banner_wortise_id']; ?>" class="form-control">
                                    </div>

                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="interstital_ads_block">
                                  <div class="interstital_ad_item">
                                    <label class="control-label">Interstitial Ads :-</label>
                                    <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="chk_interstitial" name="interstital_ad" value="true" class="cbx hidden" <?php if ($settings_row['interstital_ad'] == 'true') { ?>checked <?php } ?> />
                                      <label for="chk_interstitial" class="lbl"></label>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <p class="field_lable labels" style="padding-left:15px;">Interstitial Ad ID :-</p>
                                      <div class="col-md-12 interstital_ad_id" style="display: none">
                                        <input type="text" name="interstital_ad_id" id="interstital_ad_id" value="<?php echo $settings_row['interstital_ad_id']; ?>" class="form-control">
                                      </div>

                                      <div class="col-md-12 interstital_facebook_id" style="display: none">
                                        <input type="text" name="interstital_facebook_id" id="interstital_facebook_id" value="<?php echo $settings_row['interstital_facebook_id']; ?>" class="form-control">
                                      </div>

                                      <div class="col-md-12 interstitial_applovin_id" style="display: none">
                                        <input type="text" name="interstitial_applovin_id" id="interstitial_applovin_id" value="<?php echo $settings_row['interstitial_applovin_id']; ?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 interstitial_wortise_id" style="display: none">
                                        <input type="text" name="interstitial_wortise_id" id="interstitial_wortise_id" value="<?php echo $settings_row['interstitial_wortise_id']; ?>" class="form-control">
                                      </div>

                                    </div>
                                    <div class="form-group">
                                      <p class="field_lable " style="padding-left:15px;">Interstitial Clicks :-</p>
                                      <div class="col-md-12">
                                        <input type="text" name="interstital_ad_click" id="interstital_ad_click" value="<?php echo $settings_row['interstital_ad_click']; ?>" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="banner_ads_block native_ads_block">
                                  <div class="banner_ad_item">
                                    <label class="control-label">Native Ads:-</label>
                                    <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="checked4" name="native_ad" value="true" class="cbx hidden" <?php if ($settings_row['native_ad'] == 'true') { ?>checked <?php } ?> />
                                      <label for="checked4" class="lbl"></label>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-group" id="admob_banner_id">
                                      <p class="field_lable labels" style="padding-left:15px;">Native Ad ID :-</p>
                                      <div class="col-md-12 native_ad_id" style="display: none">
                                        <input type="text" name="native_ad_id" id="native_ad_id" value="<?php echo $settings_row['native_ad_id']; ?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 native_facebook_id" style="display: none">
                                        <input type="text" name="native_facebook_id" id="native_facebook_id" value="<?php echo $settings_row['native_facebook_id']; ?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 native_applovin_id" style="display: none">
                                        <input type="text" name="native_applovin_id" id="native_applovin_id" value="<?php echo $settings_row['native_applovin_id']; ?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 native_wortise_id" style="display: none">
                                      <input type="text" name="native_wortise_id" id="native_wortise_id" value="<?php echo $settings_row['native_wortise_id']; ?>" class="form-control">
                                    </div>

                                      <p class="field_lable" style="padding-left:15px;">Position of Ad :-<br></p>
                                      <div class="col-md-12">
                                        <input type="number" name="native_position" id="native_position" min="4" value="<?php echo $settings_row['native_position']; ?>" class="form-control ads_click">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-9">
                            <button type="submit" name="admob_submit" class="btn btn-primary">Save</button>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade in mt-80" role="alert" style="margin-top:20px;">
                              <h4 id="oh-snap!-you-got-an-error!">Ads Instruction:<a class="anchorjs-link" href="#oh-snap!-you-got-an-error!"><span class="anchorjs-icon"></span></a></h4>
                              <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> Admob= Add all your ad unit id(Banner,Interstitial,Native)</p>
                              <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> Facebook Audience Network=Use Facebook Audience Network then please select ad network Admob(Facebook Audience Network will switch to Bidding).<br /><br />
                                Follow this url to use Facebook Audience Network bidding with Admob, Follow Step-1 and Step-2 - as described in link.<br />
                                <a href="https://developers.google.com/admob/android/mediation/facebook?fbclid=IwAR0cZxXNjE0EY-TsWA1aNzM0oV3KAhf3zz4fUajoiESN8V2My6wA42xSBhU" target="_blank">Integrationg Facebook Audience Network with Mediation</a>
                              </p>
                              <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> StartApp= Add startapp app id (Only require startapp app id does not required separate id)</p>
                              <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> AppLovin's MAX= Add all your ad unit id(Banner,Interstitial)</p>
                              <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> Wortise= Add all your ad unit id(Banner,Interstitial,Native)  
                              <a href="https://dashboard.wortise.com/auth/sign-up?referrer=b8cf48ce-a1fe-4c76-b9f9-80c9a4732c21" target="_blank">Wortise Login Link</a></p>
                              <br />
                              <h4 id="oh-snap!-you-got-an-error!">Note:<a class="anchorjs-link" href="#oh-snap!-you-got-an-error!"><span class="anchorjs-icon"></span></a></h4>
                              <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> AdMob= Add your admob App Id in <strong>Android Source Code strings.xml</strong> file.</p>
                              <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> AppLovin's MAX= Add your Applovin Sdk Key in <strong>Android Source Code strings.xml</strong> file.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

            <!-- api settings -->
            <div role="tabpanel" class="tab-pane" id="api_settings">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
                      <div class="section">
                        <div class="section-body">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="col-md-5 control-label">Home Limit:-</label>
                              <div class="col-md-6">

                                <input type="number" name="api_home_limit" id="api_home_limit" value="<?php echo $settings_row['api_home_limit'];?>" class="form-control"> 
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-md-5 control-label">Latest Limit:-</label>
                              <div class="col-md-6">
                                <input type="number" name="api_latest_limit" id="api_latest_limit" value="<?php echo $settings_row['api_latest_limit'];?>" class="form-control"> 
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="col-md-5 control-label">Category List Order By:-</label>
                              <div class="col-md-6">
                                <select name="api_cat_order_by" id="api_cat_order_by" class="select2">
                                  <option value="cid" <?php if($settings_row['api_cat_order_by']=='cid'){?>selected<?php }?>>ID</option>
                                  <option value="category_name" <?php if($settings_row['api_cat_order_by']=='category_name'){?>selected<?php }?>>Name</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-md-5 control-label">Category Post Order:-</label>
                              <div class="col-md-6">

                                <select name="api_cat_post_order_by" id="api_cat_post_order_by" class="select2">
                                  <option value="ASC" <?php echo ($settings_row['api_cat_post_order_by']=='ASC') ? 'selected' : ''; ?>>ASC</option>
                                  <option value="DESC" <?php echo ($settings_row['api_cat_post_order_by']=='DESC') ? 'selected' : ''; ?>>DESC</option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                              <button type="submit" name="api_submit" class="btn btn-primary">Save</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div> 
            <!-- api terms & conditions settings -->
            <div role="tabpanel" class="tab-pane" id="app_terms_cond"> 
             <div class="container-fluid">
              <div class="rows">
                <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
                  <div class="section">
                    <div class="section-body">
                      <div class="form-group">
                        <label class="col-md-3 control-label">App Terms Conditions :-</label>
                        <div class="col-md-9">
                          <textarea name="app_terms_conditions" id="app_terms_conditions" class="form-control"><?php echo stripslashes($settings_row['app_terms_conditions']);?></textarea>
                          <script>
                            CKEDITOR.replace( 'app_terms_conditions' ,{
                              filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                              filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                              filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                            });
                          </script>
                        </div>
                      </div>
                      <br/>
                      <div class="form-group">
                        <div class="col-md-9 col-md-offset-3">
                          <button type="submit" name="app_terms_con" class="btn btn-primary">Save</button>
                        </div>
                      </div>
                      <br>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- api privacy policy settings -->
          <div role="tabpanel" class="tab-pane" id="api_privacy_policy">   
            <div class="container-fluid">
              <div class="rows">
                <div class="col-md-12">
                  <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <div class="section">
                      <div class="section-body">
                        <?php 
                        if(file_exists('privacy_policy.php'))
                        {
                          ?>
                          <div class="form-group">
                            <label class="col-md-3 control-label">App Privacy Policy URL :-</label>
                            <div class="col-md-9">
                              <input type="text" readonly class="form-control" value="<?=getBaseUrl().'privacy_policy.php'?>">
                            </div>
                          </div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-3 control-label">App Privacy Policy :-</label>
                          <div class="col-md-9">
                            <textarea name="app_privacy_policy" id="privacy_policy" class="form-control"><?php echo stripslashes($settings_row['app_privacy_policy']);?></textarea>
                            <script>
                              CKEDITOR.replace( 'privacy_policy' ,{
                                filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                                filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                                filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                              });
                            </script>
                          </div>
                        </div>
                        <br/>
                        <div class="form-group">
                          <div class="col-md-9 col-md-offset-3">
                            <button type="submit" name="app_pri_poly" class="btn btn-primary">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- api account delete settings -->
          <div role="tabpanel" class="tab-pane" id="account_delete">   
            <div class="container-fluid">
              <div class="rows">
                <div class="col-md-12">
                  <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <div class="section">
                      <div class="section-body">
                        <?php 
                        if(file_exists('delete_instruction.php'))
                        {
                          ?>
                          <div class="form-group">
                            <label class="col-md-3 control-label">Account Delete Instruction URL :-</label>
                            <div class="col-md-9">
                              <input type="text" readonly class="form-control" value="<?=getBaseUrl().'delete_instruction.php'?>">
                            </div>
                          </div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-3 control-label">Account Delete Instruction :-</label>
                          <div class="col-md-9">
                            <textarea name="account_delete_intruction" id="account_delete_intruction" class="form-control"><?php echo stripslashes($settings_row['account_delete_intruction']);?></textarea>
                            <script>
                              CKEDITOR.replace( 'account_delete_intruction' ,{
                                filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                                filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                                filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                              });
                            </script>
                          </div>
                        </div>
                        <br/>
                        <div class="form-group">
                          <div class="col-md-9 col-md-offset-3">
                            <button type="submit" name="account_delete" class="btn btn-primary">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div> 

          <!-- app update popup -->
          <div role="tabpanel" class="tab-pane" id="app_update_popup">

            <div class="container-fluid">
              <div class="rows">
                <div class="col-md-12">
                  <form action="" name="app_update_popup" method="post" class="form form-horizontal" enctype="multipart/form-data">

                    <div class="section">
                      <div class="section-body">
                        <div class="form-group">
                          <label class="col-md-3 control-label">App Update Popup Show/Hide:-
                            <p class="control-label-help" style="color:#F00">You can show/hide update popup from this option</p>
                          </label>
                          <div class="col-md-6">
                            <div class="row" style="margin-top: 15px">
                              <input type="checkbox" id="chk_update" name="app_update_status" value="true" class="cbx hidden" <?php if($settings_row['app_update_status']=='true'){ echo 'checked'; }?>/>
                              <label for="chk_update" class="lbl" style="left:13px;"></label>
                            </div>
                          </div>                   
                        </div>
                        <div class="form-group">
                          <label class="col-md-3 control-label">New App Version Code :-
                            <a href="assets/images/android_version_code.png" target="_blank"><p class="control-label-help" style="color:#F00">How to get version code</p></a>
                          </label>
                          <div class="col-md-6">
                            <input type="number" min="1" name="app_new_version" id="app_new_version" required="" value="<?php echo $settings_row['app_new_version'];?>" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-3 control-label">Description :-</label>
                          <div class="col-md-6">
                            <textarea name="app_update_desc" class="form-control"><?php echo $settings_row['app_update_desc'];?></textarea>
                          </div>
                        </div> 
                        <div class="form-group">
                          <label class="col-md-3 control-label">App Link :-
                            <p class="control-label-help">You will be redirect on this link after click on update</p>
                          </label>
                          <div class="col-md-6">
                            <input type="text" name="app_redirect_url" id="app_redirect_url" required="" value="<?php echo $settings_row['app_redirect_url'];?>" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-3 control-label">Cancel Option :-
                            <p class="control-label-help" style="color:#F00">Cancel button option will show in app update popup</p>
                          </label>
                          <div class="col-md-6">
                            <div class="row" style="margin-top: 15px">
                              <input type="checkbox" id="chk_cancel_update" name="cancel_update_status" value="true" class="cbx hidden" <?php if($settings_row['cancel_update_status']=='true'){ echo 'checked'; }?>/>
                              <label for="chk_cancel_update" class="lbl" style="left:13px;"></label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-9 col-md-offset-3">
                            <button type="submit" name="app_update_popup" class="btn btn-primary">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- end app update popup -->

        </div>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php");?>

<script type="text/javascript">
 // Show active tab
 $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
  localStorage.setItem('activeTab', $(e.target).attr('href'));
  document.title = $(this).text()+" | <?=APP_NAME?>";
});

 var activeTab = localStorage.getItem('activeTab');
 if(activeTab){
  $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
}

$(document).ready(function(e) {
  var adType = $("select[name='ad_type']").val();

  if (adType === 'admob') {
    $(".publisher_id").show();
    $(".banner_ad_id").show();
    $(".interstital_ad_id").show();
    $(".native_ads_block").show();
    $(".native_ad_id").show();
  } else if (adType === 'facebook') {
    $(".publisher_id").show();
    $(".banner_facebook_id").show();
    $(".interstital_facebook_id").show();
    $(".native_ads_block").show();
    $(".native_facebook_id").show();
  } else if (adType === 'startapp') {
    $('.start_ads_id').show();
    $('.labels').hide();
    $(".publisher_id").hide();
  } else if (adType === 'applovins') {
    $('.banner_applovin_id').show();
    $('.interstitial_applovin_id').show();
    $(".native_applovin_id").show();
    $(".publisher_id").hide();
  } else if (adType === 'wortise') {
      $('.banner_wortise_id').show();
      $('.interstitial_wortise_id').show();
      $(".native_wortise_id").show();
      $(".wortise_app_id").show();
      $(".publisher_id").hide();
  }
});

$("select[name='ad_type']").change(function(e) {
  if ($(this).val() === 'admob') {
    $(".publisher_id").show();
    $(".banner_ad_id").show();
    $(".interstital_ad_id").show();
    $(".native_ads_block").show();
    $(".native_ad_id").show();

      //banner ads
      $(".banner_facebook_id").hide();
      $('.banner_applovin_id').hide();

      //interstital ads
      $(".interstital_facebook_id").hide();
      $(".interstitial_applovin_id").hide();

      $(".native_facebook_id").hide();
      $(".native_applovin_id").hide();
      //start ads
      $('.start_ads_id').hide();
      $('.labels').show();

      $('.banner_wortise_id').hide();
      $(".interstitial_wortise_id").hide();
      $(".native_wortise_id").hide();
      $('.wortise_app_id').hide();

    } else if ($(this).val() === 'facebook') {
      $(".publisher_id").show();
      $(".banner_facebook_id").show();
      $(".interstital_facebook_id").show();
      $(".native_ads_block").show();
      $(".native_facebook_id").show();

      //start ads
      $('.start_ads_id').hide();
      $('.labels').show();

      //banner ads disable
      $(".banner_ad_id").hide();
      $('.banner_applovin_id').hide();

      //interstital
      $(".interstital_ad_id").hide();
      $(".interstitial_applovin_id").hide();

      //native ads
      $(".native_ad_id").hide();
      $(".native_applovin_id").hide();

      $('.banner_wortise_id').hide();
      $(".interstitial_wortise_id").hide();
      $(".native_wortise_id").hide();
      $('.wortise_app_id').hide();

    } else if ($(this).val() === 'startapp') {
      $(".publisher_id").hide();
      $('.start_ads_id').show();
      $('.labels').hide();

      $('.native_ads_block').show();
      $('.native_applovin_id').hide();
      //banner ads
      $(".banner_ad_id").hide();
      $(".banner_facebook_id").hide();
      $('.banner_applovin_id').hide();

      //interstitial ads 
      $(".interstital_ad_id").hide();
      $(".interstital_facebook_id").hide();
      $('.interstitial_applovin_id').hide();

      //native ads 
      $(".native_facebook_id").hide();
      $(".native_ad_id").hide();

      $('.banner_wortise_id').hide();
      $(".interstitial_wortise_id").hide();
      $(".native_wortise_id").hide();
      $('.wortise_app_id').hide();

    }else if ($(this).val() === 'applovins') {
      $('.banner_applovin_id').show();
      $('.interstitial_applovin_id').show();
      $('.native_ads_block').show();
      $('.native_applovin_id').show();

      $(".publisher_id").hide();

      //start ads
      $('.start_ads_id').hide();

      $('.labels').show();

      //disable native ads
      $('.native_ad_id').hide();
      $('.native_facebook_id').hide();

      //bannner ads disabled
      $(".banner_ad_id").hide();
      $('.banner_facebook_id').hide();

      //interstitial
      $('.interstital_ad_id').hide();
      $('.interstital_facebook_id').hide();

      $('.banner_wortise_id').hide();
      $(".interstitial_wortise_id").hide();
      $(".native_wortise_id").hide();
      $('.wortise_app_id').hide();

    }else if ($(this).val() === 'wortise') {
      
      $('.banner_wortise_id').show();
      $(".interstitial_wortise_id").show();
      $(".native_wortise_id").show();
      $('.wortise_app_id').show();
      
      $('.banner_applovin_id').hide();
      $('.interstitial_applovin_id').hide();
      $('.native_ads_block').show();
      $('.native_applovin_id').hide();

      $(".publisher_id").hide();

      //start ads
      $('.start_ads_id').hide();

      $('.labels').show();

      //disable native ads
      $('.native_ad_id').hide();
      $('.native_facebook_id').hide();

      //bannner ads disabled
      $(".banner_ad_id").hide();
      $('.banner_facebook_id').hide();

      //interstitial
      $('.interstital_ad_id').hide();
      $('.interstital_facebook_id').hide();
    }
  });

  // App logo upload 
  $("input[name='app_logo']").change(function() { 
    var file=$(this);

    if(file[0].files.length != 0){
      if(isImage($(this).val())){
        render_upload_image(this,$(this).next('.fileupload_img').find("img"));
      }
      else
      {
        $(this).val('');
        $('.notifyjs-corner').empty();
        $.notify(
          'Only jpg/jpeg, png, gif and svg files are allowed!',
          { position:"top center",className: 'error'}
          );
      }
    }
  });

</script>