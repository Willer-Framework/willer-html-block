<?php

namespace Component\HtmlBlock {
    use Core\Util;
    use Core\Exception\WException;
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

            $dom_element = $dom_document->createElement('nav');

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

            $div_container_fluid = $dom_document->createElement('div');
            $div_container_fluid->setAttribute('class','container-fluid');

            $div_header = $dom_document->createElement('div');
            $div_header->setAttribute('class','navbar-header');

            $div_container_fluid->appendChild($div_header);

            $div_collapse_id = vsprintf('%s-navbar-collapse',[mt_rand()]);

            $div_collapse = $dom_document->createElement('div');
            $div_collapse->setAttribute('class','navbar-collapse collapse');
            $div_collapse->setAttribute('id',$div_collapse_id);

            if (!empty($model)) {
                $button_navbar_toggle_collapsed = $dom_document->createElement('button');
                $button_navbar_toggle_collapsed->setAttribute('type','button');
                $button_navbar_toggle_collapsed->setAttribute('class','navbar-toggle collapsed');
                $button_navbar_toggle_collapsed->setAttribute('data-toggle','collapse');
                $button_navbar_toggle_collapsed->setAttribute('data-target','#'.$div_collapse_id);
                $button_navbar_toggle_collapsed->setAttribute('aria-expanded','false');

                $span_sr_only = $dom_document->createElement('span','Toggle navigation');
                $span_sr_only->setAttribute('class','sr-only');

                $span_icon_bar_1 = $dom_document->createElement('span');
                $span_icon_bar_1->setAttribute('class','icon-bar');

                $span_icon_bar_2 = $dom_document->createElement('span');
                $span_icon_bar_2->setAttribute('class','icon-bar');

                $span_icon_bar_3 = $dom_document->createElement('span');
                $span_icon_bar_3->setAttribute('class','icon-bar');

                $button_navbar_toggle_collapsed->appendChild($span_sr_only);
                $button_navbar_toggle_collapsed->appendChild($span_icon_bar_1);
                $button_navbar_toggle_collapsed->appendChild($span_icon_bar_2);
                $button_navbar_toggle_collapsed->appendChild($span_icon_bar_3);

                $div_header->appendChild($button_navbar_toggle_collapsed);

                $ul_navbar = $dom_document->createElement('ul');

                if (empty($navbar_direction)) {
                    $ul_navbar->setAttribute('class','nav navbar-nav');

                } else {
                    $ul_navbar->setAttribute('class',vsprintf('nav navbar-nav %s',[$navbar_direction,]));
                }

                foreach ($model as $model_data) {
                    $href = $model_data['href'] ?? null;
                    $icon = $model_data['icon'] ?? null;
                    $title = $model_data['title'] ?? null;
                    $active = $model_data['active'] ?? null;
                    $model = $model_data['model'] ?? null;

                    $ul_li_navbar = $dom_document->createElement('li');

                    if (!empty($active)) {
                        $ul_li_navbar->setAttribute('class','active');
                    }

                    $ul_li_a_navbar = $dom_document->createElement('a');

                    if (!empty($icon)) {
                        $ul_li_a_span_navbar = $dom_document->createElement('span');
                        $ul_li_a_span_navbar->setAttribute('class',$icon);

                        $ul_li_a_navbar->appendChild($ul_li_a_span_navbar);
                    }

                    $ul_li_a_navbar->appendChild(new \DOMText($title));

                    if (empty($model)) {
                        $ul_li_a_navbar->setAttribute('href',$href);

                        $ul_li_navbar->appendChild($ul_li_a_navbar);

                    } else {
                        if (!empty($active)) {
                            $ul_li_navbar->setAttribute('class','dropdown active');

                        } else {
                            $ul_li_navbar->setAttribute('class','dropdown');
                        }

                        $ul_li_a_navbar->setAttribute('class','dropdown-toggle');
                        $ul_li_a_navbar->setAttribute('data-toggle','dropdown');
                        $ul_li_a_navbar->setAttribute('role','button');
                        $ul_li_a_navbar->setAttribute('aria-haspopup','true');
                        $ul_li_a_navbar->setAttribute('aria-expanded','false');

                        $ul_li_a_span_navbar = $dom_document->createElement('span');
                        $ul_li_a_span_navbar->setAttribute('class','caret');

                        $ul_li_a_navbar->appendChild($ul_li_a_span_navbar);

                        $ul_li_navbar->appendChild($ul_li_a_navbar);

                        $ul_li_ul_navbar = $dom_document->createElement('ul');
                        $ul_li_ul_navbar->setAttribute('class','dropdown-menu');

                        foreach ($model as $model_data) {
                            $href = $model_data['href'] ?? null;
                            $icon = $model_data['icon'] ?? null;
                            $title = $model_data['title'] ?? null;
                            $active = $model_data['active'] ?? null;

                            $ul_li_ul_li_navbar = $dom_document->createElement('li');

                            if (!empty($active)) {
                                $ul_li_ul_li_navbar->setAttribute('class','active');
                            }

                            $ul_li_ul_li_a_navbar = $dom_document->createElement('a');
                            $ul_li_ul_li_a_navbar->setAttribute('href',$href);

                            if (!empty($icon)) {
                                $ul_li_ul_li_a_span_navbar = $dom_document->createElement('span');
                                $ul_li_ul_li_a_span_navbar->setAttribute('class',$icon);

                                $ul_li_ul_li_a_navbar->appendChild($ul_li_ul_li_a_span_navbar);
                            }

                            $ul_li_ul_li_a_navbar->appendChild(new \DOMText($title));

                            $ul_li_ul_li_navbar->appendChild($ul_li_ul_li_a_navbar);

                            $ul_li_ul_navbar->appendChild($ul_li_ul_li_navbar);
                        }

                        $ul_li_navbar->appendChild($ul_li_ul_navbar);
                    }

                    $ul_navbar->appendChild($ul_li_navbar);
                }

                $div_collapse->appendChild($ul_navbar);
            }

            $div_container_fluid->appendChild($div_collapse);

            $dom_element->appendChild($div_container_fluid);

            $add_container = $this->addContainer();
            $this->setDomElement($add_container);
        }

        public function renderHtml() {
            $dom_document = $this->getDomDocument();

            return $dom_document->saveHTML();
        }
    }
}
