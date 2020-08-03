<!DOCTYPE html>
<html lang=ru>
<meta charset=utf-8>
<head>
  <title>Project - Test DataTest Class</title>
</head>

<body>
<pre>
<?php
require_once 'datatest.class.php';

error_reporting(E_ALL);
if(ini_set('display_errors', 1)===false)
  echo "ERROR INI SET";

$DT = new DataTest();

echo "<h3>Test 1 - Simple array</h3>";
$data = [
  [1, 'John', '2020-03-20', '1111111'],
  [2, 'Lina', '2020-03-21', '2222222'],
  [3, 'Loss', '2020-03-22', '3516451654654']
];

$json = '{
 "columns_count":4,
 "row_number":2,
 "columns":["3",{"pattern":"Loss","compare_method":"equal","compare_result":"true"}]
}';
$DT->set_template($json);
var_dump($json, $data);
echo "<br>Result: " . ($DT->test($data) ? "Ok!" : $DT->errno . " - " . $DT->error );

echo "<h3>Test 2 - the number of rows is less than the required</h3>";
$json = '{
 "columns_count":4,
 "row_number":4
}';
$DT->set_template($json);
var_dump($json, $data);
echo "<br>Result: " . ($DT->test($data) ? "Ok!" : $DT->errno . " - " . $DT->error );

echo "<h3>Test 3 - column B is missing</h3>";
$json = '{
 "columns_count":4,
 "row_number":1,
 "columns":{
    "A": {
      "pattern":"|^[0-9]+$|",
      "compare_method":"preg_match"
    },
    "B":{
      "pattern":"|^20[0-9]{2}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}$|",
      "compare_method":"preg_match"
    }
  }
}';
$DT->set_template($json);
$data = [
  ['A' => 1, 'C' => 'John', 'D' => '2020-03-20', 'F' => '1111111'],
  ['A' => 2, 'C' => 'Lina', 'D' => '2020-03-21', 'F' => '2222222'],
  ['A' => 3, 'C' => 'Loss', 'D' => '2020-03-22', 'F' => '3516451654654']
];
var_dump($json, $data);
echo "<br>Result: " . ($DT->test($data) ? "Ok!" : $DT->errno . " - " . $DT->error );

echo "<h3>Test 4 - column C not match pattern</h3>";
$json = '{
 "columns_count":4,
 "row_number":1,
 "columns":{
    "A": {
      "pattern":"|^[0-9]+$|",
      "compare_method":"preg_match"
    },
    "C":{
      "pattern":"|^20[0-9]{2}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}$|",
      "compare_method":"preg_match"
    }
  }
}';
$DT->set_template($json);
var_dump($json, $data);
echo "<br>Result: " . ($DT->test($data) ? "Ok!" : $DT->errno . " - " . $DT->error );

echo "<h3>Test 5 - short template form</h3>";
$json = '{"cnt":4,"rn":0,"cols":{"C":"John"}}';
$DT->set_template($json);
var_dump($json, $data);
echo "<br>Result: " . ($DT->test($data) ? "Ok!" : $DT->errno . " - " . $DT->error );


echo "<h3>Test 6 - test compare_method - function</h3>";

/**
 * Check if the value is a valid date
 * @param mixed $value
 * @return boolean
 */
function is_date($value)
{
    if (!$value) {
        return false;
    }

    try {
        new \DateTime($value);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}
$json = '{"cnt":4,"rn":0,"cols":{"A":{"cm":"fn","pat":"is_numeric"},"A":"1","D":{"cm":"fn","pat":"is_date"}}}';
$DT->set_template($json);
var_dump($json, $data);
echo "<br>Result: " . ($DT->test($data) ? "Ok!" : $DT->errno . " - " . $DT->error );
?>
</pre>

</body>
</html>
