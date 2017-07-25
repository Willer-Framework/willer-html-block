<?php
declare(strict_types=1);

namespace HtmlBlock\Component {
    use Core\{Request,Util};
    use Core\ORM\Transaction;
    use HtmlBlock\HtmlBlock;
    use HtmlBlock\Interface\HtmlBlock as InterfaceHtmlBlock;
    use HtmlBlock\Exception\HtmlBlockException as ExceptionHtmlBlock;
    use \DOMDocument as DOMDocument;
    use \DOMElement as DOMElement;
    use \DOMNode as DOMNode;

    class Table implements InterfaceHtmlBlock {
        private const QUERY_LIMIT_DEFAULT = 9999;

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
        private $node_table_thead;
        private $node_table_tbody;
        private $node_table_tfoot;
        private $node_panel_body;
        private $node_container;
        private $class;
        private $style;

        public function __construct(array ...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $model = $util->contains($kwargs,'model')->getArray();
            $model = $model[0];
            $this->setModel($model);

            $column = $util->contains($kwargs,'column')->getArray();
            $this->setColumn($column);

            $pagination = $util->contains($kwargs,'pagination')->getArray();
            $this->setPagination($pagination);

            $button = $util->contains($kwargs,'button')->getArray();
            $this->setButton($button);

            $button_inline = $util->contains($kwargs,'button_inline')->getArray();
            $this->setButtonInline($button_inline);

            $button_extra = $util->contains($kwargs,'button_extra')->getArray();
            $this->setButtonExtra($button_extra);

            $button_search = $util->contains($kwargs,'button_search')->getArray();
            $this->setButtonSearch($button_search);

            $title = $util->contains($kwargs,'title')->getString();
            $this->setTitle($title);

            $title_empty = $util->contains($kwargs,'title_empty')->getString();
            $this->setTitleEmpty($title_empty);

            $text = $util->contains($kwargs,'text')->getString();
            $this->setText($text);

            $text_empty = $util->contains($kwargs,'text_empty')->getString();
            $this->setTextEmpty($text_empty);

            $footer = $util->contains($kwargs,'footer')->getString();
            $this->setFooter($footer);

            $container_class = $util->contains($kwargs,'container_class')->getString();
            $this->setContainerClass($container_class);

            $id = $util->contains($kwargs,'id')->getString();
            $this->setId($id);

            $class = $util->contains($kwargs,'class')->getString('table table-striped table-bordered table-hover table-condensed table-responsive');
            $this->setClass($class);

            $style = $util->contains($kwargs,'style')->getString();
            $this->setStyle($style);

            $dom_document = new DOMDocument('1.0',HtmlBlock::ENCODING);

            $this->setDomDocument($dom_document);
            $this->ready();

            return $this;
        }

        private function getDomDocument(): DOMDocument {
            return $this->dom_document;
        }

        private function setDomDocument(DOMDocument $dom_document): self {
            $this->dom_document = $dom_document;

            return $this;
        }

        public function getDomElement(): DOMElement {
            return $this->dom_element;
        }

        private function setDomElement(DOMElement $dom_element): self {
            $this->dom_element = $dom_element;

            return $this;
        }

        private function getModel(): ?array {
            return $this->model;
        }

        private function setModel(?array $model): self {
            $this->model = $model;

            return $this;
        }

        private function getContainerClass(): ?string {
            return $this->container_class;
        }
 
        private function setContainerClass(?string $container_class): self {
            $this->container_class = $container_class;

            return $this;
        }

        private function getId(): ?string {
            return $this->id;
        }

        private function setId(?string $id): self {
            $this->id = $id;

            return $this;
        }

        private function getClass(): ?string {
            return $this->class;
        }

        private function setClass(?string $class): self {
            $this->class = $class;

            return $this;
        }

        private function getStyle(): ?string {
            return $this->style;
        }

        private function setStyle(?string $style): self {
            $this->style = $style;

            return $this;
        }

        private function getColumn(): ?array {
            return $this->column;
        }

        private function setColumn(?array $column): self {
            $this->column = $column;

            return $this;
        }

        private function getPagination(): ?array {
            return $this->pagination;
        }

        private function setPagination(?array $pagination): self {
            $this->pagination = $pagination;

            return $this;
        }

        private function getButton(): ?array {
            return $this->button;
        }

        private function setButton(?array $button): self {
            $this->button = $button;

            return $this;
        }

        private function getButtonInline(): ?array {
            return $this->button_inline;
        }

        private function setButtonInline(?array $button_inline): self {
            $this->button_inline = $button_inline;

            return $this;
        }

        private function getButtonExtra(): ?array {
            return $this->button_extra;
        }

        private function setButtonExtra(?array $button_extra): self {
            $this->button_extra = $button_extra;

            return $this;
        }

        private function getButtonSearch(): ?array {
            return $this->button_search;
        }

        private function setButtonSearch(?array $button_search): self {
            $this->button_search = $button_search;

            return $this;
        }

        private function getTitle(): ?string {
            return $this->title;
        }

        private function setTitle(?string $title): self {
            $this->title = $title;

            return $this;
        }

        private function getTitleEmpty(): ?string {
            return $this->title_empty;
        }

        private function setTitleEmpty(?string $title_empty): self {
            $this->title_empty = $title_empty;

            return $this;
        }

        private function getText(): ?string {
            return $this->text;
        }

        private function setText(?string $text): self {
            $this->text = $text;

            return $this;
        }

        private function getTextEmpty(): ?string {
            return $this->text_empty;
        }

        private function setTextEmpty(?string $text_empty): self {
            $this->text_empty = $text_empty;

            return $this;
        }

        private function getFooter(): ?string {
            return $this->footer;
        }

        private function setFooter(?string $footer): self {
            $this->footer = $footer;

            return $this;
        }

        private function getContainerClass(): ?string {
            return $this->container_class;
        }

        private function setContainerClass(?string $container_class): self {
            $this->container_class = $container_class;

            return $this;
        }

        private function getNodeTableThead(): DOMNode {
            return $this->node_table_thead;
        }

        private function setNodeTableThead(DOMNode $node_table_thead): self {
            $this->node_table_thead = $node_table_thead;

            return $this;
        }

        private function getNodeTableTbody(): DOMNode {
            return $this->node_table_tbody;
        }

        private function setNodeTableTbody(DOMNode $node_table_tbody): self {
            $this->node_table_tbody = $node_table_tbody;

            return $this;
        }

        private function getNodeTableTfoot(): DOMNode {
            return $this->node_table_tfoot;
        }

        private function setNodeTableTfoot(DOMNode $node_table_tfoot): self {
            $this->node_table_tfoot = $node_table_tfoot;

            return $this;
        }

        private function getNodePanelBody(): DOMNode {
            return $this->node_panel_body;
        }

        private function setNodePanelBody(DOMNode $node_panel_body): self {
            $this->node_panel_body = $node_panel_body;

            return $this;
        }

        private function getNodeContainer(): DOMNode {
            return $this->node_container;
        }

        private function setNodeContainer(DOMNode $node_container): self {
            $this->node_container = $node_container;

            return $this;
        }

        private function ready(): self {
            $util = new Util;

            $dom_document = $this->getDomDocument();

            $dom_element = $dom_document->createElement('table');
            $dom_element->setAttribute('id',$this->getId());
            $dom_element->setAttribute('class',$this->getClass());
            $dom_element->setAttribute('style',$this->getStyle());

            $model = $this->getModel();

            if (empty($model) || empty($model->data)) {
                $this->setDomElement($dom_element);
                $this->addButton();
                $this->addEmpty();
                $this->addPanel();
                $this->addContainer();

                return $this;
            }

            $this->addButton();
            $this->addThead();
            $this->addSearch();
            $this->addTbody();
            $this->addTfoot();
            $this->addForm();
            $this->addPanel();
            $this->addContainer();
            $this->addTotalizer();
            $this->addPagination();

            return $this;
        }

        private function addButton(): self {
            $model = $this->getModel();

            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $element_id = $this->getId();
            $button = $this->getButton();
            $button_extra = $this->getButtonExtra();

            if (empty($button) && empty($button_extra)) {
                return $this;
            }

            $util = new Util;
            $request = new Request;

            if (!empty($button)) {
                $div_button_group = $dom_document->createElement('div');
                $div_button_group->setAttribute('class','btn-group pull-left');
                $div_button_group->setAttribute('role','group');
                $div_button_group->setAttribute('aria-label','');

                foreach ($button as $data) {
                    $href = $util->contains($data,'href')->getString();

                    $a = $dom_document->createElement('a',$util->contains($data,'label')->getString());
                    $a->setAttribute('href',$href);
                    $a->setAttribute('id',$util->contains($data,'href')->getString());
                    $a->setAttribute('role','button');
                    $a->setAttribute('class',$util->contains($data,'class')->getString('btn btn-default btn-xs'));

                    $alt = $util->contains($data,'alt')->getString();

                    if (!empty($alt)) {
                        $a->setAttribute('data-toggle','tooltip');
                        $a->setAttribute('data-placement','top');
                        $a->setAttribute('title',$alt);
                        $a->setAttribute('alt',$alt);
                        $a->setAttribute('data-toggle','tooltip');
                        $a->setAttribute('data-placement','top');
                        $a->setAttribute('data-container','body');
                    }

                    $icon = $util->contains($data,'icon')->getString();

                    if (!empty($icon)) {
                        $span = $dom_document->createElement('span');
                        $span->setAttribute('class',$icon);
                        $span->setAttribute('aria-hidden','true');

                        $a->appendChild($span);
                    }

                    $div_button_group->appendChild($a);
                }
            }

            if (!empty($button_extra)) {
                $div_button_extra_group = $dom_document->createElement('div');
                $div_button_extra_group->setAttribute('class','btn-group pull-right');
                $div_button_extra_group->setAttribute('role','group');
                $div_button_extra_group->setAttribute('aria-label','');

                foreach ($button_extra as $data) {
                    $a = $dom_document->createElement('a',$util->contains($data,'label')->getString());
                    $a->setAttribute('href',$util->contains($data,'href')->getString());
                    $a->setAttribute('id',$util->contains($data,'id')->getString());
                    $a->setAttribute('role','button');
                    $a->setAttribute('class',$util->contains($data,'class')->getString('btn btn-default btn-xs'));

                    $alt = $util->contains($data,'alt')->getString();

                    if (!empty($alt)) {
                        $a->setAttribute('data-toggle','tooltip');
                        $a->setAttribute('data-placement','top');
                        $a->setAttribute('title',$alt);
                        $a->setAttribute('alt',$alt);
                        $a->setAttribute('data-container','body');
                    }

                    $icon = $util->contains($data,'icon')->getString();

                    if (!empty($icon)) {
                        $span = $dom_document->createElement('span');
                        $span->setAttribute('class',$icon);
                        $span->setAttribute('aria-hidden','true');

                        $a->appendChild($span);
                    }

                    $div_button_extra_group->appendChild($a);
                }
            }

            $p = $dom_document->createElement('p');
            $p->setAttribute('class','pull-left');
            $p->setAttribute('style','width:100%;');

            if (!empty($button)) {
                $dom_element->insertBefore($div_button_group);
            }

            if (!empty($button_extra)) {
                $dom_element->insertBefore($div_button_extra_group);
            }

            $dom_element->insertBefore($p);

            return $this;
        }

        private function addEmpty(): self {
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

            return $this;
        }

        private function modelLoop(DOMDocument $dom_document,DOMElement $table_tr_element,$object,$object_column,string $type,?string $object_table_name_main = null): void {
            $element_id = $this->getId();
            $column = $this->getColumn();
            $object_table_name = $object->getTableName();
            $object_schema = $object->schema();
            $flag_label = null;

            $util = new Util;
            $request = new Request;

            $request_http_get = $request->getHttpGet();

            foreach ($object_column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value)) {
                    $this->modelLoop($dom_document,$table_tr_element,$object->$key,$column_value,$type);

                } else {
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
                        $input->setAttribute('value',$util->contains($request_http_get,$field_name)->getString());
                        $input->setAttribute('id',vsprintf('%s-search-%s-%s',[$element_id,$object_table_name,$column_value]));
                        $input->setAttribute('class','form-control input-sm table-search-input');
                        $input->setAttribute('type','text');
                        $input->setAttribute('placeholder','...');

                        $table_tr_type_element = $dom_document->createElement('th');
                        $table_tr_type_element->appendChild($input);
                        $table_tr_element->appendChild($table_tr_type_element);

                    } else if ($type == 'td') {
                        if (array_key_exists('option',$object_schema[$column_value]->rule) && !empty($object_schema[$column_value]->rule['option'])) {
                            if (empty($object->$column_value)) {
                                $object->$column_value = '';
                            }

                            if (array_key_exists((string) $object->$column_value,$object_schema[$column_value]->rule['option'])) {
                                $object->$column_value = $object_schema[$column_value]->rule['option'][$object->$column_value];
                            }
                        }

                        $reference = $object->getColumnReference();

                        if (!empty($reference)) {
                            $object->$column_value = $object->$reference;
                        }

                        $table_tr_type_element = $dom_document->createElement($type,$object->$column_value);
                        $table_tr_element->appendChild($table_tr_type_element);
                    }
                }
            }
        }

        private function addSearch(): void {
            $dom_document = $this->getDomDocument();
            $model = $this->getModel();

            $node_table_thead = $this->getNodeTableThead();
            $button_search = $this->getButtonSearch();
            $column = $this->getColumn();
            $element_id = $this->getId();

            if (empty($model->data)) {
                return;
            }

            $util = new Util;
            $request = new Request;

            $request_http_get = $request->getHttpGet();

            $data = $model->data[0];

            $table_thead_tr_element = $dom_document->createElement('tr');

            $data_schema = $data->schema();
            $data_table_name = $data->getTableName();
            
            if (empty($column)) {
                $column = array_keys($data->getTableColumn());
            }

            foreach ($column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value) && !is_null($data->$key)) {
                    $this->modelLoop($dom_document,$table_thead_tr_element,$data->$key,$column_value,'form',$data_table_name);

                } elseif(is_array($column_value) && (empty($column_value) || is_null($data->$key))) {
                    continue;

                } else {
                    $field_name = vsprintf('%s__%s',[$data_table_name,$column_value]);

                    if ($data_schema[$column_value]->method == 'foreignKey' || (in_array($data_schema[$column_value]->method,['char','boolean','integer']) && array_key_exists('option',$data_schema[$column_value]->rule))) {
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

                        $field_name_value = $util->contains($request_http_get,$field_name)->getString();

                        if ($data_schema[$column_value]->method == 'foreignKey') {
                            $transaction = new Transaction();

                            $class = get_class($data_schema[$column_value]->rule['table']);
                            $class = new $class($transaction);
                            $primary_key = $class
                                ->definePrimaryKey()
                                ->getPrimaryKey();
                            $reference = $class->getColumnReference();

                            $transaction->connect();

                            if (array_key_exists('filter',$data_schema[$column_value]->rule)) {
                                $class->where($data_schema[$column_value]->rule['filter']);
                            }

                            $class_execute_data = $class
                                ->limit(1,self::QUERY_LIMIT_DEFAULT)
                                ->execute();

                            $data_list = $class_execute_data->data;

                        } else {
                            $data_list = $data_schema[$column_value]->rule['option'];
                        }

                        if (!empty($data_list)) {
                            foreach ($data_list as $key => $value) {
                                if ($data_schema[$column_value]->method == 'foreignKey') {
                                    $option = $dom_document->createElement('option',$value->$reference);
                                    $option->setAttribute('value',$value->$primary_key);

                                    if ((string) $value->$primary_key == $field_name_value) {
                                        $option->setAttribute('selected','selected');
                                    }

                                    $field->appendChild($option);

                                } else if (!is_array($value)) {
                                    $option = $dom_document->createElement('option',$value);
                                    $option->setAttribute('value',$key);

                                    if ((string) $key === $field_name_value) {
                                        $option->setAttribute('selected','selected');
                                    }

                                    $field->appendChild($option);

                                } else {
                                    $option_group = $dom_document->createElement('optgroup');
                                    $option_group->setAttribute('label',$key);

                                    foreach ($value as $sub_key => $sub_value) {
                                        $option = $dom_document->createElement('option',$sub_value);
                                        $option->setAttribute('value',$sub_key);

                                        if ((string) $sub_key === $field_name_value) {
                                            $option->setAttribute('selected','selected');
                                        }

                                        $option_group->appendChild($option);
                                    }

                                    $field->appendChild($option_group);
                                }
                            }
                        }

                    } else {
                        $field = $dom_document->createElement('input');
                        $field->setAttribute('name',$field_name);
                        $field->setAttribute('value',$util->contains($request_http_get,$field_name)->getString());
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
            $button->setAttribute('name',$util->contains($button_search,'name')->getString());
            
            $button_search_alt = $util->contains($button_search,'alt')->getString();

            $button->setAttribute('alt',$button_search_alt);

            if (!empty($button_search_alt)) {
                $button->setAttribute('title',$button_search_alt);
                $button->setAttribute('data-toggle','tooltip');
                $button->setAttribute('data-placement','top');
                $button->setAttribute('data-container','body');
            }

            $button->setAttribute('value','1');
            $button->setAttribute('id',$util->contains($button_search,'id')->getString());

            $button_search_class = $util->contains($button_search,'class')->getString();

            if (empty($button_search_class)) {
                $button_search_class = 'btn btn-default btn-sm table-search-button';
            }

            $button->setAttribute('class',$button_search_class);
            $button->setAttribute('type','submit');
            
            $button_search_icon = $util->contains($button_search,'icon')->getString();
            
            if (!empty($button_search_icon)) {
                $span_button = $dom_document->createElement('span');
                $span_button->setAttribute('class',$util->contains($button_search,'icon')->getString('glyphicon glyphicon-search'));
                $span_button->setAttribute('aria-hidden','true');

                $button->appendChild($span_button);
            }

            $button_search_label = $util->contains($button_search,'label')->getString();

            if (!empty($button_search_label)) {
                $button->appendChild(new \DOMText($button_search_label));
            }

            $table_thead_tr_th_element = $dom_document->createElement('th');
            $table_thead_tr_th_element->appendChild($button);
            $table_thead_tr_element->appendChild($table_thead_tr_th_element);

            $node_table_thead->appendChild($table_thead_tr_element);
        }

        private function addThead(): void {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            $column = $this->getColumn();

            $table_thead_element = $dom_document->createElement('thead');
            $node_table_thead = $dom_element->appendChild($table_thead_element);
            $this->setNodeTableThead($node_table_thead);

            if (empty($model->data)) {
                return;
            }

            $data = $model->data[0];

            $table_thead_tr_element = $dom_document->createElement('tr');
            $data_schema = $data->schema();
            
            if (empty($column)) {
                $column = array_keys($data->getTableColumn());
            }

            foreach ($column as $key => $column_value) {
                if (is_array($column_value) && !empty($column_value) && !is_null($data->$key)) {
                    $this->modelLoop($dom_document,$table_thead_tr_element,$data->$key,$column_value,'th');

                } elseif (is_array($column_value) && (empty($column_value) || is_null($data->$key))) {
                    continue;

                } else {
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

        private function addTableButton(DOMElement $table_tbody_tr_element,array $model): ?DOMElement {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $button_inline = $this->getButtonInline();

            if (empty($button_inline)) {
                return null;
            }

            $table = $dom_document->createElement('table');
            $table_tr = $dom_document->createElement('tr');

            $util = new Util;
            $request = new Request;

            foreach ($button_inline as $data) {
                $href = null;

                if (array_key_exists('href',$data)) {
                    $href = $data['href']($model);

                }

                $a_div_td_tr_tbody = $dom_document->createElement('a',$util->contains($data,'label')->getString());
                $a_div_td_tr_tbody->setAttribute('href',$href);
                $a_div_td_tr_tbody->setAttribute('id',$util->contains($data,'id')->getString());
                $a_div_td_tr_tbody->setAttribute('role','button');
                $a_div_td_tr_tbody->setAttribute('class',$util->contains($data,'class')->getString('btn btn-default btn-xs'));

                $alt = $util->contains($data,'alt')->getString();

                if (!empty($alt)) {
                    $a_div_td_tr_tbody->setAttribute('title',$alt);
                    $a_div_td_tr_tbody->setAttribute('alt',$alt);
                    $a_div_td_tr_tbody->setAttribute('data-toggle','tooltip');
                    $a_div_td_tr_tbody->setAttribute('data-placement','top');
                    $a_div_td_tr_tbody->setAttribute('data-container','body');
                }

                $icon = $util->contains($data,'icon')->getString();

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

        private function addTbody(): void {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            $column = $this->getColumn();

            $table_tbody_element = $dom_document->createElement('tbody');
            $node_table_tbody = $dom_element->appendChild($table_tbody_element);
            $this->setNodeTableTbody($node_table_tbody);

            if (empty($model->data)) {
                return;
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
                            $column_value = [
                                $data->$column_value->definePrimaryKey()->getPrimaryKey(),
                            ];

                        } else {
                            $data_key = $data->$key;
                        }

                        if (is_null($data_key)) {
                            continue;
                        }

                        $this->modelLoop($dom_document,$table_tbody_tr_element,$data_key,$column_value,'td');

                    } else {
                        if (array_key_exists('option',$data_schema[$column_value]->rule) && !empty($data_schema[$column_value]->rule['option'])) {
                            if (empty($data->$column_value)) {
                                $data->$column_value = '';
                            }

                            if (array_key_exists((string) $data->$column_value,$data_schema[$column_value]->rule['option'])) {
                                $data->$column_value = $data_schema[$column_value]->rule['option'][$data->$column_value];
                            }
                        }

                        $table_tbody_tr_td_element = $dom_document->createElement('td',$data->$column_value);
                        $table_tbody_tr_element->appendChild($table_tbody_tr_td_element);
                    }
                }

                $table_tbody_tr_element_with_button = $this->addTableButton($table_tbody_tr_element,$data);

                if (!empty($table_tbody_tr_element_with_button)) {
                    $node_table_tbody->appendChild($table_tbody_tr_element_with_button);

                } else {
                    $node_table_tbody->appendChild($table_tbody_tr_element);
                }
            }
        }

        private function addTotalizer(): void {
            $dom_document = $this->getDomDocument();
            $model = $this->getModel();
            $node_panel_body = $this->getNodePanelBody();
            $node_container = $this->getNodeContainer();

            $register_total = 0;
            $page_total = 0;
            $register_perpage = 0;

            if (isset($model->register_total)) {
                $register_total = $model->register_total;
            }

            if (isset($model->page_total)) {
                $page_total = $model->page_total;
            }

            if (isset($model->register_perpage)) {
                $register_perpage = $model->register_perpage;
            }

            $p_text = $dom_document->createElement('p');
            $p_text->setAttribute('class','pagination');

            $p_text->appendChild(new \DOMText(
                vsprintf('Total de registros "%s". Exibindo "%s" registros por página, total de "%s" páginas',
                    [$register_total,$register_perpage,$page_total])));

            $nav_totalizer = $dom_document->createElement('nav');
            $nav_totalizer->appendChild($p_text);

            if (!empty($node_panel_body)) {
                $node_panel_body->appendChild($nav_totalizer);

            } else {
                $node_container->appendChild($nav_totalizer);
            }
        }

        private function addPagination(): void {
            $dom_document = $this->getDomDocument();
            $model = $this->getModel();

            $pagination = $this->getPagination();

            if (isset($model->page_total) && $model->register_total > $model->register_perpage) {
                $util = new Util;
                $request = new Request;

                $node_panel_body = $this->getNodePanelBody();
                $node_container = $this->getNodeContainer();
                $element_id = $this->getId();

                $nav_pagination = $dom_document->createElement('nav');
                $ul_nav = $dom_document->createElement('ul');
                $ul_nav->setAttribute('class','pagination');

                $btn_url = $util->contains($pagination,'btn_url')->getString();
                $btn_url_string = $util->contains($pagination,'btn_url_string')->getString();
                $a_class = $util->contains($pagination,'class')->getString();
                $left_alt = $util->contains($pagination,'left_alt')->getString();
                $right_alt = $util->contains($pagination,'right_alt')->getString();

                $http_get = $request->getHttpGet();
                unset($http_get[$btn_url_string]);
                $http_get_rawstring = http_build_query($http_get);

                if ($model->page_previous > 1) {
                    $li_ul_nav = $dom_document->createElement('li');
                    $a_li_ul_nav = $dom_document->createElement('a');
                    $a_li_ul_nav->setAttribute('href',vsprintf('%s?%s&%s=1',[$btn_url,$http_get_rawstring,$btn_url_string,]));
                    $a_li_ul_nav->setAttribute('class',vsprintf('%s',[$a_class,]));
                    $a_li_ul_nav->setAttribute('data-page','1');
                    $span_a_li_ul_nav = $dom_document->createElement('span');
                    $span_a_li_ul_nav->setAttribute('class','glyphicon glyphicon-chevron-left');
                    $span_a_li_ul_nav->setAttribute('alt',$left_alt);
                    $span_a_li_ul_nav->setAttribute('title',$left_alt);
                    $span_a_li_ul_nav->setAttribute('aria-hidden','true');

                    $a_li_ul_nav->appendChild($span_a_li_ul_nav);
                    $li_ul_nav->appendChild($a_li_ul_nav);

                    $ul_nav->appendChild($li_ul_nav);
                }

                if ($model->page_previous < $model->page_current) {
                    $li_ul_nav = $dom_document->createElement('li');
                    $a_li_ul_nav = $dom_document->createElement('a');
                    $a_li_ul_nav->setAttribute('href',vsprintf('%s?%s&%s=%s',[$btn_url,$http_get_rawstring,$btn_url_string,$model->page_previous,]));
                    $a_li_ul_nav->setAttribute('class',vsprintf('%s',[$a_class,]));
                    $a_li_ul_nav->setAttribute('data-page',$model->page_previous);
                    $span_a_li_ul_nav = $dom_document->createElement('span',$model->page_previous);
                    $span_a_li_ul_nav->setAttribute('aria-hidden','true');

                    $a_li_ul_nav->appendChild($span_a_li_ul_nav);
                    $li_ul_nav->appendChild($a_li_ul_nav);

                    $ul_nav->appendChild($li_ul_nav);
                }

                $li_ul_nav = $dom_document->createElement('li');
                $li_ul_nav->setAttribute('class','active');
                $a_li_ul_nav = $dom_document->createElement('a',$model->page_current);
                $a_li_ul_nav->setAttribute('class',vsprintf('%s-pag',[$element_id,]));
                $a_li_ul_nav->setAttribute('data-page',$model->page_current);

                $li_ul_nav->appendChild($a_li_ul_nav);

                $ul_nav->appendChild($li_ul_nav);

                if ($model->page_next < $model->page_total) {
                    $li_ul_nav = $dom_document->createElement('li');
                    $a_li_ul_nav = $dom_document->createElement('a');
                    $a_li_ul_nav->setAttribute('href',vsprintf('%s?%s&%s=%s',[$btn_url,$http_get_rawstring,$btn_url_string,$model->page_next]));
                    $a_li_ul_nav->setAttribute('class',vsprintf('%s',[$a_class,]));
                    $a_li_ul_nav->setAttribute('data-page',$model->page_next);
                    $span_a_li_ul_nav = $dom_document->createElement('span',$model->page_next);
                    $span_a_li_ul_nav->setAttribute('aria-hidden','true');

                    $a_li_ul_nav->appendChild($span_a_li_ul_nav);
                    $li_ul_nav->appendChild($a_li_ul_nav);

                    $ul_nav->appendChild($li_ul_nav);
                }

                if ($model->page_total > $model->page_current) {
                    $li_ul_nav = $dom_document->createElement('li');
                    $a_li_ul_nav = $dom_document->createElement('a');
                    $a_li_ul_nav->setAttribute('href',vsprintf('%s?%s&%s=%s',[$btn_url,$http_get_rawstring,$btn_url_string,$model->page_total]));
                    $a_li_ul_nav->setAttribute('class',vsprintf('%s',[$a_class,]));
                    $a_li_ul_nav->setAttribute('data-page',$model->page_total);
                    $span_a_li_ul_nav = $dom_document->createElement('span');
                    $span_a_li_ul_nav->setAttribute('class','glyphicon glyphicon-chevron-right');
                    $span_a_li_ul_nav->setAttribute('alt',$right_alt);
                    $span_a_li_ul_nav->setAttribute('title',$right_alt);
                    $span_a_li_ul_nav->setAttribute('aria-hidden','true');

                    $a_li_ul_nav->appendChild($span_a_li_ul_nav);
                    $li_ul_nav->appendChild($a_li_ul_nav);

                    $ul_nav->appendChild($li_ul_nav);
                }

                $nav_pagination->appendChild($ul_nav);

                if (!empty($node_panel_body)) {
                    $node_panel_body->appendChild($nav_pagination);

                } else {
                    $node_container->appendChild($nav_pagination);
                }
            }
        }

        private function addTfoot(): void {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();

            $table_tfoot_element = $dom_document->createElement('tfoot');
            $node_table_tfoot = $dom_element->appendChild($table_tfoot_element);

            $this->setNodeTableTfoot($node_table_tfoot);
        }

        private function addForm(): void {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $button_search = $this->getButtonSearch();

            $util = new Util;

            $button_search_href = $util->contains($button_search,'href')->getString();

            $form = $dom_document->createElement('form');
            $form->setAttribute('method','GET');
            $form->setAttribute('action',$button_search_href);
            $form->appendChild($dom_element);

            $this->setDomElement($form);
        }

        private function addPanel(): void {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $title = $this->getTitle();
            $text = $this->getText();
            $footer = $this->getFooter();

            if (empty($title) && empty($text) && empty($footer)) {
                return;
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

        private function addContainer(): self {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $container_class = $this->getContainerClass();
 
            $div_container = $dom_document->createElement('div');
            $div_container->setAttribute('class',$container_class);
            $div_container->appendChild($dom_element);

            $this->setNodeContainer($div_container); 
            $this->setDomElement($div_container);

            return $this;
        }

        public function renderHtml(): string {
            $dom_document = $this->getDomDocument();

            return $dom_document->saveHTML();
        }
    }
}
