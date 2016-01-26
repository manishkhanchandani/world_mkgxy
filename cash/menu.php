<ul>
    <li class="active"><a href="../cash/index.php">Homepage</a></li>
    <li><a href="../cash/business_manager.php">Business Manager</a></li>
    <?php if (!empty($_SESSION['user'])) { ?>
    <li><a href="../cash/login.php">Logout</a></li>
    <?php } else { ?>
    <li><a href="../cash/login.php">Login</a></li>
    <?php } ?>
</ul>
<?php //echo !empty($menu['homepage']) ? 'active' : ''; ?>