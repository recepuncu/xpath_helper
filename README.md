# PHP XPath Helper

```php
require 'src/xpath_helper.php';

$xpath_helper = new xpath_helper();
$contents = $xpath_helper->get_contents("http://www.google.com/search?hl=tr&q=samsung");
$xpath = $xpath_helper->load_xpath_from_contents($contents);

$row_path = "//div[@class='g']";

$cols_opts = array("title" => "//div[@class='rc']/h3[@class='r']/a",
    "url" => "//cite[@class='_Rm']",
    "description" => "//span[@class='st']");

$result = $xpath_helper->get_cols_in_row($xpath, $row_path, $cols_opts);

echo json_encode($result);
```
