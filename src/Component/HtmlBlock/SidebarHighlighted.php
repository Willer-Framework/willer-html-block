<?php

namespace Component\HtmlBlock {
    use Core\Exception\WException;
    use Core\Util;

    class SidebarHighlighted {
        private $html_block;
        private $dom_element;
        private $model;
        private $container_class;
        private $container_style;

        public function __construct($html_block,...$kwargs) {
            $this->setHtmlBlock($html_block);

            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);

            $container_class = Util::get($kwargs,'container_class',null);
            $this->setContainerClass($container_class);
 
            $container_style = Util::get($kwargs,'container_style',null);
            $this->setContainerStyle($container_style);

            $dom_element = $html_block->createElement('div');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','list-group');
            }

            if (isset($kwargs['style']) && !empty($kwargs['style'])) {
                $dom_element->setAttribute('style',$kwargs['style']);
            }

            $this->setDomElement($dom_element);
            $this->ready();

            return $this;
        }

        private function getHtmlBlock() {
            return $this->html_block;
        }

        private function setHtmlBlock($html_block) {
            $this->html_block = $html_block;
        }

        private function getModel() {
            return $this->model;
        }

        private function setModel($model) {
            $this->model = $model;
        }

        private function getContainerClass() {
            return $this->container_class;
        }
 
        private function setContainerClass($container_class) {
            $this->container_class = $container_class;
        }
 
        private function getContainerStyle() {
            return $this->container_style;
        }
 
        private function setContainerStyle($container_style) {
            $this->container_style = $container_style;
        }

        public function getDomElement() {
            return $this->dom_element;
        }

        private function setDomElement($dom_element) {
            $this->dom_element = $dom_element;
        }

        private function addContainer() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $container_class = $this->getContainerClass();
            $container_style = $this->getContainerStyle();
 
            $div_class_col = $html_block->createElement('div');
            $div_class_col->setAttribute('class',$container_class);
            $div_class_col->setAttribute('style',$container_style);
 
            $div_class_col->appendChild($dom_element);
 
            $this->setDomElement($div_class_col);
        }

        private function ready() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            foreach ($model as $data) {
                $title = Util::get($data,'title',null);
                $text = Util::get($data,'text',null);
                $url = Util::get($data,'url',null);
                $active = Util::get($data,'active',false);

                $a_menu = $html_block->createElement('a');

                if (!empty($active)) {
                    $a_menu->setAttribute('class','list-group-item active');

                } else {
                    $a_menu->setAttribute('class','list-group-item');
                }

                $a_menu->setAttribute('href',$url);

                $a_h4_menu = $html_block->createElement('h4',$title);
                $a_h4_menu->setAttribute('class','list-group-item-heading');

                $a_p_menu = $html_block->createElement('p',$text);
                $a_p_menu->setAttribute('class','list-group-item-text');

                $a_menu->appendChild($a_h4_menu);
                $a_menu->appendChild($a_p_menu);

                $dom_element->appendChild($a_menu);
            }

            $this->addContainer();
        }

        public function renderHtml() {
            $html_block = $this->getHtmlBlock();

            $html_block->appendBodyContainerRow($this);

            return $html_block->renderHtml();
        }
    }
}
