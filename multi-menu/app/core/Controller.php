<?php
class Controller {
  protected function view(string $path, array $data = []) {
    // Ex.: "public/home" → app/views/public/home.php
    $file = __DIR__ . '/../views/' . $path . '.php';
    if (!file_exists($file)) { echo "View não encontrada: $path"; return; }
    extract($data);
    include $file;
  }
}
