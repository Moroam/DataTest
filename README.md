# DataTest
Checks the array for matching the template.
Conditions for checking (template) are set as a JSON string.

The following conditions are checked:
  - columns_count - number of columns
  - row_number - availability of a row with the specified number (and the minimum number of rows, respectively)
  - columns* - parameters for checking the row (row_number string).
For line elements, set:
  - index / column name
  - pattern - value template
  - compare_method - comparison method
    => equal (simple equality)
    => preg_match (regular expression)
    => function**
  - compare_result - the result of the comparison
    => true / false

* - multiple checks can be made for each element
   If the validation rule for a column is a simple string, the following default values are used:
   "A": "txt"
      =>
   "A":{
      "pattern": "txt",
      "compare_method": "equal",
      "compare_result": "true"
    }
** - calculates the value boolval($pattern($value)), where $pattern is the function name, and $value is the value to check

Usage:
  1. Creating a JSON string with verification parameters.
    General view of the JSON string:
    $JSON = '{
      "columns_count" : 5,  # default 0
      "row_number": 1,      # default 1
      "columns" : {
        "A": {
          "pattern": "aaa",
          "compare_method": "preg_match", # default => "equal"
          "compare_result": "false"       # default => "true"
        }
      }
    }'
    Abbreviated entries are allowed
    - columns_count   => cnt
    - row_number      => rn
    - columns         => cols
    - pattern         => pat
    - compare_method  => cm
    - compare_result  => cr
    - equal           => eq
    - preg_match      => pm
    - function        => fn
2. Creating a DataTest object and setting the $JSON validation template
3. Check the array for matching the template

# DataTest
Проверяет массив на соответствие шаблону.
Условия для проверки (шаблон) задается в виде строки JSON.

Проверяются выполнение следующих условий:
  - columns_count - количество столбцов
  - row_number - наличие строки с указанным номером (проверка минимального числа строк)
  - columns* - параметры для проверки строки (строка row_number).
    Для элементов строки задаются:
    - индекс / имя столбца
    - pattern** - шаблон значения
    - compare_method - способ сравнения
      => equal (простое равенство)
      => preg_match (регулярное выражение)
      => function*** (функция)
    - compare_result - результат сравнения
      => true / false

* - для каждого элемента можно сделать несколько проверок.
   Если правило проверки для колонки это простая строка, то используются следующие значения по умолчанию:
   "A": "txt"
      =>
   "A":{
      "pattern": "txt",
      "compare_method": "equal",
      "compare_result": "true"
    }
** - вычисляется значение boolval($pattern($value)), где $pattern имя функции, $value - проверяемое значение

Использование:
1. Формируем JSON строку с параметрами проверки.
  Общий вид JSON строки:
  $JSON = '{
    "columns_count" : 5, # default 0
    "row_number": 1,     # default 1
    "columns" : {
      "A": {
        "pattern": "aaa",
        "compare_method": "preg_match",  # default => "equal"
        "compare_result": "false"        # default => "true"
      }
    }
  }'
  Допускаются сокращенная запись
   - columns_count  => cnt
   - row_number     => rn
   - columns        => cols
   - pattern        => pat
   - compare_method => cm
   - compare_result => cr
   - equal          => eq
   - preg_match     => pm
   - function       => fn
2. Создаем объект DataTest и задаем шаблон проверки $JSON
3. Проверяем массив на соответствие шаблону
