<?php

/**
 * XPath Helper
 * Version 0.0.1
 * 
 * Copyright 2017, Recep Uncu
 */
class xpath_helper {

    public $timeout = 1200;
    public $verify_peer = false;
    public $verify_peer_name = false;
    public $ignore_errors = false;
    public $header = 'User-Agent:Mozilla/5.0 (Windows NT 6.3; WOW64) '
            . 'AppleWebKit/537.36 (KHTML, like Gecko) '
            . 'Chrome/50.0.2661.94 Safari/537.36\r\n';

    public function get_contents($url) {
        $context = stream_context_create(array(
            'ssl' => array(
                'verify_peer' => $this->verify_peer,
                'verify_peer_name' => $this->verify_peer_name,
            ),
            'http' => array(
                'ignore_errors' => $this->ignore_errors,
                'header' => $this->header,
                'timeout' => $this->timeout
            )
        ));
        $contents = file_get_contents($url, false, $context);
        return $contents;
    }

    public function load_xpath_from_contents($contents) {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($contents, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new DOMXPath($dom);
        return $xpath;
    }

    public function load_xpath_from_url($url) {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $contents = $this->get_contents($url);
        $dom->loadHTML(mb_convert_encoding($contents, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new DOMXPath($dom);
        return $xpath;
    }

    public function get_cols_in_row($xpath, $row_path, $cols_opts = array()) {
        $new_rows = array();
        $rows = $xpath->query($row_path);
        foreach ($rows as $row_key => $row) {
            $new_cols = array();
            foreach ($cols_opts as $col_key => $col_path) {
                $value = null;
                $q = $xpath->query($col_path, $row);
                if ($q->length >= 1) {
                    $value = $q->item($row_key)->textContent;
                }
                $new_cols[$col_key] = trim($value);
            }
            $new_rows[] = $new_cols;
        }
        return $new_rows;
    }

}
