<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $pageTitle; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/styles/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/styles/bootswatch.min.css" media="screen">
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/styles/site.css" media="screen">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

</head>

<body>
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="<?php echo HTTPPATH; ?>" class="navbar-brand">RollyPolly</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <li>
              <a href="<?php echo HTTPPATH; ?>/locations/country">Choose Location</a>
            </li>
            <?php if (!empty($globalCity)) { ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Modules<span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="themes">
                <li><a href="<?php echo $globalCity['url']; ?>/businesses">Businesses</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/residential">Residential</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo $globalCity['url']; ?>/buy_sell">Buy / Sell</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/housing">Housing</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/personals">Personals</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/jobs">Jobs</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/services">Services</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/matrimony">Matrimony</a></li>
              </ul>
            </li>
            <?php } ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="manage">Manage <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Advertisements</a></li>
                <li><a href="#">Become Owner of City, State or Country</a></li>
                <li class="divider"></li>
                <li><a href="#">Admin</a></li>
                <li><a href="#">Content Manager</a></li>
                <li class="divider"></li>
                <li><a href="#">Super Admin</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="manage">About <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">About Us</a></li>
                <li><a href="#">How it works</a></li>
                <li class="divider"></li>
                <li><a href="#">Suggestions</a></li>
              </ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Login</a></li>
            <li><a href="#">My Account</a></li>
            <li><a href="#">1001 Points</a></li>
          </ul>

        </div>
      </div>
    </div>
    <div class="container">
    
    
      <div class="page-header">
        <div class="row">
          <div class="col-lg-12 col-md-7 col-sm-6">
            <h2><?php echo $pageTitle; ?></h2>
            <p class="lead">Cities, States, Countries</p>
          </div>
        </div>
      </div>

        <div class="row">
          <div class="col-lg-3">
            <div class="bs-component">
            
            
            
              <div class="panel panel-default">
                <div class="panel-heading">Search City</div>
                <div class="panel-body">
                  <?php include(SITEDIR.'/locations/searchcity.php'); ?>
                </div>
              </div>
              
              <?php if (!empty($pageDynamicNavigationItem)) { ?>
              <div class="panel panel-default">
                <div class="panel-heading">Information</div>
                <div class="panel-body">
                  <?php echo $pageDynamicNavigationItem; ?>
                </div>
              </div>
              <?php } ?>
              
              <?php if (!empty($pageDynamicNearby)) { ?>
              <div class="panel panel-default">
                <div class="panel-heading">Nearby Cities</div>
                <div class="panel-body">
                  <?php echo $pageDynamicNearby; ?>
                </div>
              </div>
              <?php } ?>
              
              
              <div class="panel panel-default">
                <div class="panel-heading">Navigation</div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-7">
            <div class="bs-component">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo $pageTitle; ?></h3>
                </div>
                <div class="panel-body">
                    <?php echo $contentForTemplate; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-2 pull-right">
            <div class="bs-component">

              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Advertisements</h3>
                </div>
                <div class="panel-body">
                  Content
                </div>
              </div>
            </div>
          </div>
        </div>
  </div>
<!-- August 23, 2014-->
<script src="<?php echo HTTPPATH; ?>/scripts/bootstrap.min.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/bootswatch.js"></script>
</body>
</html>