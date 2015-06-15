<?php

    class MarvelAPI {
        public $base_url = 'http://gateway.marvel.com/v1/public/';

        /**
         * Allows a public key to be set on the object
         * @param string $key Public Key
         */
        public function setPubKey($key) {
            $this->api_key = $key;
        }

        /**
         * Obtain the set public key
         * @return string    Public Key
         */
        private function getPubKey() {
            return $this->api_key;
        }

        /**
         * Allows a private key to be set on the object
         * @param string $key Private key
         */
        public function setPrivKey($key) {
            $this->priv_key = $key;
        }

        /**
         * Obtain the set private key
         * @return string    Private Key
         */
        private function getPrivKey() {
            return $this->priv_key;
        }

        /**
         * Generate a hash for API requests, a hash consists of:
         * Timestamp, Public Key, Private Key
         * It returns an md5 hash
         * @return string    md5 encoded hash
         */
        private function getHash($date) {
            $public = $this->getPubKey();
            $private = $this->getPrivKey();

            return md5($date->getTimestamp().$private.$public);
        }

        /**
         * Build the request URL and return the response
         * @param  string  $type    Type of information you want:
         *                          (characters, comics, creators, events, series, stories)
         * @param  string  $action  The kind of information you want about it:
         *                          (characters, comics, creators, events, series, stories)
         * @param  int     $id      ID of the object you want more information about (optional)
         * @param  array   $params  An array of additional parameters for searching/limiting results
         * @return string           Returns the curl response object
         */
        public function makeRequest($type, $id = null, $action = null, $params) {
            $date = new DateTime();
            $hash = $this->getHash($date);
            $pubkey = $this->getPubKey();

            if ( $action )
                $action = '/' . $action;

            if ( $id )
                $id = '/' . $id;

            if ( $action && !$id ) {
                return "In order to use $action, you will also need to provide an id";
            }

            $request = $this->base_url . $type . $id . $action;
            $request .= '?'.http_build_query(
                array(
                    'apikey' => $pubkey,
                    'ts' => $date->getTimestamp(),
                    'hash' => $hash
                )
            );

            $curl = curl_init($request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);

            return json_decode($curl_response);
        }
    }
