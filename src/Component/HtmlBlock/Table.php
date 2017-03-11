<?php

namespace Component\HtmlBlock {
    use Core\{Request,Util};
    use Core\Exception\WException;
    use \DOMDocument as DOMDocument;

    class Table {
        private $dom_document;
        private $dom_element;
        private $model;
        private $column;
        private $pagination;
        private $button;
        private $button_search;
        private $button_inline;
        private $button_extra;
        private $id;
        private $title;
        private $title_empty;
        private $text;
        private $text_empty;
        private $footer;
        private $container_class;
        private $container_style;
        private $node_table_thead;
        private $node_table_tbody;
        private $node_table_tfoot;
        private $node_panel_body;
        private $node_container;

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $encoding = Util::get($kwargs,'encoding','UTF-8');
            $this->setEncoding($encoding);

            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);

            $column = Util::get($kwargs,'column',null);
            $this->setColumn($column);

            $pagination = Util::get($kwargs,'pagination',null);
            $this->setPagination($pagination);

            $button = Util::get($kwargs,'button',null);
            $this->setButton($button);

            $button_inline = Util::get($kwargs,'button_inline',null);
            $this->setButtonInline($button_inline);

            $button_extra = Util::get($kwargs,'button_extra',null);
            $this->setButtonExtra($button_extra);

            $button_search = Util::get($kwargs,'button_search',null);
            $this->setButtonSearch($button_search);

            $title = Util::get($kwargs,'title',null);
            $this->setTitle($title);

            $title_empty = Util::get($kwargs,'title_empty',null);
            $this->setTitleEmpty($title_empty);

            $text = Util::get($kwargs,'text',null);
            $this->setText($text);

            $text_empty = Util::get($kwargs,'text_empty',null);
            $this->setTextEmpty($text_empty);

            $footer = Util::get($kwargs,'footer',null);
            $this->setFooter($footer);

            $container_class = Util::get($kwargs,'container_class',null);
            $this->setContainerClass($container_class);

            $container_style = Util::get($kwargs,'container_style',null);
            $this->setContainerStyle($container_style);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $dom_element = $dom_document->createElement('table');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
                $this->setId($kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','table table-striped table-bordered table-hover table-condensed table-responsive');
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

        public function getDomElement() {
            return $this->dom_element;
        }

        private function setDomElement($dom_element) {
            $this->dom_element = $dom_element;
        }

        public function getEncoding() {
            return $this->encoding;
        }

        public function setEncoding($encoding) {
            $this->encoding = $encoding;

            return $this;
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

        private function getPagination() {
            return $this->pagination;
        }

        private function setPagination($pagination) {
            $this->pagination = $pagination;
        }

        private function getButton() {
            return $this->button;
        }

        private function setButton($button) {
            $this->button = $button;
        }

        private function getButtonInline() {
            return $this->button_inline;
        }

        private function setButtonInline($button_inline) {
            $this->button_inline = $button_inline;
        }

        private function getButtonExtra() {
            return $this->button_extra;
        }

        private function setButtonExtra($button_extra) {
            $this->button_extra = $button_extra;
        }

        private function getButtonSearch() {
            return $this->button_search;
        }

        private function setButtonSearch($button_search) {
            $this->button_search = $button_search;
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

        private function getTitleEmpty() {
            return $this->title_empty;
        }

        private function setTitleEmpty($title_empty) {
            $this->title_empty = $title_empty;
        }

        private function getText() {
            return $this->text;
        }

        private function setText($text) {
            $this->text = $text;
        }

        private function getTextEmpty() {
            return $this->text_empty;
        }

        private function setTextEmpty($text_empty) {
            $this->text_empty = $text_empty;
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
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $element_id = $this->getId();
            $button = $this->getButton();
            $button_extra = $this->getButtonExtra();

            if (empty($button) && empty($button_extra)) {
                return false;
            }

            $request = new Request;

            if (!empty($button)) {
                $div_button_group = $dom_document->createElement('div');
                $div_button_group->setAttribute('class','btn-group pull-left');
                $div_button_group->setAttribute('role','group');
                $div_button_group->setAttribute('aria-label','');

                foreach ($button as $data) {
                    $href = Util::get($data,'href',null);

                    $a_div_button_group = $dom_document->createElement('a',Util::get($data,'label',null));
                    $a_div_button_group->setAttribute('href',$href);
                    $a_div_button_group->setAttribute('id',Util::get($data,'href',null));
                    $a_div_button_group->setAttribute('role','button');
                    $a_div_button_group->setAttribute('class',Util::get($data,'class','btn btn-default btn-xs'));

                    $alt = Util::get($data,'alt',null);

                    if (!empty($alt)) {
                        $a_div_button_group->setAttribute('data-toggle','tooltip');
                        $a_div_button_group->setAttribute('data-placement','top');
                        $a_div_button_group->setAttribute('title',$alt);
                        $a_div_button_group->setAttribute('alt',$alt);
                        $a_div_button_group->setAttribute('data-toggle','tooltip');
                        $a_div_button_group->setAttribute('data-placement','top');
                        $a_div_button_group->setAttribute('data-container','body');
                    }

                    $icon = Util::get($data,'icon',null);

                    if (!empty($icon)) {
                        $span_button_div_button_group = $dom_document->createElement('span');
                        $span_button_div_button_group->setAttribute('class',$icon);
                        $span_button_div_button_group->setAttribute('aria-hidden','true');

                        $a_div_button_group->appendChild($span_button_div_button_group);
                    }

                    $div_button_group->appendChild($a_div_button_group);
                }
            }

            if (!empty($button_extra)) {
                $div_button_extra_group = $dom_document->createElement('div');
                $div_button_extra_group->setAttribute('class','btn-group pull-right');
                $div_button_extra_group->setAttribute('role','group');
                $div_button_extra_group->setAttribute('aria-label','');

                foreach ($button_extra as $data) {
                    $a_div_button_extra_group = $dom_document->createElement('a',Util::get($data,'label',null));
                    $a_div_button_extra_group->setAttribute('href',Util::get($data,'href',null));
                    $a_div_button_extra_group->setAttribute('id',Util::get($data,'id',null));
                    $a_div_button_extra_group->setAttribute('role','button');
                    $a_div_button_extra_group->setAttribute('class',Util::get($data,'class','btn btn-default btn-xs'));

                    $alt = Util::get($data,'alt',null);

                    if (!empty($alt)) {
                        $a_div_button_extra_group->setAttribute('data-toggle','tooltip');
                        $a_div_button_extra_group->setAttribute('data-placement','top');
                        $a_div_button_extra_group->setAttribute('title',$alt);
                        $a_div_button_extra_group->setAttribute('alt',$alt);
                    }

                    $icon = Util::get($data,'icon',null);

                    if (!empty($icon)) {
                        $span_button_extra_div_button_group = $dom_document->createElement('span');
                        $span_button_extra_div_button_group->setAttribute('class',$icon);
                        $span_button_extra_div_button_group->setAttribute('aria-hidden','true');

                        $a_div_button_extra_group->appendChild($span_button_extra_div_button_group);
                    }

                    $div_button_extra_group->appendChild($a_div_button_extra_group);
                }
            }

            $p_element = $dom_document->createElement('p');
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

        private function addEmpty() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $title_empty = $this->getTitleEmpty();
            $text_empty = $this->getTextEmpty();

            $thead = $dom_document->createElement('thead');
            $tr = $dom_document->createElement('tr');
            $th = $dom_document->createElement('th',$title_empty);
            $tr->appendChild($th);
            $thead->appendChild($tr);
            
            $tbody = $dom_document->createElement('tbody');
            $tr = $dom_document->createElement('tr');
            $td = $dom_document->createElement('td',$text_empty);
            $tr->appendChild($td);

            $tbody->appendChild($tr);

            $dom_element->appendChild($thead);
            $dom_element->appendChild($tbody);
        }

        private function modelLoop($dom_document,$table_tr_element,$object,$object_column,$type,$object_table_name_main = null) {
            $element_id = $this->getId();
            $column = $this->getColumn();
            $object_table_name = $object->getTableName();
            $object_schema = $object->schema();
            $flag_label = null;

            $request = new Request;
            $request_http_get = $request->getHttpGet();

            foreach ($object_column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value)) {
                    $this->modelLoop($dom_document,$table_tr_element,$object->$key,$column_value,$type);

                } else {
                    if (!array_key_exists($column_value,$object)) {
                        continue;
                    }

                    if ($type == 'th') {
                        $object_column_value_label = $column_value;

                        if (array_key_exists('label',$object_schema[$column_value]->rule) && !empty($object_schema[$column_value]->rule['label'])) {
                            $object_column_value_label = $object_schema[$column_value]->rule['label'];
                        }

                        $table_tr_type_element = $dom_document->createElement($type,$object_column_value_label);
                        $table_tr_element->appendChild($table_tr_type_element);

                    } else if ($type == 'form') {
                        $field_name = vsprintf('%s_%s__%s',[$object_table_name_main,$object_table_name,$column_value]);

                        $input = $dom_document->createElement('input');
                        $input->setAttribute('name',$field_name);
                        $input->setAttribute('value',Util::get($request_http_get,$field_name,null));
                        $input->setAttribute('id',vsprintf('%s-search-%s-%s',[$element_id,$object_table_name,$column_value]));
                        $input->setAttribute('class','form-control input-sm table-search-input');
                        $input->setAttribute('type','text');
                        $input->setAttribute('placeholder','...');

                        $table_tr_type_element = $dom_document->createElement('th');
                        $table_tr_type_element->appendChild($input);
                        $table_tr_element->appendChild($table_tr_type_element);

                    } else if ($type == 'td') {
                        if (array_key_exists('option',$object_schema[$column_value]->rule) && !empty($object_schema[$column_value]->rule['option'])) {
                            if (array_key_exists((string) $object->$column_value,$object_schema[$column_value]->rule['option'])) {
                                $object->$column_value = $object_schema[$column_value]->rule['option'][$object->$column_value];
                            }
                        }

                        $table_tr_type_element = $dom_document->createElement($type,$object->$column_value);
                        $table_tr_element->appendChild($table_tr_type_element);
                    }
                }
            }
        }

        private function addSearch() {
            $dom_document = $this->getDomDocument();
            $model = $this->getModel();
            $node_table_thead = $this->getNodeTableThead();
            $column = $this->getColumn();
            $element_id = $this->getId();
            $button_search = $this->getButtonSearch();

            if (empty($model->data)) {
                return false;
            }

            $request = new Request;
            $request_http_get = $request->getHttpGet();

            $data = $model->data[0];

            $table_thead_form = $dom_document->createElement('form');
            $table_thead_form->setAttribute('method','GET');

            $table_thead_tr_element = $dom_document->createElement('tr');

            $data_schema = $data->schema();
            $data_table_name = $data->getTableName();
            
            if (empty($column)) {
                $column = array_keys($data->getTableColumn());
            }

            foreach ($column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value)) {
                    $this->modelLoop($dom_document,$table_thead_tr_element,$data->$key,$column_value,'form',$data_table_name);

                } else {
                    if (!array_key_exists($column_value,$data)) {
                        continue;
                    }

                    $field_name = vsprintf('%s__%s',[$data_table_name,$column_value]);

                    if (in_array($data_schema[$column_value]->method,['char','boolean']) && array_key_exists('option',$data_schema[$column_value]->rule)) {
                        $field = $dom_document->createElement('select');
                        $field->setAttribute('name',$field_name);
                        $field->setAttribute('id',vsprintf('%s-search-%s-%s',[$element_id,$data_table_name,$column_value]));
                        $field->setAttribute('class','form-control input-sm table-search-input');

                        $option = $dom_document->createElement('option','...');
                        $option->setAttribute('value','');

                        $field->appendChild($option);

                        if (array_key_exists('multiple',$data_schema[$column_value]->rule) && !empty($data_schema[$column_value]->rule['multiple'])) {
                            $field->setAttribute('multiple','multiple');
                        }

                        $field_name_value = Util::get($request_http_get,$field_name,null);

                        if (!empty($data_schema[$column_value]->rule['option'])) {
                            foreach ($data_schema[$column_value]->rule['option'] as $key => $value) {
                                $option = $dom_document->createElement('option',$value);
                                $option->setAttribute('value',$key);

                                if ((string) $key === $field_name_value) {
                                    $option->setAttribute('selected','selected');
                                }

                                $field->appendChild($option);
                            }
                        }

                    } else {
                        $field = $dom_document->createElement('input');
                        $field->setAttribute('name',$field_name);
                        $field->setAttribute('value',Util::get($request_http_get,$field_name,null));
                        $field->setAttribute('id',vsprintf('%s-search-%s-%s',[$element_id,$data_table_name,$column_value]));
                        $field->setAttribute('class','form-control input-sm table-search-input');
                        $field->setAttribute('type','text');
                        $field->setAttribute('placeholder','...');
                    }

                    $table_thead_tr_th_element = $dom_document->createElement('th','');
                    $table_thead_tr_th_element->appendChild($field);
                    $table_thead_tr_element->appendChild($table_thead_tr_th_element);
                }
            }

            $button = $dom_document->createElement('button');
            $button->setAttribute('name',Util::get($button_search,'name',null));
            
            $button_search_alt = Util::get($button_search,'alt',null);

            $button->setAttribute('alt',$button_search_alt);

            if (!empty($button_search_alt)) {
                $button->setAttribute('title',$button_search_alt);
                $button->setAttribute('data-toggle','tooltip');
                $button->setAttribute('data-placement','top');
                $button->setAttribute('data-container','body');
            }

            $button->setAttribute('value','1');
            $button->setAttribute('id',Util::get($button_search,'id',null));

            $button_search_class = Util::get($button_search,'class',null);

            if (empty($button_search_class)) {
                $button_search_class = 'btn btn-default btn-sm table-search-button';
            }

            $button->setAttribute('class',$button_search_class);
            $button->setAttribute('type','submit');
            
            $button_search_icon = Util::get($button_search,'icon',null);
            
            if (!empty($button_search_icon)) {
                $span_button = $dom_document->createElement('span');
                $span_button->setAttribute('class',Util::get($button_search,'icon','glyphicon glyphicon-search'));
                $span_button->setAttribute('aria-hidden','true');

                $button->appendChild($span_button);
            }

            $button_search_label = Util::get($button_search,'label',null);

            if (!empty($button_search_label)) {
                $button->appendChild(new \DOMText($button_search_label));
            }

            $table_thead_tr_th_element = $dom_document->createElement('th');
            $table_thead_tr_th_element->appendChild($button);
            $table_thead_tr_element->appendChild($table_thead_tr_th_element);
            $table_thead_form->appendChild($table_thead_tr_element);

            $node_table_thead->appendChild($table_thead_form);
        }

        private function addThead() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();
            $column = $this->getColumn();

            $table_thead_element = $dom_document->createElement('thead');
            $node_table_thead = $dom_element->appendChild($table_thead_element);
            $this->setNodeTableThead($node_table_thead);

            if (empty($model->data)) {
                return false;
            }

            $data = $model->data[0];

            $table_thead_tr_element = $dom_document->createElement('tr');
            $data_schema = $data->schema();
            
            if (empty($column)) {
                $column = array_keys($data->getTableColumn());
            }

            foreach ($column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value)) {
                    $this->modelLoop($dom_document,$table_thead_tr_element,$data->$key,$column_value,'th');

                } else {
                    if (!array_key_exists($column_value,$data)) {
                        continue;
                    }

                    if (array_key_exists('label',$data_schema[$column_value]->rule)) {
                        $column_value = $data_schema[$column_value]->rule['label'];
                    }

                    $table_thead_tr_th_element = $dom_document->createElement('th',$column_value);
                    $table_thead_tr_element->appendChild($table_thead_tr_th_element);
                }
            }

            $table_thead_tr_th_element = $dom_document->createElement('th');
            $table_thead_tr_element->appendChild($table_thead_tr_th_element);

            $node_table_thead->appendChild($table_thead_tr_element);
        }

        private function addTableButton($table_tbody_tr_element,$id) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $button_inline = $this->getButtonInline();

            if (empty($button_inline)) {
                return false;
            }

            $table = $dom_document->createElement('table');
            $table_tr = $dom_document->createElement('tr');

            $request = new Request;

            foreach ($button_inline as $data) {
                $href = null;

                if (array_key_exists('href',$data)) {
                    $href = $data['href']($id);

                }

                $a_div_td_tr_tbody = $dom_document->createElement('a',Util::get($data,'label',null));
                $a_div_td_tr_tbody->setAttribute('href',$href);
                $a_div_td_tr_tbody->setAttribute('id',Util::get($data,'id',null));
                $a_div_td_tr_tbody->setAttribute('role','button');
                $a_div_td_tr_tbody->setAttribute('class',Util::get($data,'class','btn btn-default btn-xs'));

                $alt = Util::get($data,'alt',null);

                if (!empty($alt)) {
                    $a_div_td_tr_tbody->setAttribute('title',$alt);
                    $a_div_td_tr_tbody->setAttribute('alt',$alt);
                    $a_div_td_tr_tbody->setAttribute('data-toggle','tooltip');
                    $a_div_td_tr_tbody->setAttribute('data-placement','top');
                    $a_div_td_tr_tbody->setAttribute('data-container','body');
                }

                $icon = Util::get($data,'icon',null);

                if (!empty($icon)) {
                    $span_button_div_td_tr_tbody = $dom_document->createElement('span');
                    $span_button_div_td_tr_tbody->setAttribute('class',$icon);
                    $span_button_div_td_tr_tbody->setAttribute('aria-hidden','true');

                    $a_div_td_tr_tbody->appendChild($span_button_div_td_tr_tbody);    
                }

                $table_tr_td = $dom_document->createElement('td');
                $table_tr_td->appendChild($a_div_td_tr_tbody);

                $table_tr->appendChild($table_tr_td);

                $table->appendChild($table_tr);
            }

            $table_tbody_tr_td_option = $dom_document->createElement('td');
            $table_tbody_tr_td_option->appendChild($table);
            $table_tbody_tr_element->appendChild($table_tbody_tr_td_option);

            return $table_tbody_tr_element;
        }

        private function addTbody() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();
            $column = $this->getColumn();

            $table_tbody_element = $dom_document->createElement('tbody');
            $node_table_tbody = $dom_element->appendChild($table_tbody_element);
            $this->setNodeTableTbody($node_table_tbody);

            if (empty($model->data)) {
                return false;
            }

            $field_primary_key = null;
            $model_data = $model->data[0];

            foreach ($model_data->schema() as $field => $schema) {
                if ($schema->method == 'primaryKey') {
                    $field_primary_key = $field;

                    break;
                }
            }
            
            if (empty($column)) {
                $column = array_keys($model_data->getTableColumn());
            }

            foreach ($model->data as $data) {
                $data_schema = $data->schema();
                $table_tbody_tr_element = $dom_document->createElement('tr');

                foreach ($column as $key => $column_value) {
                    if ((is_array($column_value) || (is_object($data->$column_value))) && !empty($column_value)) {
                        if (!is_array($column_value) && is_object($data->$column_value)) {
                            $data_key = $data->$column_value;
                            $column_value = [$data->$column_value->getPrimaryKey()];

                        } else {
                            $data_key = $data->$key;
                        }

                        $this->modelLoop($dom_document,$table_tbody_tr_element,$data_key,$column_value,'td');

                    } else {
                        if (!array_key_exists($column_value,$data)) {
                            continue;
                        }

                        if (array_key_exists('option',$data_schema[$column_value]->rule) && !empty($data_schema[$column_value]->rule['option'])) {
                            if (array_key_exists((string) $data->$column_value,$data_schema[$column_value]->rule['option'])) {
                                $data->$column_value = $data_schema[$column_value]->rule['option'][$data->$column_value];
                            }
                        }

                        $table_tbody_tr_td_element = $dom_document->createElement('td',$data->$column_value);
                        $table_tbody_tr_element->appendChild($table_tbody_tr_td_element);
                    }
                }

                $table_tbody_tr_element = $this->addTableButton($table_tbody_tr_element,$data->$field_primary_key);

                $node_table_tbody->appendChild($table_tbody_tr_element);
            }
        }

        private function addPanel() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $title = $this->getTitle();
            $text = $this->getText();
            $footer = $this->getFooter();

            if (empty($title) && empty($text) && empty($footer)) {
                return false;
            }

            $div_class_panel = $dom_document->createElement('div');
            $div_class_panel->setAttribute('class','panel panel-default');

            if (!empty($title)) {
                $div_class_panel_head = $dom_document->createElement('div',$title);
                $div_class_panel_head->setAttribute('class','panel-heading');
                $node_div_panel_head = $div_class_panel->appendChild($div_class_panel_head);
            }

            $div_class_panel_body = $dom_document->createElement('div');

            if (!empty($text)) {
                $p_text = $dom_document->createElement('p',$text);
                $div_class_panel_body->appendChild($p_text);
            }

            $div_class_panel_body->setAttribute('class','panel-body');
            $node_div_panel_body = $div_class_panel->appendChild($div_class_panel_body);
            $node_div_panel_body->appendChild($dom_element);
            $this->setNodePanelBody($node_div_panel_body);

            if (!empty($footer)) {
                $div_class_panel_footer = $dom_document->createElement('div',$footer);
                $div_class_panel_footer->setAttribute('class','panel-footer');
                $node_div_panel_footer = $div_class_panel->appendChild($div_class_panel_footer);
            }

            $this->setDomElement($div_class_panel);
        }

        private function addPagination() {
            $dom_document = $this->getDomDocument();
            $model = $this->getModel();
            $pagination = $this->getPagination();

            if (isset($model->page_total) && $model->register_total > $model->register_perpage) {
                $node_panel_body = $this->getNodePanelBody();
                $node_container = $this->getNodeContainer();
                $element_id = $this->getId();

                $nav_pagination = $dom_document->createElement('nav');
                $ul_nav_pagination = $dom_document->createElement('ul');
                $ul_nav_pagination->setAttribute('class','pagination');

                if ($model->page_previous > 1) {
                    $li_ul_nav_pagination = $dom_document->createElement('li');
                    $a_li_ul_nav_pagination = $dom_document->createElement('a');
                    $a_li_ul_nav_pagination->setAttribute('href',vsprintf('?%s=1',[Util::get($pagination,'btn_url_string',''),]));
                    $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s',[Util::get($pagination,'class',''),]));
                    $a_li_ul_nav_pagination->setAttribute('data-page','1');
                    $span_a_li_ul_nav_pagination = $dom_document->createElement('span');
                    $span_a_li_ul_nav_pagination->setAttribute('class','glyphicon glyphicon-chevron-left');
                    $span_a_li_ul_nav_pagination->setAttribute('alt',Util::get($pagination,'left_alt',null));
                    $span_a_li_ul_nav_pagination->setAttribute('title',Util::get($pagination,'left_alt',null));
                    $span_a_li_ul_nav_pagination->setAttribute('aria-hidden','true');

                    $a_li_ul_nav_pagination->appendChild($span_a_li_ul_nav_pagination);
                    $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                    $ul_nav_pagination->appendChild($li_ul_nav_pagination);
                }

                if ($model->page_previous < $model->page_current) {
                    $li_ul_nav_pagination = $dom_document->createElement('li');
                    $a_li_ul_nav_pagination = $dom_document->createElement('a');
                    $a_li_ul_nav_pagination->setAttribute('href',vsprintf('?%s=%s',[Util::get($pagination,'btn_url_string',''),$model->page_previous,]));
                    $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s',[Util::get($pagination,'class',''),]));
                    $a_li_ul_nav_pagination->setAttribute('data-page',$model->page_previous);
                    $span_a_li_ul_nav_pagination = $dom_document->createElement('span',$model->page_previous);
                    $span_a_li_ul_nav_pagination->setAttribute('aria-hidden','true');

                    $a_li_ul_nav_pagination->appendChild($span_a_li_ul_nav_pagination);
                    $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                    $ul_nav_pagination->appendChild($li_ul_nav_pagination);
                }

                $li_ul_nav_pagination = $dom_document->createElement('li');
                $li_ul_nav_pagination->setAttribute('class','active');
                $a_li_ul_nav_pagination = $dom_document->createElement('a',$model->page_current);
                $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s-pag',[$element_id,]));
                $a_li_ul_nav_pagination->setAttribute('data-page',$model->page_current);

                $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                $ul_nav_pagination->appendChild($li_ul_nav_pagination);

                if ($model->page_next < $model->page_total) {
                    $li_ul_nav_pagination = $dom_document->createElement('li');
                    $a_li_ul_nav_pagination = $dom_document->createElement('a');
                    $a_li_ul_nav_pagination->setAttribute('href',vsprintf('?%s=%s',[Util::get($pagination,'btn_url_string',''),$model->page_next]));
                    $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s',[Util::get($pagination,'class',''),]));
                    $a_li_ul_nav_pagination->setAttribute('data-page',$model->page_next);
                    $span_a_li_ul_nav_pagination = $dom_document->createElement('span',$model->page_next);
                    $span_a_li_ul_nav_pagination->setAttribute('aria-hidden','true');

                    $a_li_ul_nav_pagination->appendChild($span_a_li_ul_nav_pagination);
                    $li_ul_nav_pagination->appendChild($a_li_ul_nav_pagination);

                    $ul_nav_pagination->appendChild($li_ul_nav_pagination);
                }

                if ($model->page_total > $model->page_current) {
                    $li_ul_nav_pagination = $dom_document->createElement('li');
                    $a_li_ul_nav_pagination = $dom_document->createElement('a');
                    $a_li_ul_nav_pagination->setAttribute('href',vsprintf('?%s=%s',[Util::get($pagination,'btn_url_string',''),$model->page_total]));
                    $a_li_ul_nav_pagination->setAttribute('class',vsprintf('%s',[Util::get($pagination,'class',''),]));
                    $a_li_ul_nav_pagination->setAttribute('data-page',$model->page_total);
                    $span_a_li_ul_nav_pagination = $dom_document->createElement('span');
                    $span_a_li_ul_nav_pagination->setAttribute('class','glyphicon glyphicon-chevron-right');
                    $span_a_li_ul_nav_pagination->setAttribute('alt',Util::get($pagination,'right_alt',null));
                    $span_a_li_ul_nav_pagination->setAttribute('title',Util::get($pagination,'right_alt',null));
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
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();

            $table_tfoot_element = $dom_document->createElement('tfoot');
            $node_table_tfoot = $dom_element->appendChild($table_tfoot_element);
            $this->setNodeTableTfoot($node_table_tfoot);
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

            $this->setNodeContainer($div_class_col);
            $this->setDomElement($div_class_col);
        }

        private function ready() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            if (empty($model) || !is_object($model) || !isset($model->data) || empty($model->data)) {
                $this->addButton();
                $this->addEmpty();
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
            $dom_document = $this->getDomDocument();

            return $dom_document->saveHTML();
        }
    }
}
