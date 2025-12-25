<?php
function dump($var)
{
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
}

// function dd($var)
// {
//   echo '<pre>';
//   var_dump($var);
//   echo '</pre>';
//   die();
// }

// function dd(...$vars)
// {
//   foreach ($vars as $var) {
//     echo '<pre style="background:#111;color:#eee;padding:10px;border-radius:6px;">';
//     if (is_array($var) || is_object($var)) {
//       echo htmlspecialchars(print_r($var, true));
//     } else {
//       var_dump($var);
//     }
//     echo '</pre>';
//   }
//   die();
// }

function dd(...$vars)
{
    foreach ($vars as $var) {

        // CLI output
        if (PHP_SAPI === 'cli') {
            if (is_array($var) || is_object($var)) {
                var_export($var);
            } else {
                var_dump($var);
            }
            echo PHP_EOL;
            continue;
        }

        // Browser output
        echo '<pre style="background:#111;color:#eee;padding:12px;border-radius:6px;overflow:auto;">';

        if (is_array($var) || is_object($var)) {
            echo htmlspecialchars(var_export($var, true), ENT_QUOTES, 'UTF-8');
        } else {
            var_dump($var);
        }

        echo '</pre>';
    }

    exit;
}