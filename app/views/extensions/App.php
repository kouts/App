<?php

class views_extensions_App extends Twig_Extension
{
    private $environment;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getName()
    {
        return 'app_extension';
    }    

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('t', 'Translation::get'),
        );
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('uri', 'Request::uri'),
            new Twig_SimpleFunction('link', 'Lang::link'),
            new Twig_SimpleFunction('link_active', array($this, 'link_active')),
            new Twig_SimpleFunction('display_flash_messages', array($this, 'display_flash_messages'))
        );
    }

    public function link_active($link, $active_class='active'){
        if(Lang::link($link) == Request::uri()){
            return $active_class;
        }
    }

    public function display_flash_messages($template = null, $type='all'){
        if(!$template){
            $template = 'blocks/js_msg.tpl';
        }
        $msgs = Msg::get($type);
        if(!empty($msgs)){
            $this->environment->display($template, array('msgs' => $msgs));
        }
        Msg::clear();
    }


}