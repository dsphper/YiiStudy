<?php
class Routes
{
    //PathInfo 路由规则
    static public function path_info()
    {
        $_GET['m'] = empty($_GET['m']) ? 'index' : $_GET['m'];
        $_GET['c'] = empty($_GET['c']) ? 'index' : $_GET['c'];
        //执行l 路由规则
        self::Lroute();
        !empty($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] = $_SERVER['PATH_INFO'];
        if (!empty($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
            $get  = explode('/', $path);
            unset($get[0]);
            foreach ($get as $key => $value) {
                if ($key > 0) {
                    if (count($get) == 1) {
                        $_GET['c'] = $get[1];
                    } else if (count($get) == 2) {
                        $_GET['c'] = $get[1];
                        $_GET['a'] = $get[2];
                    } else if (count($get) > 2) {
                        $_GET['m'] = $get[1];
                        $_GET['c'] = $get[2];
                        $_GET['a'] = $get[3];
                    }
                }
                if (count($get) > 4) {
                    $param = array_slice($get, 3);
                    $param = array_filter($param);
                    for ($i = 0; $i < count($param); $i += 2) {
                        if ($param[$i] != 'm' && $param[$i] != 'a' && $param[$i] != 'c' && !empty($param[$i + 1])) {
                            $_GET[$param[$i]] = $param[$i + 1];
                        }
                    }
                }
            }
        }
    }
    //l url访问模式处理器
    static private function Lroute()
    {
        if (!empty($_GET['l'])) {
            $l = $_GET['l'];
            $l = explode('/', $l);
            if (count($l) >= 3) {
                $_GET['m'] = !empty($l[0]) ? $l[0] : '';
                $_GET['c'] = !empty($l[1]) ? $l[1] : '';
                $_GET['a'] = !empty($l[2]) ? $l[2] : '';
            } else if (count($l) == 2) {
                $_GET['c'] = !empty($l[0]) ? $l[0] : '';
                $_GET['a'] = !empty($l[1]) ? $l[1] : '';
            } else if (count($l) == 1) {
                $_GET['c'] = 'index';
                $_GET['a'] = !empty($l[1]) ? $l[1] : '';
            }
            if (count($l) > 3) {
                $param = array_slice($l, 3);
                $param = array_filter($param);
                for ($i = 0; $i < count($param); $i += 2) {
                    if ($param[$i] != 'm' && $param[$i] != 'a' && $param[$i] != 'c' && !empty($param[$i + 1])) {
                        $_GET[$param[$i]] = $param[$i + 1];
                    }
                }
            }
        }
    }
    //路由处理函数
    static public function route()
    {
        //判断访问模式
        if (!empty($_SERVER['PATH_INFO'])) {
            $_SESSION['urlmodel'] = 2;
        } else if (!empty($_GET['l'])) {
            $_SESSION['urlmodel'] = 3;
        } else {
            $_SESSION['urlmodel'] = 1;
        }
        // self::path_info(); l=index/index/index
        if (!empty($_GET['c']) && !empty($_GET['a'])) {
            $c = ucfirst($_GET['c']) . "Controller";
            $a = ucfirst($_GET['a']);
        } else if (!empty($_GET['c'])) {
            $c = ucfirst($_GET['c']) . "Controller";
            $a = 'index';
        } else {
            $c = 'index' . "Controller";
            $a = 'index';
        }
        $con = new $c;
        if (!method_exists($con, $a)) {
            die($a . '方法不存在！');
        } else if (!method_exists($con, $a)) {
            die('error');
        }
        $con->$a();
    }
}

