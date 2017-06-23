<?php

namespace Component\HtmlBlock {
    use Core\Util;
    use \DOMDocument as DOMDocument;

    class Nav {
        private $dom_document;
        private $dom_element;
        private $model;
        private $navbar_direction;
        private $title;
        private $title_small;
        private $title_url;
        private $title_img;
        private $container_class;
        private $container_style;

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $encoding = $util->contains($kwargs,'encoding')->getString('UTF-8');
            $this->setEncoding($encoding);

            $model = $util->contains($kwargs,'model')->getArray();
            $this->setModel($model);

            $navbar_direction = $util->contains($kwargs,'navbar_direction')->getString();
            $this->setNavBarDirection($navbar_direction);

            $title = $util->contains($kwargs,'title')->getString();
            $this->setTitle($title);

            $title_small = $util->contains($kwargs,'title_small')->getString();
            $this->setTitleSmall($title_small);

            $title_url = $util->contains($kwargs,'title_url')->getString();
            $this->setTitleUrl($title_url);

            $title_img = $util->contains($kwargs,'title_img')->getString();
            $this->setTitleImg($title_img);

            $container_class = $util->contains($kwargs,'container_class')->getString();
            $this->setContainerClass($container_class);

            $container_style = $util->contains($kwargs,'container_style')->getString();
            $this->setContainerStyle($container_style);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $dom_element = $dom_document->createElement('nav');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','navbar navbar-default');
            }

            if (isset($kwargs['style']) && !empty($kwargs['style'])) {
                $dom_element->setAttribute('style',$kwargs['style']);
            }

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

        private function getNavBarDirection() {
            return $this->navbar_direction;
        }

        private function setNavBarDirection($navbar_direction) {
            $this->navbar_direction = $navbar_direction;
        }

        private function getTitle() {
            return $this->title;
        }

        private function setTitle($title) {
            $this->title = $title;
        }

        private function getTitleSmall() {
            return $this->title_small;
        }

        private function setTitleSmall($title_small) {
            $this->title_small = $title_small;
        }

        private function getTitleUrl() {
            return $this->title_url;
        }

        private function setTitleUrl($title_url) {
            $this->title_url = $title_url;
        }

        private function getTitleImg() {
            return $this->title_img;
        }

        private function setTitleImg($title_img) {
            $this->title_img = $title_img;
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

            $div_class_col = $dom_document->createElement('div');
            $div_class_col->setAttribute('class',$container_class);
            $div_class_col->setAttribute('style',$container_style);

            $div_class_col->appendChild($dom_element);

            return $div_class_col;
        }

        private function ready() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();
            $navbar_direction = $this->getNavBarDirection();
            $title = $this->getTitle();
            $title_small = $this->getTitleSmall();
            $title_url = $this->getTitleUrl();
            $title_img = $this->getTitleImg();

            $div_container_fluid = $dom_document->createElement('div');
            $div_container_fluid->setAttribute('class','container-fluid');

            $div_header = $dom_document->createElement('div');
            $div_header->setAttribute('class','navbar-header');

            if (!empty($title)) {
                if (!empty($title_img)) {
                    $img_brand = $dom_document->createElement('img');
                    $img_brand->setAttribute('src',$title_img);
                    $img_brand->setAttribute('alt',$title);
                    $img_brand->setAttribute('style','float: left;margin-right: 5px;');
                    $img_brand->setAttribute('width','20');
                    $img_brand->setAttribute('height','20');

                    $a_brand = $dom_document->createElement('a');
                    $a_brand->appendChild($img_brand);

                    $a_brand->appendChild(new \DOMText($title));

                    if (!empty($title_small)) {
                        $title_small = $dom_document->createElement('small',$title_small);

                        $a_brand->appendChild($title_small);
                    }

                } else {
                    $a_brand = $dom_document->createElement('a',$title);

                    if (!empty($title_small)) {
                        $title_small = $dom_document->createElement('small',$title_small);

                        $a_brand->appendChild($title_small);
                    }
                }

                $a_brand->setAttribute('class','navbar-brand');
                $a_brand->setAttribute('href',$title_url);

                $div_header->appendChild($a_brand);
            }

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
