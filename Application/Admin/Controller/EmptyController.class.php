<?php
namespace Admin\Controller;

use Think\Controller;

class EmptyController extends Controller
{
    public function _empty()
    {
        $this->redirect('indexadm.php/Alerterror/error404');
    }


}