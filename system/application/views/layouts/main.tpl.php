<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>

<?php echo $css; ?>
<?php echo $js; ?>


</head>

<body>
    <div id="page">

        <div id="header">
            <h1><?php echo $title; ?></h1>
            <?php //$this->load->view('partials/menu.tpl.php'); ?>
        </div>

        <div id="content">
            <h2><?php echo $heading; ?></h2>
            <?php echo $content; ?>
        </div>

        <div id="footer">
            <p>&copy; 2010 Parspake.</p>
        </div>

    </div>
</body>
</html>
