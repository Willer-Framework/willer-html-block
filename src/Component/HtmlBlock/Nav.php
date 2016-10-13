<?php

namespace Component\HtmlBlock {
    use Core\Exception\WException;
    use Core\Util;

    class Nav {
        private $html_block;
        private $dom_element;
        private $model;
        private $navbar_direction;
        private $title;
        private $title_url;
        private $title_img;

        public function __construct($html_block,...$kwargs) {
            $this->setHtmlBlock($html_block);

            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);

            $navbar_direction = Util::get($kwargs,'navbar_direction',null);
            $this->setNavBarDirection($navbar_direction);

            $title = Util::get($kwargs,'title',null);
            $this->setTitle($title);

            $title_url = Util::get($kwargs,'title_url',null);
            $this->setTitleUrl($title_url);

            $title_img = Util::get($kwargs,'title_img',null);
            $this->setTitleImg($title_img);

            $dom_element = $html_block->createElement('nav');

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

        private function getHtmlBlock() {
            return $this->html_block;
        }

        private function setHtmlBlock($html_block) {
            $this->html_block = $html_block;
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

        private function ready() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();
            $navbar_direction = $this->getNavBarDirection();
            $title = $this->getTitle();
            $title_url = $this->getTitleUrl();
            $title_img = $this->getTitleImg();

            $div_container_fluid = $html_block->createElement('div');
            $div_container_fluid->setAttribute('class','container-fluid');

            $div_header = $html_block->createElement('div');
            $div_header->setAttribute('class','navbar-header');

            if (!empty($title)) {
                if (!empty($title_img)) {
                    $img_brand = $html_block->createElement('img');
                    $img_brand->setAttribute('src',$title_img);
                    $img_brand->setAttribute('alt',$title);

                    $a_brand = $html_block->createElement('a');
                    $a_brand->appendChild($img_brand);

                } else {
                    $a_brand = $html_block->createElement('a',$title);
                }

                $a_brand->setAttribute('class','navbar-brand');
                $a_brand->setAttribute('href',$title_url);

                $div_header->appendChild($a_brand);
            }

            $div_container_fluid->appendChild($div_header);

            $div_collapse = $html_block->createElement('div');
            $div_collapse->setAttribute('class','navbar-collapse collapse');
            $div_collapse->setAttribute('id',vsprintf('%s-navbar-collapse',[mt_rand()]));

            if (!empty($model)) {
                $ul_navbar = $html_block->createElement('ul');

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

                    $ul_li_navbar = $html_block->createElement('li');

                    if (!empty($active)) {
                        $ul_li_navbar->setAttribute('class','active');
                    }

                    $ul_li_a_navbar = $html_block->createElement('a');

                    if (!empty($icon)) {
                        $ul_li_a_span_navbar = $html_block->createElement('span');
                        $ul_li_a_span_navbar->setAttribute('class',$icon);

                        $ul_li_a_navbar->appendChild($ul_li_a_span_navbar);
                    }

                    $ul_li_a_navbar->appendChild(new \DOMText($title));

                    if (empty($model)) {
                        $ul_li_a_navbar->setAttribute('href',$href);

                        $ul_li_navbar->appendChild($ul_li_a_navbar);

                    } else {
                        $ul_li_navbar->setAttribute('class','dropdown');

                        $ul_li_a_navbar->setAttribute('class','dropdown-toggle');
                        $ul_li_a_navbar->setAttribute('data-toggle','dropdown');
                        $ul_li_a_navbar->setAttribute('role','button');
                        $ul_li_a_navbar->setAttribute('aria-haspopup','true');
                        $ul_li_a_navbar->setAttribute('aria-expanded','false');

                        $ul_li_a_span_navbar = $html_block->createElement('span');
                        $ul_li_a_span_navbar->setAttribute('class','caret');

                        $ul_li_a_navbar->appendChild($ul_li_a_span_navbar);

                        $ul_li_navbar->appendChild($ul_li_a_navbar);

                        $ul_li_ul_navbar = $html_block->createElement('ul');
                        $ul_li_ul_navbar->setAttribute('class','dropdown-menu');

                        foreach ($model as $model_data) {
                            $href = $model_data['href'] ?? null;
                            $icon = $model_data['icon'] ?? null;
                            $title = $model_data['title'] ?? null;
                            $active = $model_data['active'] ?? null;

                            $ul_li_ul_li_navbar = $html_block->createElement('li');

                            if (!empty($active)) {
                                $ul_li_ul_li_navbar->setAttribute('class','active');
                            }

                            $ul_li_ul_li_a_navbar = $html_block->createElement('a');
                            $ul_li_ul_li_a_navbar->setAttribute('href',$href);

                            if (!empty($icon)) {
                                $ul_li_ul_li_a_span_navbar = $html_block->createElement('span');
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
        }

        public function renderHtml() {
            $html_block = $this->getHtmlBlock();

            $html_block->appendBody($this);

            return $html_block->renderHtml();
        }
    }
}
