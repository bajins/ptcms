<?php

class ConfigModel extends Model {

    /**
     * 插入数据
     *
     * @param $param
     * @return mixed
     */
    public function add($param) {
        $res=$this->insert($param);
        $this->createConfigFile();
        return $res;
    }

    /**
     * 修改
     *
     * @param $param
     * @return mixed
     */
    public function edit($param) {
        $res=$this->update($param);
        $this->createConfigFile();
        return $res;
    }

    /**
     * 删除数据
     *
     * @param $where
     */
    public function del($where) {
        $this->where($where)->delete();
        $this->createConfigFile();
    }

    public function getlist() {
        $config_group=C('config_group');
        $list = $this->select();
        foreach ($list as &$v) {
            //后台
            $v['groupname']=$config_group[$v['group']];
            $v['create_username'] = dc::get('user', $v['create_user_id'], 'name');
            $v['update_username'] = dc::get('user', $v['update_user_id'], 'name');
            $v['url_edit'] = U('admin.config.edit', array('id' => $v['id']));
            $v['create_time'] = $v['create_time'] ? date('Y-m-d H:i', $v['create_time']) : '';
            $v['update_time'] = $v['update_time'] ? date('Y-m-d H:i', $v['update_time']) : '';
        }
        return $list;
    }

    public function createConfigFile() {
        $list=$this->field('key,value,type')->where(array('status'=>1,'level'=>1))->select();
        $config=array();
        foreach($list as $v){
            if ($v['type']=='array'){
                $config[$v['key']]=json_decode($v['value'],true);
            }else{
                $config[$v['key']]=$v['value'];
            }
        }
        if($config){
            F(APP_PATH.'/common/config.php',$config);
            F(CACHE_PATH.'/pt_runtime.php',null);
        }
    }

    public function parseConfigValue($list) {
        foreach($list as &$item){
            if (in_array($item['type'],array('radio','checkbox'))){
                $value=($item['type']=='checkbox')?explode(',',trim($item['value'])):array($item['value']);
                $tmp=explode("\n",trim($item['extra']));
                foreach($tmp as $q){
                    $t=explode(':',trim($q));
                    $item['list'][]=array(
                        'value'=>$t['0'],
                        'title'=>empty($t['1'])?'___':$t['1'],
                        'status'=>in_array($t['0'],$value)?'checked':'',
                    );
                }
            }elseif($item['type']=='select'){
                $tmp=json_decode($item['extra'],true);
                foreach($tmp as $kkk=>$vvv){
                    $item['list'][]=array(
                        'value'=>$kkk,
                        'title'=>$vvv,
                        'status'=>($item['value']==$kkk)?'selected':'',
                    );
                }
            }
        }
        return $list;
    }

    public function seturl($list)
    {
        $param=rewrite::format($list);
        $config = array_flip(C('pagesign'));
        $rules=array(C('default_module').'.index.index'=>'/');
        foreach($param as $k=>$v){
            $this->where(array('key'=>$k))->edit(array('value'=>$v));
            $k=substr($k,4);
            if (isset($config[$k])){
                $rules[$config[$k]]=$v;
            }
        }
        $res=$this->where(array('key'=>'url_rules'))->edit(array('value'=>json_encode($rules)));
        $router=rewrite::createrouter($rules);
        $this->where(array('key'=>'url_router'))->edit(array('value'=>json_encode($router)));
    }

    public function settkd($arr)
    {
        $alias=array(
            '{sitename}'=>'{$pt.config.sitename}',
            '{siteurl}'=>'{$pt.config.siteurl}',
            '{novelname}'=>'{$novel.name}',
            '{author}'=>'{$author.name}',
            '{topname}'=>'{$top.name}',
            '{topkey}'=>'{$top.key}',
            '{categoryname}'=>'{$category.name}',
            '{pagetitle}'=>'{$name}',
            '{fromname}'=>'{$sitename}',
            '{searchkey}'=>'{$searchkey}',
            '{chaptername}'=>'{$chapter.name}',
        );
        foreach($arr as $k=>$v){
            $tkdval=str_replace(
                array_keys($alias),
                array_values($alias),
                $v
            );
            $this->where(array('key'=>$k))->edit(array('value'=>json_encode($v)));
            $this->where(array('key'=>substr($k,0,-8)))->edit(array('value'=>json_encode($tkdval)));
        }
    }
}