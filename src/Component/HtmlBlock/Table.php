<?php

namespace Component\HtmlBlock {
    use Core\{Request,Util};
    use Core\Exception\WException;

    class Table {
        private $html_block;
        private $dom_element;
        private $model;
        private $column;
        private $button;
        private $button_extra;
        private $id;
        private $title;
        private $text;
        private $footer;
        private $container_class;
        private $container_style;
        private $node_table_thead;
        private $node_table_tbody;
        private $node_table_tfoot;
        private $node_panel_body;
        private $node_container;

        public function __construct($html_block,...$kwargs) {
            $this->setHtmlBlock($html_block);

            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);

            $column = Util::get($kwargs,'column',null);
            $this->setColumn($column);

            $button = Util::get($kwargs,'button',null);
            $this->setButton($button);

            $button_extra = Util::get($kwargs,'button_extra',null);
            $this->setButtonExtra($button_extra);

            $title = Util::get($kwargs,'title',null);
            $this->setTitle($title);

            $text = Util::get($kwargs,'text',null);
            $this->setText($text);

            $footer = Util::get($kwargs,'footer',null);
            $this->setFooter($footer);

            $container_class = Util::get($kwargs,'container_class',null);
            $this->setContainerClass($container_class);

            $container_style = Util::get($kwargs,'container_style',null);
            $this->setContainerStyle($container_style);

            $dom_element = $html_block->createElement('table');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
                $this->setId($kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','table table-striped table-bordered table-hover table-condensed');
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

        private function getColumn() {
            return $this->column;
        }

        private function setColumn($column) {
            $this->column = $column;
        }

        private function getButton() {
            return $this->button;
        }

        private function setButton($button) {
            $this->button = $button;
        }

        private function getButtonExtra() {
            return $this->button_extra;
        }

        private function setButtonExtra($button_extra) {
            $this->button_extra = $button_extra;
        }

        private function getId() {
            return $this->id;
        }

        private function setId($id) {
            $this->id = $id;
        }

        private function getTitle() {
            return $this->title;
        }

        private function setTitle($title) {
            $this->title = $title;
        }

        private function getText() {
            return $this->text;
        }

        private function setText($text) {
            $this->text = $text;
        }

        private function getFooter() {
            return $this->footer;
        }

        private function setFooter($footer) {
            $this->footer = $footer;
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

        private function getNodeTableThead() {
            return $this->node_table_thead;
        }

        private function setNodeTableThead($node_table_thead) {
            $this->node_table_thead = $node_table_thead;
        }

        private function getNodeTableTbody() {
            return $this->node_table_tbody;
        }

        private function setNodeTableTbody($node_table_tbody) {
            $this->node_table_tbody = $node_table_tbody;
        }

        private function getNodeTableTfoot() {
            return $this->node_table_tfoot;
        }

        private function setNodeTableTfoot($node_table_tfoot) {
            $this->node_table_tfoot = $node_table_tfoot;
        }

        private function getNodePanelBody() {
            return $this->node_panel_body;
        }

        private function setNodePanelBody($node_panel_body) {
            $this->node_panel_body = $node_panel_body;
        }

        private function getNodeContainer() {
            return $this->node_container;
        }

        private function setNodeContainer($node_container) {
            $this->node_container = $node_container;
        }

        private function addButton() {
            $model = $this->getModel();
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $element_id = $this->getId();
            $button = $this->getButton();
            $button_extra = $this->getButtonExtra();

            if (empty($button) && empty($button_extra)) {
                return false;
            }

            if (!empty($button)) {
                $div_button_group = $html_block->createElement('div');
                $div_button_group->setAttribute('class','btn-group pull-left');
                $div_button_group->setAttribute('role','group');
                $div_button_group->setAttribute('aria-label','');
            }

            $request = new Request;

            if (!empty($button) && array_key_exists('add',$button)) {
                $href = !empty($button['add']) ? $button['add'] : vsprintf('?%s-add=1',[$element_id]);

                $a_div_button_group = $html_block->createElement('a');
                $a_div_button_group->setAttribute('href',$href);
                $a_div_button_group->setAttribute('id',vsprintf('%s-add',[$element_id]));
                $a_div_button_group->setAttribute('role','button');
                $a_div_button_group->setAttribute('class','btn btn-default btn-xs');

                $span_button_div_button_group = $html_block->createElement('span');
                $span_button_div_button_group->setAttribute('class','glyphicon glyphicon-plus');
                $span_button_div_button_group->setAttribute('aria-hidden','true');

                $a_div_button_group->appendChild($span_button_div_button_group);
                $div_button_group->appendChild($a_div_button_group);
            }

            if (!empty($button) && array_key_exists('refresh',$button)) {
                $href = !empty($button['refresh']) ? $button['refresh'] : vsprintf('?%s-refresh=1',[$element_id]);

                $a_div_button_group = $html_block->createElement('a');
                $a_div_button_group->setAttribute('href',$href);
                $a_div_button_group->setAttribute('id',vsprintf('%s-refresh',[$element_id]));
                $a_div_button_group->setAttribute('role','button');
                $a_div_button_group->setAttribute('class','btn btn-default btn-xs');

                $span_button_div_button_group = $html_block->createElement('span');
                $span_button_div_button_group->setAttribute('class','glyphicon glyphicon-refresh');
                $span_button_div_button_group->setAttribute('aria-hidden','true');

                $a_div_button_group->appendChild($span_button_div_button_group);
                $div_button_group->appendChild($a_div_button_group);
            }

            if (!empty($button_extra)) {
                $div_button_extra_group = $html_block->createElement('div');
                $div_button_extra_group->setAttribute('class','btn-group pull-right');
                $div_button_extra_group->setAttribute('role','group');
                $div_button_extra_group->setAttribute('aria-label','');

                foreach ($button_extra as $key_name => $data) {
                    $a_div_button_extra_group = $html_block->createElement('a',Util::get($data,'label',null));
                    $a_div_button_extra_group->setAttribute('href',Util::get($data,'href',null));
                    $a_div_button_extra_group->setAttribute('id',Util::get($data,'id',null));
                    $a_div_button_extra_group->setAttribute('role','button');
                    $a_div_button_extra_group->setAttribute('class','btn btn-default btn-xs');

                    $span_button_extra_div_button_group = $html_block->createElement('span');
                    $span_button_extra_div_button_group->setAttribute('class',Util::get($data,'class_icon',null));
                    $span_button_extra_div_button_group->setAttribute('aria-hidden','true');

                    $a_div_button_extra_group->appendChild($span_button_extra_div_button_group);
                    $div_button_extra_group->appendChild($a_div_button_extra_group);
                }
            }

            $p_element = $html_block->createElement('p');
            $p_element->setAttribute('class','pull-left');
            $p_element->setAttribute('style','width:100%;');

            if (!empty($button)) {
                $dom_element->insertBefore($div_button_group);
            }

            if (!empty($button_extra)) {
                $dom_element->insertBefore($div_button_extra_group);
            }

            $dom_element->insertBefore($p_element);
        }

        private function modelLoop($html_block,$table_tr_element,$object,$object_column,$type) {
            $element_id = $this->getId();
            $column = $this->getColumn();
            $object_table_name = $object->getTableName();
            $object_schema = $object->schema();
            $flag_label = null;

            foreach ($object_column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value)) {
                    $this->modelLoop($html_block,$table_tr_element,$object->$key,$column_value,$type);

                } else {
                    if (!array_key_exists($column_value,$object)) {
                        continue;
                    }

                    if ($type == 'th') {
                        $object->$column_value = $column_value;

                        if (array_key_exists('label',$object_schema[$column_value]->rule) && !empty($object_schema[$column_value]->rule['label'])) {
                            $object->$column_value = $object_schema[$column_value]->rule['label'];
                        }

                        $table_tr_type_element = $html_block->createElement($type,$object->$column_value);
                        $table_tr_element->appendChild($table_tr_type_element);

                    } else if ($type == 'form') {
                        $input = $html_block->createElement('input');
                        $input->setAttribute('id',vsprintf('%s-search-%s-%s',[$element_id,$object_table_name,$column_value]));
                        $input->setAttribute('class','form-control input-sm table-search-input');
                        $input->setAttribute('type','text');
                        $input->setAttribute('placeholder','...');

                        $table_tr_type_element = $html_block->createElement('th');
                        $table_tr_type_element->appendChild($input);
                        $table_tr_element->appendChild($table_tr_type_element);

                    } else if ($type == 'td') {
                        if (array_key_exists('multiple',$object_schema[$column_value]->rule) && !empty($object_schema[$column_value]->rule['multiple'])) {
                            foreach ($object_schema[$column_value]->rule['multiple'] as $multiple_dict) {
                                if (array_key_exists((string) $object->$column_value,$multiple_dict)) {
                                    $object->$column_value = $multiple_dict[$object->$column_value];

                                    break;
                                }
                            }
                        }

                        $table_tr_type_element = $html_block->createElement($type,$object->$column_value);
                        $table_tr_element->appendChild($table_tr_type_element);
                    }
                }
            }
        }

        private function addSearch() {
            $html_block = $this->getHtmlBlock();
            $model = $this->getModel();
            $node_table_thead = $this->getNodeTableThead();
            $column = $this->getColumn();
            $element_id = $this->getId();

            $data = $model['data'][0];

            $table_thead_tr_element = $html_block->createElement('tr');

            $data_schema = $data->schema();
            $data_table_name = $data->getTableName();

            foreach ($column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value)) {
                    $this->modelLoop($html_block,$table_thead_tr_element,$data->$key,$column_value,'form');

                } else {
                    if (!array_key_exists($column_value,$data)) {
                        continue;
                    }

                    $input = $html_block->createElement('input');
                    $input->setAttribute('id',vsprintf('%s-search-%s-%s',[$element_id,$data_table_name,$column_value]));
                    $input->setAttribute('class','form-control input-sm table-search-input');
                    $input->setAttribute('type','text');
                    $input->setAttribute('placeholder','...');

                    $table_thead_tr_th_element = $html_block->createElement('th','');
                    $table_thead_tr_th_element->appendChild($input);
                    $table_thead_tr_element->appendChild($table_thead_tr_th_element);
                }
            }

            $button = $html_block->createElement('button');
            $button->setAttribute('id',vsprintf('%s-search-button',[$element_id,]));
            $button->setAttribute('class','btn btn-default btn-sm table-search-button');
            $button->setAttribute('type','submit');

            $span_button = $html_block->createElement('span');
            $span_button->setAttribute('class','glyphicon glyphicon-search');
            $span_button->setAttribute('aria-hidden','true');

            $button->appendChild($span_button);

            $table_thead_tr_th_element = $html_block->createElement('th');
            $table_thead_tr_th_element->appendChild($button);
            $table_thead_tr_element->appendChild($table_thead_tr_th_element);

            $node_table_thead->appendChild($table_thead_tr_element);
        }

        private function addThead() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();
            $column = $this->getColumn();

            $table_thead_element = $html_block->createElement('thead');
            $node_table_thead = $dom_element->appendChild($table_thead_element);
            $this->setNodeTableThead($node_table_thead);

            $data = $model['data'][0];

            $table_thead_tr_element = $html_block->createElement('tr');
            $data_schema = $data->schema();

            foreach ($column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value)) {
                    $this->modelLoop($html_block,$table_thead_tr_element,$data->$key,$column_value,'th');

                } else {
                    if (!array_key_exists($column_value,$data)) {
                        continue;
                    }

                    if (array_key_exists('label',$data_schema[$column_value]->rule)) {
                        $column_value = $data_schema[$column_value]->rule['label'];
                    }

                    $table_thead_tr_th_element = $html_block->createElement('th',$column_value);
                    $table_thead_tr_element->appendChild($table_thead_tr_th_element);
                }
            }

            $table_thead_tr_th_element = $html_block->createElement('th');
            $table_thead_tr_element->appendChild($table_thead_tr_th_element);

            $node_table_thead->appendChild($table_thead_tr_element);
        }

        private function addTableButton($table_tbody_tr_element,$id) {
            $html_block = $this->getHtmlBlock();
            $element_id = $this->getId();
            $button = $this->getButton();

            if (empty($button)) {
                return false;
            }

            $div_td_tr_tbody = $html_block->createElement('div');
            $div_td_tr_tbody->setAttribute('class','btn-group btn-group-xs');
            $div_td_tr_tbody->setAttribute('style','width: 50px;');
            $div_td_tr_tbody->setAttribute('role','group');
            $div_td_tr_tbody->setAttribute('aria-label','');

            $request = new Request;

            if (array_key_exists('update',$button) && !empty($button['update'])) {
                $href = $button['update']($id);

                $a_div_td_tr_tbody = $html_block->createElement('a');
                $a_div_td_tr_tbody->setAttribute('href',$href);
                $a_div_td_tr_tbody->setAttribute('id',vsprintf('%s-edit-%s',[$element_id,$id]));
                $a_div_td_tr_tbody->setAttribute('role','button');
                $a_div_td_tr_tbody->setAttribute('class','btn btn-default');

                $span_button_div_td_tr_tbody = $html_block->createElement('span');
                $span_button_div_td_tr_tbody->setAttribute('class','glyphicon glyphicon-edit');
                $span_button_div_td_tr_tbody->setAttribute('aria-hidden','true');

                $a_div_td_tr_tbody->appendChild($span_button_div_td_tr_tbody);
                $div_td_tr_tbody->appendChild($a_div_td_tr_tbody);
            }

            if (array_key_exists('delete',$button) && !empty($button['delete'])) {
                $href = $button['delete']($id);

                $a_div_td_tr_tbody = $html_block->createElement('a');
                $a_div_td_tr_tbody->setAttribute('href',$href);
                $a_div_td_tr_tbody->setAttribute('id',vsprintf('%s-remove-%s',[$element_id,$id]));
                $a_div_td_tr_tbody->setAttribute('role','button');
                $a_div_td_tr_tbody->setAttribute('class','btn btn-default');

                $span_button_div_td_tr_tbody = $html_block->createElement('span');
                $span_button_div_td_tr_tbody->setAttribute('class','glyphicon glyphicon-remove');
                $span_button_div_td_tr_tbody->setAttribute('aria-hidden','true');

                $a_div_td_tr_tbody->appendChild($span_button_div_td_tr_tbody);
                $div_td_tr_tbody->appendChild($a_div_td_tr_tbody);
            }

            $table_tbody_tr_td_option = $html_block->createElement('td');
            $table_tbody_tr_td_option->appendChild($div_td_tr_tbody);
            $table_tbody_tr_element->appendChild($table_tbody_tr_td_option);

            return $table_tbody_tr_element;
        }

        private function addTbody() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();
            $column = $this->getColumn();

            $table_tbody_element = $html_block->createElement('tbody');
            $node_table_tbody = $dom_element->appendChild($table_tbody_element);
            $this->setNodeTableTbody($node_table_tbody);

            $field_primary_key = null;
            $model_data = $model['data'][0];

            foreach ($model_data->schema() as $field => $schema) {
                if ($schema->method == 'primaryKey') {
                    $field_primary_key = $field;

                    break;
                }
            }

            foreach ($model['data'] as $data) {
                $data_schema = $data->schema();
                $table_tbody_tr_element = $html_block->createElement('tr');

                foreach ($column as $key => $column_value) {
                    if (is_array($column_value) && !empty($column_value)) {
                        $this->modelLoop($html_block,$table_tbody_tr_element,$data->$key,$column_value,'td');

                    } else {
                        if (!array_key_exists($column_value,$data)) {
                            continue;
                        }

                        if (array_key_exists('multiple',$data_schema[$column_value]->rule) && !empty($data_schema[$column_value]->rule['multiple'])) {
                            foreach ($data_schema[$column_value]->rule['multiple'] as $multiple_dict) {
                                if (array_key_exists((string) $data->$column_value,$multiple_dict)) {
                                    $data->$column_value = $multiple_dict[$data->$column_value];

                                    break;
                                }
                            }
                        }

                        $table_tbody_tr_td_element = $html_block->createElement('td',$data->$column_value);
                        $table_tbody_tr_element->appendChild($table_tbody_tr_td_element);
                    }
                }

                $table_tbody_tr_element = $this->addTableButton($table_tbody_tr_element,$data->$field_primary_key);

                $node_table_tbody->appendChild($table_tbody_tr_element);
            }
        }

        private function addPanel() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $title = $this->getTitle();
            $text = $this->getText();
            $footer = $this->getFooter();

            if (empty($title) && empty($text) && empty($footer)) {
                return false;
            }

            $div_class_panel = $html_block->createElement('div');
            $div_class_panel->setAttribute('class','panel panel-default');

            if (!empty($title)) {
                $div_class_panel_head = $html_block->createElement('div',$title);
                $div_class_panel_head->setAttribute('class','panel-heading');
                $node_div_panel_head = $div_class_panel->appendChild($div_class_panel_head);
            }

            $div_class_panel_body = $html_block->createElement('div');

            if (!empty($text)) {
                $p_text = $html_block->createElement('p',$text);
                $div_class_panel_body->appendChild($p_text);
            }

            $div_class_panel_body->setAttribute('class','panel-body');
            $node_div_panel_body = $div_class_panel->appendChild($div_class_panel_body);
            $node_div_panel_body->appendChild($dom_element);
            $this->setNodePanelBody($node_div_panel_body);

            if (!empty($footer)) {
                $div_class_panel_footer = $html_block->createElement('div',$footer);
                $div_class_panel_footer->setAttribute('class','panel-footer');
                $node_div_panel_footer = $div_class_panel->appendChild($div_class_panel_footer);
            }

            $this->setDomElement($div_class_panel);
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

            $this->setNodeContainer($div_class_col);
            $this->setDomElement($div_class_col);
        }

        private function addPagination() {
            $html_block = $this->getHtmlBlock();
            $model = $this->getModel();

            if (!empty($model) && is_array($model) && isset($model['page_total']) && !empty($model['data']) && $model['register_total'] > $model['register_perpage']) {
                $node_panel_body = $this->getNodePanelBody();
                $node_container = $this->getNodeContainer();
                $element_id = $this->getId();

                $nav_pagination = $html_block->createElement('nav');
                $ul_nav_pagination = $html_block->createElement('ul');
                $ul_nav_pagination->setAttribute('class','pagination');

                if ($model['page_previous'] > 1) {
                    $li_ul_nav_pagination = $html_block->createElement('li');
                    $a_li_ul_nav_pagination = $html_block->createElement('a');
                    $a_li_ul_nav_pagination->setAttribute('href',vsprintf('?%s-pag-page=1',[$element_id,]));
                    $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s-pag',[$element_id,]));
                    $a_li_ul_nav_pagination->setAttribute('data-page','1');
                    $span_a_li_ul_nav_pagination = $html_block->createElement('span','«');
                    $span_a_li_ul_nav_pagination->setAttribute('aria-hidden','true');

                    $a_li_ul_nav_pagination->appendChild($span_a_li_ul_nav_pagination);
                    $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                    $ul_nav_pagination->appendChild($li_ul_nav_pagination);
                }

                if ($model['page_previous'] < $model['page_current']) {
                    $li_ul_nav_pagination = $html_block->createElement('li');
                    $a_li_ul_nav_pagination = $html_block->createElement('a');
                    $a_li_ul_nav_pagination->setAttribute('href',vsprintf('?%s-pag-page=%s',[$element_id,$model['page_previous']]));
                    $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s-pag',[$element_id,]));
                    $a_li_ul_nav_pagination->setAttribute('data-page',$model['page_previous']);
                    $span_a_li_ul_nav_pagination = $html_block->createElement('span',$model['page_previous']);
                    $span_a_li_ul_nav_pagination->setAttribute('aria-hidden','true');

                    $a_li_ul_nav_pagination->appendChild($span_a_li_ul_nav_pagination);
                    $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                    $ul_nav_pagination->appendChild($li_ul_nav_pagination);
                }

                $li_ul_nav_pagination = $html_block->createElement('li');
                $li_ul_nav_pagination->setAttribute('class','active');
                $a_li_ul_nav_pagination = $html_block->createElement('a',$model['page_current']);
                $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s-pag',[$element_id,]));
                $a_li_ul_nav_pagination->setAttribute('data-page',$model['page_current']);

                $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                $ul_nav_pagination->appendChild($li_ul_nav_pagination);

                if ($model['page_next'] < $model['page_total']) {
                    $li_ul_nav_pagination = $html_block->createElement('li');
                    $a_li_ul_nav_pagination = $html_block->createElement('a');
                    $a_li_ul_nav_pagination->setAttribute('href',vsprintf('?%s-pag-page=%s',[$element_id,$model['page_next']]));
                    $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s-pag',[$element_id,]));
                    $a_li_ul_nav_pagination->setAttribute('data-page',$model['page_next']);
                    $span_a_li_ul_nav_pagination = $html_block->createElement('span',$model['page_next']);
                    $span_a_li_ul_nav_pagination->setAttribute('aria-hidden','true');

                    $a_li_ul_nav_pagination->appendChild($span_a_li_ul_nav_pagination);
                    $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                    $ul_nav_pagination->appendChild($li_ul_nav_pagination);
                }

                if ($model['page_total'] > $model['page_current']) {
                    $li_ul_nav_pagination = $html_block->createElement('li');
                    $a_li_ul_nav_pagination = $html_block->createElement('a');
                    $a_li_ul_nav_pagination->setAttribute('href',vsprintf('?%s-pag-page=%s',[$element_id,$model['page_total']]));
                    $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s-pag',[$element_id,]));
                    $a_li_ul_nav_pagination->setAttribute('data-page',$model['page_total']);
                    $span_a_li_ul_nav_pagination = $html_block->createElement('span','»');
                    $span_a_li_ul_nav_pagination->setAttribute('aria-hidden','true');

                    $a_li_ul_nav_pagination->appendChild($span_a_li_ul_nav_pagination);
                    $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                    $ul_nav_pagination->appendChild($li_ul_nav_pagination);
                }

                $nav_pagination->appendChild($ul_nav_pagination);

                if (!empty($node_panel_body)) {
                    $node_panel_body->appendChild($nav_pagination);

                } else {
                    $node_container->appendChild($nav_pagination);
                }
            }
        }

        private function addTfoot() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();

            $table_tfoot_element = $html_block->createElement('tfoot');
            $node_table_tfoot = $dom_element->appendChild($table_tfoot_element);
            $this->setNodeTableTfoot($node_table_tfoot);
        }

        private function ready() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            if (empty($model) || !is_array($model) || !isset($model['data']) || empty($model['data'])) {
                $this->addButton();
                $this->addPanel();
                $this->addContainer();

                return false;
            }

            $this->addButton();
            $this->addThead();
            $this->addSearch();
            $this->addTbody();
            $this->addTfoot();
            $this->addPanel();
            $this->addContainer();
            $this->addPagination();
        }

        public function renderHtml() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();

            $html_block->appendBodyContainerRow($dom_element);

            return $html_block->renderHtml();
        }
    }
}
