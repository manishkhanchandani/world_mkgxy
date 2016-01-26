<?php
session_start();
include_once('../functions.php');

if (empty($_SESSION['user'])) {
  header("Location: ../user/login.php");
  exit;
}
if (!empty($_POST)) {
  $content = json_encode($_POST);
  $post = array();
  $post['data']['uid'] = $_SESSION['user']['id'];
  $post['data']['content_updated'] = date('Y-m-d H:i:s');
  $post['data']['promo_code'] = $_POST['promo'];
  $post['data']['content'] = $content;
  if (!empty($_POST['id'])) {
    $url = 'http://world.mkgalaxy.com/api/help/websites/edit?id='.$_POST['id'].'&token=';
  } else {
    $url = 'http://world.mkgalaxy.com/api/help/websites/add?token=';
  }
  $r = curlget($url, 1, http_build_query($post));
  $result = json_decode($r, 1);
  $error =  $result['data']['confirm'];
  if ($result['success'] == 0) {
    $error = $result['msg'];
  }
}
$data = array();
if (isset($_GET['website_id'])) {
  $url = 'http://world.mkgalaxy.com/api/help/websites/detail?id='.$_GET['website_id'].'&cache=0&token=';
  $r = curlget($url);
  $current = json_decode($r, 1);
  if ($current['success'] == 1) {
    $data = json_decode($current['data']['content'], 1);
  }
}
pr($data);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Create New Website</title>
</head>

<body>
<h1>Create New Website</h1>
<?php if (!empty($error)) echo $error; ?>
<p><a href="index.php">Back</a></p>
<form id="form1" name="form1" method="post">
  <p>
    <label for="domain"><strong>Domain:</strong></label>
    <input name="domain" type="text" id="domain" placeholder="e.g. dailyspa.com" value="<?php if (isset($data['domain'])) echo $data['domain']; else echo 'dailyspa.co'; ?>">
  </p>
  <p><strong>Website Name:
  </strong>
    <input name="name" type="text" id="name" placeholder="e.g. Beauty Center" value="<?php if (isset($data['name'])) echo $data['name']; else echo 'Beauty Center'; ?>">
  e.g. Beauty Center</p>
  <p><strong>Tag Name:
  </strong>
    <input name="tagname" type="text" id="tagname" placeholder="e.g. Keep Your Perfect Look" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>">
  e.g. Keep Your Perfect Look</p>
  <p><strong>Promo Code:</strong>
    <input name="promo" type="text" id="promo" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>FIRSTYEARFREE">
  </p>
  <h2>Follow Us Links</h2>
  <p><strong>Facebook Link:
    </strong>
    <input name="follow_fb" type="text" id="follow_fb" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://facebook.com">
  </p>
  <p><strong>Twitter Link:</strong>
    <input name="follow_tw" type="text" id="follow_tw" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://twitter.com">
  </p>
  <p><strong>Google Plus Link:
    </strong>
    <input name="follow_gp" type="text" id="follow_gp" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://google.com">
  </p>
  <h2>Template 1 Details:</h2>
  <p>Tab 1 Name:
    <input name="template[1][tab][1][name]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Hair">
  e.g. Hair</p>
  <p>Tab 1 Image:
    <input name="template[1][tab][1][image]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/slider-img1.jpg">
  e.g http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/slider-img1.jpg</p>
  <p>Tab2 Name:
    <input name="template[1][tab][2][name]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Nails">
  e.g. Nails</p>
  <p>Tab2 Image:
    <input name="template[1][tab][2][image]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/slider-img2.jpg">
  e.g. http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/slider-img2.jpg</p>
  <p>Tab3 Name:
    <input name="template[1][tab][3][name]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Makeup">
  e.g. Makeup</p>
  <p>Tab3 Image:
    <input name="template[1][tab][3][image]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/slider-img3.jpg">
    e.g. http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/slider-img3.jpg</p>
  <p>Tab4 Name:
    <input name="template[1][tab][4][name]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Spa">
  e.g. Spa</p>
  <p>Tab4 Image:
    <input name="template[1][tab][4][image]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/slider-img3.jpg">
  e.g. http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/slider-img3.jpg</p>
  <p>Paragraph 1 Title:
    <input name="template[1][para][1][title]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>About Us">
  e.g. About Us</p>
  <p>Paragraph 1 Content:<br>
    <textarea name="template[1][para][1][content]" cols="50" rows="5"><?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Beauty Center is one of best spa in bay area </textarea>
    <br>
    e.g. Beauty Center is one of best spa in bay area
  </p>
  <p>Paragraph 2 Title:
    <input name="template[1][para][2][title]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Our Gallery">
    e.g. Our Gallery</p>
  <p>Paragraph 2 Content:<br>
    <textarea name="template[1][para][2][content]" cols="50" rows="5"><?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Check out our new gallery.</textarea>
    <br>
    e.g. Check out our new gallery.
  </p>
  <p>Paragraph 3 Title:
    <input name="template[1][para][3][title]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Our Services">
    e.g. Our Services</p>
  <p>Paragraph 3 Content:<br>
    <textarea name="template[1][para][3][content]" cols="50" rows="5"><?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Best service in bay area. </textarea>
    <br>
    e.g. Best service in bay area.
  </p>
  <p>Paragraph 4 Title:
    <input type="text" name="template[1][para][4][title]" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>">
    e.g. Our Services</p>
  <p>Paragraph 4 Image:
    <input type="text" name="template[1][para][4][image]" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>">
  e.g. http://cashflow.mkgalaxy.com/massageNew/Templates/template1/images/page1-img1.jpg</p>
  <p>Paragraph 4 Content 1:<br>
    <textarea name="template[1][para][4][content][1]" cols="50" rows="5"><?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?><h6>At vero eos et accusamus etiusto dignissimos</h6>
									Ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum fuga harum quidem rerum facilis.</textarea>
    <br>
    e.g.
									&lt;h6&gt;At vero eos et accusamus etiusto dignissimos&lt;/h6&gt;<br>
Ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum fuga harum quidem rerum facilis.</p>
  <p>Paragraph 4 Content 2:<br>
    <textarea name="template[1][para][4][content][2]" cols="50" rows="5"><?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?><span class="color-2">Omnis voluptas assumenda est, omnis dolor repellendus.</span> Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae itaque earum rerum hic tenetur.</textarea>
    <br>
    e.g. &lt;span class=&quot;color-2&quot;&gt;Omnis voluptas assumenda est, omnis dolor repellendus.&lt;/span&gt; Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae itaque earum rerum hic tenetur.</p>
  <p>Paragraph 4 Content 3 (Read More):<br>
    <textarea name="template[1][para][4][content][3]" cols="50" rows="5"><?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>more text comes here</textarea>
    <br>
    e.g. more text comes here</p>
  <p>Tips Main Title:
    <input name="template[1][tips][title]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>Beauty Tips"> e.g. Beauty Tips</p>
  <p><strong>Content:</strong></p>
  <p>Tips 1 Title:
  <input name="template[1][tips][content][1][title]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo '<span class="color-2">Praesentum voluptatum</span> deleniti atque corrupti quos dolores.'; ?>"> e.g. <span class="color-2">Praesentum voluptatum</span> deleniti atque corrupti quos dolores.</p>
  <p>Tips 1 Links:
    <input name="template[1][tips][content][1][link]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://www.cashflow.mkgalaxy.com"> e.g. http://www.cashflow.mkgalaxy.com</p>
  <p>Tips 2Title:
    <input name="template[1][tips][content][2][title]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>">
    e.g. <span class="color-2">Culpa officia deserunt</span> mollitia animi id est laborum.</p>
  <p>Tips 2 Links:
    <input name="template[1][tips][content][2][link]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://www.cashflow.mkgalaxy.com">
    e.g. http://www.cashflow.mkgalaxy.com</p>
  <p>Tips 3 Title:
    <input name="template[1][tips][content][3][title]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>">
  e.g. <span class="color-2"></span>&lt;span class=&quot;color-2&quot;&gt;Voluptas assumenda&lt;/span&gt; est, omnis dolor repellendus.</p>
  <p>Tips 3 Links:
    <input name="template[1][tips][content][3][link]" type="text" value="<?php if (isset($data['tagname'])) echo $data['tagname']; else echo 'Keep Your Perfect Look'; ?>http://www.cashflow.mkgalaxy.com">
    e.g. http://www.cashflow.mkgalaxy.com</p>
  <p>Tips 4 Title:
    <input name="template[1][tips][content][4][title]" type="text" value="<?php if (isset($data['template'][1]['tips']['content'][4]['title'])) echo $data['template'][1]['tips']['content'][4]['title']; else echo '&lt;span class=&quot;color-2&quot;&gt;Temporibus autem&lt;/span&gt; quibusdam officiis debitis aut rerum necessit.'; ?>">
    e.g. <span class="color-2">Temporibus autem</span> quibusdam officiis debitis aut rerum necessit.</p>
  <p>Tips 4 Links:
    <input name="template[1][tips][content][4][link]" type="text" value="<?php if (isset($data['template'][1]['tips']['content'][4]['link'])) echo $data['template'][1]['tips']['content'][4]['link']; else echo 'http://www.cashflow.mkgalaxy.com'; ?>">
  e.g. http://www.cashflow.mkgalaxy.com</p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit">
  </p>
  <p>
    <input type="text" name="id" id="id" value="<?php if (isset($_GET['website_id'])) echo $_GET['website_id']; ?>">
  </p>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>