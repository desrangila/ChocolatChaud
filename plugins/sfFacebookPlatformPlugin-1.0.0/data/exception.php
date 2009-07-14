<style type="text/css">
  a { color: #333 }
  h1 { margin: 0 0 0 10px; padding: 10px 0 10px 0; font-weight: bold; font-size: 120% }
  h2 { margin: 0; padding: 5px 0; font-size: 110% }
  ul { padding-left: 0; list-style: decimal }
  ul li { padding-bottom: 5px; margin: 0 }
  ol { font-family: monospace; white-space: pre; list-style-position: inside; margin: 0; padding: 10px 0 }
  ol li { margin: -5px; padding: 0 }
  ol .selected { font-weight: bold; background-color: #ddd; padding: 2px 0 }
  table.vars { padding: 0; margin: 0; border: 1px solid #999; background-color: #fff; }
  table.vars th { padding: 2px; background-color: #ddd; font-weight: bold }
  table.vars td  { padding: 2px; font-family: monospace; white-space: pre }
  p.error { padding: 10px; background-color: #f00; font-weight: bold; text-align: center; -moz-border-radius: 10px; }
  p.error a { color: #fff }
  #main { padding: 20px; padding-left: 40px; text-align:left; }
  #message { padding: 10px; margin-bottom: 10px; background-color: #eee; -moz-border-radius: 10px }
</style>
<script type="text/javascript">
  function toggle(id)
  {
    el = document.getElementById(id); el.style.display = el.style.display == 'none' ? 'block' : 'none';
  }
</script>
<div id="main">
  <h1>[<?php echo $name ?>]</h1>
  <h2 id="message"><?php echo $message ?></h2>
  <?php if ($error_reference): ?>
    <p class="error"><a href='http://www.symfony-project.com/errors/<?php echo $error_reference ?>'>learn more about this issue</a></p>
  <?php endif; ?>
  <h2>stack trace</h2>
  <ul><li><?php echo implode('</li><li>', $traces) ?></li></ul>

  <p id="footer">
    symfony v.<?php echo file_get_contents(sfConfig::get('sf_symfony_lib_dir').'/VERSION') ?> - php <?php echo PHP_VERSION ?><br />
    for help resolving this issue, please visit <a href="http://www.symfony-project.com/">http://www.symfony-project.com/</a>.
  </p>
</div>
