<?php
declare(strict_types=1);

namespace HtmlBlock\Exception {
    use \Error as Error;

    class HtmlBlockException extends Error {
        private $html_block_message;
        private $html_block_code;

        public function __construct(string $html_block_message,int $html_block_code = 0) {
            parent::__construct($html_block_message,$html_block_code);

            $this->setHtmlBlockMessage($html_block_message);
            $this->setHtmlBlockCode($html_block_code);
        }

        public function getHtmlBlockMessage(): string {
            return $this->getMessage();
        }

        public function setHtmlBlockMessage(string $html_block_message): self {
            $this->html_block_message = vsprintf('[HtmlBlock]%s',[$html_block_message,]);

            $this->setMessage($this->html_block_message);

            return $this;
        }
    }
}