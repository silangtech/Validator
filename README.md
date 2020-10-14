# validtor
规则验证器

```$xslt

$validator = \SilangPHP\Validator\Validator::make();

$validator->scene(['add' => 'data,data2', 'edit' => 'id,data,data2']); // 设置多个场景

// 验证条件
$rule = [
    'id|ID' => 'require|number',
    'data|数组验证' => 'require|array',
    'data2|内置正则' => 'require|alpha',
    'data3|内置方法' => 'require|ip',
    'data4|in方法' => 'require|in:a,b,c',
    'data5|自定义正则方法' => ['require','regex:(a|b)'], // 正则使用了 | 必须使用数组方式
    'data6|联合配置6' => 'require|eq:0',
];

$data = [
    'id' => '1',
    'data' => ['a'=>'a'],
    'data2' => 'b',
    'data3' => '127.0.0.1',
    'data4' => 'b',
    'data5' => 'a',
    'data6' => '0',
];
if (!$validator->check($data, $rule, 'add')) {
    exit($validator->getError());
}

echo '全通过';

```
