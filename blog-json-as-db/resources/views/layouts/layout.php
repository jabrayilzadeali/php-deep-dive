<?php
function layout($contentFile, $data = [])
{
  extract($data);
  require_once BASE_PATH . '/resources/views/components/header.php';
  require_once $contentFile;
  require_once BASE_PATH . '/resources/views/components/footer.php';
}
