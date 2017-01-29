<?php

namespace Component\HtmlBlock {
    use Core\Exception\WException;
    use Core\Util;

    class ListGroup {
        private $html_block;
        private $dom_element;
        private $model;
        private $title;
        private $container_class;
        private $container_style;

        public function __construct($html_block,...$kwargs) {
            $this->setHtmlBlock($html_block);

            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);

            $title = Util::get($kwargs,'title',null);
            $this->setTitle($title);

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

        public function getDomElement() {
            return $this->dom_element;
        }

        private function setDomElement($dom_element) {
            $this->dom_element = $dom_element;
        }

        private function getTitle() {
            return $this->title;
        }

        private function setTitle($title) {
            $this->title = $title;
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

        private function addContainer() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $container_class = $this->getContainerClass();
            $container_style = $this->getContainerStyle();

            $div_class_col = $html_block->createElement('div');
            $div_class_col->setAttribute('class',$container_class);
            $div_class_col->setAttribute('style',$container_style);

            $div_class_col->appendChild($dom_element);

            return $div_class_col;
        }
        
        private function addSubMenu($a_or_div_list_group_item,$model_sub,$model_sub_id,$menu_active) {
            if (empty($model_sub)) {
                return false;
            }

            $html_block = $this->getHtmlBlock();

            $ul_list_group = $html_block->createElement('ul');
            $ul_list_group->setAttribute('class','list-group');

            foreach ($model_sub as $model_sub_data) {
                $href = $model_sub_data['href'] ?? null;
                $title = $model_sub_data['title'] ?? null;
                $active = $model_sub_data['active'] ?? null;
                $icon = $model_sub_data['icon'] ?? null;

                $li_a_list_group = $html_block->createElement('a');
                $li_a_list_group->setAttribute('href',$href);
                
                if (!empty($icon)) {
                    $li_a_span_list_group = $html_block->createElement('span');
                    $li_a_span_list_group->setAttribute('class',$icon);

                    $li_a_list_group->appendChild($li_a_span_list_group);
                }

                if (!empty($active)) {
                    $strong_list_group = $html_block->createElement('strong',$title);

                    $li_a_list_group->appendChild($strong_list_group);

                } else {
                    $li_a_list_group->appendChild(new \DOMText($title));
                }

                $li_list_group = $html_block->createElement('li');

                $li_list_group->setAttribute('class','list-group-item');

                $li_list_group->appendChild($li_a_list_group);
                $ul_list_group->appendChild($li_list_group);
            }

            $div_list_group = $html_block->createElement('div');

            $div_list_group->setAttribute('class','row collapse');

            $div_list_group->setAttribute('id',$model_sub_id);
            $div_list_group->setAttribute('style','margin-top:10px;');

            $div_list_group->appendChild($ul_list_group);

            $a_or_div_list_group_item->appendChild($div_list_group);
        }

        private function ready() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            if (empty($model)) {
                return false;
            }

            foreach ($model as $model_data) {
                $href = $model_data['href'] ?? null;
                $title = $model_data['title'] ?? null;
                $content = $model_data['content'] ?? null;
                $active = $model_data['active'] ?? null;
                $icon = $model_data['icon'] ?? null;
                $model_sub = $model_data['model'] ?? null;

                $model_sub_id = uniqid();

                if (!empty($model_sub)) {
                    $a_or_div_list_group_item = $html_block->createElement('div');
                    $a_or_div_list_group_item->setAttribute('style','cursor:pointer;');
                    $a_or_div_list_group_item->setAttribute('data-toggle','collapse');
                    $a_or_div_list_group_item->setAttribute('data-target',vsprintf('#%s',[$model_sub_id,]));
                    $a_or_div_list_group_item->setAttribute('aria-expanded','false');
                    $a_or_div_list_group_item->setAttribute('aria-controls',$model_sub_id);

                } else {
                    $a_or_div_list_group_item = $html_block->createElement('a');
                    $a_or_div_list_group_item->setAttribute('href',$href);
                }

                if (!empty($active)) {
                    $a_or_div_list_group_item->setAttribute('class','list-group-item active');

                } else {
                    $a_or_div_list_group_item->setAttribute('class','list-group-item');
                }

                $a_h4_heading  = $html_block->createElement('h4');
                $a_h4_heading->setAttribute('class','list-group-item-heading');

                if (!empty($icon)) {
                    $a_h4_span_heading = $html_block->createElement('span');
                    $a_h4_span_heading->setAttribute('class',vsprintf('%s pull-right',[$icon,]));

                    $a_h4_heading->appendChild($a_h4_span_heading);
                }

                $a_h4_heading->appendChild(new \DOMText($title));

                $a_or_div_list_group_item->appendChild($a_h4_heading);

                if (!empty($content)) {
                    $a_p_heading = $html_block->createElement('p',$content);
                    $a_p_heading->setAttribute('class','list-group-item-text');

                    $a_or_div_list_group_item->appendChild($a_p_heading);
                }

                $this->addSubMenu($a_or_div_list_group_item,$model_sub,$model_sub_id,$active);

                $dom_element->appendChild($a_or_div_list_group_item);
            }

            $add_container = $this->addContainer();
            $this->setDomElement($add_container);
        }

        public function renderHtml() {
            $html_block = $this->getHtmlBlock();

            $html_block->appendBody($this);

            return $html_block->renderHtml();
        }
    }
}
