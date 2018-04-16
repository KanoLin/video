<?php
/*----trie字典树过滤----*/
/*食用方法：
  include此文件至服务器文件；
  调用函数Trie($filename)，其中$filename为过滤词文件；
  使用filter($str)对$str进行过滤；
  文件编码为UTF-8；
*/



$head = new node;
/*----字典树----*/
class node
{
    public $value, $end = false, $child = array();
    public function &addChild($value)
    {
        $node = $this->searchChild($value);
        if (empty($node)) {
            $node = new node();
            $node->value = $value;
            $this->child[] = $node;
        }
        return $node;
    }
    public function searchChild($value)
    {
        foreach ($this->child as $k => $v) {
            if ($v->value == $value) {
                return $this->child[$k];
            }
        }
        return false;
    }
}

/*----添加字符串至字典树----*/
function addString(&$head, $strary)
{
    $node = null;
    $len = count($strary);
    foreach ($strary as $i => $s) {
        $end = $i != $len - 1 ? false : true;
        if ($i == 0) {
            $node = $head->addChild($s);
        } else {
            $node = $node->addChild($s);
        }
        if ($end) $node->end = $end;
    }
}

/*----查找字符串----*/
function searchString($node, $str)
{
    for ($i = 0; $i < mb_strlen($str, 'utf-8'); $i++) {
        $tmp = mb_substr($str, $i, 1, 'utf-8');
        if ($tmp != ' ') {
            $node = $node->searchChild($tmp);
            if (empty($node)) {
                return false;
            }
        }
    }
    if ($node->end == false) return false;
    else return true;
}

/*----过滤字符串----*/
function filter($str)
{
    global $head;
    $l = array();
    $r = array();
    for ($i = 0; $i < mb_strlen($str, 'utf-8'); $i++) {
        for ($j = $i; $j < mb_strlen($str, 'utf-8'); $j++) {
            $tmp = mb_substr($str, $i, $j - $i + 1, 'utf-8');
            if (searchString($head, $tmp)) {
                //$t=str_repeat('*',mb_strlen($tmp,'utf-8'));
                //$str=str_replace($tmp,$t,$str);
                $l[] = $i;
                $r[] = $j;
            }
        }
    }
    for ($i = 0; $i < count($l); $i++) {
        $t = str_repeat('*', $r[$i] - $l[$i] + 1);
        $str = str_replace(mb_substr($str, $l[$i], $r[$i] - $l[$i] + 1, 'utf-8'), $t, $str);
    }
    return $str;
}

/*----读取并处理----*/
function Trie($filename)
{
    global $head;
    $file = fopen($filename, "r");
    $str=array();
    while (!feof($file)) {
        $strt = fgets($file);
        $strt = str_replace(PHP_EOL, '', $strt);
        $ary = array();
        for ($i = 0; $i < mb_strlen($strt, 'utf-8'); $i++) {
            $ary[] = mb_substr($strt, $i, 1, 'utf-8');
        }
        array_push($str, $ary);
    }
    fclose($file);
    foreach ($str as $k => $ary) {
        addString($head, $ary);
    }
}

?>