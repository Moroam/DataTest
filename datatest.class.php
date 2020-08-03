<?php
require_once 'errormsg.trait.php';
/**
 * Class DataTest
 *
 * @version 1.0.0
 */
class DataTest{
  use ErrorMessage;

  protected $template_array = [];

  /**
   * Set template_array from json template
   *
   * @param string $json json template
   * @throws set_template
   */
  public function __construct(string $json = ''){
    if($json == ''){
      return;
    }
    $this->set_template($json);
  }

  /**
   * Set template_array from json template
   *
   * @param string $json json template
   * @throws JSON template not valid
   */
  public function set_template(string $json) : bool {
    if($json == ''){
      $this->template_array = [];
      return $this->ok();
    }

    $this->template_array = [];

    $template_arr = json_decode($json, true);
    if(json_last_error() !==  JSON_ERROR_NONE){
      return $this->err('JSON template not valid. Error msg: ' . json_last_error_msg(), json_last_error(), true);
    }

    $this->template_array['columns_count'] = $template_arr['columns_count'] ?? $template_arr['cnt']  ?? 0;
    $this->template_array['row_number']    = $template_arr['row_number']    ?? $template_arr['rn']   ?? 1;
    $this->template_array['columns']       = $template_arr['columns']       ?? $template_arr['cols'] ?? [];

    return $this->ok();
  }

  /**
   * Checking the value to match the template
   *
   * @param string $value value for checking
   * @param array|string $template template for comparison:
   *              pattern = pat -> sting pattern
   *              compare_method = cm -> method for comparing values => equal | preg_match | function => eq | pm | fn
   *              compare_result = cr -> what should be the result (true / false)
   *              if $template is string => [pattern = template, compare_method = equal, compare_result = true]
   * @return bool
   */
  protected function check_one_value(string $value, $template) : bool {
    if(is_string($template)){
      return $value == $template;
    }

    $pattern = $template['pattern'] ?? $template['pat'] ?? '';
    $cm = $template['compare_method'] ?? $template['cm'] ?? 'equal';
    $cr = $template['compare_result'] ?? $template['cr'] ?? true;

    $res = false;
    if($cm == 'equal' || $cm == 'eq')
      $res = $value == $pattern;

    if($cm == 'preg_match' || $cm == 'pm')
      $res = preg_match($pattern, $value);

    if($cm == 'function' || $cm == 'fn')
      $res = boolval($pattern($value));

    return $cr ? $res : !$res;
  }

  /**
   * Checking data table to match the template array
   *
   * @return bool
   */
  public function test(array &$data_array) : bool {
    if(count($this->template_array) == 0){
      return $this->ok();
    }

    $len = count($data_array);
    if($len == 0){
      return $this->err("Data array empty", 6);
    }

    if($len < $this->template_array['row_number']) {
      return $this->err("ERROR: no row with head", 7);
    }

    $xla = $data_array[$this->template_array['row_number']];
    if($this->template_array['columns_count']>0 && count($xla) != $this->template_array['columns_count']){
      return $this->err("ERROR: incorrect columns count", 8);
    }

    $columns = $this->template_array['columns'];
    if(count($columns)>0){
      if( count( array_diff_key($columns, $xla) ) != 0){
        return $this->err("ERROR: incorrect set of columns - required columns are missing", 9);
      }

      foreach ($columns as $key => $template){
        if( !$this->check_one_value($xla[$key], $template) ){
          return $this->err("ERROR: incorrect table head", 10);
        }
      }
    }

    return $this->ok();
  }

}
