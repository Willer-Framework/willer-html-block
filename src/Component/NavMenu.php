<?php

namespace Component\HtmlBlock {
    use Core\Util;
    use \DOMDocument as DOMDocument;

    class NavMenu {
        private $dom_document;
        private $dom_element;
        private $model;
        private $container_class;
        private $container_style;
        private $encoding;
        private $id;
        private $class;
        private $style;

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $encoding = $util->contains($kwargs,'encoding')->getString('UTF-8');
            $this->setEncoding($encoding);

            $model = $util->contains($kwargs,'model')->getArray();
            $this->setModel($model);

            $container_class = $util->contains($kwargs,'container_class')->getString();
            $this->setContainerClass($container_class);

            $container_style = $util->contains($kwargs,'container_style')->getString();
            $this->setContainerStyle($container_style);

            $id = $util->contains($kwargs,'id')->getString();
            $this->setId($id);

            $class = $util->contains($kwargs,'class')->getString('nav nav-tabs');
            $this->setClass($class);

            $style = $util->contains($kwargs,'style')->getString();
            $this->setStyle($style);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $dom_element = $dom_document->createElement('ul');
            $dom_element->setAttribute('id',$id);
            $dom_element->setAttribute('class',$class);
            $dom_element->setAttribute('style',$style);

            $this->setDomElement($dom_element);
            $this->ready();

            return $this;
        }

        private function getDomDocument() {
            return $this->dom_document;
        }

        private function setDomDocument($dom_document) {
            $this->dom_document = $dom_document;
        }

        public function getEncoding() {
            return $this->encoding;
        }

        public function setEncoding($encoding) {
            $this->encoding = $encoding;

            return $this;
        }

        public function getDomElement() {
            return $this->dom_element;
        }

        private function setDomElement($dom_element) {
            $this->dom_element = $dom_element;
        }

        private function getModel() {
            return $this->model;
        }

        private function setModel($model) {
            $this->model = $model;
        }

        private function getId() {
            return $this->id;
        }

        private function setId($id) {
            $this->id = $id;
        }

        private function getClass() {
            return $this->class;
        }

        private function setClass($class) {
            $this->class = $class;
        }

        private function getStyle() {
            return $this->style;
        }

        private function setStyle($style) {
            $this->style = $style;
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
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $container_class = $this->getContainerClass();
            $container_style = $this->getContainerStyle();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class',$container_class);
            $div->setAttribute('style',$container_style);

            $div->appendChild($dom_element);

            return $div;
        }

        private function ready() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            if (empty($model)) {
                return;
            }

            $util = new Util;

            foreach ($model as $model_data) {
                $href = $util->contains($model_data,'href')->getString();
                $title = $util->contains($model_data,'title')->getString();
                $active = $util->contains($model_data,'active')->getBoolean();
                $icon = $util->contains($model_data,'icon')->getString();
                $model = $util->contains($model_data,'model')->getArray();

                $active_class = '';

                if (!empty($active)) {
                    $active_class = 'active';
                }

                $li = $dom_document->createElement('li');

                if (!empty($model)) {
                    $li->setAttribute('role','presentation');
                    $li->setAttribute('class',vsprintf('dropdown %s',[$active_class,]));

                    $a = $dom_document->createElement('a');
                    $a->setAttribute('class','dropdown-toggle');
                    $a->setAttribute('data-toggle','dropdown');
                    $a->setAttribute('href','#');
                    $a->setAttribute('role','button');
                    $a->setAttribute('aria-haspopup','true');
                    $a->setAttribute('aria-expanded','false');

                    if (!empty($icon)) {
                        $span = $dom_document->createElement('span');
                        $span->setAttribute('class',$icon);
                        $span->setAttribute('aria-hidden','true');

                        $a->appendChild($span);
                    }

                    $span = $dom_document->createElement('span');
                    $span->setAttribute('class','caret');

                    $a->appendChild(new \DOMText($title));
                    $a->appendChild($span);

                    $li->appendChild($a);

                    $ul = $dom_document->createElement('ul');
                    $ul->setAttribute('class','dropdown-menu');

                    foreach ($model as $model_sub_data) {
                        $href_sub = $util->contains($model_sub_data,'href')->getString();
                        $title_sub = $util->contains($model_sub_data,'title')->getString();
                        $active_sub = $util->contains($model_sub_data,'active')->getBoolean();
                        $icon_sub = $util->contains($model_sub_data,'icon')->getString();

                        $active_sub_class= '';

                        if (!empty($active_sub)) {
                            $active_sub_class= 'active';
                        }

                        $li_sub = $dom_document->createElement('li');
                        $li_sub->setAttribute('class',$active_sub_class);

                        $a_sub = $dom_document->createElement('a');
                        $a_sub->setAttribute('href',$href_sub);

                        if (!empty($icon_sub)) {
                            $span = $dom_document->createElement('span');
                            $span->setAttribute('class',$icon_sub);
                            $span->setAttribute('aria-hidden','true');

                            $a_sub->appendChild($span);
                        }

                        $a_sub->appendChild(new \DOMText($title_sub));

                        $li_sub->appendChild($a_sub);

                        $ul->appendChild($li_sub);
                    }

                    $li->appendChild($ul);

                } else {
                    $li->setAttribute('role','presentation');
                    $li->setAttribute('class',$active_class);

                    $a = $dom_document->createElement('a');
                    $a->setAttribute('href',$href);

                    if (!empty($icon)) {
                        $span = $dom_document->createElement('span');
                        $span->setAttribute('class',$icon);
                        $span->setAttribute('aria-hidden','true');

                        $a->appendChild($span);
                    }

                    $a->appendChild(new \DOMText($title));

                    $li->appendChild($a);
                }

                $dom_element->appendChild($li);
            }

            $add_container = $this->addContainer();

            $this->setDomElement($add_container);

            return;
        }

        public function renderHtml() {
            $dom_document = $this->getDomDocument();

            return $dom_document->saveHTML();
        }
    }
}
