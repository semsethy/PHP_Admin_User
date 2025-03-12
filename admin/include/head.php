<?php
require_once 'include/settingConf.php';
$setting = new Setting();
$settings = $setting->getSettings();
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo  htmlspecialchars($settings['title']); ?></title>
  <link rel="shortcut icon" type="image/png" href="<?php echo  htmlspecialchars($settings['icon']); ?>" />
  <link rel="stylesheet" href="css/styles.min.css" />
  <link rel="stylesheet" href="product.css" />
</head>